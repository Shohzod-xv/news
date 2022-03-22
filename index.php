<?php
include "public/db.php";

define('API_KEY','5203963382:AAGZ4cxzuVGl_0zshTBOeLtyGa4aGwqkaEc');

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$tx = $message->text;
$PhoneNumber = $message->contact->phone_number;
$first_name = $message->from->first_name;
$user_id = $message->from->id;
$callback = $update->callback_query;
$cbid = $callback->from->id;
$data = $callback->data;
$mes = $callback->message;
$mid = $mes->message_id;
$mmid = $callback->inline_message_id;

$menuBtn = json_encode(
    ['resize_keyboard' => true,
        'keyboard' => [
            [['text' => "Haydovchi kerak"],['text' => "Haydovchiman"]],
            [['text' => "Pochta yuborish"], ['text' => "Malumotlar"]]
        ]
    ]);
if ($tx == "/start") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Assalomu alekum",
    ]);
    if (!check_user($user_id)) {
        query("INSERT INTO users(`first_name`,`user_id`,`insert_at`) values('{$first_name}','{$user_id}',NOW())");
    }
    if (phone($user_id) == null) {
        sleep(0.5);
        bot('sendMessage', [
            'chat_id' => $cid,
            'text' => "Botdan to'liq foydanalish uchun\n*Telefon raqam yuboring*",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => "📱 Send number", 'request_contact' => true]
                    ]
                ]
            ])
        ]);
        update_phone($PhoneNumber,$user_id);
    } else {
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => "*SeonExpress* telegram botiga xush kelibsiz!\n\n*Kerakli bo‘limni tanlang! 👇*",
            'parse_mode' => "markdown",
            'reply_markup' => $menuBtn
        ]);
    }
}
if (mb_stripos($PhoneNumber, "998")!==false) {
    update_phone($PhoneNumber,$user_id);
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "*SeonExpress* telegram botiga xush kelibsiz!\n\n*Kerakli bo‘limni tanlang! 👇*",
        'parse_mode' => "markdown",
        'reply_markup' => $menuBtn
    ]);
}
$tuman = json_encode([
        'inline_keyboard' => [
            [['text' => "Urganch Shahri",'callback_data' => "Urganch Shahri"]],
            [['text' => "Yangibozor",'callback_data' => "Yangibozor"], ['text' => "Bogot",'callback_data' => "Bogot"]],
            [['text' => "Hazorasp",'callback_data' => "Hazorasp"], ['text' => "Gurlan",'callback_data' => "Gurlan"]],
            [['text' => "Tuproq qal'a",'callback_data' => "Tuproq qala"], ['text' => "Qo'shko'pir",'callback_data' => "Qoshkopir"]],
            [['text' => "Shovot",'callback_data' => "Shovot"], ['text' => "Xiva",'callback_data' => "Xiva"]],
            [['text' => "Xonqa",'callback_data' => "Xonqa"], ['text' => "Yangiariq",'callback_data' => "Yangiariq"]],
            [['text' => "🔙 Orqaga",'callback_data' => "bosh_menu_orqaga"]]
        ]
]);
if ($data == "bosh_menu_orqaga"){
    kerak($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*SeonExpress* telegram botiga xush kelibsiz!\n\n*Kerakli bo‘limni tanlang! 👇*",
        'parse_mode' => "markdown"
    ]);
}

if ($tx == "Haydovchi kerak"){
    kerak($user_id);
    buyurtma_start($user_id,$tx,$first_name);
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "*Siz hozir qayerda turibsiz? * \n\nTumanlardan birini tanlang! 👇",
        'parse_mode' => "markdown",
        'reply_markup' => $tuman
    ]);
}

