<?php

/****************************************************************/
/*	テーブルアクセス用関数群									*/
/*																*/
/*	File:		itemfunc.php									*/
/*	Writer:		Hidetsugu.suzuki								*/
/*	Data:		2024/06/10										*/
/*	Memo:		テーブルリスト									*/
/*				infomation:		お知らせテーブル				*/
/*				item:			備品テーブル					*/
/*				document:		書類テーブル					*/
/*																*/
/****************************************************************/

/****************************************************************/
/*	お知らせテーブル情報										*/
/*	テーブル名：		infomation								*/
/*	id:					お知らせID（自動付与）					*/
/*	info_title:			お知らせタイトル（MAX100）				*/
/*	info_date:			お知らせ日（更新日）					*/
/*	info_writer:		お知らせ更新者（個人情報テーブルID）	*/
/*	info_memo:			お知らせ内容							*/
/*																*/
/****************************************************************/
/*	お知らせテーブルアクセス関数軍								*/
/****************************************************************/

/* お知らせテーブル作成処理 */
function	make_info_table($pdo)
{
	/* お知らせテーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS info(id int auto_increment primary key, info_title varchar(100), info_date DATETIME, info_writer int, info_memo text, foreign key(info_writer) references chardata(id) on update cascade on delete set null)");
}

/* お知らせリスト取得処理 */
function	get_info_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from info");
	$i = 0;
	foreach ($result as $row) {
		$data['info_list'][$i++] = $row;
	}
}

/* お知らせデータ取得処理 */
function	get_info_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM info WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM info WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['info_data'] = $result->fetch();
}

/* お知らせデータ追加処理 */
function	insert_info_data(&$data, $info_title, $info_date, $info_writer, $info_memo)
{
	$result = $data['pdo']->prepare("INSERT INTO info (info_title, info_date, info_writer, info_memo) VALUES (:info_title, :info_date, :info_writer, :info_memo)");
	$result->bindParam(':info_title', $info_title, PDO::PARAM_STR);
	$result->bindParam(':info_date', $info_date, PDO::PARAM_STR);
	$result->bindValue(':info_writer', $info_writer, PDO::PARAM_INT);
	$result->bindParam(':info_memo', $info_memo, PDO::PARAM_STR);
	$result->execute();
}

/* お知らせデータ更新処理 */
function	update_info_data(&$data, $record, $info_title, $info_date, $info_writer, $info_memo)
{
	$result = $data['pdo']->prepare("UPDATE info SET info_title = :info_title, info_date = :info_date, info_writer = :info_writer, info_memo = :info_memo WHERE id = :id");
	$result->bindParam(':info_title', $info_title, PDO::PARAM_STR);
	$result->bindParam(':info_date', $info_date, PDO::PARAM_STR);
	$result->bindValue(':info_writer', $info_writer, PDO::PARAM_INT);
	$result->bindParam(':info_memo', $info_memo, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$ret = $result->execute();
}

/* お知らせデータ削除処理 */
function	delete_info_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM info WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* お知らせリスト取得処理(リレーションあり) */
function	get_info_list(&$data)
{
	$result = $data['pdo']->query("SELECT info.*, chardata.family_name, chardata.first_name FROM info INNER JOIN chardata ON info.info_writer = chardata.id");
	$i = 0;
	foreach ($result as $row) {
		$data['info_list'][$i++] = $row;
	}
}


/****************************************************************/
/*	備品テーブル情報											*/
/*	テーブル名：		item									*/
/*	id:					備品ID（自動付与）						*/
/*	item_name:			備品名（MAX100）						*/
/*	item_count:			備品数量								*/
/*	item_pos:			備品保管場所							*/
/*	item_pos_photo:		備品保管場所写真URL						*/
/*	item_memo:			備品説明								*/
/*																*/
/****************************************************************/
/*	備品テーブルアクセス関数軍									*/
/****************************************************************/

/* 備品テーブル作成処理 */
function	make_item_table($pdo)
{
	/* 備品テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS item(id int auto_increment primary key, item_name varchar(100), item_count int, item_pos text, item_pos_photo text, item_memo text)");
}

/* 備品リスト取得処理 */
function	get_item_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from item");
	$i = 0;
	foreach ($result as $row) {
		$data['item_list'][$i++] = $row;
	}
}

/* 備品データ取得処理 */
function	get_item_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM item WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM item WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['item_data'] = $result->fetch();
}

