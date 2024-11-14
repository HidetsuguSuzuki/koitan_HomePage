<!-- ダウンロード資料編集 START -->
<?php
/* エラーメッセージ初期化 */
$errorMessage = "";

/* POSTパラメータ取得 */
if(isset($_REQUEST['record'])){
	$record = $_REQUEST['record'];				/* 編集 */
}else{
	$record = 0;								/* 新規作成 */
}

/* 登録処理 */
if(isset($_REQUEST['regist']))
{
	$document = $_FILES['document'];				/* 画像取得 */

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['document_name'])){
		$errorMessage = "書類名が未無入力です。";
	} else if(empty($_REQUEST['document_memo'])) {
		$errorMessage = "書類説明が未無入力です。";
	} else if(empty($document['name'])) {
		$errorMessage = "書類ファイルが未無入力です。";
	} else if ($document['size'] > 5000000) {
		$errorMessage = "書類ファイルが大きすぎます。";
	} else {
		/* 書類ファイル保存 */
		$document_url = 'uploads/doc/' . $document['name'];
		move_uploaded_file($document['tmp_name'], $document_url);
		/* ダウンロード資料を追加 */
		insert_document_data($data, $_REQUEST['document_name'], $_REQUEST['document_memo'], $document_url);
		header("Location: member_index.php?page=10&sub_page=9&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	$document = $_FILES['document'];				/* 画像取得 */

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['document_name'])){
		$errorMessage = "書類名が未無入力です。";
	} else if(empty($_REQUEST['document_memo'])) {
		$errorMessage = "書類説明が未無入力です。";
	} else if(empty($document['name'])) {
		$errorMessage = "書類ファイルが未無入力です。";
	} else if ($document['size'] > 5000000) {
		$errorMessage = "書類ファイルが大きすぎます。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_document_data($data, $record, true);							/* レコード参照とロック */
		if(($_REQUEST['document_name_bk'] == $data['document_data']['document_name'])
			&& ($_REQUEST['document_memo_bk'] == $data['document_data']['document_memo'])
			&& ($_REQUEST['document_url_bk'] == $data['document_data']['document_url']))
		{
			/* 書類ファイル保存 */
			if(empty($_REQUEST['document_url']) <> true) {
				/* 古いドキュメントファイルを削除 */
				unlink($_REQUEST['document_url']);
			}
			$document_url = 'uploads/doc/' . $document['name'];
			move_uploaded_file($document['tmp_name'], $document_url);
			/* ダウンロード資料を更新 */
			update_document_data($data, $record, $_REQUEST['document_name'], $_REQUEST['document_memo'], $document_url);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=9&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>ダウンロード資料編集</h1>

<?php
	if($errorMessage != "") {
?>
		<div class="alert alert-danger" role="alert"><?php echo $errorMessage ?></div>
<?php
	}
?>

<?php
if($record == 0) {
	/* 新規作成の場合 */
?>
<form method="post"  enctype="multipart/form-data">
	書類名：<br /> 
	<input type="text" name="document_name" size="100" value="" /><br />
	書類説明：<br />
	<textarea name="document_memo" cols="100" rows="10"></textarea><br />
	書類ファイル：<br />
	<input type="file" name="document" accept=".pdf"><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_document_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	書類名：<br /> 
	<input type="text" name="document_name" size="100" value="<?php echo $data['document_data']['document_name']?>"><br />
	書類説明：<br />
	<textarea name="document_memo" cols="100" rows="10"><?php echo $data['document_data']['document_memo']?></textarea><br />
	書類ファイル：<br />
	<input type="file" name="document" accept=".pdf"><br />
	<input type="hidden" name="document_url" value="<?php echo $data['document_data']['document_url']?>">
	<br />
	<input type="hidden" name="document_name_bk" value="<?php echo $data['document_data']['document_name']?>">
	<input type="hidden" name="document_memo_bk" value="<?php echo $data['document_data']['document_memo']?>">
	<input type="hidden" name="document_url_bk" value="<?php echo $data['document_data']['document_url']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- ダウンロード資料編集 END -->
