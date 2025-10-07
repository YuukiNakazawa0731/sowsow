<?php
	session_start();
	require_once("system.php");

	//直接アクセス防止
	if (!isset($_POST["insert_submit"])) {
		header("Location: ../main.php");
	}
	//二重送信防止トークン
	if (!isset($_SESSION["edit_token"]) || ($_SESSION["edit_token"] != "edit_on")) {
		header("Location: ../main.php");
	}

	//DB接続
	require_once("sowsow_DB.php");
	$pdo = db_connect();

	//変数の設定
	$insert_result = "";
	$insert_err = "";
	$id = s($_POST["id"]);
	$category = s($_POST["category"]);
	$message = s($_POST["message"]);

	//日時情報取得
	date_default_timezone_set("Asia/Tokyo");
	$date = date("Y/m/d H:i:s");

	//DBに書き込み
	try {
		$pdo -> beginTransaction();
		$sql = "INSERT INTO bbs (date,id,category,message)
			VALUES (:date,:id,:category,:message)";
		$statement = $pdo -> prepare($sql);
		$statement -> bindParam(":date",$date);
		$statement -> bindParam(":id",$id);
		$statement -> bindParam(":category",$category);
		$statement -> bindParam(":message",$message);
		$statement -> execute();
		$pdo -> commit();
		$insert_result ="この内容で書き込み完了しました";
	}
	catch (PDOException $Exception) {
		print('接続エラー:'.$Exception -> getMessage());
		$insert_err ="書き込み失敗しました";
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow 書き込み完了</title>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/full/common.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="../css/middle/common_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="../css/mobile/common_mobile.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="../css/full/main.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="../css/middle/main_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="../css/mobile/main_mobile.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="../css/full/other.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="../css/middle/other_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="../css/mobile/other_mobile.css" media="screen and (max-width:480px)">
		<script src="../../jQuery.js"></script>
		<script src="../sowsow.js"></script>
	</head>


	<body>
		<div id="main-wrapper">
			<main>
				<!--=[書き込み表示モーダル]=-->
				<div id="insert-ol">
					<!--[書き込み結果]-->
					<div id="insert-result-outer">
						<h2 id="insert-title">書き込み内容</h2>

						<div id="insert-result">
							<div id="insert-result-head">
								<!--名前-->
								<div id="insert-result-user-outer">ID:</div>
								<div id="insert-result-user">
									<?php print s($id); ?>
								</div>
							</div>
							<div id="insert-result-date">投稿日時:
								<?php print s($date); ?>
							</div>
							<div id="insert-result-message">
								<?php print s($message); ?>
							</div>
							<div id="insert-result-category">カテゴリ:
								<?php print s($category); ?>
							</div>
						</div>

						<div id="insert-nav-outer">
							<div id="insert-nav-txt">
								<?php print s($insert_result); ?>
							</div>
							<div id="insert-err-txt">
								<?php print s($insert_err); ?>
							</div>
						</div>
						<nav id="return-main" class="fla-nav">画面クリックでメインへ戻ります</nav>
					</div>
				</div>
			</main>
		</div>
	</body>
</html>

<?php
	//セッション削除
	unset($_SESSION["edit_token"]);
	exit();
?>
