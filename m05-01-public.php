<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>m05-01</title>
    </head>
    <body>
<?php

    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成→数字のみにしない、マイナスの記号は使わないのが無難
    $sql = "CREATE TABLE IF NOT EXISTS database_5_1"

    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(10),"
    . "comment TEXT,"
    . "created_date DATETIME,"
    . "new_password char(20)"
    .");";
    $stmt = $pdo->query($sql);
    
    //投稿機能

    //名前とコメントが空でないとき
    if(!empty($_POST["name"]) && !empty($_POST["comment"])){
        //かつパスワードが空でないとき
        if(!empty($_POST["new_password"])){
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $new_password = $_POST['new_password'];
            $created_date = date('Y/n/j G:i:s');
            $sql = $pdo -> prepare("INSERT INTO database_5_1 (id, name, comment, created_date, new_password) VALUES (:id, :name, :comment, :created_date, :new_password)");
            $sql -> bindParam(':id', $id, PDO::PARAM_INT);
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':created_date', $created_date, PDO::PARAM_STR);
            $sql -> bindParam(':new_password', $new_password, PDO::PARAM_STR);
            $sql -> execute();
        }
    
        //editnum_hiddenが空でないとき
        if(!empty($_POST["editnum_hidden"])){
            
            //編集機能
            $id = $_POST['editnum_hidden'];
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $created_date = date("Y/n/j G:i:s");   
            $sql = 'UPDATE database_5_1 SET name=:name, comment=:comment, created_date=:created_date WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':created_date', $created_date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            } 
    }

   //削除機能
   if(!empty($_POST["delnum"]) && (!empty($_POST["del_password"]))){
        $id = $_POST['delnum'];
        $del_password = $_POST['del_password'];
        $sql = 'delete from database_5_1 where id=:id && new_password=:del_password';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':del_password', $del_password, PDO::PARAM_STR);
        $stmt->execute();
   }

    //編集選択機能
    if(!empty($_POST["editnum"])){
        $editnum = $_POST['editnum'];
        $edit_password = $_POST['edit_password'];
        $sql = 'SELECT * FROM database_5_1 WHERE id=:editnum && new_password=:edit_password';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':editnum', $editnum, PDO::PARAM_INT);
        $stmt->bindParam(':edit_password', $edit_password, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            $editname = $row['name'];
            $editcomment = $row['comment'];
            $editnumber = $row['id'];
        }
    }

?>

    <!--投稿フォーム-->
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value = "<?php if(isset($editname)) {echo $editname;} ?>"> <br>
        <input type="text" name="comment" placeholder="コメント" value = "<?php if(isset($editcomment)) {echo $editcomment;} ?>"> <br>
        <input type="password" name="new_password" placeholder="パスワード"> <br>
        <input type="submit" name="submit"><br>
        <input type="hidden" name="editnum_hidden" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>"> <br>
    </form>

    <!--削除フォーム-->
    <form action="" method="post">
        <input type="text" name="delnum" placeholder="削除対象番号"> <br>
         <input type="password" name="del_password" placeholder="パスワード"> <br>
        <input type="submit" value="削除"><br><br>
    </form>

    <!--編集番号指定用フォーム-->
    <form action="" method="post">
        <input type="text" name="editnum" placeholder="編集対象番号"> <br>
        <input type="password" name="edit_password" placeholder="パスワード"> <br>
        <input type="submit" value="編集"><br><br>
    </form>

<?php
    $sql = 'SELECT * FROM database_5_1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['created_date'].'<br>';
        echo "<hr>";
    }
?>

</body>
</html>