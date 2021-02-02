<?php


namespace bot;


use bot\Queries\Query;
use bot\Types\CallBackQuery;

class CallBackHandler
{
    public static function Handle(CallBackQuery $query, \TelegramMysql $mysqli) {
        $data = jsonDecode($query->data, false);
        $Query = new Query($query, $mysqli);

        switch ($data->type) {
            case Query::REQUEST_ACCEPT:
                $Query->RequestAccept();
                break;
            case Query::REQUEST_DECLINE:
                $Query->RequestDecline();
                break;
            case Query::REQUEST_DECLINE_AND_BLOCK:
                $Query->RequestDeclineAndBlock();
                break;
        }
    }
}