/* 備品データ追加処理 */
function	insert_item_data(&$data, $item_name, $item_count, $item_pos, $item_pos_photo, $item_memo)
{
	$result = $data['pdo']->prepare("INSERT INTO item (item_name, item_count, item_pos, item_pos_photo, item_memo) VALUES (:item_name, :item_count, :item_pos, :item_pos_photo, :item_memo)");
	$result->bindParam(':item_name', $item_name, PDO::PARAM_STR);
	$result->bindParam(':item_count', $item_count, PDO::PARAM_INT);
	$result->bindParam(':item_pos', $item_pos, PDO::PARAM_STR);
	$result->bindParam(':item_pos_photo', $item_pos_photo, PDO::PARAM_STR);
	$result->bindParam(':item_memo', $item_memo, PDO::PARAM_STR);
	$result->execute();
}

/* 備品データ更新処理 */
function	update_item_data(&$data, $record, $item_name, $item_count, $item_pos, $item_pos_photo, $item_memo)
{
	$result = $data['pdo']->prepare("UPDATE item SET item_name = :item_name, item_count = :item_count, item_pos = :item_pos, item_pos_photo = :item_pos_photo, item_memo = :item_memo WHERE id = :id");
	$result->bindParam(':item_name', $item_name, PDO::PARAM_STR);
	$result->bindParam(':item_count', $item_count, PDO::PARAM_INT);
	$result->bindParam(':item_pos', $item_pos, PDO::PARAM_STR);
	$result->bindParam(':item_pos_photo', $item_pos_photo, PDO::PARAM_STR);
	$result->bindParam(':item_memo', $item_memo, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 備品データ削除処理 */
function	delete_item_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM item WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}


/****************************************************************/
/*	書類テーブル情報											*/
/*	テーブル名：		document								*/
/*	id:					書類ID（自動付与）						*/
/*	document_name:		書類名（MAX100）						*/
/*	document_memo:		書類説明								*/
/*	document_url:		書類保存場所URL							*/
/*																*/
/****************************************************************/
/*	書類テーブルアクセス関数軍									*/
/****************************************************************/

/* 書類テーブル作成処理 */
function	make_document_table($pdo)
{
	/* 書類テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS document(id int auto_increment primary key, document_name varchar(100), document_memo text,  document_url text)");
}

/* 書類リスト取得処理 */
function	get_document_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from document");
	$i = 0;
	foreach ($result as $row) {
		$data['document_list'][$i++] = $row;
	}
}

/* 書類データ取得処理 */
function	get_document_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM document WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM document WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['document_data'] = $result->fetch();
}

/* 書類データ追加処理 */
function	insert_document_data(&$data, $document_name, $document_memo, $document_url)
{
	$result = $data['pdo']->prepare("INSERT INTO document (document_name, document_memo, document_url) VALUES (:document_name, :document_memo, :document_url)");
	$result->bindParam(':document_name', $document_name, PDO::PARAM_STR);
	$result->bindParam(':document_memo', $document_memo, PDO::PARAM_STR);
	$result->bindParam(':document_url', $document_url, PDO::PARAM_STR);
	$result->execute();
}

/* 書類データ更新処理 */
function	update_document_data(&$data, $record, $document_name, $document_memo, $document_url)
{
	$result = $data['pdo']->prepare("UPDATE document SET document_name = :document_name, document_memo = :document_memo, document_url = :document_url WHERE id = :id");
	$result->bindParam(':document_name', $document_name, PDO::PARAM_STR);
	$result->bindParam(':document_memo', $document_memo, PDO::PARAM_STR);
	$result->bindParam(':document_url', $document_url, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 書類データ削除処理 */
function	delete_document_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM document WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}


?>
