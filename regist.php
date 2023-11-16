<!-- comments.php（コメント表示ページ） -->
<!DOCTYPE html>
<html>
<head>
    <title>コメント管理</title>
    <link rel="stylesheet" href="./css/aroma.css">
</head>
<body>
    <h1>管理画面</h1>
    <?php
    // データベース接続
    $db = new PDO('mysql:host=127.0.0.1;dbname=git_test', 'root', '');

    // コメントを取得
    $stmt = $db->prepare('SELECT id, name, email, message, visibility FROM comments');
    $stmt->execute();
    $comments = $stmt->fetchAll();

    // 取得したコメントを表示
    echo '<form action="" method="post">';
    foreach ($comments as $comment) {
        echo '<div class="comment">';
        echo '<input type="checkbox" name="visibility_' . $comment['id'] . '">';
        echo '<div class="read">';
        echo '<span>ID: ' . $comment['id'] . '</span><br>';
        echo '<span>Name: ' . $comment['name'] . '</span><br>';
        echo '<span>Email: ' . $comment['email'] . '</span><br>';
        echo '<span>Message: ' . $comment['message'] . '</span>';
        echo '</div>';
        if (isset($comment['visibility']) && $comment['visibility'] == 1) {
            echo '<div class="resulto">送信</div>';
        } else {
            echo '<div class="resultn">非表示</div>';
        }
        echo '</div>';
    }

    echo '<input type="submit" name="action" value="送信">';
    echo '<input type="submit" name="action" value="非表示">';
    echo '</form>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === '送信') {
                // 「表示」ボタンが押された場合
                foreach ($comments as $comment) {
                    if (isset($_POST['visibility_' . $comment['id']])) {
                        // チェックボックスが選択されたコメントの visibility 値を1に設定する
                        $updateStmt = $db->prepare('UPDATE comments SET visibility = 1 WHERE id = :comment_id');
                        $updateStmt->bindParam(':comment_id', $comment['id']);
                        $updateStmt->execute();
                    }
                }
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit;
            } elseif ($_POST['action'] === '非表示') {
                // 「非表示」ボタンが押された場合
                foreach ($comments as $comment) {
                    if (isset($_POST['visibility_' . $comment['id']])) {
                        // チェックボックスが選択されたコメントの visibility 値を0に設定する
                        $updateStmt = $db->prepare('UPDATE comments SET visibility = 0 WHERE id = :comment_id');
                        $updateStmt->bindParam(':comment_id', $comment['id']);
                        $updateStmt->execute();
                    }
                }
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
    ?>
</body>
</html>
