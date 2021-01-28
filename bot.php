<?php

// Connect Modules
require_once "./src/ConditionalExecution.php";
require_once "./src/TelegramMysql.php";

// Constants
define("CONFIG", json_decode(file_get_contents("config.json"), true));
define("TELEGRAM_REQUEST_URL", "https://api.telegram.org/bot");

// Local descriptions for bot
function Bot($token) {
    $Object = json_decode(file_get_contents("php://input"));
    $MySqlConnection = new TelegramMysql(CONFIG);


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