<?php
session_start();

// データベース接続
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sns", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 写真の情報を取得（新しい順に表示）
    $stmt = $pdo->query("SELECT photos.*, users.username FROM photos JOIN users ON photos.user_id = users.id ORDER BY photos.created_at DESC");
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>写真SNS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .photo-post {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .photo-post img {
            max-width: 100%;
            height: auto;
        }
        .description {
            margin-top: 10px;
        }
        .username {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>最新の写真投稿</h1>

    <?php if ($photos): ?>
        <?php foreach ($photos as $photo): ?>
            <div class="photo-post">
                <p class="username"><?php echo htmlspecialchars($photo['username']); ?> さんの投稿</p>
                <img src="<?php echo htmlspecialchars($photo['image_path']); ?>" alt="photo">
                <p class="description"><?php echo htmlspecialchars($photo['description']); ?></p>
                <p><small>投稿日時: <?php echo date('Y-m-d H:i', strtotime($photo['created_at'])); ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>投稿された写真はまだありません。</p>
    <?php endif; ?>
</div>

</body>
</html>
