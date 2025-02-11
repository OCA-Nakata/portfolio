<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // パスワードをハッシュ化
    $email = $_POST['email'];

    // データベース接続
    $pdo = new PDO("mysql:host=localhost;dbname=sns", "root", "");
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $email]);

    echo "登録完了！";
}
?>

<form method="post">
    ユーザー名: <input type="text" name="username"><br>
    パスワード: <input type="password" name="password"><br>
    メール: <input type="email" name="email"><br>
    <input type="submit" value="登録">
</form>
