<!-- 係り編集 START -->
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
	if(empty($_REQUEST['role_name'])){
		$errorMessage = "係りが未無入力です。";
	} else if(empty($_REQUEST['role_memo'])) {
		$errorMessage = "内容が未無入力です。";
	} else {
		/* 係りを追加 */
		insert_role_data($data, $_REQUEST['role_name'], $_REQUEST['role_memo']);
		header("Location: member_index.php?page=10&sub_page=5&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['role_name'])){
		$errorMessage = "係りが未無入力です。";
	} else if(empty($_REQUEST['role_memo'])) {
		$errorMessage = "内容が未無入力です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_role_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['role_name_bk'] == $data['role_data']['role_name'])
			&& ($_REQUEST['role_memo_bk'] == $data['role_data']['role_memo']))
		{
			/* お知らせを更新 */
			update_role_data($data, $record, $_REQUEST['role_name'], $_REQUEST['role_memo']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=5&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
err:
?>

<div class="data_edit">
<h1>係り編集</h1>

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
	係り：<br /> 
	<input type="text" name="role_name" size="100" value="" /><br />
	内容：<br />
	<textarea name="role_memo" cols="100" rows="10"></textarea><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_role_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	係り：<br /> 
	<input type="text" name="role_name" size="100" value="<?php echo $data['role_data']['role_name']?>"/><br />
	内容：<br />
	<textarea name="role_memo" cols="100" rows="10"><?php echo $data['role_data']['role_memo']?></textarea><br />
	<input type="hidden" name="role_name_bk" value="<?php echo $data['role_data']['role_name']?>">
	<input type="hidden" name="role_memo_bk" value="<?php echo $data['role_data']['role_memo']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- 係り編集 END -->
