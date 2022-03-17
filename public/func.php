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

class seonExpress{
    public function query($sorov){
        global $conn;
        return mysqli_query($conn, $sorov);
    }
    public function fetch_array($var){
        return mysqli_fetch_all($var, MYSQLI_ASSOC);
    }
    function check_user($name){
        $a = $this->query("SELECT * FROM users WHERE first_name = '{$name}'");

        if (mysqli_num_rows($a) == 0)
            return false;
        else
            return true;
    }
}

