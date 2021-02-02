<?php


namespace bot\Queries;

use bot\ReplyOf;
use bot\Types\CallBackQuery;
use bot\types\User;

class Query
{
    public const REQUEST_ACCEPT = 1;
    public const REQUEST_DECLINE = 2;
    public const REQUEST_DECLINE_AND_BLOCK = 3;

    private $query;
    private $mysqli;

    private $User;
    private $user;

    public function __construct(CallBackQuery $query, \TelegramMysql $mysqli)
    {
        $this->query = $query;
        $this->mysqli = $mysqli;
        $this->User = $this->query->getFrom();
        $this->user = $this->mysqli->createUserIfDontExists($this->User);
    }

    public function RequestAccept() {
        if ($this->user[5] != "owner" && $this->user[5] != "support" && $this->user[5] != "admin") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Вы не являетесь уполномоченым лицом чтобы принять заявку пользователя");
            die();
        }

        $targetUser = $this->mysqli->getUserByDb(jsonDecode($this->query->data, true)['telegram_id']);

        if ($targetUser[4] != "wait") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь не отправил заявку");
            die();
        }

        if ($targetUser[5] != "guest") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь уже проходил проверку и имеет ранг");
            die();
        }

        $this->mysqli->changeUserRank($targetUser[1], 'worker');
        $this->mysqli->changeUserStateWithID($targetUser[1], '');
        ReplyOf::replyOfSuccess($this->query->getMessage()->getChat()->id, "Пользователь успешно принят");
        requestApi("sendMessage", [
            "chat_id" => $targetUser[1],
            "text" => "*Ваша заявка была принята*",
            "parse_mode" => "MarkDown",
            "reply_markup" => json_encode([
                "keyboard" => KeyBoard(''),
                "resize_keyboard" => true
            ])
        ]);
    }

    public function RequestDecline() {
        if ($this->user[5] != "owner" || $this->user[5] != "support" || $this->user[5] != "admin") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Вы не являетесь уполномоченым лицом чтобы принять заявку пользователя");
            die();
        }

        $targetUser = $this->mysqli->getUserByDb(jsonDecode($this->query->data, true)['telegram_id']);

        if ($targetUser[4] != "wait") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь не отправил заявку");
            die();
        }

        if ($targetUser[5] != "guest") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь уже проходил проверку и имеет ранг");
            die();
        }

        $this->mysqli->changeUserRank($targetUser[1], 'worker');
        $this->mysqli->changeUserStateWithID($targetUser[1], '');
        ReplyOf::replyOfSuccess($this->query->getMessage()->getChat()->id, "Пользователь успешно принят");
        requestApi("sendMessage", [
            "chat_id" => $targetUser[1],
            "text" => "*Ваша заявка была принята*",
            "parse_mode" => "MarkDown",
            "reply_markup" => json_encode([
                "keyboard" => KeyBoard(''),
                "resize_keyboard" => true
            ])
        ]);
    }

    public function RequestDeclineAndBlock() {
        if ($this->user[5] != "owner" || $this->user[5] != "support" || $this->user[5] != "admin") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Вы не являетесь уполномоченым лицом чтобы принять заявку пользователя");
            die();
        }
    }
}