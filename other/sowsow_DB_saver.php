<?php
	//DB接続
	function db_connect() {
		//定数
		$db_user = "haru0731029_1";
		$db_pass = "Han0731029";
		$db_host = "mysql1.php.xdomain.ne.jp";
		$db_name = "haru0731029_sowsow";
		$db_type = "mysql";

		$dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";
		
		try {
			$pdo = new PDO($dsn,$db_user,$db_pass);
			$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		}
		catch (PDOException $Exception){
			die("接続エラー:".$Exception -> getMessage());
		}

		return $pdo;
	}
