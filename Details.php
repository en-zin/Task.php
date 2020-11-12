<?php

date_default_timezone_set('Asia/Tokyo');

$id = $_GET['id'];
$title = $_GET['title'];
$text = $_GET['text'];
$fail = 'coment.txt';
$subId = uniqid();
$date = date("Y年m月d日 H時i分s秒");
$coment = $_POST['coment'];
$data = [];
$board = [];

$error_message = [];
$limit_coment = 50;

echo $title;
echo $text;
if (file_exists($fail)) {
    $board = json_decode(file_get_contents($fail));
}

if(mb_strlen($coment) >= $limit_coment) $error_message[] = '50文字以内でコメントを書いてください';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(empty($error_message)) {

        if(!empty($coment)) {
            $data = [$subId, $coment, $date,];

            $board[] = $data;

            file_put_contents($fail,json_encode($board, JSON_UNESCAPED_UNICODE));

            header('Location:' .$_SERVER['QUERY_STRING']);
            exit;


        } else {
            if(empty($coment)) $error_message[] = "コメントを記入してください";
        };

    };
};


?>



<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

    <h1>
        <a href="http://localhost/Task/task.php">PHPニュース</a>
    </h1>

	<?php foreach($error_message as $value): ?>
		<p>
			<?php echo $value ?>
		</p>
	<?php endforeach ?>



<hr>
    <form action="" method="post" onsubmit="return confirm_test()">
     	<div>
     			<label for="coment">コメント：</label>
     			<textarea name="coment" id="coment" cols="20" rows="5"></textarea>
     	</div>

      <input class="btn" type="submit" name="btn_submit" value="送信">

    </form>

    <?php foreach(array_reverse($board) as $value): ?>
		<p>
            <?php
                echo $value[1];
                echo '<br>';
            ?>
		</p>
	<?php endforeach ?>


<script src="js.js"></script>
</body>
</html>
