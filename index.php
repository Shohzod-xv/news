<?php

define('API_KEY','5203963382:AAGZ4cxzuVGl_0zshTBOeLtyGa4aGwqkaEc');
include "public/func.php";

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$tx = $message->text;

if ($tx == "/start"){
    bot('sendMessage',[
       'chat_id' =>$cid,
       'text' =>"Assalomu alekum",
    ]);
}