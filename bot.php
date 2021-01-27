<?php

// Connect Modules
require_once "./src/ConditionalExecution.php";

// Constants
define("CONFIG", json_decode(file_get_contents("config.json"), true));
define("TELEGRAM_REQUEST_URL", "https://api.telegram.org/bot");

// Local descriptions for bot
function Bot($token) {
    $Object = json_decode(file_get_contents("php://input"));

    $ConditionalMessages = new ConditionalExecution(
        [
            $Object->message,
            $Object->message->text == "/start"
        ]
    );

    $ConditionalMessages->Execute(function () use ($token, $Object) {
        requestApi("sendMessage", $token, [
            "chat_id" => $Object->message->chat->id,
            "text" => "*Welcome*",
            "parse_mode" => "MarkDown"
        ]);
    });
}

// For making requests on api Telegram
function requestApi($method, $token, $dataSets = []): bool
{
    $requestUrl = TELEGRAM_REQUEST_URL.$token.'/'.$method.'?'.http_build_query($dataSets);
    $curl = curl_init($requestUrl);

    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}

// Run Bot Scripts
Bot(CONFIG['token']);