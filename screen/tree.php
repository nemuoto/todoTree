<?php
SearchPost();
// データベース内の投稿を全て表示
function SearchPost()
{
    require('../component/dbconect.php');
    $user_id = $_SESSION['id'];
    $stmt = $db->prepare('SELECT time, post FROM posts WHERE id=?');
    $stmt->bind_param('s', $user_id);
    $ret = $stmt->execute();
    $stmt->bind_result($time_stamp, $post);
    $posts = [];
    while ($stmt->fetch()) {
        array_push($posts, $post);
        echo $post;
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
<?php require('../component/meta.php') ?>

</head>

<body>
    <div class="tree">
        <div class="black">
            <p class="logout">ログアウト</p>
            <div class="todo">
            </div>
        </div>

        <div class="result">
            <h1>おめでとう！</h1>
            <p class="result_time_screen"></p>
            <p class="result_task_screen"></p>
            <p class="result_rank_screen"></p>
            <div class="green_btn go_top">
                <p>トップへ戻る</p>
            </div>
        </div>
    </div>
</body>

</html>