<!-- 個人情報編集 START -->
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
//	var_dump($image);
//	goto err;

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['family_name'])){
		$errorMessage = "苗字（漢字）が未無入力です。";
	} else if(empty($_REQUEST['first_name'])) {
		$errorMessage = "名前（漢字）が未無入力です。";
	} else if(empty($_REQUEST['family_kana_name'])) {
		$errorMessage = "苗字（カナ）が未無入力です。";
	} else if(empty($_REQUEST['first_kana_name'])) {
		$errorMessage = "名前（カナ）が未無入力です。";
//	} else if(empty($_REQUEST['zip'])) {
//		$errorMessage = "郵便番号が未無入力です。";
//	} else if(empty($_REQUEST['address'])) {
//		$errorMessage = "住所が未無入力です。";
	} else if(empty($_REQUEST['tel'])) {
		$errorMessage = "電話番号が未無入力です。";
	} else if (empty($image['name'])<>true && $image['type'] <> 'image/jpeg' && $image['type'] <> 'image/png') {
		$errorMessage = "顔写真が無効なファイル形式です。";
	} else if (empty($image['name'])<>true && $image['size'] > 5000000) {
		$errorMessage = "顔写真のファイルが大きすぎます。";
	} else {
		/* 顔写真保存 */
		if(empty($image['name'])<>true) {
			$face_photo_url = 'uploads/char/' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
			move_uploaded_file($image['tmp_name'], $face_photo_url);
		} else {
			$face_photo_url = NULL;
		}
		/* 個人情報を追加 */
		insert_character_data($data, $_REQUEST['family_name'], $_REQUEST['first_name'], $_REQUEST['family_kana_name'], $_REQUEST['first_kana_name'], $_REQUEST['zip'], $_REQUEST['address'], $_REQUEST['tel'], $face_photo_url);
		header("Location: member_index.php?page=10&sub_page=4&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	$image = $_FILES['image'];				/* 画像取得 */

	/* 必須パラメータ確認 */
	if(empty($_REQUEST['family_name'])){
		$errorMessage = "苗字（漢字）が未無入力です。";
	} else if(empty($_REQUEST['first_name'])) {
		$errorMessage = "名前（漢字）が未無入力です。";
	} else if(empty($_REQUEST['family_kana_name'])) {
		$errorMessage = "苗字（カナ）が未無入力です。";
	} else if(empty($_REQUEST['first_kana_name'])) {
		$errorMessage = "名前（カナ）が未無入力です。";
//	} else if(empty($_REQUEST['zip'])) {
//		$errorMessage = "郵便番号が未無入力です。";
//	} else if(empty($_REQUEST['address'])) {
//		$errorMessage = "住所が未無入力です。";
	} else if(empty($_REQUEST['tel'])) {
		$errorMessage = "電話番号が未無入力です。";
	} else if (empty($image['name'])<>true && $image['type'] <> 'image/jpeg' && $image['type'] <> 'image/png') {
		$errorMessage = "顔写真が無効なファイル形式です。";
	} else if (empty($image['name'])<>true && $image['size'] > 5000000) {
		$errorMessage = "顔写真のファイルが大きすぎます。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_character_data($data, $record, true);							/* レコード参照とロック */
		if(($_REQUEST['family_name_bk'] == $data['character_data']['family_name'])
			&& ($_REQUEST['first_name_bk'] == $data['character_data']['first_name'])
			&& ($_REQUEST['family_kana_name_bk'] == $data['character_data']['family_kana_name'])
			&& ($_REQUEST['first_kana_name_bk'] == $data['character_data']['first_kana_name'])
			&& ($_REQUEST['zip_bk'] == $data['character_data']['zip'])
			&& ($_REQUEST['address_bk'] == $data['character_data']['address'])
			&& ($_REQUEST['tel_bk'] == $data['character_data']['tel'])
			&& ($_REQUEST['face_photo_url_bk'] == $data['character_data']['face_photo_url']))
		{
			/* 顔写真保存 */
			if(empty($image['name'])<>true) {
				if(empty($_REQUEST['face_photo_url'])) {
					$face_photo_url = 'uploads/char/' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
				} else {
					$face_photo_url = $_REQUEST['face_photo_url'];
				}
				move_uploaded_file($image['tmp_name'], $face_photo_url);
			} else {
				$face_photo_url = NULL;
				if(empty($_REQUEST['face_photo_url'])<>true) {
					unlink($_REQUEST['face_photo_url']);
				}
			}
			/* 個人情報を更新 */
			update_character_data($data, $record, $_REQUEST['family_name'], $_REQUEST['first_name'], $_REQUEST['family_kana_name'], $_REQUEST['first_kana_name'], $_REQUEST['zip'], $_REQUEST['address'], $_REQUEST['tel'], $face_photo_url);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=4&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>個人情報編集</h1>

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
	苗字（漢字）：<br /> 
	<input type="text" name="family_name" size="20" value="" /><br />
	名前（漢字）：<br />
	<input type="text" name="first_name" size="20" value="" /><br />
	苗字（カナ）：<br />
	<input type="text" name="family_kana_name" size="20" value="" /><br />
	名前（カナ）：<br />
	<input type="text" name="first_kana_name" size="20" value="" /><br />
	郵便番号：<br />
	<input type="text" name="zip" size="10" value="" /><br />
	住所：<br />
	<input type="text" name="address" size="100" value="" /><br />
	電話番号：<br />
	<input type="text" name="tel" size="20" value="" /><br />
	顔写真：<br />
	<input type="file" name="image"><br />
	<br />
	<input type="submit" value="保存" name="regist"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
} else {
	/* 編集の場合 */
	get_character_data($data, $record);
?>
<form method="post"  enctype="multipart/form-data">
	<input type="hidden" name="record" value="<?php echo $record?>">
	苗字（漢字）：<br /> 
	<input type="text" name="family_name" size="20" value="<?php echo $data['character_data']['family_name']?>" /><br />
	名前（漢字）：<br />
	<input type="text" name="first_name" size="20" value="<?php echo $data['character_data']['first_name']?>" /><br />
	苗字（カナ）：<br />
	<input type="text" name="family_kana_name" size="20" value="<?php echo $data['character_data']['family_kana_name']?>" /><br />
	名前（カナ）：<br />
	<input type="text" name="first_kana_name" size="20" value="<?php echo $data['character_data']['first_kana_name']?>" /><br />
	郵便番号：<br />
	<input type="text" name="zip" size="10" value="<?php echo $data['character_data']['zip']?>" /><br />
	住所：<br />
	<input type="text" name="address" size="200" value="<?php echo $data['character_data']['address']?>" /><br />
	電話番号：<br />
	<input type="text" name="tel" size="20" value="<?php echo $data['character_data']['tel']?>" /><br />
	顔写真：<br />
	<?php 
	if($data['character_data']['face_photo_url'] <> NULL) {
		echo  '<img class="face_photo" src="' . $data['character_data']['face_photo_url'] . '" alt="アップロードされた画像">';
	}
	?>
	<input type="file" name="image"><br />
	<input type="hidden" name="face_photo_url" value="<?php echo $data['character_data']['face_photo_url']?>">
	<br />
	<input type="hidden" name="family_name_bk" value="<?php echo $data['character_data']['family_name']?>">
	<input type="hidden" name="first_name_bk" value="<?php echo $data['character_data']['first_name']?>">
	<input type="hidden" name="family_kana_name_bk" value="<?php echo $data['character_data']['family_kana_name']?>">
	<input type="hidden" name="first_kana_name_bk" value="<?php echo $data['character_data']['first_kana_name']?>">
	<input type="hidden" name="zip_bk" value="<?php echo $data['character_data']['zip']?>">
	<input type="hidden" name="address_bk" value="<?php echo $data['character_data']['address']?>">
	<input type="hidden" name="tel_bk" value="<?php echo $data['character_data']['tel']?>">
	<input type="hidden" name="face_photo_url_bk" value="<?php echo $data['character_data']['face_photo_url']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
</form>
<?php
}
?>
</div>
<!-- 個人情報編集 END -->
