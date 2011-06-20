<?php
require_once __DIR__.'/vendor/facebook/src/facebook.php';

// appId と secret は「マイアプリ」のページで確認できます
// https://www.facebook.com/developers/apps.php
$facebook = new Facebook(array(
    'appId' => '###################',
    'secret' => '#####################',
));

// ログイン状態を取得します
$user = $facebook->getUser();

if ($user) {
    // メッセージが投稿されたときは Facebook に送信
    if(isset($_POST['message'])) {
        $facebook->api('/me/feed', 'POST', array(
            'message' => $_POST['message'],
        ));
        header(sprintf('Location: http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME']));
        exit;
    }

    // ユーザーの情報を取得します
    $user_profile = $facebook->api('/me');

    // ログインしている場合はログアウトページ
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    // ログインしていない場合はログインページ
    // ウォールに投稿する権限を取得
    $loginUrl = $facebook->getLoginUrl(array(
        'scope' => 'publish_stream',
    ));
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>otobank-test</title>
</head>
<body>
<?php if ($user): ?>
  <p><?php echo $user_profile['name'] ?> さんの今日の気分は？</p>
  <form action="" method="post">
    <ul>
      <li><input type="submit" name="message" value="飲みに行こう！" /></li>
      <li><input type="submit" name="message" value="探さないでください" /></li>
      <li><input type="submit" name="message" value="ぎゃふん" /></li>
    </ul>
  </form>
<?php else: ?>
  <p>アプリを使用するには<a target="_top" href="<?php echo $loginUrl ?>">ログイン</a>してください</p>
<?php endif ?>

<p><a target="_blank" href="http://tech.otobank.co.jp/">OTOBANK Developer's Blog</a></p>

</body>
</html>
