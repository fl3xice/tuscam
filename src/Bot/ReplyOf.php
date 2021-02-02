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

    public static function replyOfInfo($chat_id, $message) {
        requestApi("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "ℹ $message",
            "parse_mode" => "MarkDown"
        ]);
    }

    public static function replyOfSuccess($chat_id, $message) {
        requestApi("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "✅ $message",
            "parse_mode" => "MarkDown"
        ]);
    }
}