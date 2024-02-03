<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';
try {
  // DB接続
  $pdo = new PDO($dsn , $user, $password);

  // 更新処理
  if (isset($_POST['submit'])) {
    if(isset($_GET['id'])){
      $sql_update = 'UPDATE books 
                     SET book_code = :book_code, book_name = :book_name, price = :price, stock_quantity = :stock_quantity, genre_code = :genre_code 
                     WHERE id = :id';
      $stmt_update = $pdo->prepare($sql_update);
      $stmt_update->bindValue(':book_code',$_POST['book_code'],PDO::PARAM_INT);
      $stmt_update->bindValue(':book_name',$_POST['book_name'],PDO::PARAM_STR);
      $stmt_update->bindValue(':price',$_POST['price'],PDO::PARAM_INT);
      $stmt_update->bindValue(':stock_quantity',$_POST['stock_quantity'],PDO::PARAM_INT);
      $stmt_update->bindValue(':genre_code',$_POST['genre_code'],PDO::PARAM_INT);
      $stmt_update->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
      $stmt_update->execute();
      $cnt = $stmt_update->rowCount();

      // メッセージをセットして、一覧画面へ
      $message = "{$cnt}件の書籍情報を更新しました。";
    } else {
      $message = "更新キーが取得できないため、更新できませんでした。";
    }
    header("location: read.php?message={$message}");
  }

  // プルダウン値取得
  $sql_pull = 'SELECT genre_code FROM genres';
  $stmt_pull = $pdo->query($sql_pull);
  $gender_code_list = $stmt_pull->fetchAll(PDO::FETCH_COLUMN);

  // 初期値取得
  if (isset($_GET['id'])) {
    $sql_select = 'SELECT * FROM books WHERE id = :id';
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':id', $_GET['id'] ,PDO::PARAM_INT);
    $stmt_select->execute();
    // TODO　引数忘れた
    $book_data = $stmt_select->fetch();

  } else {
    exit("検索情報が取得できませんでした。");
  }

} catch (PDOException $e) {
  exit($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>書籍管理アプリ</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Google Fontの読み込み -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav><a href="index.php">書籍管理アプリ</a></nav>
  </header>
  <main class="createmain">
  <h2 class="center">書籍編集</h2>
  <div class="display">
    <div><button onclick="location.href='./read.php'" class="btn"><戻る</button></div>
  </div>
  <form action="edit.php?id=<?= $_GET['id'] ?>" method="post">
  <div class="display">
    <div><label for="">書籍コード</label></div>
    <div><input type="text" name="book_code" value="<?= $book_data['book_code'] ?>" min="0" max="100000000" required></div>
    <div><label for="">書籍名</label></div>
    <div><input type="text" name="book_name" value="<?= $book_data['book_name'] ?>" maxlength="50" required></div>
    <div><label for="">単価</label></div>
    <div><input type="text" name="price" value="<?= $book_data['price'] ?>" min="0" max="100000000" required></div>
    <div><label for="">在庫数</label></div>
    <div><input type="text" name="stock_quantity" value="<?= $book_data['stock_quantity'] ?>" min="0" max="100000000" required></div>
    <div><label for="">ジャンルコード</label></div>
    <div><select name="genre_code" required>
      <option value="<?= $book_data['genre_code'] ?>" selected><?= $book_data['genre_code'] ?></option>
      <?php
        foreach ($gender_code_list as $gender_code) {
          echo "<option value='{$gender_code}'>{$gender_code}</option>";
        }
      ?>
    </select></div>
  </div>
  <div class="create-btn"><button type="submit" name='submit' class="btn">更新</button></div>
  </form>
  </main>
  <footer>
    <p class="copyright">&copy; 書籍管理アプリ All rights reserved.</p>
  </footer>
</body>
</html>