<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php
//ＤＢ接続設定
    $dsn = 'mysql:dbname=tb******db;host=localhost';
	$user = 'tb-******';
	$password = 'PASSWORD';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	    
//データベース内にテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest_51"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "password TEXT,"
	. "date TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	/*$sql ='SHOW CREATE TABLE tbtest_51';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";*/
	
//変数指定
if (!empty($_POST["name"])) {
    $name=$_POST["name"];
}
if (!empty($_POST["comment"])) {
    $comment = $_POST["comment"];
}
if (!empty($_POST["password"])) {
    $password = $_POST["password"];
}
if (!empty($_POST["date"])) {
    $date = date("Y年m月d日 H時i分s秒");
}    
if (!empty($_POST["delete"])) {
    $delete=$_POST["delete"];
}
if (!empty($_POST["deletepass"])) {
    $deletepass=$_POST["deletepass"];
}
if (!empty($_POST["editNO"])) {
    $editNO=$_POST["editNO"];
}
if (!empty($_POST["edit"])) {
    $edit=$_POST["edit"];//編集機能フォーム
}
if (!empty($_POST["editpass"])) {
    $editpass=$_POST["editpass"];
}

//編集、投稿機能
/*//以下3-5
     $lines=file($filename,FILE_IGNORE_NEW_LINES);
      $fp=fopen($filename,"w");
      foreach($lines as $line){
      $form=explode("<>",$line);
        if($form[0]==$editNO && $form[4] ==$password){//投稿番号＝編集番号,投稿＝パスワード
         fwrite($fp, $editNO . "<>" . $name . "<>" . $comment . "<>" . $date . "\n");
        }else{ //投稿番号＝編集番号ではないとき
         fwrite($fp,$form[0]."<>".$form[1]."<>".$form[2]."<>".$form[3].PHP_EOL);
        }
        }
        fclose($fp);*/
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){ //名前、コメントが空ではないとき
     if(!empty($_POST["editNO"])){//編集番号が空ではないとき
       $date = date("Y年m月d日H時i分s秒");
       $id = $editNO; //変更する投稿番号
	   $sql = 'UPDATE tbtest_51 SET name=:name,comment=:comment,date=:date WHERE id=:id';
	   $stmt = $pdo->prepare($sql);
	   $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	   $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	   $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	   $stmt->execute();
	   
	/*if($row['id']==$editNO && $row['password']==$password){//投稿番号＝編集番号,パスワードが一致
    $sql = 'SELECT * FROM tbtest_51 WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
	    echo $row['date'].'<br>';
		
	echo "<hr>";
	}*/

}else{//投稿機能 
        $date = date("Y年m月d日H時i分s秒"); 
	    $sql = $pdo -> prepare("INSERT INTO tbtest_51 (name, comment, date, password) 
	                          VALUES (:name, :comment, :date, :password)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	    $sql -> execute();
}
}

	//表示
	$sql = 'SELECT * FROM tbtest_51';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
	}
	
   //削除機能 
   /*if(!empty($delete) && !empty($deletepass)){//削除フォームに入力がある場合，テーブルのデータを呼び出し配列にする
        $sql = 'SELECT * FROM tbtest_51'; //テーブル名を自分のものに変えてください
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){ //ここまででテーブル内のデータを呼び出した状態
            if($row['id']==$delete){ //該当投稿ならば
                if($row['password']==$deletepass){ //パスワードを照会
                    $sql = 'delete from tbtest_51 where id=:id';    
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
    }*/
   /*if (empty($delete)){
    } else {  //削除指定が空ではない時
         if(empty($deletepass)){
         echo "Error!!";
    }  else{*/
    if(!empty($delete)){
       if(!empty($deletepass)){
            if($delete!= id){    
    
    $id =$delete;
   $sql = 'delete from tbtest_51 where id=:id';
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   $stmt->execute();


	//続けて、4-6の SELECTで表示させる機能 も記述し、表示もさせる。
		$id =$delete; // idがこの値のデータだけを抽出したい、とする

$sql = 'SELECT * FROM tbtest_51 WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll(); 
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
    
            }
     }
    }
  
//編集選択機能
        if(!empty($_POST["edit"])){ //編集対象番号が空ではなかったら
            $sql = 'SELECT * FROM tbtest_51';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
	            //以下で編集したい投稿を探す。
	            if(!empty($_POST["editpass"])){//編集パスが空ではない
	            if($row['id']==$edit){ //該当投稿ならば
                        $editnum=$row['id'];
                        $editname=$row['name'];
                        $editcomment=$row['comment'];
	            }
            }    
        }
    }   


?>

   <form action=""method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $editname;?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment;?>"><br>
        <input type="text" name="editNO" placehpilder="編集番号" value="<?php echo $editnum;?>"><br>
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="submit"><br>
    <br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="deletepass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br>
    <br>
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="hensyu" value="編集"><br>
   </form>
    
</body>
</html>