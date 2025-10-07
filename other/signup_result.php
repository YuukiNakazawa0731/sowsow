<?php
	session_start();

	//サニタイズ処理
	function s($statement){
		return htmlspecialchars($statement, ENT_QUOTES, "utf-8");
	}

	if (!isset($_POST["signup_submit"])) {
		header("Location:login.php");
	}

	//DB接続
	require_once("sowsow_DB.php");
	$pdo = db_connect();

	//新規登録処理
	$signup_id = s($_POST["signup_id"]);
	$signup_pass = s($_POST["signup_pass"]);

	//バリデーション
	if (!isset ($signup_id) || !isset($signup_pass)) {
		header("Location:login.php");
	}

	if (isset ($signup_id) && isset($signup_pass)) {
		//SQL接続
		$sql = "SELECT * FROM bbs_pass WHERE id = :user_name";
		$statement = $pdo -> prepare($sql);
		$statement -> bindValue(":user_name",$signup_id);
		$statement -> execute();
		$match_id = $statement -> rowCount();

		//アカウント名重複チェック
		if ($match_id != 0) {
			$signup_result = "このアカウント名は使用されています";
			$result_color = "red";
		}
		//アカウント登録
		else {
			//パスワード暗号化
			$hash_pass = password_hash($signup_pass,PASSWORD_BCRYPT);
			//SQL接続
			try {
				$sql = "INSERT INTO bbs_pass (id, password, admin) VALUES (:user_name, :password, :admin)";
				$statement = $pdo->prepare($sql);
				$statement -> bindValue(":user_name", $signup_id);
				$statement -> bindValue(":password", $hash_pass);
				$statement -> bindValue(":admin", "OFF");
				$statement -> execute();
				$signup_result = "アカウントを登録しました";
				$result_color = "blue";
			}
			catch (PDOException $e) {
				$signup_err = "接続エラーです:" . $e->getMessage();
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow 会員登録結果</title>
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
				<!--=[新規登録モーダル]=-->
				<div id="signup-result-ol">
					<div id="signup-result-outer">
						<dl>
							<dt id="signup-result" style="color: <?php print($result_color); ?>;">
								<?php print($signup_result); ?>
							</dt>
						</dl>
					</div>
					<div id="login-link-outer" class="link-txt-outer">
						<a href="login.php" class="link-txt">ログインへ</a>
					</div>
				</div>
			</main>
		</div>
	</body>

	<?php
		//セッションを破棄
		unset($_SESSION["signup_token"]);
		exit();
	?>
</html>
