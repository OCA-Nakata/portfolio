<?php
session_start();

// ユーザーがログインしているか確認
if (!isset($_SESSION['user_id'])) {
    echo "ログインしてください。";
    exit;
}

// ファイルがアップロードされたときの処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    // ユーザーIDと投稿内容を取得
    $user_id = $_SESSION['user_id'];
    $description = $_POST['description'];

    // アップロードされたファイルの情報
    $image = $_FILES['photo'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    // 許可する画像の拡張子
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

    // 拡張子の確認
    if (!in_array($image_ext, $allowed_exts)) {
        echo "画像ファイルのみアップロードできます（jpg, jpeg, png, gif）。";
        exit;
    }

    // ファイルサイズの制限（2MBまで）
    if ($image_size > 2 * 1024 * 1024) {
        echo "ファイルサイズは2MB以下にしてください。";
        exit;
    }

    // アップロード先ディレクトリ
    $target_dir = 'uploads/';
    // ユニークなファイル名を生成
    $image_name_unique = uniqid() . '.' . $image_ext;
    $target_file = $target_dir . $image_name_unique;

    // 画像をアップロード先ディレクトリに移動
    if (move_uploaded_file($image_tmp_name, $target_file)) {
        // データベース接続
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=photo_sharing", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 写真情報をデータベースに保存
            $stmt = $pdo->prepare("INSERT INTO photos (user_id, image_path, description) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $target_file, $description]);

            echo "写真が投稿されました！";
        } catch (PDOException $e) {
            echo "エラー: " . $e->getMessage();
        }
    } else {
        echo "写真のアップロードに失敗しました。";
    }
}
?>

<!-- HTMLフォーム -->
<form method="post" enctype="multipart/form-data">
    <label for="photo">写真を選択:</label>
    <input type="file" name="photo" id="photo" required><br><br>

    <label for="description">説明:</label><br>
    <textarea name="description" id="description" rows="4" cols="50" required></textarea><br><br>

    <input type="submit" value="投稿">
</form>
