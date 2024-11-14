<!-- 団員編集 START -->
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
	if(empty($_REQUEST['member_info_id'])) {
		$errorMessage = "団員が未無入力です。";
	} else if(empty($_REQUEST['hogosya1_id'])) {
		$errorMessage = "保護者１が未無入力です。";
	} else {
		/* 団員情報を追加 */
		insert_member_data($data, $_REQUEST['member_info_id'], $_REQUEST['license_no'], $_REQUEST['team_id'], $_REQUEST['role_id'], $_REQUEST['school_id'], $_REQUEST['school_year_id'], $_REQUEST['hogosya1_id'], $_REQUEST['hogo1zokugara_id'], $_REQUEST['hogosya2_id'], $_REQUEST['hogo2zokugara_id']);
		header("Location: member_index.php?page=10&sub_page=2&id=0");
	}
}

/* 更新処理 */
if(isset($_REQUEST['update']))
{
	/* 必須パラメータ確認 */
	if(empty($_REQUEST['member_info_id'])) {
		$errorMessage = "団員が未無入力です。";
	} else if(empty($_REQUEST['hogosya1_id'])) {
		$errorMessage = "保護者１が未無入力です。";
	} else {
		/* 更新チェック */
		db_begin($data);													/* トランザクション開始 */
		get_member_data($data, $record, true);								/* レコード参照とロック */
		if(($_REQUEST['member_info_id_bk'] == $data['member_data']['member_info_id'])
			&& ($_REQUEST['license_no_bk'] == $data['member_data']['license_no'])
			&& ($_REQUEST['team_id_bk'] == $data['member_data']['team_id'])
			&& ($_REQUEST['role_id_bk'] == $data['member_data']['role_id'])
			&& ($_REQUEST['school_id_bk'] == $data['member_data']['school_id'])
			&& ($_REQUEST['school_year_id_bk'] == $data['member_data']['school_year_id'])
			&& ($_REQUEST['hogosya1_id_bk'] == $data['member_data']['hogosya1_id'])
			&& ($_REQUEST['hogo1zokugara_id_bk'] == $data['member_data']['hogo1zokugara_id'])
			&& ($_REQUEST['hogosya2_id_bk'] == $data['member_data']['hogosya2_id'])
			&& ($_REQUEST['hogo2zokugara_id_bk'] == $data['member_data']['hogo2zokugara_id']))
		{
			/* 団員情報を更新 */
			update_member_data($data, $record, $_REQUEST['member_info_id'], $_REQUEST['license_no'], $_REQUEST['team_id'], $_REQUEST['role_id'], $_REQUEST['school_id'], $_REQUEST['school_year_id'], $_REQUEST['hogosya1_id'], $_REQUEST['hogo1zokugara_id'], $_REQUEST['hogosya2_id'], $_REQUEST['hogo2zokugara_id']);
			db_commit($data);												/* データ更新＆トランザクション終了 */
			header("Location: member_index.php?page=10&sub_page=2&id=0");
		} else {
			$errorMessage = "他のユーザによりデータが更新されたため、処理を中断しました。";
			db_rollback($data);												/* データ破棄＆トランザクション終了 */
		}
	}
}
?>

<div class="data_edit">
<h1>団員編集</h1>

<?php
	if($errorMessage != "") {
?>
		<div class="alert alert-danger" role="alert"><?php echo $errorMessage ?></div>
<?php
	}
?>

<?php
/* データベースから権限のリストを取得 */
get_member_table_list($data);
get_character_name_list($data);
get_team_name_list($data);
get_role_name_list($data);
get_school_name_list($data);
get_school_year_list($data);
get_relationship_list($data);
if($record == 0) {
	/* 新規作成の場合 */
?>
<form method="post">
	団員：<br />
	<select name="member_info_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['family_name'] . " " . $row['first_name']?></option>
<?php
		}
	}
?>
	</select><br />
	ライセンス番号：<br />
	<input type="text" name="license_no" size="20" value="" /><br />
	チーム：<br />
	<select name="team_id">
