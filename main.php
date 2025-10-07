<?php
	//DB接続
	session_start();
	require_once("other/system.php");

	//直接アクセス禁止
	session_regenerate_id(true);
	if (!isset($_SESSION["login_id"]) && $_SESSION["login_token"] != "login_token") {
		header ("Location: other/login.php");
		exit;
	}
	//二重送信防止トークン発行
	//トークン(書き込み/削除)
	$edit_token = "edit_on";
	$_SESSION["edit_token"] = $edit_token;

	//ログインID取得
	$login_id = $_SESSION["login_id"];

	//DB接続
	require_once("other/sowsow_DB.php");
	$pdo = db_connect();

	//変数の初期化
	$total_count = "";
	$result_count = "";

	//--[ページ処理計算]--//
	if (isset($_GET["page"])) {
		$now_page = $_GET["page"];
	}
	else {
		$now_page = 1;
	}
	//スタート位置計算
	if ($now_page > 1) {
		$start = ($now_page * 10) -10;
	}
	else {
		$start = 0;
	}

	//--[表示処理]--//
	//検索があった場合
	if (isset($_POST["search_submit"]) && $_POST["key_word"] !== "") {
		$key_word = s($_POST["key_word"]);
		$search_key = '%'.$key_word.'%';
		$result_title = "検索結果「".$key_word."」を含む記事";

		try {
			$sql ="SELECT * FROM bbs WHERE
				id LIKE :search_name
				OR
				message LIKE :search_message
				ORDER BY No DESC";
			$statement = $pdo -> prepare($sql);
			$statement -> bindValue(":search_name", $search_key);
			$statement -> bindValue(":search_message", $search_key);
			$statement -> execute();
			$total_count = $statement -> rowCount();
			//切り抜き
			$sql ="SELECT * FROM bbs WHERE id LIKE :search_name
				OR
				message LIKE :search_message
				ORDER BY No DESC LIMIT $start,10";
			$statement = $pdo -> prepare($sql);
			$statement -> bindValue(":search_name", $search_key);
			$statement -> bindValue(":search_message", $search_key);
			$statement -> execute();
		}
		catch (PDOException $Exception) {
			print ("接続エラー:".$Exception -> getMessage());
		}
	}
	//検索なしで一覧表示
	else {
		$result_title = "書き込み一覧";

		try {
			$sql = "SELECT * FROM bbs ORDER BY No DESC";
			$statement = $pdo -> prepare($sql);
			$statement -> execute();
			$total_count = $statement -> rowCount();
			//切り抜き
			$sql = "SELECT * FROM bbs ORDER BY No DESC LIMIT $start,10";
			$statement = $pdo -> prepare($sql);
			$statement -> execute();
		}
		catch (PDOException $Exception) {
			print ('接続エラー:'.$Exception -> getMessage());
		}
	}

	if ($total_count < 1) {
		$result_count = '<div id="no-result">書きこみはありません</div>';
	}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>sowsow</title>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="css/full/common.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="css/middle/common_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="css/mobile/common_mobile.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="css/full/main.css" media="screen and (min-width: 1024px)">
		<link rel="stylesheet" href="css/middle/main_middle.css" media="screen and (min-width:481px) and (max-width:1023px)">
		<link rel="stylesheet" href="css/mobile/main_mobile.css" media="screen and (max-width:480px)">
		<link rel="shortcut icon" href="images/favicon.ico">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@400;500;700&display=swap" rel="stylesheet">
		<script src="../jQuery.js"></script>
		<script src="sowsow.js"></script>
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
					<img src="images/logo_low.png" class="header-icon" alt="header_icon">
				</div>

				<div id="welcome-user-outer">
					ようこそ
						<div id="welcome-user">
							<?php print s($login_id); ?>
						</div>
					さん
				</div>
			</header>


			<main>
				<!--=[サイドコンテンツ]=-->
				<div id="side-container">

					<!--[メニュー]-->
					<div id="menu-outer">

						<!--[検索]-->
						<div id="menu-search" class="menu-contents">
							search
							<img src="images/search_icon.png" class="menu-icon" alt="search_icon">
						</div>
						<div id="search-window" class="menu-window">
							<div id="close-btn-search" class="menu-close-btn">
								<img src="images/close.png" class="close-icon" alt="close_icon">
							</div>

							<nav class="search-nav">次の項目を入力し、[検索]をクリックして下さい。</nav>
							<form id="search" action="main.php" method="POST">
								<input type="text" id="key-word" name="key_word">
								<nav class="search-nav">をメッセージに含む記事</nav>
								<input type="submit" id="search-submit" class="reg-btn" name="search_submit" value="検索">
							</form>
						</div>

						<!--[書き込み]-->
						<div id="menu-insert" class="menu-contents">write
							<img src="images/write_icon.png" class="menu-icon" alt="write_icon">
						</div>
						<div id="insert-window" class="menu-window">
							<div id="close-btn-insert" class="menu-close-btn">
								<img src="images/close.png" class="close-icon" alt="close_icon">
							</div>

							<nav id="insert-nav">次の項目を入力し、[書き込む]をクリックして下さい。</nav>
							<form id="insert-form" action="other/insert.php" method="POST">
								<dt id="username-lead">アカウント</dt>
								<dd id="username-insert">
									<input type="hidden" id="id" name="id" value="<?php print($login_id); ?>">
									<?php print s($login_id); ?>
								</dd>

								<dt id="category-lead">カテゴリ
									<div id="err-category"><!--エラーメッセージ--></div>
								</dt>
								<dd id="category-insert">
									<select id="category" name="category">
										<option value="0">--選択して下して下さい--</option>
										<option value="つぶやき">つぶやき</option>
										<option value="案内">案内</option>
										<option value="連絡">連絡</option>
										<option value="その他">その他</option>
									</select>
								</dd>

								<dt id="message-lead">メッセージ
									<div id="err-message"><!--エラーメッセージ--></div>
								</dt>
								<dd id="message-insert">
									<textarea id="message" name="message" cols="50"></textarea>
								</dd>

								<dt id="insert-submit-outer">
									<input type="submit" id="insert-submit" class="reg-btn" name="insert_submit" value="書き込む">
								</dt>
							</form>
						</div>

						<!--[info]-->
						<div id="menu-info" class="menu-contents">info
							<img src="images/info_icon.png" class="menu-icon" alt="info_icon">
						</div>

						<!--[アカウント管理]-->
						<?php print ($account_edit); ?>

						<!--[ログアウト]-->
						<div id="logout" class="menu-contents">logout
							<img src="images/logout_icon.png" class="menu-icon" alt="logout_icon">
						</div>
					</div>
				</div>

				<!--=[サイドコンテンツ mobile]=-->
				<div id="side-container-m">

					<!--[メニュー]-->
					<div id="menu-outer">
						<!--[モード表示]-->
						<?php print ($login_mode); ?>

						<!--[検索 mobile]-->
						<div id="menu-search-m" class="menu-contents-m">search
							<img src="images/search_icon.png" class="menu-icon" alt="search_icon">
						</div>

						<!--[書き込み mobile]-->
						<div id="menu-insert-m" class="menu-contents-m">write
							<img src="images/write_icon.png" class="menu-icon" alt="write_icon">
						</div>

						<!--[info mobile]-->
						<div id="menu-info-m" class="menu-contents-m">info
							<img src="images/info_icon.png" class="menu-icon" alt="info_icon">
						</div>

						<!--[アカウント管理]-->
						<?php print ($account_edit_m); ?>

						<!--[ログアウト mobile]-->
						<div id="logout-m" class="menu-contents-m">logout
							<img src="images/logout_icon.png" class="menu-icon" alt="logout_icon">
						</div>
					</div>
				</div>

				<!--=[メインコンテンツ(記事)]=-->
				<div id="main-container">
					<!--[メインヘッダー]-->
					<div id="main-inner">
						<div id="main-head">
							<div id="result-title">
								<?php print s($result_title); ?>
							</div>
							<a href="main.php" id="update-btn">
								<div id="update-txt">更新</div>
								<img src="images/update_icon.png" id="update-icon" alt="update_icon">
							</a>
						</div>

						<!--[ページング]-->
						<nav id="paging">
							<?php
								$disp_limit = 10;//表示件数
								$total_page = ceil ($total_count / $disp_limit);//トータルページ
								//ページ番号一覧を出力
								for ($page = 1; $page <= $total_page; $page++) {
									//現在地のページ番号
									if ($page == $now_page) {
										print ('<div id="now-page">'.$now_page.'</div>');
									}
									//その他のページ番号
									else {
										print ('<a class="other-page" href="main.php?page='.$page.'">'.$page.'</a>');
									}
								}
							?>
						</nav>

						<!--[記事タイトル]-->
						<div id="result-window">
							<!--[記事タイトル(記事なし)]-->
							<?php print ($result_count); ?>

							<!--[記事一覧表示]-->
							<?php
								while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
							?>
							<form class="result-form" action="other/delete.php" method="post">
								<table class="result-list">
									<tbody class="bbs-box">
										<tr>
											<th class="bbs-head">
												<!--id-->
												<div class="bbs-no">
													<?php print s($result["No"]); ?>
													:
												</div>
												<!--名前-->
												<div class="bbs-name">
													<?php print s($result["id"]); ?>
												</div>
												<!--日時-->
												<div class="bbs-time">投稿日時:
													<?php print s($result["date"]); ?>
												</div>
											</th>
										</tr>
										<tr>
											<td>
												<!--本文-->
												<div class="bbs-txt">
													<?php print s($result["message"]); ?>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<!--カテゴリ-->
												<div class="bbs-category">カテゴリ:
													<?php print s($result["category"]); ?>
												</div>
											</td>
											<td>
												<!--[掲示板削除]-->
												<input type="hidden" name="delete-no" value="<?php print ($result["No"]); ?>">
												<?php print ($delete_btn); ?>
											</td>
										</tr>
									</tbody>
								</table>
							</form>
							<?php
								}
							?>
						</div>

						<!--[スクロールダウン]-->
						<div id="scroll-down">
							<span id="scroll-down-txt">scroll</span>
						</div>
					</div>
				</div>

				<!--[infoモーダル]-->
				<div id="info-ol">
					<div id="info-outer">
						<article id="info-title">infomation</article>
						<p id="info-lead">
							ここは自由になんでも書き込める掲示板です。
							<br>
							掲示板について、ご説明致します。
						</p>
						<dl id="sammary-outer">
							<dt class="dictionary-txt">【草々】
								<div class="dictionary-txt-detail">(読み)そう‐そう</div>
							</dt>
							<dd class="dictionary-list-outer">
								<ol class="dictionary-list-inner">
									<li class="dictionary-list">簡略なさま。粗略なさま。</li>
									<li class="dictionary-list">手紙文の末尾に、急ぎ走り書きをしたことをわびる意で、書き添える語。</li>
								</ol>
							</dd>
							<dt class="dictionary-txt">【sow】
								<div class="dictionary-txt-detail">(発音)s&#243;u</div>
							</dd>
							<dd class="dictionary-list-outer">
								<ol class="dictionary-list-inner">
									<li class="dictionary-list">(植物の種を)（畑・庭などに）まく。植える，作付けする。</li>
									<li class="dictionary-list">(出来事などの)種をまく。きっかけ、原因を作る。</li>
								</ol>
							</dd>
						</dl>

						<dl id="rule-outer">
							<dt class="rule-head">掲示板について</dt>
							<dd class="rule-list-outer">
								<ul class="rule-list-inner">
									<li class="rule-list">基本的に自由になんでも書き込んで下さい。</li>
									<li class="rule-list">画面の左のボタンでコンテンツを利用できます。</li>
								</ul>
							</dd>
							<dt class="rule-head">検索</dt>
							<dd class="rule-list-outer">
								<ul class="rule-list-inner">
									<li class="rule-list">投稿者・メッセージでワード検索ができます。</li>
								</ul>
							</dd>
							<dt class="rule-head">書き込み</dt>
							<dd class="rule-list-outer">
								<ul class="rule-list-inner">
									<li class="rule-list">電話番号、住所などの個人情報は投稿しないで下さい。</li>
									<li class="rule-list">他人を誹謗、中傷する投稿しないで下さい。</li>
									<li class="rule-list">他人になりすましての投稿はしないで下さい。</li>
									<li class="rule-list">広告、商品あるいはサービスの販売を禁止です。</li>
									<li class="rule-list">記事の削除は、書き込みフォームで管理人にご報告下さい。</li>
								</ul>
							</dd>
						</dl>
					</div>

					<nav id="return-main" class="fla-nav">画面クリックでメインへ戻ります</nav>
				</div>


				<!--=[ログアウトモーダル]=-->
				<div id="logout-ol">
					<form method="POST" id="logout-form" name="logout-form" action="other/logout.php">
						<dl>
							<dt id="logout-txt">ログアウトしますか？</dt>
							<dd id="logout-btn-outer">
								<a href="other/logout.php" id="logout-btn" class="reg-btn">ログアウト</a>
								<button type="button" id="close-logout" class="reg-btn">キャンセル</button>
							</dd>
						</dl>
					</form>
				</div>
			</main>
		</div>
	</body>

	<?php
		//デバイスを縦にしてください
		require_once("../turn_device/turn_device.html");
	?>
</html>
