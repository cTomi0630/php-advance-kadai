<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';
try {
  // DB接続
  $pdo = new PDO($dsn , $user, $password);
  if (isset($_GET['id'])) {
    $sql_del = 'DELETE FROM books WHERE id = :id';
    $stmt_del = $pdo->prepare($sql_del);
    $stmt_del->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt_del->execute();
    $massage = "削除しました。";
  } else {
    exit("処理対象が見つかりませんでした。");
  }
  header("location:read.php?message={$message}");
} catch (PDOException $e) {
  exit($e->getMessage());
}
?>