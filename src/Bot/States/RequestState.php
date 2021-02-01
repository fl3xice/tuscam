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

                if (strlen($text) > 50) {
                    ReplyOf::replyOfError($this->hook->getMessage()->getFrom()->getId(),"Введите текст не длинее 50-ти символом");
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
                    "text" => "УХЙ",
                    "parse_mode" => "markdown",
                    "reply_markup" => jsonEncode([
                        "keyboard" => KeyBoard('fillRequest_2'),
                        "resize_keyboard" => true
                    ])
                ]);

                $this->mysqli->setInput($User, '');
                $this->nextStage($stage);
            }
        ];

        $stages[$stage[0]]();
    }
}