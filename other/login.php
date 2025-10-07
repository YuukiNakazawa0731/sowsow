<?php
	session_start();

	//サニタイズ処理
	function s($statement) {
		return htmlspecialchars($statement, ENT_QUOTES, "utf-8");
	}

	//二重送信防止トークン
	$signup_token = "signup_on";
	$_SESSION["signup_token"] = $signup_token;

	//DB接続
	require_once("sowsow_DB.php");
	$pdo = db_connect();

	//変数の初期化
	$login_err = "";

	//ログインボタンが押された時
	if (!empty($_POST["login_submit"])) {
		$login_id = s($_POST["login_id"]);
		$login_pass = s($_POST["login_pass"]);

		//SQL接続
		try {
			$sql = "SELECT * FROM bbs_pass WHERE id = ?";
			$statement = $pdo -> prepare($sql);
			$statement -> bindValue(1, $login_id, PDO::PARAM_STR);
			$statement -> execute();
			$account = $statement -> fetch(PDO::FETCH_ASSOC);
			//ID・パスワード認証
			if ($account && password_verify($login_pass,$account["password"])) {
				$_SESSION["login_id"] = $account["id"];
				$login_token = "login_on";
				$_SESSION["login_token"] = $login_token;
				$_SESSION["admin_token"] = $account["admin"];
				//セッションを破棄
				unset($_SESSION["signup_token"]);
				header("Location: ../main.php");
			}
			else {
				$login_err = "IDまたはパスワードが違います";
			}
		}
		catch (PDOException $e) {
			print("DB接続エラー！:" . $e -> getMessage());
		}
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow ログイン</title>
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
				<!--=[ログインコンテンツ]=-->
				<div id="title-logo"></div>
				<form id="login-form" action="" method="POST">
					<dl id="login-form-inner">
						<dt class="login-list">アカウント</dt>
						<dd class="login-item">
							<input type="text" id="login-id" name="login_id" value="ゲスト">
						</dd>

						<dt class="login-list">パスワード</dt>
						<dd class="login-item">
							<input type="password" id="login-pass" name="login_pass" value="password">
							<input type= "checkbox" id="pass-eye">
							<label for="pass-eye" id="pass-eye-label">
								<img src="../images/pass_eye.png" id="pass-eye-img" alt="pass_eye">
							</label>
						</dd>

						<dt class="login-list">
							<input type="submit" id="login-submit" class="reg-btn" name="login_submit" value="ログイン">
						</dt>

						<dt id="login-err">
							<!--エラーメッセージ-->
							<?php print ($login_err); ?>
						</dt>
					</dl>
				</form>
				<div id="signup-link-outer" class="link-txt-outer">
					<p id="signup-link" class="link-txt">アカウント作成はこちら</p>
				</div>


				<!--=[新規登録モーダル]=-->
				<div id="signup-ol">
					<form id="signup-form" action="signup_result.php" method="POST">
						<dl id="signup-form-inner">
							<dt id="signup-title">新規登録フォーム</dt>
							<dt class="signup-nav">項目入力後「新規登録」をクリックしてください</dt>
							<dt class="signup-list">アカウント</dt>
							<dd class="signup-item">
								<input type="text" id="signup-id" name="signup_id" value="">
							</dd>
							<dt class="signup-list">パスワード</dt>
							<dd class="signup-item">
								<input type="password" id="signup-pass" name="signup_pass">
								<input type= "checkbox" id="signup-pass-eye">
								<label for="pass-eye" id="signup-pass-eye-label">
									<img src="../images/pass_eye.png" id="signup-pass-eye-img" alt="pass_eye">
								</label>
							</dd>

							<dt class="signup-list">
								<button type="submit" id="signup-submit" class="reg-btn" name="signup_submit">
									<span class="reg-btn-txt">新規登録</span>
								</button>
							</dt>

							<dt id="signup-err"><!--エラーメッセージ--></dt>
						</dl>
					</form>
					<div id="login-link-outer" class="link-txt-outer">
						<nav id="return-login" class="link-txt">ログインへ</nav>
					</div>
				</div>
			</main>

			<footer>
				<div id="copy-right">
					<p>&copy;Nakazawa Yuuki 2022</p>
				</div>
			</footer>
		</div>
	</body>
</html>