<?php
	if($data['team_list'] != NULL) {
		foreach ($data['team_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['team_name']?></option>
<?php
		}
	}
?>
	</select><br />
	係り：<br />
	<select name="role_id">
<?php
	if($data['role_list'] != NULL) {
		foreach ($data['role_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['role_name']?></option>
<?php
		}
	}
?>
	</select><br />
	学校：<br />
	<select name="school_id">
<?php
	if($data['school_list'] != NULL) {
		foreach ($data['school_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['school_name']?></option>
<?php
		}
	}
?>
	</select><br />
	学年：<br />
	<select name="school_year_id">
<?php
	if($data['school_year_list'] != NULL) {
		foreach ($data['school_year_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['school_year_name']?></option>
<?php
		}
	}
?>
	</select><br />
	保護者１：<br />
	<select name="hogosya1_id">
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
	保護者１続柄：<br />
	<select name="hogo1zokugara_id">
<?php
	if($data['relationship_list'] != NULL) {
		foreach ($data['relationship_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['relationship_name']?></option>
<?php
		}
	}
?>
	</select><br />
	保護者２：<br />
	<select name="hogosya2_id">
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
	保護者２続柄：<br />
	<select name="hogo2zokugara_id">
<?php
	if($data['relationship_list'] != NULL) {
		foreach ($data['relationship_list'] as $row) {
?>
		<option value="<?php echo $row['id']?>"><?php echo $row['relationship_name']?></option>
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
	/* 編集の場合 */
	get_member_data($data, $record);
?>
<form method="post">
	<input type="hidden" name="record" value="<?php echo $record?>">
	団員：<br />
	<select name="member_info_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
			if($row['id'] == $data['member_data']['member_info_id']) {
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
	ライセンス番号：<br />
	<input type="text" name="license_no" size="20" value="<?php echo $data['member_data']['license_no']?>"><br />
	チーム：<br />
	<select name="team_id">
<?php
	if($data['team_list'] != NULL) {
		foreach ($data['team_list'] as $row) {
			if($row['id'] == $data['member_data']['team_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['team_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['team_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	係り：<br />
	<select name="role_id">
<?php
	if($data['role_list'] != NULL) {
		foreach ($data['role_list'] as $row) {
			if($row['id'] == $data['member_data']['role_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['role_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['role_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	学校：<br />
	<select name="school_id">
<?php
	if($data['school_list'] != NULL) {
		foreach ($data['school_list'] as $row) {
			if($row['id'] == $data['member_data']['school_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['school_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['school_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	学年：<br />
	<select name="school_year_id">
<?php
	if($data['school_year_list'] != NULL) {
		foreach ($data['school_year_list'] as $row) {
			if($row['id'] == $data['member_data']['school_year_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['school_year_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['school_year_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	保護者１：<br />
	<select name="hogosya1_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
			if($row['id'] == $data['member_data']['hogosya1_id']) {
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
	保護者１続柄：<br />
	<select name="hogo1zokugara_id">
<?php
	if($data['relationship_list'] != NULL) {
		foreach ($data['relationship_list'] as $row) {
			if($row['id'] == $data['member_data']['hogo1zokugara_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['relationship_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['relationship_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	保護者２：<br />
	<select name="hogosya2_id">
<?php
	if($data['character_list'] != NULL) {
		foreach ($data['character_list'] as $row) {
			if($row['id'] == $data['member_data']['hogosya2_id']) {
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
	保護者２続柄：<br />
	<select name="hogo2zokugara_id">
<?php
	if($data['relationship_list'] != NULL) {
		foreach ($data['relationship_list'] as $row) {
			if($row['id'] == $data['member_data']['hogo2zokugara_id']) {
?>
				<option value="<?php echo $row['id']?>" selected><?php echo $row['relationship_name']?></option>
<?php
			} else {
?>
				<option value="<?php echo $row['id']?>"><?php echo $row['relationship_name']?></option>
<?php
			}
		}
	}
?>
	</select><br />
	<br />
	<input type="hidden" name="member_info_id_bk" value="<?php echo $data['member_data']['member_info_id']?>">
	<input type="hidden" name="license_no_bk" value="<?php echo $data['member_data']['license_no']?>">
	<input type="hidden" name="team_id_bk" value="<?php echo $data['member_data']['team_id']?>">
	<input type="hidden" name="role_id_bk" value="<?php echo $data['member_data']['role_id']?>">
	<input type="hidden" name="school_id_bk" value="<?php echo $data['member_data']['school_id']?>">
	<input type="hidden" name="school_year_id_bk" value="<?php echo $data['member_data']['school_year_id']?>">
	<input type="hidden" name="hogosya1_id_bk" value="<?php echo $data['member_data']['hogosya1_id']?>">
	<input type="hidden" name="hogo1zokugara_id_bk" value="<?php echo $data['member_data']['hogo1zokugara_id']?>">
	<input type="hidden" name="hogosya2_id_bk" value="<?php echo $data['member_data']['hogosya2_id']?>">
	<input type="hidden" name="hogo2zokugara_id_bk" value="<?php echo $data['member_data']['hogo2zokugara_id']?>">
	<input type="submit" value="保存" name="update"/>
	<input type="button" onclick="self.history.back()" value="戻る" />
	</form>
<?php
}
?>

</div>
<!-- 団員編集 END -->
