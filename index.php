<?php
//Channel : @ZarinSource
//Author : @MaMaD_NoP
$update = json_decode(file_get_contents('php://input'));
require_once "Medoo.php";
require_once "ZarinSource.php";
use Medoo\Medoo;

$Token = ' 000 '; // توکن ربات
$bot = new ZarinSource($Token);
$DB_NAME = " 000 "; // نام دیتابیس
$DB_USER = " 000 "; // یوزرنیم دیتابیس
$DB_PASS = " 000 "; // پسورد دیتابیس
$Medoo = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => $DB_NAME,
    'username' => $DB_USER,
    'password' => $DB_PASS,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
]);

if(isset($update->message)){
    $message = $update->message;
    $text = $message->text;
    $chat_id = $message->chat->id;
    $name = $message->chat->first_name;
    $from_id = $message->from->id;
    $message_id = $message->message_id;
} else {
    if (isset($update->callback_query)) {
        $callback_query = $update->callback_query;
        $data = $callback_query->data;
        $chat_id = $callback_query->message->chat->id;
        $from_id = $callback_query->from->id;
        $message_id = $callback_query->message->message_id;
        $callback_id = $callback_query->id;
    }
}
function start($lang){
    if ($lang == "fa") {
        $keystart = json_encode([
            'inline_keyboard'=>[
                [
                    ['text' => '📥 دانلود فایل', 'callback_data' => "dlfile"]
                ],
                [
                    ['text' => "📚 راهنما", 'callback_data' => "help"] , ['text' => "🔄 تغییر زبان", 'callback_data' => "changelang"]
                ],
                [
                    ['text' => "📣 چنل ما", 'url' => "https://t.me/ZarinSource"]
                ]
            ]
        ]);
    } else {
        $keystart = json_encode([
            'inline_keyboard'=>[
                [
                    ['text' => '📥 Download File', 'callback_data' => "dlfile"]
                ],
                [
                    ['text' => "📚 Help", 'callback_data' => "help"] , ['text' => "🔄 Change The Language", 'callback_data' => "changelang"]
                ],
                [
                    ['text' => "📣 Our Channel", 'url' => "https://t.me/ZarinSource"]
                ]
            ]
        ]);
    }
    return $keystart;
}
function text($step, $lang, $name = NULL){
    if ($lang == "en") {
        if ($step == "help") {
            $text = "help";
        }elseif ($step == "start") {
            $text = "Hello dear  $name ⁦✋🏻\nWelcome to the download bot from Github\n\nUse the button below to download 👇🏻";
        }elseif ($step == "changelang") {
            $text = "💬 Please select the language you want.";
        }elseif ($step == "dlfile") {
            $text = "💬 Send Github link to download file ...\nExample : <code>https://github.com/MrLotfi696/inline-keyboard</code>";
        }
    } else {
        if ($step == "help") {
            $text = "راهنما";
        }elseif ($step == "start") {
            $text = "سلام $name عزیز ⁦✋🏻\nبه ربات دانلود از گیت هاب خوش اومدی .\n\nبرای دانلود از دکمه زیر استفاده کنید ⁦👇🏻";
        }elseif ($step == "changelang") {
            $text = "💬 لطفا زبان مورد نظر خود را انتخاب کنید.";
        }elseif ($step == "dlfile") {
            $text = "💬 لینک گیت هاب رو برای دانلود فایل ارسال کن ...\nمثال : <code>https://github.com/MrLotfi696/inline-keyboard</code>";
        }
    }
    return $text;
}
function dlfile($lang, $url){
    if ($lang == "fa") {
        $keydl = json_encode([
            'inline_keyboard'=>[
                [
                    ['text' => '📥 دانلود فایل', 'url' => $url]
                ]
            ]
        ]);
    } else {
        $keydl = json_encode([
            'inline_keyboard'=>[
                [
                    ['text' => '📥 Download File', 'url' => $url]
                ]
            ]
        ]);
    }
    return $keydl;
}


$keyback = json_encode([
    'inline_keyboard'=>[
        [
            ['text' => "🔙", 'callback_data' => "back"]
        ]
    ]
]);
$keylang = json_encode([
    'inline_keyboard'=>[
        [
            ['text' => "🇬🇧 English | انگلیسی", 'callback_data' => "en"] , ['text' => "🇮🇷 فارسی | Farsi", 'callback_data' => "fa"]
        ]
    ]
]);

