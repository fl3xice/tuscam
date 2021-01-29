<?php

// Connect Modules
use bot\ObjectHook;
use bot\Screen;

require_once "./src/ConditionalExecution.php";
require_once "./src/TelegramMysql.php";
require_once "./src/Bot/Screen.php";
require_once "./src/Bot/ObjectHook.php";
require_once "./src/Bot/Types/Message.php";
require_once "./src/Bot/DirectBot.php";

// Constants
define("CONFIG", json_decode(file_get_contents("config.json"), true));
define("TELEGRAM_REQUEST_URL", "https://api.telegram.org/bot");

// Local descriptions for bot
function Bot($token) {
    $Object = json_decode(file_get_contents("php://input"));
    $MySqlConnection = new TelegramMysql(CONFIG);
    $Hook = new ObjectHook($Object);

    logDev($Object);

    HandleCommand($Hook, CONFIG);
}

// For log
function logDev($data) {
    file_put_contents("dev.txt", print_r($data, true)."-----------------"."\n\n".file_get_contents("dev.txt"));
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