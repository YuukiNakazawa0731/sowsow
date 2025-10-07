<?php
	//サニタイズ処理
	function s($statement) {
		return htmlspecialchars($statement, ENT_QUOTES, "utf-8");
	}

	//管理システム
	if ($_SESSION["admin_token"] == "ON") {
		$login_mode = '<div id="login-mode">管理モード</div>';

		$account_edit =
			'<a href="other/user_edit.php" id="menu-edit" class="menu-contents">
			account
			<img src="images/info_icon.png" class="menu-icon" alt="info_icon">
			</a>';

		//アカウント管理(モバイル)
		$account_edit_m =
			'<a href="other/user_edit.php" id="menu-edit" class="menu-contents-m">
			account
			<img src="images/info_icon.png" class="menu-icon" alt="info_icon">
			</a>';

		$delete_btn =
			'<button type="submit" class="delete-btn">
			<img src="images/dust_box.png" class="delete-icon" alt="delete_icon">
			</button>';
	}
	else {
		$login_mode = "";
		$account_edit = "";
		$delete_btn = "";
		$account_edit = "";
		$account_edit_m = "";
	}
