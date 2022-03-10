<?php
require('../component/functions.php');
require('../component/dbconect.php');

// check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $error = [];
  $mail = $_POST['mail'];
  $pass = sha1($_POST['pass1']);

  // echo $pass;

  if (checkblank('mail')) {
    $error['mail'] = 'blank';
  }
  if (checkblank('pass1')) {
    $error['pass1'] = 'blank';
  }

  if (count($error) === 0) {

    $stmt = $db->prepare('SELECT pass,id FROM users WHERE mail=?');
    $stmt->bind_param('s', $mail);
    $ret = $stmt->execute();
    $stmt->bind_result($result, $result2);
    $stmt->fetch();
    if ($result === $pass) {
      //次のページにアカウント情報を渡す
      session_start();
      $_SESSION['login'] = true;
      $_SESSION['id'] = $result2;

      header('Location:register.php');
    } else {
      // echo 'アカウント情報が誤っています';
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
      <main class="login">
        <div class="ct">
          <form action="" method="post">
            <h1>LogIn</h1>
            <div class="formal_input">
              <p>メールアドレス
                <?php if ($error['mail'] === 'blank') { ?>
                  <span class="error">※メールアドレスを入力してください</span>
                <? } ?>
              </p>
              <input type="text" name="mail" value="<?php echo htmlspecialchars($_POST['mail']) ?>">
            </div>
            <div class="formal_input">
              <p>パスワード
                <?php if ($error['pass1'] === 'blank') { ?>
                  <span class="error">※パスワードを入力してください</span>
                <? } ?>

              </p>
              <input type="password" name="pass1">
            </div>
            <div class="submit">
              <a href="signup.php">
                <p>登録がまだの方はコチラ</p>
              </a>
              <button class="green_btn">送信</button>
            </div>
          </form>
        </div>
      </main>


    </body>

    </html>
  </main>
</body>

</html>