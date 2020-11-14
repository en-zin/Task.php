<?php

date_default_timezone_set('Asia/Tokyo');

$id = $_GET['id'];  //URLのパラメータを取得
$title = $_GET['title'];    //URLのパラメータを取得
$text = $_GET['text'];  //URLのパラメータを取得
$fail = 'coment.txt';   //保存するファイル名
$subId = uniqid();  //コメントに番号を振り分ける
$date = date("Y年m月d日 H時i分s秒");
$coment = $_POST['coment'];
$data = []; //配列に表示したい内容をまとめる
$board = [];    //まとめた内容を配列の中にいれて操作しやすくしている [[]]この状態

$error_message = [];
$limit_coment = 50;

$erase = [];

if (file_exists($fail)) {
    $board = json_decode(file_get_contents($fail));
};

if(mb_strlen($coment) >= $limit_coment) $error_message[] = '50文字以内でコメントを書いてください';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(empty($error_message)) {

        if(!empty($coment)) {

            $data = [$subId, $id, $coment, $date,];

            $board[] = $data;

            file_put_contents($fail,json_encode($board, JSON_UNESCAPED_UNICODE));


            header("Location:" . $_SERVER['REQUEST_URI']);
			exit;



        } else if(isset($_POST['del'])) {


            $newborad = [];

            foreach ($board as $value) {

                if($value[0] !== $_POST['del']) {
                    $newborad[] = $value;
                    // echo $value[0];
                    // echo '<br>';
                    // var_dump($value);
                };

            };

            file_put_contents($fail, json_encode($newborad));

            header("Location:" . $_SERVER['REQUEST_URI']);
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

    <p>
        <?php echo $title?>
    </p>

    <p>
        <?php echo $text?>
    </p>

	<?php foreach($error_message as $value): ?>

		<p>
			<?php echo $value ?>
		</p>

	<?php endforeach ?>



<hr>
    <form action="" method="post" >

     	<div>
     		<label for="coment">コメント：</label>
     		<textarea name="coment" id="coment" cols="20" rows="5"></textarea>
     	</div>

      <input class="btn" type="submit" name="btn_submit" value="送信">

    </form>

    <form action="" method = "post" onsubmit ="return confirm_test()" >

        <?php foreach(array_reverse($board) as $value): ?>

            <?php if ($id === $value[1]): ?>

                <p>
                    <?php echo $value[2] ?>
                    <?php echo $value[0] ?>
                </p>

                <input type= "hidden" name= "del" value= "<?php echo $value[0] ?>">
                <input class = "btn"  type = "submit"   value = "消去">

            <?php endif ?>

	    <?php endforeach ?>



    </form>




<script src="js.js"></script>
</body>
</html>
