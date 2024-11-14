<!-- 学校編集 START -->
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
	if(empty($_REQUEST['school_name'])){
		$errorMessage = "学校名が未無入力です。";
	} else if(empty($_REQUEST['school_zip'])) {
		$errorMessage = "郵便番号が未無入力です。";
	} else if(empty($_REQUEST['school_address'])) {
		$errorMessage = "住所が未無入力です。";
	} else if(empty($_REQUEST['school_tel'])) {
		$errorMessage = "電話番号が未無入力です。";
	} else {
		/* 学校を追加 */
		insert_school_data($data, $_REQUEST['school_name'], $_REQUEST['school_zip'], $_REQUEST['school_address'], $_REQUEST['school_tel'], $_REQUEST['school_map_url']);
		header("Location: member_index.php?page=10&sub_page=6&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['school_name'])){
		$errorMessage = "学校名が未無入力です。";
	} else if(empty($_REQUEST['school_zip'])) {
		$errorMessage = "郵便番号が未無入力です。";
	} else if(empty($_REQUEST['school_address'])) {
		$errorMessage = "住所が未無入力です。";
	} else if(empty($_REQUEST['school_tel'])) {
		$errorMessage = "電話番号が未無入力です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_school_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['school_name_bk'] == $data['school_data']['school_name'])
			&& ($_REQUEST['school_zip_bk'] == $data['school_data']['school_zip'])
			&& ($_REQUEST['school_address_bk'] == $data['school_data']['school_address'])
			&& ($_REQUEST['school_tel_bk'] == $data['school_data']['school_tel'])
			&& ($_REQUEST['school_map_url_bk'] == $data['school_data']['school_map_url']))
		{
			/* 学校を更新 */
			update_school_data($data, $record, $_REQUEST['school_name'], $_REQUEST['school_zip'], $_REQUEST['school_address'], $_REQUEST['school_tel'], $_REQUEST['school_map_url']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=6&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
err:
?>

<div class="data_edit">
<h1>学校編集</h1>

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
	学校名：<br /> 
	<input type="text" name="school_name" size="20" value="" /><br />
	郵便番号：<br />
	<input type="text" name="school_zip" size="10" value="" /><br />
	住所：<br />
	<input type="text" name="school_address" size="100" value="" /><br />
	電話番号：<br />
	<input type="text" name="school_tel" size="20" value="" /><br />
	学校周辺地図のURL：<br />
	<textarea name="school_map_url" cols="100" rows="10"></textarea><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_school_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	学校名：<br /> 
	<input type="text" name="school_name" size="20" value="<?php echo $data['school_data']['school_name']?>" /><br />
	郵便番号：<br />
	<input type="text" name="school_zip" size="10" value="<?php echo $data['school_data']['school_zip']?>" /><br />
	住所：<br />
	<input type="text" name="school_address" size="100" value="<?php echo $data['school_data']['school_address']?>" /><br />
	電話番号：<br />
	<input type="text" name="school_tel" size="20" value="<?php echo $data['school_data']['school_tel']?>" /><br />
	学校周辺地図のURL：<br />
	<textarea name="school_map_url" cols="100" rows="10"><?php echo $data['school_data']['school_map_url']?></textarea><br />
	<input type="hidden" name="school_name_bk" value="<?php echo $data['school_data']['school_name']?>">
	<input type="hidden" name="school_zip_bk" value="<?php echo $data['school_data']['school_zip']?>">
	<input type="hidden" name="school_address_bk" value="<?php echo $data['school_data']['school_address']?>">
	<input type="hidden" name="school_tel_bk" value="<?php echo $data['school_data']['school_tel']?>">
	<input type="hidden" name="school_map_url_bk" value="<?php echo $data['school_data']['school_map_url']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- 学校編集 END -->
