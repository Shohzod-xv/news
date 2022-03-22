<?php

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
function fetch_array($var){
    return mysqli_fetch_all($var,MYSQLI_ASSOC);
}
function fetch_assoc($var){
    return mysqli_fetch_assoc($var);
}
function query($sorov){
    global $conn;
    return mysqli_query($conn,$sorov);
}
function check_user($user_id){
    $a = query("SELECT * FROM `users` WHERE `user_id` = '{$user_id}'");
    if (mysqli_num_rows($a) == 0)
        return false;
    else
        return true;
}
function phone($user_id){
    $phone = query("SELECT * FROM users WHERE user_id = '{$user_id}'");
    foreach ($phone as $phones):
        return $phones['phone_number'];
    endforeach;
}
function update_phone($PhoneNumber,$user_id){
    return query("UPDATE users SET phone_number = '{$PhoneNumber}' WHERE user_id = '{$user_id}'");
}
function buyurtma_start($user_id,$tx,$first_name)
{
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$user_id}'");
    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET update_at = NOW() where user_id = '$user_id'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`first_name`,`kerak`,`insert_at`) values('$user_id','$first_name','$tx',NOW())");
    }
}
function tuman_one($cbid,$data){
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$cbid}'");
    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET loc_now = '$data', update_at = NOW() where user_id = '$cbid'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`loc_now`,`insert_at`) values('$cbid','$data',NOW())");
    }
}
function tuman_two($cbid,$data){
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$cbid}'");
    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET loc_after = '$data', update_at = NOW() where user_id = '$cbid'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`loc_after`,`insert_at`) values('$cbid','$data',NOW())");
    }
}
function how_many($cbid,$data){
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$cbid}'");

    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET how_many = '$data', update_at = NOW() where user_id = '$cbid'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`how_many`,`insert_at`) values('$cbid','$data',NOW())");
    }
}
function go_come($cbid,$data){
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$cbid}'");

    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET go_come = '$data', update_at = NOW() where user_id = '$cbid'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`go_come`,`insert_at`) values('$cbid','$data',NOW())");
    }
}
function auto_write($cbid,$data){
    $a = query("SELECT * FROM `buyurtma` WHERE `user_id` = '{$cbid}'");

    if (mysqli_num_rows($a) != 0) {
        return query("UPDATE `buyurtma` SET auto = '$data', update_at = NOW() where user_id = '$cbid'");
    } else {
        return query("INSERT INTO buyurtma(`user_id`,`auto`,`insert_at`) values('$cbid','$data',NOW())");
    }
}
function auto_name($cbid){
    $tuman = query("Select * from buyurtma WHERE `user_id` = '{$cbid}'");
    foreach ($tuman as $t):
        return $t['auto'];
    endforeach;
}
function kerak($cbid){
    return query("DELETE FROM `buyurtma` WHERE `user_id` = '{$cbid}'");
}
function qayerdan($cbid){
    $tuman = query("Select * from buyurtma WHERE `user_id` = '{$cbid}'");
    foreach ($tuman as $t):
        return $t['loc_now'];
    endforeach;
}
function qayerga($cbid){
    $tuman = query("Select * from buyurtma WHERE `user_id` = '{$cbid}'");
    foreach ($tuman as $t):
        return $t['loc_after'];
    endforeach;
}
function tuman_edit($cbid){
    return query("Update buyurtma Set loc_now = null where user_id = '{$cbid}'");
}
function tuman2_edit($cbid){
    return query("Update buyurtma Set loc_after = null where user_id = '{$cbid}'");
}
function auto_edit($cbid){
    return query("Update buyurtma Set auto = null where user_id = '{$cbid}'");
}

function auto($cbid){
    $tuman = query("Select * from buyurtma where user_id = '$cbid'");
    foreach ($tuman as $t):
        return $t['auto'];
    endforeach;
}
function loc_now($cbid){
    $tuman = query("Select * from buyurtma where user_id = '$cbid'");
    foreach ($tuman as $t):
        return $t['loc_now'];
    endforeach;
}
function loc_after($cbid){
    $tuman = query("Select * from buyurtma where user_id = '$cbid'");
    foreach ($tuman as $t):
        return $t['loc_after'];
    endforeach;
}
function id($id)
{
    $sorov = query("Select * from haydovchi where user_id = '$id'");
    foreach ($sorov as $s):
        return $s['user_id'];
    endforeach;
}

