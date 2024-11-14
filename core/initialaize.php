<?php

/* データベースへの接続関数 */
function	db_connect()
{
	global	$SQL_host, $DatabaseName, $SQL_user, $SQL_pass;

	/* MySQLへ接続 */
	$pdo = null;
	try {
		$pdo = new PDO('mysql:host='.$SQL_host.';dbname='.$DatabaseName.';charset=utf8', $SQL_user, $SQL_pass,
			array(PDO::ATTR_EMULATE_PREPARES => false));
	} catch (PDOException $e) {
		die('データベース接続失敗。'.$e->getMessage());
	}
	return $pdo;
}

/* テーブル初期作成関数 */
function	init_db($pdo)
{
	/* テーブルがなければ作成する(PHP8.0 例外対応) */
	try{
		$result = $pdo->query('SELECT * from setting');
	} catch (PDOException $e) {
		/* データベース内のテーブルを作成 */
		/* データベースアクセス管理用テーブル作成 */
		$pdo->query("CREATE TABLE IF NOT EXISTS setting(set_date DATETIME)");
		$pdo->query("INSERT INTO setting VALUES(NOW())");

		/* 学年テーブル作成 */
		make_school_year_table($pdo);

		/* 続柄テーブル作成 */
		make_relationship_table($pdo);

		/* 権限テーブル作成 */
		make_attribute_table($pdo);

		/* チームテーブル作成 */
		make_team_table($pdo);

		/* 学校テーブル作成 */
		make_school_table($pdo);

		/* 係りテーブル作成 */
		make_role_table($pdo);

		/* 個人情報テーブル作成 */
		make_character_table($pdo);

		/* 団員テーブル作成処理 */
		make_member_table($pdo);

		/* ユーザテーブル作成処理 */
		make_user_table($pdo);

		/* お知らせテーブル作成処理 */
		make_info_table($pdo);

		/* 備品テーブル作成処理 */
		make_item_table($pdo);

		/* 書類テーブル作成処理 */
		make_document_table($pdo);
	}
}

/* 排他処理はやらないことにする */
/* DBトランザクション開始 */
function	db_begin(&$data)
{
	$result = $data['pdo']->query("BEGIN;");
}

/* DBトランザクション終了（変更あり） */
function	db_commit(&$data)
{
	$result = $data['pdo']->query("COMMIT;");
}

/* DBトランザクション終了（変更キャンセル） */
function	db_rollback(&$data)
{
	$result = $data['pdo']->query("ROLLBACK;");
}

?>
