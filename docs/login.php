<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // データベース接続
    $pdo = new PDO("mysql:host=localhost;dbname=sns", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "ログイン成功！";
    } else {
        echo "無効なユーザー名またはパスワードです。";
    }
}
?>

<form method="post">
    ユーザー名: <input type="text" name="username"><br>
    パスワード: <input type="password" name="password"><br>
    <input type="submit" value="ログイン">
</form>
