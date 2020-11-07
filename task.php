<?php

// 定数 メッセージを保存するファイルのパス設定
define('FILENAME', './message.txt');

date_default_timezone_set('Asia/Tokyo');


// 変数の初期化
// Q なぜ変数の初期化を行うのか（元々変数だけの型はnullである）
$now_date =null;
$data = null;
$file_handle =null;
$split_data = null;
$message = array();
$message_arry = array();
$error_message = array();


$name = $_POST['view_name'];
$txt =  $_POST['message'];
$limit_name = 10;
$limit_message = 50;
$limit_display = 5;


// method属性がpostであるフォームから送られた情報
// Q.なぜ!emptyの否定なのか。
if ( !empty($_POST['btn_submit'])) {


//名前が未入力だと表示される
	if( empty($_POST['view_name']) ) {
	 	$error_message[] = '名前を入力してください';
	};

	//未入力だと表示される
	if( empty($_POST['message']) ) {
	 $error_message[] = '内容が書かれていです';
	};


	//投稿された名前が制限を超えたら表示される
	//Q mb_strlenを使うことで何をしているか
	if(mb_strlen($name) >= $limit_name) {
			$error_message[] = '名前は10文字以内でお願いします';
	};

	//投稿された内容が制限を超えたら表示される
	if(mb_strlen($txt) >= $limit_message) {
			$error_message[] = '内容は50文字以内でお願いします';
	};


		//$error_messageがからだったら動かない
		//Q このままだとtrueの状態に見えるがなぜ空だと実行されないのか ..11/7は意味が分かったぞ未来の自分
		//HINT 空じゃなければfalse
		if(empty($error_message) ) {


	// ファイルを開いて書き込んでねという処理を行っている
	// 	Q なぜ変数を用いてfopenを書いているか？
	//  Q2  fopenの記述方法  "a"とは何か？
	if( $file_handle = fopen( FILENAME, "a")) {


		// 書き込み日時を取得
		$now_date = date("Y-m-d H:i:s");


		// 書き込むデータの作成
		//  Qこの時の$_POSTの中身は何を表しているか
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";


		// 書き込み
		fwrite( $file_handle,$data);


		// ファイルを閉じる
		// Qなぜfclose関数で閉じているか
		fclose( $file_handle);
	};


			// リロードによる二重投稿の防止
			//Q なぜこの場所に記述しないといけないのか
			if ($_SERVER['REQUEST_METHOD'] ==='POST') {
			header("Location:http://localhost/Task/task.php");
			exit;
		 };

 };

};



// 上記と同じ
// Q なぜ'r'と書いているのか
if($file_handle = fopen(FILENAME,'r')) {


	// 一行ずつ読み込んでいる状態
	// Q なぜループをさせているか fgetについて
	// for分でも掛ける希ガスでもわからない
	while ($data = fgets($file_handle)) {


		// 文字の分割
		// Q 文字の分割によることで次の連想配列につながるなぜ？
		$split_data = preg_split('/\'/',$data);


		//$messageに連想配列
		//Q この時$messageは何をおこなっているのか
		$message = [
			'view_name' => $split_data[1],
			'message' => $split_data[3],
			'post_date' =>$split_data[5]
		];


		// $message_arryの中に$messageを代入している状態
		// Q array_unshiftについてggrks
		array_unshift($message_arry,$message);
		// echo $data."<br>";
	}
	fclose($file_handle);
};



// var_dump($message_arry);

  // echo var_dump($message_arry);

// $_POSTが値を取得できているかの確認
// var_dump($_POST);

// メッセージを保存する.textのpathの確認
// var_dump(is_writable("message.txt"));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>


<!-- $error_messageに文字が入りと起動する -->
<?php if (!empty($error_message)):?>
 <?php foreach($error_message as $value):?>
 <p><?php echo $value; ?></p>
 <?php endforeach ?>
 <?php endif ?>


 <script>
 function confirm_test(){
	 let select = confirm("送信しますか")
	 return select;
 }
 </script>


	<form method="post" onsubmit="return confirm_test()">
	<div>
		<label for ="">表示名</label>
	  <input id="" type="text" name="view_name" value="">
  </div>
 <div>
	<label for="message">一言メッセージ</label>
	<textarea id= "message" name ="message"></textarea>
 </div>
 <input type="submit" name="btn_submit" value="書き込む" id = "btn">
 </form>
<hr>
 <section>

	<!-- message_arryに文字が代入されたら起動する -->

	 <?php if(!empty($message_arry)):?>

	 <!--連想配列 Qこの時$valueは何を表しているか -->
	 <?php foreach($message_arry as $value):?>
	 <article>
		 <div class="info">

		 		<time>
			 <?php echo date('Y年m月d日 H:i',strtotime($value['post_date']));?>
			 </time>

			 <h2><?php echo $value ['view_name'];?></h2>

		 </div>
		 <p><?php echo $value ['message']; ?></p>
	 </article>
<?php endforeach; ?>
<?php endif; ?>
 </section>
</body>
</html>
