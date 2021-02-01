<?php

use bot\ObjectHook;
use bot\Screen;
use bot\States\RequestState;

function Button($name): string
{
    $Buttons = [
        "send_request" => "📎 Отправить заявку",
        "get_profile" => "💎 Профиль",
        "get_options" => "⚙ Настройки",
        "get_ads" => "📦 Объявления",
        "get_info" => "🔆 Информация",
        "set_back" => "⏪ Назад",
        "set_skip" => "⏩ Пропустить"
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
        case 'fillRequest_0':
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
        case 'fillRequest_1':
            return [
                [
                    Button("set_skip")
                ],
                [
                    Button("set_back")
                ]
            ];
        case 'fillRequest_2':
            return [
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
        case "blocked":
            return "⛔ Заблокирован";
    }
}

function State($user, TelegramMysql $mysqli, ObjectHook $Hook)
{
    switch ($user[4])
    {
        case "fillRequest":
            $State = new RequestState($mysqli, $Hook);
            $State->run();
    }
}

function HandleCommand(ObjectHook $Hook, $config, TelegramMysql $mysqli) {

    if ($Hook->getMessage()->getChat()->id < 0) {
        die();
    }

    // Get User Object from Hook
    $User = $Hook->getMessage()->getFrom();
    // Get user from database
    $user = $mysqli->createUserIfDontExists($User);

    if ($Hook->getMessage()->getText() !== Button("set_back") && $Hook->getMessage()->getText() !== "/start") {
        State($user, $mysqli, $Hook);
    }

    $Commands = [
        "/start" => function () use ($config, $Hook, $mysqli) {

            $user = $mysqli->createUserIfDontExists($Hook->getMessage()->getFrom());

            if ($user[5] == 'guest') {
                $keyboard = 'start';
            } else {
                $keyboard = $user[4]."_".$user[3];
            }

            requestApi("sendMessage", [
                "chat_id" => $Hook->getMessage()->getFrom()->getId(),
                "text" => "Клавиатура загружена и данные обновлены 🚿",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard($keyboard),
                    "resize_keyboard" => true
                ])
            ]);
        },
        Button("set_back") => function () use ($config, $Hook, $mysqli) {
            $user = $mysqli->createUserIfDontExists($Hook->getMessage()->getFrom());
            $state = $mysqli->changeUserState($Hook->getMessage()->getFrom(), '');
            if ($user[5] == 'guest') {
                $state = $mysqli->changeUserState($Hook->getMessage()->getFrom(), 'start');
            }

            requestApi("sendMessage", [
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

            $mysqli->changeUserState($User, "fillRequest");
            $mysqli->setInput($User, 0);

            requestApi("sendMessage", [
                "chat_id" => $Hook->getMessage()->getFrom()->getId(),
                "text" => "Откуда вы о нас узнали?",
                "parse_mode" => "markdown",
                "reply_markup" => json_encode([
                    "keyboard" => KeyBoard('fillRequest_0'),
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

function sendRequest($User, $user, $config) {
    $requestScreen = new Screen("Заявка на добавление", [
        "*❗ВНИМАНИЕ❗*: Перед добавлением прочтите информацию о пользователе которую он оставил в заявке",
        "",
    ]);

    $usernameFull = "";

    if ($User->getUsername()) {
        $usernameFull .= "[@".$User->getUsername()."](https://t.me/".$User->getUsername().")";
    } else {
        $usernameFull .= "Скрыто";
    }

    if (strlen(trim($User->getFirstName())) != 0) {
        $usernameFull .= " ".$User->getFirstName();
    }

    if (strlen(trim($User->getLastName())) != 0) {
        $usernameFull .= " ".$User->getLastName();
    }

    $userDataState = jsonDecode($user[6], true);

    if ($userDataState[2] == "Пропущено") {
        $screen = "`(Отсутствует)`";
    } else {

        $res = requestApi("getFile", [
            "file_id" => $userDataState[2][2]['file_id'],
        ]);

        $result = jsonDecode($res, true)['result'];

        $link = uploadFile(TELEGRAM_FILES_STORAGE.$config['token']."/".$result['file_path'])['link'];

        $screen = "[(Присутствует)]($link)";
    }

    $requestScreen->addLine("1️⃣ Имя Пользователя: $usernameFull");
    $requestScreen->addLine("2️⃣ ID: `".$User->getId()."`");
    $requestScreen->addLine("3️⃣ Ранг: `".Rank($user[5])."`");
    $requestScreen->addLine("4️⃣ Откуда узнал: `".$userDataState[1]."`");
    $requestScreen->addLine("5️⃣ Скриншот: ".$screen."");

    $r = requestApi("sendMessage", [
        "chat_id" => $config['bot']['admin_chat_id'],
        "text" => $requestScreen->getText(),
        "parse_mode" => "markdown",
        "reply_markup" => jsonEncode([
            "inline_keyboard" => [
                [
                    [
                        "text" => "💚 Принять заявку",
                        "callback_data" => jsonEncode(["type" => "acceptRequest", ["id" => 1]])
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

