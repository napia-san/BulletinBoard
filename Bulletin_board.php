<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mission5</title>
</head>
<body>
  <form method="post">
    <!--投稿フォーム-->
    <p>＊＊投稿フォーム＊＊<br>名前:<input type="text" name="name"></p>
    <p>コメント:<input type="text" name="comment"></p>
    <p>パスワード:<input type="password" name="new_pass"></p>
    <p><input type="submit" name="newpost"></p>
  
    <!--削除フォーム-->
    <p>==削除フォーム==<br>投稿番号:<input type="number" name="del"></p>
    <p>パスワード:<input type="password" name="del_pass"></p>
    <p><input type="submit" value="削除" name="delete"></p>

    <!--編集フォーム-->
    <p>＜＜編集フォーム＞＞<br>投稿番号: <input type="number" name="mod_num"></p>
    <p>コメント： <input type="text" name="ed_content"></p>
    <p>パスワード:<input type="password" name="ed_pass"></p>
    <p><input type="submit" value="編集" name="edit"></p>
    <hr>
  </form>

  <?php
    //DB接続設定
    $dsn = 'mysql:dbname=********;host=localhost;charset=utf8';
    $user = '********';
    $password = '********';
    //PDOインスタンスの生成
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //DBのセットアップ
    /** 
     * id:投稿番号 自然数，自動でカウント
     * time:タイムスタンプ
     * pass:パスワード
    */
    $sql = "CREATE TABLE IF NOT EXISTS bbs"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name CHAR(32),"
    ."comment VARCHAR(140),"
    ."time CHAR(32),"
    ."pass CHAR(32)"
    .");";
    $stmt = $pdo -> query($sql);

    //新規投稿パート
    if($_POST['newpost'] && !empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['new_pass'])){
      $names = $_POST['name'];
      $comments = $_POST['comment'];
      $time = date('Y/m/d H:i:s');
      $pass = $_POST['new_pass'];
      $sql = $pdo -> prepare("INSERT INTO bbs (name, comment, time, pass) VALUES (:name,:comment,:time,:pass)");
      $sql -> bindParam(':name', $names, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comments, PDO::PARAM_STR);
      $sql -> bindParam(':time', $time, PDO::PARAM_STR);
      $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
      $sql -> execute();
    }elseif($_POST['newpost']){/**不正入力判定 */
      if(empty($_POST['name'])){
        echo '<span style="color:red;">[ERROR]名前が入力されていません.<br></span>';
      }
      if(empty($_POST['comment'])){
        echo '<span style="color:red;">[ERROR]コメントが入力されていません.<br></span>';
      }
      if(empty($_POST['new_pass'])){
        echo '<span style="color:red;">[ERROR]パスワードが入力されていません.<br></span>';
      }
    }

    //削除パート
    if($_POST['delete'] && !empty($_POST['del']) && !empty($_POST['del_pass'])){
        $del_num = $_POST['del'];
        $pass = $_POST['del_pass'];
        $stmt = $pdo -> prepare("DELETE FROM bbs WHERE id = :id AND pass = :pass");
        $stmt -> bindParam(':id',$del_num,PDO::PARAM_INT);
        $stmt -> bindParam(':pass',$pass,PDO::PARAM_STR);
        $stmt -> execute();
    }elseif($_POST['delete']){/**不正入力判定 */
      if(empty($_POST['del'])){
        echo '<span style="color:red;">[ERROR]削除対象が指定されていません.<br></span>';
      }
      if(empty($_POST['del_pass'])){
        echo '<span style="color:red;">[ERROR]パスワードが入力されていません.<br></span>';
      }
    }

    //編集パート
    if($_POST['edit'] && !empty($_POST['mod_num']) && !empty($_POST['ed_content'])){
      $num = $_POST['mod_num'];
      $comment = $_POST['ed_content'];
      $pass = $_POST['ed_pass'];
      $stmt = $pdo -> prepare("UPDATE bbs SET comment = :comment WHERE id = :id AND pass = :pass");
      $stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);
      $stmt -> bindParam(':id',$num,PDO::PARAM_INT);
      $stmt -> bindParam(':pass',$pass,PDO::PARAM_STR);
      $stmt -> execute();
    }elseif($_POST['edit']){/**不正入力判定 */
      if(empty($_POST['mod_num'])){
        echo '<span style="color:red;">[ERROR]編集対象が指定されていません.<br></span>';
      }
      if(empty($_POST['ed_content'])){
        echo '<span style="color:red;">[ERROR]編集内容が入力されていません.<br></span>';
      }
      if(empty($_POST['ed_pass'])){
        echo '<span style="color:red;">[ERROR]パスワードが入力されていません.<br></span>';
      }
    }

    //表示パート
    $sql = "SELECT * FROM bbs";
    $stmt = $pdo -> query($sql);
    $get = $stmt -> fetchAll();
    echo '<br>';
    foreach($get as $row){
      echo 'No: '. $row['id'] . '<br>';
      echo 'Name: '. $row['name'] . '<br>';
      echo 'Comment: '. $row['comment'] . '<br>';
      echo 'date:'. $row['time'].'<br>';
      echo '＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊<br>';
    }

  ?>
</body>
</html>