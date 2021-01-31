<?php

// Connect Modules
use bot\ObjectHook;

require_once __DIR__."/vendor/autoload.php";

require_once "./src/ConditionalExecution.php";
require_once "./src/TelegramMysql.php";
require_once "./src/Bot/DirectBot.php";

// Constants
define("CONFIG", json_decode(file_get_contents("config.json"), true));
define("TELEGRAM_REQUEST_URL", "https://api.telegram.org/bot");

// Local descriptions for bot
function Bot($token) {
    $Object = json_decode(file_get_contents("php://input"));
    $MySqlConnection = new TelegramMysql(CONFIG);

    $Hook = new ObjectHook($Object);

    HandleCommand($Hook, CONFIG, $MySqlConnection);
}

// For log
function logDev($data) {
    file_put_contents("dev.txt", print_r($data, true)."-----------------"."\n\n".file_get_contents("dev.txt"));
}

// True Encode JSON
function jsonEncode($value) {
    return json_encode($value, JSON_UNESCAPED_UNICODE);
}

// True Decode
function jsonDecode($value, $associative) {
    return json_decode($value, $associative);
}

// For making requests on api Telegram
function requestApi($method, $dataSets = []): bool
{
    $requestUrl = TELEGRAM_REQUEST_URL.CONFIG['token'].'/'.$method.'?'.http_build_query($dataSets);
    $curl = curl_init($requestUrl);

    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}

// Run Bot Scripts
Bot(CONFIG['token']);