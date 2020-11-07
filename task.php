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



// method属性がpostであるフォームから送られた情報
// Q.なぜ!emptyの否定なのか。


if ( !empty($_POST['btn_submit'])) {


//名前が未入力だと表示される
	if( empty($_POST['view_name']) ) {
	 echo	($error_message[] = '名前を入力してください');
	};
	//内容が書かれてと表示される
	if( empty($_POST['message']) ) {
	 echo	($error_message[] = '内容が書かれていです！');
	};

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
};

//

// 上記と同じ
// Q なぜ'r'と書いているのか
if($file_handle = fopen(FILENAME,'r')) {

	// 一行ずつ読み込んでいる状態
	// Q なぜループをさせているか fgetについて
	while ($data = fgets($file_handle)) {


		// 文字の分割
		$split_data = preg_split('/\'/',$data);


		//$messageに連想配列
		//Q
		$message = [
			'view_name' => $split_data[1],
			'message' => $split_data[3],
			'post_date' =>$split_data[5]
		];

		array_unshift($message_arry,$message);
		// echo $data."<br>";

	}
	fclose($file_handle);
};

if ($_SERVER['REQUEST_METHOD'] ==='POST') {
	header("Location:http://localhost/Task/task.php");
	exit;
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
	<form method="post" >
	<div>
		<label for ="">表示名</label>
	  <input id="" type="text" name="view_name" value="">
  </div>
 <div>
	<label for="message">一言メッセージ</label>
	<textarea id= "message" name ="message"></textarea>
 </div>
 <input type="submit" name="btn_submit" value="書き込む">
 </form>

 <hr>
 <section>

	<!-- message_arryに文字が代入されたら起動する -->
	 <?php if(!empty($message_arry)):?>

	 <!--連想配列 -->
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
