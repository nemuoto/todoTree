<?php


require('../component/dbconect.php');
require('../component/functions.php');








if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = [];


    if (checkblank('mail')) {
        $error['mail'] = 'blank';
    } elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
        $error['mail'] = 'wrong';
    }
    if (checkblank('pass1') || strlen($_POST['pass1']) < 5) {
        $error['pass1'] = 'wrong';
    }

    if (checkblank('pass2') || $_POST['pass1'] != $_POST['pass2']) {
        $error['pass2'] = 'wrong';
    }

    if (count($error) === 0) {

        //値を変数で受け取る

        $mail = $_POST['mail'];
        $pass = sha1($_POST['pass2']);


        $stmt = $db->prepare('SELECT mail FROM users WHERE mail=?');
        $stmt->bind_param('s', $mail);
        $ret = $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        
        if ($result === NULL) {

            // 受け取った値をDBへ登録
            $stmt = $db->prepare('INSERT INTO users (mail,pass) VALUES (?,?)');
            $stmt->bind_param('ss', $mail, $pass);
            $ret = $stmt->execute();
            header('Location:login.php');
        }else{
        $error['mail'] = 'already';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <?php require('../component/meta.php') ?>
</head>

<body>
    <main class="signup">
        <div class="ct">
            <form action="" method="post">
                <h1>Sign Up</h1>
                <?php if ($error['account'] === 'already') { ?>
                    <span class="error">※このアカウントは既に登録済みです</span>
                <?php } ?>
                <div class="formal_input">
                    <p>メールアドレス
                        <?php if ($error['mail'] === 'blank') { ?>
                            <span class="error">※メールアドレスを入力してください</span>
                        <? } ?>
                        <?php if ($error['mail'] === 'wrong') { ?>
                            <span class="error">※メールの書式で入力してください</span>
                        <? } ?>
                        <?php if ($error['mail'] === 'already') { ?>
                            <span class="error">※このメールアドレスはすでに登録済みです</span>
                        <? } ?>

                    </p>
                    <input type="text" name="mail" value="<?php echo htmlspecialchars($_POST['mail']) ?>">
                </div>
                <div class="formal_input">
                    <p>パスワード
                        <?php if ($error['pass1'] === 'wrong') { ?>
                            <span class="error">※5文字以上のパスワードを入力してください</span>
                        <? } ?>
                    </p>
                    <input type="password" name="pass1" value="<?php echo htmlspecialchars($_POST['pass1']) ?>">
                </div>
                <div class="formal_input">
                    <p>パスワード（二回目）
                        <?php if ($error['pass2'] === 'wrong') { ?>
                            <span class="error">※1回目と同じパスワードを入力してください</span>
                        <? } ?>
                    </p>
                    <input type="password" name="pass2">
                </div>
                <div class="submit">
                    <a href="login.php">
                        <p>登録済の方はコチラ</p>
                    </a>
                    <button class="green_btn">送信</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>