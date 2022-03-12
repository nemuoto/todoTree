<?

session_start();
error_reporting(0);

if (isset($_SESSION['login'])) {
    require('../component/dbconect.php');
    // ユーザーIDを取得
    $user_id = $_SESSION['id'];
    $stmt = $db->prepare('SELECT time, post FROM posts WHERE id=?');
    $stmt->bind_param('s', $user_id);
    $ret = $stmt->execute();
    $stmt->bind_result($time_stamp, $post);
    $posts = [];

    while ($stmt->fetch()) {
        array_push($posts, $post);
    }
    $start_date =  $time_stamp[8] . $time_stamp[9];

    $posts_c = count($posts);
} else {
    header('Location:login.php');
} ?>



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

    <script>
        {
            let tree = document.querySelector(".tree");
            let todo = document.querySelector(".todo");
            let item = [];
            let btn = [];
            let text = [];
            let hp = 100;

            <?php for ($i = 0; $i <  count($posts); $i++) { ?>
                item[<?php echo $i ?>] = document.createElement("div");
                item[<?php echo $i ?>].classList.add('item');
                item[<?php echo $i ?>].classList.add('<?php echo $i ?>');

                btn[<?php echo $i ?>] = document.createElement("div");
                btn[<?php echo $i ?>].classList.add('btn');

                text[<?php echo $i ?>] = document.createElement("p");
                btn[<?php echo $i ?>].classList.add('text');

                text[<?php echo $i ?>].textContent = '<?php echo $posts[$i] ?>'

                todo.appendChild(item[<?php echo $i ?>]);
                item[<?php echo $i ?>].appendChild(btn[<?php echo $i ?>]);
                item[<?php echo $i ?>].appendChild(text[<?php echo $i ?>]);

                btn[<?php echo $i ?>].addEventListener('click', () => {
                    //消す処理を入れたい
                    // window.open('../component/dltask.php', '');

                    btn[<?php echo $i ?>].parentElement.remove();
                    change_tree(energy);
                });

                <? if ($i === count($posts) - 1) { ?>
                    let item_c = document.querySelectorAll('.item').length
                    let energy = hp / item_c;
                <? } ?>

                function change_tree(energy) {
                    hp -= energy;
                    console.log(hp);
                    if (hp < 95) {
                        tree.style.background = "url('../img/def/0.gif')";
                    }
                    if (hp < 80) {
                        tree.style.background = "url('../img/def/1.gif')";
                    }
                    if (hp < 60) {
                        tree.style.background = "url('../img/def/2.gif')";
                    }
                    if (hp < 40) {
                        tree.style.background = "url('../img/def/3.gif')";
                    }
                    if (hp < 20) {
                        tree.style.background = "url('../img/def/4.gif')";
                    }
                    if (hp < 1) {

                        result();
                    }
                }

                function result() {
                    let result = document.querySelector('.result');
                    let go_top = document.querySelector('.go_top');
                    let result_time_screen = document.querySelector('.result_time_screen');
                    let result_task_screen = document.querySelector('.result_task_screen');
                    let result_rank_screen = document.querySelector('.result_rank_screen');

                    let finish_date = <?php echo date('j'); ?>;
                    let result_date = finish_date - <?php echo $start_date ?>

                    result.style.opacity = '1';
                    result_time_screen.textContent = 'かかった日にち:' + result_date + '日';
                    result_task_screen.textContent = '<?php echo '達成したタスク:' . $posts_c . '個' ?>'


                    go_top.addEventListener('click', () => {
                        window.open('../component/new.php', '');

                    })
                }

            <?php } ?>
        }

        {
            let logout = document.querySelector('.logout');
            logout.addEventListener('click', () => {
                window.open('../component/logout.php', '');

            })
        }

        {
            //ランクによってボーダーカラーを変更

            let tree = document.querySelector('.tree');
            <?php
            $stmt = $db->prepare('SELECT rank FROM users WHERE id=?');
            $stmt->bind_param('i', $user_id);
            $ret = $stmt->execute();
            $stmt->bind_result($rank);
            $stmt->fetch();
            $stmt->close();

            if ($rank > 100) { ?>
                tree.style.border = '3px solid skyblue';
            <?php                } elseif ($rank > 50) { ?>
                tree.style.border = '3px solid purple';
            <?php           } elseif ($rank > 20) { ?>
                tree.style.border = '3px solid gold';
            <?php      } elseif ($rank > 10) { ?>
                tree.style.border = '3px solid silver';
            <?php   } elseif ($rank > 0) { ?>
                tree.style.border = '3px solid brown';
            <?php } ?>

        }
    </script>
</body>

</html>