<!-- 備品編集 START -->
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
	$image = $_FILES['image'];				/* 画像取得 */

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['item_name'])){
		$errorMessage = "品名が未無入力です。";
	} else if(empty($_REQUEST['item_count'])) {
		$errorMessage = "数量が未無入力です。";
	} else if(empty($_REQUEST['item_pos'])) {
		$errorMessage = "保管場所が未無入力です。";
	} else if (empty($image['name'])<>true && $image['type'] <> 'image/jpeg' && $image['type'] <> 'image/png') {
		$errorMessage = "保管場所写真が無効なファイル形式です。";
	} else if (empty($image['name'])<>true && $image['size'] > 5000000) {
		$errorMessage = "保管場所写真のファイルが大きすぎます。";
	} else {
		/* 保管場所写真保存 */
		if(empty($image['name'])<>true) {
			$item_photo_url = 'uploads/item/' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
			move_uploaded_file($image['tmp_name'], $item_photo_url);
		} else {
			$item_photo_url = NULL;
		}
		/* 備品を追加 */
		insert_item_data($data, $_REQUEST['item_name'], $_REQUEST['item_count'], $_REQUEST['item_pos'], $item_photo_url, $_REQUEST['item_memo']);
		header("Location: member_index.php?page=10&sub_page=8&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	$image = $_FILES['image'];				/* 画像取得 */

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['item_name'])){
		$errorMessage = "品名が未無入力です。";
	} else if(empty($_REQUEST['item_count'])) {
		$errorMessage = "数量が未無入力です。";
	} else if(empty($_REQUEST['item_pos'])) {
		$errorMessage = "保管場所が未無入力です。";
	} else if (empty($image['name'])<>true && $image['type'] <> 'image/jpeg' && $image['type'] <> 'image/png') {
		$errorMessage = "保管場所写真が無効なファイル形式です。";
	} else if (empty($image['name'])<>true && $image['size'] > 5000000) {
		$errorMessage = "保管場所写真のファイルが大きすぎます。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_item_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['item_name_bk'] == $data['item_data']['item_name'])
			&& ($_REQUEST['item_count_bk'] == $data['item_data']['item_count'])
			&& ($_REQUEST['item_pos_bk'] == $data['item_data']['item_pos'])
			&& ($_REQUEST['item_memo_bk'] == $data['item_data']['item_memo'])
			&& ($_REQUEST['item_pos_photo_bk'] == $data['item_data']['item_pos_photo']))
		{
			/* 保管場所写真保存 */
			if(empty($image['name'])<>true) {
				if(empty($_REQUEST['item_pos_photo'])) {
					$item_photo_url = 'uploads/item/' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
				} else {
					$item_photo_url = $_REQUEST['item_pos_photo'];
				}
				move_uploaded_file($image['tmp_name'], $item_photo_url);
			} else {
				$item_photo_url = NULL;
				if(empty($_REQUEST['item_pos_photo'])<>true) {
					unlink($_REQUEST['item_pos_photo']);
				}
			}
			/* 備品を更新 */
			update_item_data($data, $record, $_REQUEST['item_name'], $_REQUEST['item_count'], $_REQUEST['item_pos'], $item_photo_url, $_REQUEST['item_memo']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=8&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>備品編集</h1>

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
	品名：<br /> 
	<input type="text" name="item_name" size="100" value="" /><br />
	数量：<br />
	<input type="number" name="item_count" size="20" value="" /><br />
	保管場所：<br />
	<textarea name="item_pos" cols="100" rows="10"></textarea><br />
	備品説明：<br />
	<textarea name="item_memo" cols="100" rows="10"></textarea><br />
	保管場所写真：<br />
	<input type="file" name="image"><br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_item_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	品名：<br /> 
	<input type="text" name="item_name" size="100" value="<?php echo $data['item_data']['item_name']?>"><br />
	数量：<br />
	<input type="number" name="item_count" size="20" value="<?php echo $data['item_data']['item_count']?>"><br />
	保管場所：<br />
	<textarea name="item_pos" cols="100" rows="10"><?php echo $data['item_data']['item_pos']?></textarea><br />
	備品説明：<br />
	<textarea name="item_memo" cols="100" rows="10"><?php echo $data['item_data']['item_memo']?></textarea><br />
	保管場所写真：<br />
	<?php 
	if($data['item_data']['item_pos_photo'] <> NULL) {
		echo  '<img class="item_photo" src="' . $data['item_data']['item_pos_photo'] . '" alt="アップロードされた画像">';
	}
	?>
	<input type="file" name="image"><br />
	<input type="hidden" name="item_pos_photo" value="<?php echo $data['item_data']['item_pos_photo']?>">
	<br />
	<input type="hidden" name="item_name_bk" value="<?php echo $data['item_data']['item_name']?>">
	<input type="hidden" name="item_count_bk" value="<?php echo $data['item_data']['item_count']?>">
	<input type="hidden" name="item_pos_bk" value="<?php echo $data['item_data']['item_pos']?>">
	<input type="hidden" name="item_memo_bk" value="<?php echo $data['item_data']['item_memo']?>">
	<input type="hidden" name="item_pos_photo_bk" value="<?php echo $data['item_data']['item_pos_photo']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- 備品編集 END -->
