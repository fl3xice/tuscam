<?php


namespace bot\States;


use bot\ObjectHook;
use bot\ReplyOf;
use bot\State;

class RequestState extends State
{
    public function __construct(\TelegramMysql $mysqli, ObjectHook $hook)
    {
        parent::__construct($mysqli, $hook);
    }
    public function run()
    {
        $stage = $this->mysqli->getInput($this->hook->getMessage()->getFrom())[0];
        $stages = [
            function () use ($stage) {

                $text = $this->hook->getMessage()->getText();
                $User = $this->hook->getMessage()->getFrom();

                if ($text == Button('set_back')) {
                    $this->mysqli->setInput($User, '');
                    $this->mysqli->changeUserState($User, 'start');
                    die();
                }

                if (strlen($text) > 50) {
                    ReplyOf::replyOfError($User->getId(),"Введите текст не длинее 50-ти символом");
                    die();
                }

                $this->mysqli->setDataForState($this->hook->getMessage()->getFrom(), json_encode([
                    "1" => trim($this->hook->getMessage()->getText())
                ], JSON_UNESCAPED_UNICODE));

                requestApi("sendMessage", [
                    "chat_id" => $this->hook->getMessage()->getFrom()->getId(),
                    "text" => "Есть ли у вас опыт? (Прикрипите скриншот) *Необязательно*",
                    "parse_mode" => "markdown",
                    "reply_markup" => json_encode([
                        "keyboard" => KeyBoard('fillRequest_1'),
                        "resize_keyboard" => true
                    ])
                ]);
                $this->nextStage($stage);
            },
            function () use ($stage) {
                $User = $this->hook->getMessage()->getFrom();
                $text = $this->hook->getMessage()->getText();
                $photo = $this->hook->getMessage()->getPhoto();
                $beforeStateData = jsonDecode($this->mysqli->getDataForState($User), true);

                if ($text == Button('set_back')) {
                    $this->prevStage($stage);
                    die();
                }

                if ($text == Button("set_skip")) {
                    $beforeStateData['2'] = "Пропущено";
                    $this->mysqli->setDataForState($User, jsonEncode($beforeStateData));
                    $this->nextStage($stage);
                } else {
                    if (!$photo) {
                        ReplyOf::replyOfError($User->getId(),"Отправьте скриншот или пропустите этот этап");
                        die();
                    }

                    $beforeStateData['2'] = $photo;
                    $this->mysqli->setDataForState($User, jsonEncode($beforeStateData));
                }

                $user = $this->mysqli->createUserIfDontExists($User);

                sendRequest($User, $user, CONFIG);

                requestApi("sendMessage", [
                    "chat_id" => $this->hook->getMessage()->getFrom()->getId(),
                    "text" => "Ваша заявка отправлена ✅ как только администраторы примут её вы сможете продолжить пользование ботом",
                    "parse_mode" => "markdown"
                ]);


                $this->mysqli->setInput($User, '');
                $this->mysqli->changeUserState($User, 'wait');
            }
        ];

        $stages[$stage[0]]();
    }
}