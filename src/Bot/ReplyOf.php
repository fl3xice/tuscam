<?php


namespace bot;


class ReplyOf
{
    public static function replyOfError($chat_id, $message) {
        requestApi("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "🚫 $message",
            "parse_mode" => "MarkDown"
        ]);
    }
}