<!-- お知らせ編集 START -->
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
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['info_title'])){
		$errorMessage = "お知らせタイトルが未無入力です。";
	} else if(empty($_REQUEST['info_memo'])) {
		$errorMessage = "お知らせ内容が未無入力です。";
	} else {
		/* お知らせを追加 */
		insert_info_data($data, $_REQUEST['info_title'], date("Y-m-d H:i:s"), $_SESSION['user_info'], $_REQUEST['info_memo']);
		header("Location: member_index.php?page=10&sub_page=1&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['info_title'])){
		$errorMessage = "お知らせタイトルが未無入力です。";
	} else if(empty($_REQUEST['info_memo'])) {
		$errorMessage = "お知らせ内容が未無入力です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_info_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['info_title_bk'] == $data['info_data']['info_title'])
			&& ($_REQUEST['info_memo_bk'] == $data['info_data']['info_memo']))
		{
			/* お知らせを更新 */
			update_info_data($data, $record, $_REQUEST['info_title'], date("Y-m-d H:i:s"), $_SESSION['user_info'], $_REQUEST['info_memo']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=1&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
err:
?>

<div class="data_edit">
<h1>お知らせ編集</h1>

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
	タイトル：<br /> 
	<input type="text" name="info_title" size="100" value="" /><br />
	内容：<br />
	<textarea name="info_memo" cols="100" rows="10"></textarea><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_info_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	タイトル：<br /> 
	<input type="text" name="info_title" size="100" value="<?php echo $data['info_data']['info_title']?>"/><br />
	内容：<br />
	<textarea name="info_memo" cols="100" rows="10"><?php echo $data['info_data']['info_memo']?></textarea><br />
	<input type="hidden" name="info_title_bk" value="<?php echo $data['info_data']['info_title']?>">
	<input type="hidden" name="info_memo_bk" value="<?php echo $data['info_data']['info_memo']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- お知らせ編集 END -->
