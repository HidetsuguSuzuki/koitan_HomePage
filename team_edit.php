<!-- チーム編集 START -->
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
	if(empty($_REQUEST['team_name'])){
		$errorMessage = "チーム名が未無入力です。";
	} else if(empty($_REQUEST['team_memo'])) {
		$errorMessage = "チーム説明が未無入力です。";
	} else {
		/* チームを追加 */
		insert_team_data($data, $_REQUEST['team_name'], $_REQUEST['team_memo']);
		header("Location: member_index.php?page=10&sub_page=7&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['team_name'])){
		$errorMessage = "チーム名が未無入力です。";
	} else if(empty($_REQUEST['team_memo'])) {
		$errorMessage = "チーム説明が未無入力です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_team_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['team_name_bk'] == $data['team_data']['team_name'])
			&& ($_REQUEST['team_memo_bk'] == $data['team_data']['team_memo']))
		{
			/* チームを更新 */
			update_team_data($data, $record, $_REQUEST['team_name'], $_REQUEST['team_memo']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=7&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>チーム編集</h1>

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
	<input type="text" name="team_name" size="20" value="" /><br />
	内容：<br />
	<textarea name="team_memo" cols="100" rows="10"></textarea><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_team_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	係り：<br /> 
	<input type="text" name="team_name" size="20" value="<?php echo $data['team_data']['team_name']?>"/><br />
	内容：<br />
	<textarea name="team_memo" cols="100" rows="10"><?php echo $data['team_data']['team_memo']?></textarea><br />
	<input type="hidden" name="team_name_bk" value="<?php echo $data['team_data']['team_name']?>">
	<input type="hidden" name="team_memo_bk" value="<?php echo $data['team_data']['team_memo']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- チーム編集 END -->
