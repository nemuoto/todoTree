<?php

$db = new mysqli('mysql41.onamae.ne.jp','bdb67_nemuoto','lRpcw1(!','bdb67_my_db');
if ($db->connect_error) {
    echo $db->connect_error;
    exit();
} else {
    // echo'接続にせいこうしました';
}