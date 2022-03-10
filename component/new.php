<?php
require('../component/dbconect.php');
session_start();

$user_id = $_SESSION['id'];

// 投稿を削除
$stmt = $db->prepare('DELETE FROM posts WHERE id=?');
$stmt->bind_param('s', $user_id);
$ret = $stmt->execute();
$stmt->bind_result($result);
$stmt->close();

// //ランクを取得
$stmt = $db->prepare('SELECT rank FROM users WHERE id=?');
$stmt->bind_param('s', $user_id);
$ret = $stmt->execute();
$stmt->bind_result($rank);
$stmt->fetch();
$stmt->close();

//ランクを更新
$rank += 1;
$stmt = $db->prepare("UPDATE users SET rank = ? WHERE id=?");
$stmt->bind_param('ss', $rank, $user_id);
$stmt->execute();
$stmt->close();


// 連続ログインの時と処理
$stmt = $db->prepare('SELECT last_date FROM users WHERE id=?');
$stmt->bind_param('s', $user_id);
$ret = $stmt->execute();
$stmt->bind_result($yesterday);
$stmt->fetch();
$stmt->close();

//今日の日にち
$today =  (int)date('d');

//先月の月末の日にち
$month_last_date = (int)date('d', mktime(0, 0, 0, date('m') + 0, 0, date('Y')));

if ($yesterday === $month_last_date) {
        $yesterday = 0;
}

if ($today - $yesterday === 1) {
        //ランクを取得
        $stmt = $db->prepare('SELECT rank FROM users WHERE id=?');
        $stmt->bind_param('s', $user_id);
        $ret = $stmt->execute();
        $stmt->bind_result($rank);
        $stmt->fetch();
        $stmt->close();

        //ランクを更新
        $rank += 1;
        $stmt = $db->prepare("UPDATE users SET rank = ? WHERE id=?");
        $stmt->bind_param('ss', $rank, $user_id);
        $stmt->execute();
        $stmt->close();
}

$stmt = $db->prepare("UPDATE users SET last_date = ? WHERE id=?");
$stmt->bind_param('ss', $today, $user_id);
$stmt->execute();
$stmt->close();

header('Location:../screen/register.php');