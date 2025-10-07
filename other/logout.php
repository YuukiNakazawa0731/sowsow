<?php
	session_start();
	require_once("system.php");

	//直接アクセス禁止
	if (!isset($_SESSION["login_id"])) {
		header("Location: login.php");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow ログアウト</title>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="shortcut icon" href="../images/favicon.ico">
		<link rel="stylesheet" href="../css/full/common.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="../css/middle/common_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="../css/mobile/common_mobile.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="../css/full/log.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="../css/middle/log_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="../css/mobile/log_mobile.css" media="screen and (max-width:480px)">
		<script src="../../jQuery.js"></script>
		<script src="../sowsow.js"></script>
	</head>


	<body>
		<div id="main-wrapper">
			<main>
				<div id="logout-container">
					<div id="logout-outer">
						<div id="logout-item-up">ログアウトしました</div>
						<canvas id="logout-center-line"></canvas>
						<div id="logout-item-down">see you</div>
					</div>
				</div>
				<div id="login-link-outer" class="link-txt-outer">
					<a href="login.php" class="fla-nav">画面クリックでログインへ</a>
				</div>
			</main>
		</div>
	</body>

	<?php
		//すべてのセッション削除
		session_destroy();
		exit;
	?>
</html>
