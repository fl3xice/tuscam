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
        "get_info" => "🔆 Информация",
        "set_back" => "⏪ Назад"
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
        case 'fillRequest_1':
            return [
                [
                    "Форум",
                    "Реклама",
                    "Друзья"
                ],
                [
                    Button("set_back")
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

function Rank($rank = null): string
{
    switch ($rank) {
        case "guest":
            return "🕶 Аноним";
        case "worker":
            return "🦺 Работник";
        case "support":
            return "⛑ Саппорт";
        case "admin":
            return "🧣 Админ";
        case "owner":
            return "👑 Владелец";
    }
}

function HandleCommand(ObjectHook $Hook, $config, TelegramMysql $mysqli) {

    $User = $Hook->getMessage()->getFrom();
    $user = $mysqli->createUserIfDontExists($User);

    if ($user[4] != '' || $user[4] != 'start') {
        switch ($user[4]) {
            case "fillRequest_1":
                $mysqli->setDataForState($User, json_encode(["fillRequest" => [
                    "1" => $Hook->getMessage()->getText()
                ]]));
                break;
        }

        exit();
    }

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
        Button("set_back") => function () use ($config, $Hook, $mysqli) {
            $user = $mysqli->createUserIfDontExists($Hook->getMessage()->getFrom());
            if ($user[4] == '') {
                $state = $mysqli->changeUserState($Hook->getMessage()->getFrom(), '');
            } else {
                $state = $mysqli->changeUserState($Hook->getMessage()->getFrom(), 'start');
            }

            requestApi("sendMessage", $config['token'], [
                "chat_id" => $Hook->getMessage()->getFrom()->getId(),
                "text" => "Клавиатура загружена и данные обновлены 🚿",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard($state),
                    "resize_keyboard" => true
                ])
            ]);
        },
        Button("send_request") => function () use ($config, $Hook, $mysqli) {
            $User = $Hook->getMessage()->getFrom();
            $user = $mysqli->createUserIfDontExists($User);

            $mysqli->changeUserState($User, "fillRequest_1");

            requestApi("sendMessage", $config['token'], [
                "chat_id" => $Hook->getMessage()->getFrom()->getId(),
                "text" => "Откуда вы о нас узнали?",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard('fillRequest_1'),
                    "resize_keyboard" => true
                ])
            ]);

//            sendRequest($User, $user, $config);
        }
    ];

    function sendRequest($User, $user, $config) {
        $requestScreen = new Screen("Заявка на добавление", [
            "*❗ВНИМАНИЕ❗*: Перед добавлением прочтите информацию о пользователе которую он оставил в заявке",
            "",
        ]);


        $usernameFull = "";

        if ($User->getUsername()) {
            $usernameFull .= "@".$User->getUsername();
        } else {
            $usernameFull .= "Скрыто";
        }

        if (strlen(trim($User->getFirstName())) != 0) {
            $usernameFull .= " ".$User->getFirstName();
        }

        if (strlen(trim($User->getLastName())) != 0) {
            $usernameFull .= " ".$User->getLastName();
        }

        $requestScreen->addLine("1️⃣ Имя Пользователя: $usernameFull");
        $requestScreen->addLine("2️⃣ ID: `".$User->getId()."`");
        $requestScreen->addLine("3️⃣ Ранг: `".Rank($user[5])."`");
        $requestScreen->addLine("");

        requestApi("sendMessage", $config['token'], [
            "chat_id" => $config['bot']['admin_chat_id'],
            "text" => $requestScreen->getText(),
            "parse_mode" => "markdown",
            "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        [
                            "text" => "💚 Принять заявку",
                            "callback_data" => "acceptRequest"
                        ],
                        [
                            "text" => "🚫 Отклонить заявку",
                            "callback_data" => "cancelRequest"
                        ]
                    ],
                    [
                        [
                            "text" => "⛔ Отклонить заявку и заблокировать",
                            "callback_data" => "cancelAndBanRequest"
                        ]
                    ]
                ]
            ])
        ]);
    }

    $Cond = new ConditionalExecution([
        $Hook->isMessage()
    ]);

    $Cond->Execute(
        $Commands[trim($Hook->getMessage()->getText())]
    );
}

