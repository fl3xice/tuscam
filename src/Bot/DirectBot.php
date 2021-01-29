<?php

use bot\ObjectHook;
use bot\Screen;

function KeyBoard(): array
{
    return [
        [
            "💎 Профиль",
            "⚙ Настройки"
        ],
        [
            "📦 Объявления",
            "🔆 Информация"
        ]
    ];
}

function HandleCommand(ObjectHook $Hook, $config) {
    $Commands = [
        "/start" => function () use ($config, $Hook) {
            requestApi("sendMessage", $config['token'], [
                "chat_id" => $Hook->getMessage()->getChat()->id,
                "text" => "Клавиатура загружена и данные обновлены 🚿",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard(),
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

