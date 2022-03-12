<?php
session_start();
// error_reporting(1);
require('../component/dbconect.php');


if (isset($_SESSION['login'])) {


    $user_id = $_SESSION['id'];
    $error = [];

    //すでに投稿が存在するか
    $stmt = $db->prepare('SELECT post FROM posts WHERE id=?');
    $stmt->bind_param('s', $user_id);
    $ret = $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();

    //まだタスクを入力していない場合
    if (count($result) === 0) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $set_todo = count($_POST);

            if ($set_todo < 3) {
                $error['list'] = 'short';
            }

            if (count($error) === 0) {
                for ($i = 1; $i < $set_todo + 1; $i++) {
                    $s =  strval('todo' . $i);
                    $posts = [];
                    $posts[$i] = $_POST[$s];

                    $stmt = $db->prepare('INSERT INTO posts (id,post) VALUES (?,?)');
                    $stmt->bind_param('ss', $user_id, $posts[$i]);
                    $ret = $stmt->execute();
                    $stmt->close();
                    header('Location:tree.php');
                }
            }
        }





        //すでにタスクを入力済みの場合
    } else {
        header('Location:tree.php');
    }
} else {
    header('Location:login.php');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php require('../component/meta.php') ?>
</head>


<body>
    <main class="register">
        <div class="ct">
            <p class="logout">ログアウト</p>
            <form action="" method="POST">
                <h1>右下の＋ボタンからタスクを追加してください。<br>
                    全て入力し終えたら、左下の「開始する」を押してください。</h1>


                <? if ($error['list'] === 'short') { ?>
                    <p class="error">最低３つ以上のタスクを入力してください</p>
                <? } ?>

                <div class="list" id="list"></div>

                <div class="formal_input" id="formal_input">
                    <p>タスクを入力してください
                        <?php if ($error['pass1'] === 'blank') { ?>
                            <span class="error">※パスワードを入力してください</span>
                        <? } ?>
                    </p>
                    <input type="text" id="content">
                    <div class="green_btn" id="green_btn2">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
                <div class="submit">
                    <button type="submit" onsubmit="false;">
                        開始する
                    </button>

                    <div class="green_btn" id="green_btn1">
                        <i class="fa-solid fa-plus"></i>
                        <!-- <i class="fas fa-hand-pointer"></i> -->
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script>
        {
            let green_btn1 = document.getElementById('green_btn1');
            let green_btn2 = document.getElementById('green_btn2');
            let formal_input = document.getElementById('formal_input');
            let list = document.getElementById('list');
            let input_c = 0;

            green_btn1.addEventListener('click', () => {
                formal_input.style.transform = "translateY(" + 0 + "px)";

            })

            green_btn2.addEventListener('click', () => {
                let content = document.getElementById('content');

                if (content.value != "") {
                    
                    input_c++;
                    //入力した値を受け取る
                    //入力した値をinputとして生成
                    let input = document.createElement('input');
                input.value = content.value;
                input.name = `todo${input_c}`;
                
                let delete_b = document.createElement('i');
                delete_b.classList.add('fa-solid');
                delete_b.classList.add('fa-trash');
                delete_b.id = `todo${input_c}`;
                
                let list_item = document.createElement('div');
                
                // input.readOnly = true;
                
                //アペンドチャイルド
                list.appendChild(list_item);
                list_item.appendChild(delete_b);
                list_item.appendChild(input);
                
                formal_input.style.transform = "translateY(" + -1000 + "px)";
                
                content.value = '';
                
                
                delete_b.addEventListener('click', (e) => {
                    let id = e.target.id
                    let delete_input = document.getElementsByName(id);

                    console.log(e.target.parentNode);
                    
                    
                    e.target.parentNode.remove();
                })
            } 

            })



            window.addEventListener('keyup', (e) => {

                if (e.keyCode === 13) {
                    input_c++;
                    //入力した値を受け取る
                    let content = document.getElementById('content');
                    //入力した値をinputとして生成
                    let input = document.createElement('input');
                    input.value = content.value;
                    input.name = `todo${input_c}`;

                    let delete_b = document.createElement('i');
                    delete_b.classList.add('fa-solid');
                    delete_b.classList.add('fa-trash');
                    delete_b.id = `todo${input_c}`;

                    let list_item = document.createElement('div');

                    // input.readOnly = true;

                    //アペンドチャイルド
                    list.appendChild(list_item);
                    list_item.appendChild(delete_b);
                    list_item.appendChild(input);

                    formal_input.style.transform = "translateY(" + -1000 + "px)";

                    content.value = '';

                    console.log(delete_b);

                    delete_b.addEventListener('click', (e) => {
                        delete_b.parentNode.remove();
                    })
                }
            })
        }

        {
            let logout = document.querySelector('.logout');
            logout.addEventListener('click', () => {
                window.open('../component/logout.php', '');

            })
        }
    </script>
</body>

</html>