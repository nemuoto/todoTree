<?php
//本番用

// $db = new mysqli('mysql41.onamae.ne.jp','bdb67_nemuoto','lRpcw1(!','bdb67_my_db');
// if ($db->connect_error) {
//     echo $db->connect_error;
//     exit();
// } else {
//     // echo'接続にせいこうしました';
// }

//ローカル用
$db  = new mysqli('localhost', 'root', 'root', 'mydb', 8889);
if ($mysql->connect_errno) {
    die($mysql->connect_error);
}