if ($data == "Urganch Shahri") {
    tuman_one($cbid, $data);
    $t = qayerdan($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' =>"*".$t."dan*  ➡️Qayerga bormoqchisiz? \n\nQuyidagilardan birini tanlang! 👇",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Yangibozor", 'callback_data' => "Yangibozor tumani"], ['text' => "Bogot", 'callback_data' => "Bogot tumani"]],
                [['text' => "Hazorasp", 'callback_data' => "Hazorasp tumani"], ['text' => "Gurlan", 'callback_data' => "Gurlan tumani"]],
                [['text' => "Tuproqqal'a", 'callback_data' => "Tuproqala tumani"], ['text' => "Qoshkopir", 'callback_data' => "Qoshkopir tumani"]],
                [['text' => "Shovot", 'callback_data' => "Shovot tumani"], ['text' => "Xiva", 'callback_data' => "Xiva tumani"]],
                [['text' => "Xonqa", 'callback_data' => "Xonqa tumani"], ['text' => "Yangiariq", 'callback_data' => "Yangiariq tumani"]],
                [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman_edit"]],
                [['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
            ]
        ])
    ]);
}
if ($data == "tuman_edit"){
    tuman_edit($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*Siz hozir qayerda turibsiz? * \n\nIltimos ma'lumot kiritishda e'tiborli boling! 👇",
        'parse_mode' => "markdown",
        'reply_markup' => $tuman
    ]);
}
if ($data == "tuman_delete"){
    kerak($cbid);
    bot('sendMessage', [
        'chat_id' => $cbid,
        'text' => "Barcha ma'lumot o'chirildi"
    ]);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*Kerakli bo‘limni tanlang! 👇*",
        'parse_mode' => "markdown"
    ]);
}
if (($data == "Yangibozor") or ($data == "Bogot") or ($data == "Hazorasp") or ($data == "Gurlan") or ($data == "Tuproq qala") or ($data == "Qoshkopir") or ($data == "Shovot") or ($data == "Xiva") or ($data == "Xonqa") or ($data == "Yangiariq")) {
    tuman_one($cbid, $data);
    $qayerdan = qayerdan($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*".$qayerdan."dan ➡️* Qayerga bormoqchisiz\n\nQuyidagilardan birini tanlang! 👇",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Urganch Shahri", 'callback_data' => "Urganch"]],
                [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman_edit"], ['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
            ]
        ])
    ]);
}
if(($data == "Urganch") or ($data == "Yangibozor tumani") or ($data == "Tuproqala tumani") or ($data == "Shovot tumani") or ($data == "Xonqa tumani") or ($data == "Hazorasp tumani") or ($data == "Bogot tumani") or ($data == "Gurlan tumani") or ($data == "Qoshkopir tumani") or ($data == "Xiva tumani") or ($data == "Yangiariq tumani")) {
    tuman_two($cbid, $data);
    $qayerdan = qayerdan($cbid);
    $qayerga = qayerga($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "<b>".$qayerdan."dan</b> 🔜<b> ".$qayerga."ga</b> \n\nQanday avtomobilda ketasiz?",
        'parse_mode' => "html",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Damas", 'callback_data' => "Damas"], ['text' => "Nexia,", 'callback_data' => "Nexia"], ['text' => "Cobalt", 'callback_data' => "Cobalt"], ['text' => "Lasetti", 'callback_data' => "Lasetti"]],
                [['text' => "Eng arzonida", 'callback_data' => "cheap"]],
                [['text' => "Farqi yo'q", 'callback_data' => "same"]],
                [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman2_edit"], ['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
            ]
        ])
    ]);
}
function editBtn($cbid){
    $qayerdan = qayerdan($cbid);
    $a = json_encode([
        'inline_keyboard' => [
            [['text' => "Yangibozor", 'callback_data' => "Yangibozor tumani"], ['text' => "Bogot", 'callback_data' => "Bogot tumani"]],
            [['text' => "Hazorasp", 'callback_data' => "Hazorasp tumani"], ['text' => "Gurlan", 'callback_data' => "Gurlan tumani"]],
            [['text' => "Tuproqqal'a", 'callback_data' => "Tuproqala tumani"], ['text' => "Qo'shko'pir", 'callback_data' => "Qoshkopir tumani"]],
            [['text' => "Shovot", 'callback_data' => "Shovot tumani"], ['text' => "Xiva", 'callback_data' => "Xiva tumani"]],
            [['text' => "Xonqa", 'callback_data' => "Xonqa tumani"], ['text' => "Yangiariq", 'callback_data' => "Yangiariq tumani"]],
            [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman_edit"]],
            [['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
        ]
    ]);
    $b = json_encode([
        'inline_keyboard' => [
            [['text' => "Urganch Shahri", 'callback_data' => "Urganch"]],
            [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman_edit"], ['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
        ]
    ]);
    if ($qayerdan == "Urganch Shahri"){
        return $a;
    }else{
        return $b;
    }
}
if ($data == "tuman2_edit"){
    tuman2_edit($cbid);
    $qayerdan = qayerdan($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*".$qayerdan."dan ➡️* Qayerga bormoqchisiz\n\nQuyidagilardan birini tanlang! 👇",
        'parse_mode' => "markdown",
        'reply_markup' => editBtn($cbid)
    ]);
}
if ($data == "Damas" or $data == "Nexia" or $data == "Cobalt" or $data == "Lasetti" or $data == "cheap" or $data == "same"){
    auto_write($cbid,$data);
    $auto_name = auto_name($cbid);
    $qayerdan = qayerdan($cbid);
    $qayerga = qayerga($cbid);
    bot('editMessageText', [
    'chat_id' => $cbid,
    'message_id' => $mid,
    'inline_message_id' => $mmid,
    'text' => "📍 <b>".$qayerdan."dan</b> 🔜<b> ".$qayerga."ga\n🚕 ".$auto_name."da</b> \n\nNechta kishi ketasiz?",
    'parse_mode' => "html",
    'reply_markup' => json_encode([
        'inline_keyboard' => [
            [['text' => " 1 ", 'callback_data' => "1"], ['text' => " 2 ", 'callback_data' => "2"], ['text' => " 3 ", 'callback_data' => "3"], ['text' => " 4 ", 'callback_data' => "4"]],
            [['text' => "Butun salon", 'callback_data' => "Butun salon"]],
            [['text' => "✍️  O'zgartirish", 'callback_data' => "auto_edit"], ['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
        ]
    ])
]);
}
if ($data == "auto_edit"){
    auto_edit($cbid);
    $qayerdan = qayerdan($cbid);
    $qayerga = qayerga($cbid);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "<b>".$qayerdan."dan</b> 🔜<b> ".$qayerga."ga</b> \n\nQanday avtomobilda ketasiz?",
        'parse_mode' => "html",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "Damas", 'callback_data' => "Damas"], ['text' => "Nexia", 'callback_data' => "Nexia"], ['text' => "Cobalt", 'callback_data' => "Cobalt"], ['text' => "Lasetti", 'callback_data' => "Lasetti"]],
                [['text' => "Eng arzonida", 'callback_data' => "cheap"]],
                [['text' => "Farqi yo'q", 'callback_data' => "same"]],
                [['text' => "✍️  O'zgartirish", 'callback_data' => "tuman2_edit"], ['text' => "❌ Bekor qilish", 'callback_data' => "tuman_delete"]]
            ]
        ])
    ]);
}
if (($data == "1") or ($data == "2") or ($data == "3") or ($data == "4") or ($data == "Butun salon")){
    how_many($cbid,$data);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "*Manzilga o'zingiz kelasizmi?*\n(Manzil yuboriladi)\n\nHaydovchi borib olib ketsinmi?",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "O'zim boraman",'callback_data' => "Ozim boraman"],['text' => "Olib ketsin",'callback_data' => "Olib ketsin"]],
            ]
        ])
    ]);
}
if (($data == "Ozim boraman") or ($data == "Olib ketsin")) {
    go_come($cbid, $data);
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "Iltimos kutib turing...",
        'parse_mode' => "markdown",
    ]);
    sleep(1);
    $tuman = query("Select * from buyurtma where user_id = '$cbid'");
    while ($key = fetch_assoc($tuman)) {
        $auto = $key['auto'];
        $loc_now = $key['loc_now'];
        $loc_after = $key['loc_after'];
        $tuman = query("Select * from haydovchi WHERE `m_turi` = '{$auto}' and `old_loc` = '{$loc_now}' and `next_loc` = '{$loc_after}'");
        foreach ($tuman as $t)
        $id = $t['user_id'];
        $fio = $t['name'];
        $old_loc = $t['old_loc'];
        $next_loc = $t['next_loc'];
        $m_turi = $t['m_turi'];
        $kv = $t['ketish_vaqti'];
        $narx = $t['narx'];
        if ($fio == null) {
            bot('sendMessage', [
                'chat_id' => $cbid,
                'text' => "Hozircha Bunday turdagi avtomobile topilmadi, \n\n<b>Kutib turingSizga shu yonalishdagi boshqa avtomobilni yuboraman</b>",
                'parse_mode' =>'html'
            ]);
            $tuman = query("Select * from haydovchi WHERE `old_loc` = '{$loc_now}' and `next_loc` = '{$loc_after}'");
            while ($t = fetch_assoc($tuman)) {
                $id = $t['user_id'];
                $fio = $t['name'];
                $old_loc = $t['old_loc'];
                $next_loc = $t['next_loc'];
                $m_turi = $t['m_turi'];
                $kv = $t['ketish_vaqti'];
                $narx = $t['narx'];
                sleep(1);
                bot('sendMessage', [
                    'chat_id' => $cbid,
                    'text' => "👨‍✈️ Haydovchi: *$fio*\n\n➖  Turgan Manzil: *$old_loc*\n➖  Boradigan Manzil: *$next_loc*\n➖  Ketish vaqti *$kv*\n\n➖  Moshina  *$m_turi*\n💰 Narxi: *$narx* so'm",
                    'parse_mode' => "markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['text' => "☎️ Haydovchi bilan bog'lanish", 'callback_data' => "aloqa_$id"]],
                        ]
                    ])
                ]);
            }
        } else {
            bot('sendMessage', [
                'chat_id' => $cbid,
                'text' => "👨‍✈️ Haydovchi: *$fio*\n\n➖  Turgan Manzil: *$old_loc*\n➖  Boradigan Manzil: *$next_loc*\n➖  Ketish vaqti *$kv*\n\n➖  Moshina  *$m_turi*\n💰 Narxi: *$narx* so'm",
                'parse_mode' => "markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "☎️ Haydovchi bilan bog'lanish", 'callback_data' => "aloqa_$id"]],
                    ]
                ])
            ]);
        }
    }
}
if (mb_stripos($data, "aloqa")!==false) {
    $id = explode("_", $data)[1];
    $sorov = query("Select * from `haydovchi` where `user_id` = '$id'");
    while ($t = fetch_assoc($sorov)) {
        $did = $t['id'];
        $id = $t['user_id'];
        $fio = $t['name'];
        $old_loc = $t['old_loc'];
        $next_loc = $t['next_loc'];
        $number = $t['number'];
        $m_turi = $t['m_turi'];
        $color = $t['color'];
        $avto_num = $t['avto_num'];
        $bj = $t['bosh_joy'];
        $kv = $t['ketish_vaqti'];
        $narx = $t['narx'];
        bot('editMessageText', [
            'chat_id' => $cbid,
            'message_id' => $mid,
            'inline_message_id' => $mmid,
            'text' => "👨‍✈️ Haydovchi: <b>$fio</b>\n\n▪️ Turgan manzil  <b>$old_loc</b>\n▪️ Boradigan Manzil: <b>$next_loc</b>\n▪️ Bo'sh joylar soni: <b>$bj</b> ta\n▪️ Ketish vaqti: <b>$kv</b>\n\n💰 Narxi: <b>$narx</b> so'm\n▪️ Moshina: <b>$color  $m_turi</b>\n▪️ Raqami: <b>$avto_num</b>\n\n📞 Telefon: <b>$number</b>\n\n✍️Haydovchi telegrami: <b><a href='tg://user?id=$id'>$fio</a></b>\n\n<code>Yuqoridagi raqamga qo'ng'iroq qilib ketish vaqtii gaplashib oling</code>",
            'parse_mode' => "html",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "❌ Bekor qilish", 'callback_data' => "bekor_qilish_$id"], ['text' => "✅ Kelishdim", 'callback_data' => "kelishdim_$id"]],
                ]
            ])
        ]);
        $malumot = query("select * from buyurtma where user_id = '$cbid'");
        foreach ($malumot as $m)
        $order = $m['id'];
        $first_name = $m['first_name'];
        query("Insert into success_order(`status`,`dr_user_id`,`dr_first_name`,`buyurtma_id`,`cu_user_id`,`cu_first_name`,`insert_at`) values ('off','$id','$fio','$order','$cbid','$first_name',NOW())");
    }
}
if (mb_stripos($data, "kelishdim")!==false) {
    $id = explode("_", $data)[1];
    query("Update success_order set status = 'on' where dr_user_id = '$id'");
    bot('editMessageText', [
        'chat_id' => $cbid,
        'message_id' => $mid,
        'inline_message_id' => $mmid,
        'text' => "Sizga yordamim tekganidan xursandman",
        'parse_mode' => "markdown",
    ]);
    $sorov = query("Select * from buyurtma where user_id = '$cbid'");
    while ($key = fetch_assoc($sorov)) {
        $fn = $key['first_name'];
        $loc_now = $key['loc_now'];
        $loc_after = $key['loc_after'];
        $how_many = $key['how_many'];
        $go_come = $key['go_come'];
        $sorov = query("Select * from users where user_id = '$cbid'");
        foreach ($sorov as $gov)
            $tel = $gov['phone_number'];
        if ($go_come == "Olib ketsin"){
            $go_come = "Olib ketish kerak";
            bot('sendMessage', [
                'chat_id' => $id,
                'text' => "<b>Sizga Yangi Yo'lovchi keldi</b>\n\n<b>Ism: <a href='tg://user?id=$cbid'>$fn</a>\nTelefon: $tel</b>\n\n<code><b>Manzil:</b> $loc_now dan ➡️ $loc_after ga $how_many kishi ketadi $go_come</code>",
                'parse_mode' => "html"

            ]);
        }elseif($go_come == "Ozim boraman"){
            $go_come = "Kutib turing manzilga o'zlari boradi";
            bot('sendMessage', [
                'chat_id' => $id,
                'text' => "<b>Sizga Yangi Yo'lovchi keldi</b>\n\n<b>Ism: <a href='tg://user?id=$cbid'>$fn</a>\nTelefon: $tel</b>\n\n<code><b>Manzil:</b> $loc_now dan ➡️ $loc_after ga $how_many kishi ketadi $go_come</code>",
                'parse_mode' => "html"

            ]);
        }

    }
}






