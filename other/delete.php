<?php
	session_start();

	//直接アクセス防止
	if (!isset ($_POST["delete-btn"])) {
		header("Location: main.php");
	}
	//二重送信防止トークン発行
	if (!isset ($_SESSION["edit_token"]) && ($_SESSION["edit_token"] != "edit_on")) {
		header("Location: main.php");
	}

	//DB接続
	require_once("sowsow_DB.php");
	$pdo = db_connect();

	//変数初期化
	$delete_no = "";

	//DBから削除
	$delete_no = $_POST["delete-no"];

	try {
		$pdo -> beginTransaction();
		$sql = "DELETE FROM bbs WHERE No = :delete_no";
		$statement = $pdo -> prepare($sql);
		$statement -> bindValue(":delete_no",$delete_no);
		$statement -> execute();
		$pdo -> commit();
		unset($_SESSION["edit_token"]);
		header("Location: ../main.php");
	}
	catch (PDOException $e) {
		print("DB接続エラー！:" . $e -> getMessage());
		unset($_SESSION["delete_token"]);
	}