$user = $Medoo->select('user','*',['id' => $from_id])[0];
$userB = $Medoo->has('user',['id' => $from_id]);


if ($data == "fa") {
    if ($userB == false) {$Medoo->insert('user',['id' => $from_id, 'lang' => "en"]);}
    $bot->send($from_id,text("start","fa",$name),$message_id,start("fa"));
    $Medoo->update('user',['lang' => "fa"],['id' => $from_id]);
    $bot->deletemessage($from_id,$message_id);
    return false;
}
if ($data == "en") {
    if ($userB == false) {$Medoo->insert('user',['id' => $from_id, 'lang' => "en"]);}
    $bot->send($from_id,text("start","en",$name),$message_id,start("en"));
    $Medoo->update('user',['lang' => "en"],['id' => $from_id]);
    $bot->deletemessage($from_id,$message_id);
    return false;
}
if ($userB == false || $user['lang'] == NULL) {
    if ($userB == false) {$Medoo->insert('user',['id' => $from_id]);}
    $bot->send($from_id,"لطفاً زبان مورد نظر را انتخاب کنید\nPlease select the desired language",$message_id,$keylang);
    return false;
}



if ($user['lang'] == "en") {   
    if ($text == "/start"){
        $bot->send($from_id,text("start","en",$name),$message_id,start("en"));
    }

    elseif ($data == "help") {
        $bot->editMessageText($from_id,$message_id,text("help","en"),$keyback);
    }
    elseif ($data == "back") {
        $bot->editMessageText($from_id,$message_id,text("start","en",$name),start("en"));
        $Medoo->update('user',['step' => "none"],['id' => $from_id]);
    }
    elseif ($data == "changelang") {
        $bot->editMessageText($from_id,$message_id,text("changelang","en"),$keylang);
    }
    elseif ($data == "dlfile") {
        $bot->editMessageText($from_id,$message_id,text("dlfile","en"),$keyback);
        $Medoo->update('user',['step' => "dlfile"],['id' => $from_id]);
    }

    elseif ($user['step'] == "dlfile") {
        if (strstr($text,"https://github.com/") == true) {
            $url = $text."/archive/refs/heads/main.zip";
            $bot->send($from_id,"Click the button below to download.\nRestart for further operations.\n/start",NULL,dlfile('en',$url));
            $Medoo->update('user',['step' => "none"],['id' => $from_id]);
        } else {
            $bot->send($from_id,"The submitted link is incorrect.\nExample : <code>https://github.com/MrLotfi696/inline-keyboard</code>",NULL,start("fa"));
            $Medoo->update('user',['step' => "none"],['id' => $from_id]);
        }
    }
} else {
    if ($text == "/start"){
        $bot->send($from_id,text("start","fa",$name),$message_id,start("fa"));
    }
    
    elseif ($data == "help") {
        $bot->editMessageText($from_id,$message_id,text("help","fa"),$keyback);
    }
    elseif ($data == "back") {
        $bot->editMessageText($from_id,$message_id,text("start","fa",$name),start("fa"));
    }
    elseif ($data == "changelang") {
        $bot->editMessageText($from_id,$message_id,text("changelang","fa"),$keylang);
    }
    elseif ($data == "dlfile") {
        $bot->editMessageText($from_id,$message_id,text("dlfile","fa"),$keyback);
        $Medoo->update('user',['step' => "dlfile"],['id' => $from_id]);
    }

    elseif ($user['step'] == "dlfile") {
        if (strstr($text,"https://github.com/") == true) {
            $url = $text."/archive/refs/heads/main.zip";
            $bot->send($from_id,"برای دانلود روی دکمه زیر بزنید\nبرای انجام عملیات دیگر ، مجدد استارت کنید.\n/start",NULL,dlfile('en',$url));
            $Medoo->update('user',['step' => "none"],['id' => $from_id]);
        } else {
            $bot->send($from_id,"لینک ارسالی اشتباه است.\nمثال : <code>https://github.com/MrLotfi696/inline-keyboard</code>",NULL,start("fa"));
            $Medoo->update('user',['step' => "none"],['id' => $from_id]);
        }
    }
}