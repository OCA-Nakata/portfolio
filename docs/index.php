<?php
$pdo = new PDO("mysql:host=localhost;dbname=photo_sharing", "root", "");
$stmt = $pdo->query("SELECT photos.*, users.username FROM photos JOIN users ON photos.user_id = users.id ORDER BY photos.created_at DESC");

while ($photo = $stmt->fetch()) {
    echo '<div>';
    echo '<p>投稿者: ' . htmlspecialchars($photo['username']) . '</p>';
    echo '<img src="' . htmlspecialchars($photo['image_path']) . '" alt="photo" width="300"><br>';
    echo '<p>' . htmlspecialchars($photo['description']) . '</p>';
    echo '</div>';
}
?>
