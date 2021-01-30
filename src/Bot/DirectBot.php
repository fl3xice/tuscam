<?php

use bot\ObjectHook;
use bot\Screen;


function Button($name): string
{
    $Buttons = [
        "send_request" => "📎 Отправить заявку",
        "get_profile" => "💎 Профиль",
        "get_options" => "⚙ Настройки",
        "get_ads" => "📦 Объявления",
        "get_info" => "🔆 Информация"
    ];
    return $Buttons[$name];
}


function KeyBoard($state = null) : array
{
    switch ($state) {
        case 'start':
            return [
                [
                    Button("send_request")
                ]
            ];
        default:
            return [
                [
                    Button("get_profile"),
                    Button("get_options")
                ],
                [
                    Button("get_ads"),
                    Button("get_info")
                ]
            ];
    }
}

function HandleCommand(ObjectHook $Hook, $config, TelegramMysql $mysqli) {
    $Commands = [
        "/start" => function () use ($config, $Hook, $mysqli) {

            $user = $mysqli->createUserIfDontExists($Hook->getMessage()->getFrom());

            requestApi("sendMessage", $config['token'], [
                "chat_id" => $Hook->getMessage()->getFrom()->getId(),
                "text" => "Клавиатура загружена и данные обновлены 🚿",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard($user[4]),
                    "resize_keyboard" => true
                ])
            ]);
        },
        Button("send_request") => function () use ($config, $Hook, $mysqli) {
            $user = $mysqli->createUserIfDontExists($Hook->getMessage()->getFrom());

            requestApi("sendMessage", $config['token'], [
                "chat_id" => $config['bot']['admin_chat_id'],
                "text" => "Заявка отправленна",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard($user[4]),
                    "resize_keyboard" => true
                ])
            ]);
        }
    ];

    $Cond = new ConditionalExecution([
        $Hook->isMessage()
    ]);

    $Cond->Execute(
        $Commands[trim($Hook->getMessage()->getText())]
    );
}

