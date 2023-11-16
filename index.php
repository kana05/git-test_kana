<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="./css/style.css">
    <title>Git PHP SQL テスト課題</title>
</head>
<body>
    <div class="profile-container">
        <img src="ladybug_01.png" alt="プロフィール写真" class="profile-image">
        <div class="text-container">
            <h1>嘉名 蒼生</h1>
            <p>嘉名蒼生です。かなと読みますが、苗字をよく間違えて呼ばれます^^</p>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=git_test;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $name = $_POST['Name'] ?? null;
            $email = $_POST['email'] ?? null;
            $message = $_POST['message'] ?? null;

            $stmt = $pdo->prepare("INSERT INTO comments (name, email, message, visibility) VALUES (:name, :email, :message, 1)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            $stmt->execute();
        } catch (PDOException $e) {
            exit("エラー: " . $e->getMessage());
        }
    }
    ?>

    <div id="data-form">
        <div id="Title"><h1>コメント一覧</h1></div>
        <div id="comment">
            <?php
            try {
                $dsn = 'mysql:dbname=git_test;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                $sql = 'SELECT id, name, `message` FROM comments WHERE visibility = 1';
                $stmt = $dbh->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id'];
                    $commenterName = $row['name'];
                    $commentMessage = $row['message'];

                    echo "<p>{$id}.{$commenterName}さん:{$commentMessage}</p>\n";
                }
            } catch (PDOException $e) {
                print('エラー:' . $e->getMessage());
                die();
            }
            $dbh = null;
            ?>
        </div>

        <form action="" method="post" id="comment_form">
            <h1>コメントフォーム</h1>
            <div id="name"><h2>ニックネーム</h2><input type="text" name="Name" id="Input_name" placeholder="ああああ" onkeydown="return preventEnterSubmitAndMoveNext(event);"></div>
            <div id="email"><h2>メールアドレス</h2><input type="text" name="email" id="Input_mailadress" placeholder="sample@yahoo.co.jp" onkeydown="return preventEnterSubmitAndMoveNext(event);"></div>
            <div id="message"><h2>内容</h2><textarea name="message" id="textarea_content" cols="30" rows="5" placeholder="入力欄" maxlength="1000" onkeydown="return preventEnterSubmitAndMoveNext(event);"></textarea></div>
            <button type="submit" id="send-btn" onclick="return validateForm(event)">送信</button>
        </form>
    </div>
</body>
</html>

