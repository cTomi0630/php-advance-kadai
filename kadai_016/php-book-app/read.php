<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';
try {
  // DB接続
  $pdo = new PDO($dsn , $user, $password);

  // sort条件の判定と設定
  if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
  } else {
    $sort = 'asc';
  }

  // 検索条件の判定と設定
  if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    // SQL準備
    if($sort === 'desc'){
      $sql_select = 'SELECT * FROM books WHERE book_name like :keyword ORDER BY book_code DESC';
    } else {
      $sql_select = 'SELECT * FROM books WHERE book_name like :keyword ORDER BY book_code ASC';
    }
    // バインド変数の設定
    $keywordlike = "%{$keyword}%";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':keyword', $keywordlike, PDO::PARAM_STR);
    // SQL実行
    $stmt_select->execute();
  } else {
    // 検索条件の指定がない場合は、WHERE条件なし。
    $keyword = NULL;
    // SQL準備
    if($sort === 'desc'){
      $sql_select = 'SELECT * FROM books ORDER BY book_code DESC';
    } else {
      $sql_select = 'SELECT * FROM books ORDER BY book_code ASC';
    }
    // SQL実行
    $stmt_select = $pdo->query($sql_select);
  }

  // 結果の取得 TODO 引数なんだっけ
  $booklist = $stmt_select->fetchAll();

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
<div class="wrapper">
  <header>
    <nav><a href="index.php">書籍管理アプリ</a></nav>
  </header>
  <main>
  <h2>書籍一覧</h2>
  <?php
      // 商品の登録・編集・削除後、messageパラメータの価を受け取っていれば
      if(isset($_GET['message'])){
        echo "<p class='success'>{$_GET['message']}</p>";
      }
  ?>
  <div class="parentdiv">
    <div>
      <a href="read.php?sort=asc"><img src="images/asc.png" alt="昇順" class="sort"></a>
      <a href="read.php?sort=desc"><img src="images/desc.png" alt="降順" class="sort"></a>
    </div>
    <div>
      <form action="read.php" method="get">
        <input type="text" class="serchtext" placeholder="書籍名で検索" name="keyword" value="<?= $keyword ?>">
      </form>
    </div>
    <div>
     <button onclick="location.href='./create.php'" class="btn">書籍登録</button>
    </div>
  </div>
  <table class='books-table'>
    <tr>
      <th>書籍コード</th>
      <th>書籍名</th>
      <th>単価</th>
      <th>在庫数</th>
      <th>ジャンルコード</th>
      <th>編集</th>
      <th>削除</th>
    </tr>
      <?php
      foreach ($booklist as $book) {
        $table_row = " 
          <tr>
             <td>{$book['book_code']}</td>
             <td>{$book['book_name']}</td>
             <td>{$book['price']}</td>
             <td>{$book['stock_quantity']}</td>
             <td>{$book['genre_code']}</td>
             <td><a href='edit.php?id={$book['id']}'><img src='images/edit.png' alt='編集' class='change'></a></td>
             <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' alt='削除' class='change'></a></td>
          <tr>
        ";
        echo $table_row;
      }
      ?>
  </table>
  </main>
  <footer>
    <p class="copyright">&copy; 書籍管理アプリ All rights reserved.</p>
  </footer>
</div>
</body>
</html>