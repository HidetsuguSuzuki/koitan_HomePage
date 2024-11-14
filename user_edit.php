<!-- ログインユーザ編集 START -->
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
	get_attribute_count($data);
	get_character_count($data);
	if(empty($_REQUEST['login_id'])) {
		$errorMessage = "ログインIDが未無入力です。";
	} else if(empty($_REQUEST['login_pw'])) {
		$errorMessage = "パスワードが未無入力です。";
	} else if(($_REQUEST['attribute_id'] < 1) || ($_REQUEST['attribute_id'] > $data['attrib_count'])){
		$errorMessage = "権限設定が不正です。";
	} else if(($_REQUEST['user_info_id'] < 1) || ($_REQUEST['user_info_id'] > $data['chardata_count'])){
		$errorMessage = "個人データが不正です。";
	} else {
		/* ユーザ情報を追加 */
		insert_user_data($data, $_REQUEST['login_id'], $_REQUEST['login_pw'], $_REQUEST['attribute_id'], $_REQUEST['user_info_id']);
		header("Location: member_index.php?page=10&sub_page=3&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	get_attribute_count($data);
	get_character_count($data);
	if(empty($_REQUEST['login_id'])) {
		$errorMessage = "ログインIDが未無入力です。";
	} else if(empty($_REQUEST['login_pw'])) {
		$errorMessage = "パスワードが未無入力です。";
	} else if(($_REQUEST['attribute_id'] < 1) || ($_REQUEST['attribute_id'] > $data['attrib_count'])){
		$errorMessage = "権限設定が不正です。";
	} else if(($_REQUEST['user_info_id'] < 1) || ($_REQUEST['user_info_id'] > $data['chardata_count'])){
		$errorMessage = "個人データが不正です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_user_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['login_id_bk'] == $data['user_data']['login_id'])
			&& ($_REQUEST['login_pw_bk'] == $data['user_data']['login_pw'])
			&& ($_REQUEST['attribute_id_bk'] == $data['user_data']['attribute_id'])
			&& ($_REQUEST['user_info_id_bk'] == $data['user_data']['user_info_id']))
		{
			/* ユーザ情報を更新 */
			update_user_data($data, $record, $_REQUEST['login_id'], $_REQUEST['login_pw'], $_REQUEST['attribute_id'], $_REQUEST['user_info_id']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=3&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>ログインユーザ編集</h1>

<?php
	if($errorMessage != "") {
?>
		<div class="alert alert-danger" role="alert"><?php echo $errorMessage ?></div>
<?php
	}
?>

<?php
/* データベースから権限のリストを取得 */
get_attribute_list($data);
get_character_table_list($data);
if($record == 0) {
	/* 新規作成の場合 */
?>
<form method="post">
	ログインID：<br />
	<input type="text" name="login_id" size="20" value="" /><br />
	パスワード：<br />
	<input type="password" name="login_pw" size="20" value="" /><br />
	権限設定：<br />
	<select name="attribute_id">
<?php
	if($data['attrib_list'] != NULL) {
		foreach ($data['attrib_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['attrib_name']?></option>
<?php
		}
	}
?>
	</select><br />
	個人情報リンク：<br />
	<select name="user_info_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['family_name'] . ' ' . $row['first_name']?></option>
<?php
		}
	}
?>
	</select><br />
	<br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合（今後、パスワードの変更処理に関しては要件等とする） */
	get_user_data($data, $record);
?>
<form method="post">
	<input type="hidden" name="record" value="<?php echo $record?>">
	ログインID：<br />
	<input type="text" name="login_id" size="20" value="<?php echo $data['user_data']['login_id']?>" /><br />
	パスワード：<br />
	<input type="password" name="login_pw" size="20" value="" /><br />
	権限設定：<br />
	<select name="attribute_id">
<?php
	if($data['attrib_list'] != NULL) {
		foreach ($data['attrib_list'] as $row) {
			if($row['id'] == $data['user_data']['attribute_id']) {
?>
		<option value="<?php echo $row['id']?>" selected><?php echo $row['attrib_name']?></option>
<?php
			} else {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['attrib_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	個人情報リンク：<br />
	<select name="user_info_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
			if($row['id'] == $data['user_data']['user_info_id']) {
?>
		<option value="<?php echo $row['id']?>" selected><?php echo $row['family_name'] . ' ' . $row['first_name']?></option>
<?php
			} else {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['family_name'] . ' ' . $row['first_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	<br />
	<input type="hidden" name="login_id_bk" value="<?php echo $data['user_data']['login_id']?>">
	<input type="hidden" name="login_pw_bk" value="<?php echo $data['user_data']['login_pw']?>">
	<input type="hidden" name="attribute_id_bk" value="<?php echo $data['user_data']['attribute_id']?>">
	<input type="hidden" name="user_info_id_bk" value="<?php echo $data['user_data']['user_info_id']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
}
?>

</div>
<!-- ログインユーザ編集 END -->
