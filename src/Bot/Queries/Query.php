<?php


namespace bot\Queries;

use bot\ReplyOf;
use bot\Types\CallBackQuery;
use bot\types\User;
use TelegramMysql;

class Query
{
    public const REQUEST_ACCEPT = 1;
    public const REQUEST_DECLINE = 2;
    public const REQUEST_DECLINE_AND_BLOCK = 3;

    private $query;
    private $mysqli;

    private $User;
    private $user;

    public function __construct(CallBackQuery $query, TelegramMysql $mysqli)
    {
        $this->query = $query;
        $this->mysqli = $mysqli;
        $this->User = $this->query->getFrom();
        $this->user = $this->mysqli->createUserIfDontExists($this->User);
    }

    private function getTargetUser($data) {
        return $this->mysqli->getUserByDb(jsonDecode($data, true)['telegram_id']);
    }

    private function RequestVerify($targetUser) {
        if ($this->user[5] != "owner" && $this->user[5] != "support" && $this->user[5] != "admin") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Вы не являетесь уполномоченым лицом чтобы принять заявку пользователя");
            die();
        }

        if ($targetUser[4] != "wait") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь не отправил заявку");
            die();
        }

        if ($targetUser[5] != "guest") {
            ReplyOf::replyOfError($this->query->getMessage()->getChat()->id, $this->User->getFirstName().", Этот пользователь уже проходил проверку и имеет ранг");
            die();
        }
    }

    public function RequestAccept() {

        $targetUser = $this->getTargetUser($this->query->data);

        $this->RequestVerify($targetUser);

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

        $targetUser = $this->getTargetUser($this->query->data);

        $this->RequestVerify($targetUser);

        $this->mysqli->changeUserStateWithID($targetUser[1], 'start');
        ReplyOf::replyOfSuccess($this->query->getMessage()->getChat()->id, "Заявка пользователя успешно отклонена");
        requestApi("sendMessage", [
            "chat_id" => $targetUser[1],
            "text" => "*Ваша заявка была отклонена*",
            "parse_mode" => "MarkDown",
            "reply_markup" => json_encode([
                "keyboard" => KeyBoard('start'),
                "resize_keyboard" => true
            ])
        ]);
    }

    public function RequestDeclineAndBlock() {

        $targetUser = $this->getTargetUser($this->query->data);

        $this->RequestVerify($targetUser);

        $this->mysqli->changeUserRank($targetUser[1], 'blocked');
        $this->mysqli->changeUserStateWithID($targetUser[1], '');
        ReplyOf::replyOfSuccess($this->query->getMessage()->getChat()->id, "Заявка пользователя успешно отклонена и пользователь заблокирован");
        requestApi("sendMessage", [
            "chat_id" => $targetUser[1],
            "text" => "*Ваша заявка была отклонена и вы были заблокированны*",
            "parse_mode" => "MarkDown"
        ]);
    }
}