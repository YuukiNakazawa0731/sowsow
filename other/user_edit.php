<?php
	session_start();
	require_once("system.php");

	//直接アクセス禁止
	session_regenerate_id(true);
	if (!$_SESSION["admin_token"] && $_SESSION["admin_token"] != "ON") {
		header("Location: login.php");
		exit;
	}

	//ログインID取得
	$login_id = $_SESSION["login_id"];

	//DB接続
	require_once("sowsow_DB.php");
	$pdo = db_connect();

	//変数の初期化
	$total_count = "";
	$result_count = "";

	//変更ボタンが押された時
	if (isset($_POST["edit-submit"])) {
		$account_no = ($_POST["account-no"]);
		$admin_select = ($_POST["admin-select"]);

		//SQL接続
		try {
			$sql = "UPDATE bbs_pass SET admin = :admin_select WHERE No = :account_no";
			$statement = $pdo -> prepare($sql);
			$statement -> bindValue(":account_no",$account_no);
			$statement -> bindValue(":admin_select",$admin_select);
			$statement -> execute();
			}
		catch (PDOException $e) {
			print("DB接続エラー！:" . $e -> getMessage());
		}
	}

	//削除ボタンが押された時
	if (isset ($_POST["delete-submit"])) {
		$account_no = s($_POST["account-no"]);

		//SQL接続
		try {
			$pdo -> beginTransaction();
			$sql = "DELETE FROM bbs_pass WHERE No = :account_no";
			$statement = $pdo -> prepare($sql);
			$statement -> bindValue(":account_no",$account_no);
			$statement -> execute();
			$pdo -> commit();
		}
		catch (PDOException $e) {
			print("DB接続エラー！:" . $e -> getMessage());
		}
	}

	//--[表示処理]--//
	try {
		$sql = "SELECT * FROM bbs_pass ORDER BY No DESC";
		$statement = $pdo -> query($sql);
		$statement -> execute();
	}
	catch (PDOException $Exception) {
		print('接続エラー:'.$Exception -> getMessage());
	}

	$total_count = $statement -> rowCount();
?>


<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow アカウント管理</title>
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
			<!--=[ヘッダーコンテンツ]=-->
			<header>
				<!--[メニューボタン]-->
				<div id="menu-btn">
					<span class="menu-line"></span>
					<span class="menu-line"></span>
				</div>

				<div id="header-logo-outer">
					<img src="../images/logo_low.png" class="header-icon" alt="header_icon">
				</div>

				<div id="welcome-user-outer">
					ようこそ
						<div id="welcome-user">
							<?php print s($login_id); ?>
						</div>
					さん
				</div>
			</header>


			<!--=[メインコンテンツ]=-->
			<main>
				<!--=[サイドコンテンツ]=-->
				<div id="side-container">

					<!--[メニュー]-->
					<div id="menu-outer">
						<!--[MAINに戻る ]-->
						<a href="../main.php" id=return-main-edit class="menu-contents">MAIN
							<img src="../images/logout_icon.png" class="menu-icon" alt="logout_icon">
						</a>
					</div>
				</div>

				<!--=[サイドコンテンツ mobile]=-->
				<div id="side-container-m">
					<!--[メニュー]-->
					<div id="menu-outer">
						<!--[MAINに戻る mobile]-->
						<a href="../main.php" id=return-main-edit-m class="menu-contents-m">MAIN
							<img src="../images/logout_icon.png" class="menu-icon" alt="logout_icon">
						</a>
					</div>
				</div>


				<!--=[メインコンテンツ(アカウント一覧)]=-->
				<div id="edit-container">
					<!--[メインヘッダー]-->
					<div id="edit-inner">
						<div id="main-head">
							<div id= "result-title">アカウント一覧</div>
						</div>
						<table id="account-list-head">
							<tr>
								<!--No-->
								<td id="head-no">No</td>
								<!--id-->
								<td id="head-id">ID</td>
								<!--admin-->
								<td id="head-admin">admin</td>
								<!--admin-->
								<td id="head-edit">変更</td>
								<!--admin-->
								<td id="head-delete">削除</td>
							</tr>
						</table>

						<!--[アカウント一覧表示]-->
						<div id="result-window">
							<?php
								while ($account = $statement -> fetch(PDO::FETCH_ASSOC)) {
							?>
							<div class="account-result-list">
								<form class="account-result-form" action="" method="post">
									<!--No-->
									<div class="account-no">
										<?php print s($account["No"]); ?>
										<input type="hidden" id="hid-account-no" name="account-no" value="<?php print ($account["No"]); ?>">
									</div>
									<!--id-->
									<div class="account-id">
										<?php print s($account["id"]); ?>
									</div>
									<!--admin-->
									<div class="account-admin" name="account-admin">
										<select class="admin-select" name="admin-select">
											<option selected>
												<?php print s($account["admin"]); ?>
											</option>
											<option value="ON">ON</option>
											<option value="OFF">OFF</option>
										</select>
									</div>
									<!--更新-->
									<button type="submit" class="edit-btn" name="edit-submit">
										<img src="../images/update_icon.png" class="edit-icon" alt="edit_icon">
									</button>
									<!--削除-->
									<button type="submit" class="edit-btn" name="delete-submit">
										<img src="../images/dust_box.png" class="edit-icon" alt="delete-icon">
									</button>
								</form>
							</div>	
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</main>
		</div>
	</body>
</html>
