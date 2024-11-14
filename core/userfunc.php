<?php

/****************************************************************/
/*	テーブルアクセス用関数群									*/
/*																*/
/*	File:		userfunc.php									*/
/*	Writer:		Hidetsugu.suzuki								*/
/*	Data:		2024/06/04										*/
/*	Memo:		テーブルリスト									*/
/*				user:			ユーザテーブル					*/
/*				member:			団員テーブル					*/
/*				character:		個人情報テーブル				*/
/*				role:			係りテーブル					*/
/*				school:			学校テーブル					*/
/*				team:			チームテーブル					*/
/*				attribute:		権限テーブル（固定）			*/
/*				relationship:	続柄テーブル（固定）			*/
/*				school_year:	学年テーブル（固定）			*/
/*																*/
/****************************************************************/

/****************************************************************/
/*	ユーザ（利用者）テーブル情報								*/
/*	テーブル名：		user									*/
/*	id:					ユーザID（自動付与）					*/
/*	login_id:			ログインID（MAX20）						*/
/*	login_pw:			ログインPW（MAX100※60以上にする）	   */
/*	attribute_id:		権限ID（権限テーブルID）				*/
/*	user_info_id:		個人データ（個人情報テーブルID）		*/
/*																*/
/****************************************************************/
/*	ユーザテーブルアクセス関数軍								*/
/****************************************************************/

/* ユーザテーブル作成処理 */
function	make_user_table($pdo)
{
	$AdminUser = "admin";
	$AdminPass = "root";

	/* ユーザテーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS user(id int auto_increment primary key, login_id varchar(20), login_pw varchar(100), attribute_id int, user_info_id int, foreign key(attribute_id) references attribute(id) on update cascade on delete set null,  foreign key(user_info_id) references chardata(id) on update cascade on delete set null)");

	/* 管理者ユーザ作成 */
	$result = $pdo->prepare("INSERT INTO user (login_id, login_pw, attribute_id, user_info_id) VALUES (:login_id, :login_pw, :attribute_id, :user_info_id)");
	$result->bindParam(':login_id', $AdminUser, PDO::PARAM_STR);
	$password = password_hash($AdminPass, PASSWORD_DEFAULT);
	$result->bindParam(':login_pw', $password, PDO::PARAM_STR);
	$result->bindValue(':attribute_id', 1, PDO::PARAM_INT);
	$result->bindValue(':user_info_id', 1, PDO::PARAM_INT);
	$result->execute();
}

/* ユーザリスト取得処理 */
function	get_user_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from user");
	$i = 0;
	foreach ($result as $row) {
		$data['user_list'][$i++] = $row;
	}
}

/* ユーザデータ取得処理 */
function	get_user_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM user WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM user WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['user_data'] = $result->fetch();
}

/* ユーザデータ追加処理 */
function	insert_user_data(&$data, $login_id, $login_pw, $attribute_id, $user_info_id)
{
	$result = $data['pdo']->prepare("INSERT INTO user (login_id, login_pw, attribute_id, user_info_id) VALUES (:login_id, :login_pw, :attribute_id, :user_info_id)");
	$result->bindParam(':login_id', $login_id, PDO::PARAM_STR);
	$password = password_hash($login_pw, PASSWORD_DEFAULT);
	$result->bindParam(':login_pw', $password, PDO::PARAM_STR);
	$result->bindValue(':attribute_id', $attribute_id, PDO::PARAM_INT);
	$result->bindValue(':user_info_id', $user_info_id, PDO::PARAM_INT);
	$result->execute();
}

/* ユーザデータ更新処理 */
function	update_user_data(&$data, $record, $login_id, $login_pw, $attribute_id, $user_info_id)
{
	$result = $data['pdo']->prepare("UPDATE user SET login_id = :login_id, login_pw = :login_pw, attribute_id = :attribute_id, user_info_id = :user_info_id WHERE id = :id");
	$result->bindParam(':login_id', $login_id, PDO::PARAM_STR);
	$password = password_hash($login_pw, PASSWORD_DEFAULT);
	$result->bindParam(':login_pw', $password, PDO::PARAM_STR);
	$result->bindValue(':attribute_id', $attribute_id, PDO::PARAM_INT);
	$result->bindValue(':user_info_id', $user_info_id, PDO::PARAM_INT);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* ユーザデータ削除処理 */
function	delete_user_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM user WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* ログインユーザリスト取得処理 */
function	get_loginuser_list(&$data)
{
	$result = $data['pdo']->query("SELECT user.*, chardata.family_name, chardata.first_name, attribute.attrib_name FROM user INNER JOIN chardata ON user.user_info_id = chardata.id  INNER JOIN attribute ON user.attribute_id = attribute.id");
	$i = 0;
	foreach ($result as $row) {
		$data['user_list'][$i++] = $row;
	}
}

/* ログインユーザデータ取得処理 */
function	get_loginuser_data(&$data, $login_id)
{
	$result = $data['pdo']->prepare("SELECT user.*, chardata.family_name, chardata.first_name FROM user INNER JOIN chardata ON user.user_info_id = chardata.id WHERE login_id=:login_id");
//	$result = $data['pdo']->prepare("SELECT * FROM user WHERE login_id=:login_id");
	$result->bindParam(':login_id', $login_id, PDO::PARAM_STR);
	$result->execute();
	$data['loginuser_data'] = $result->fetch();
}


/****************************************************************/
/*	団員テーブル情報											*/
/*	テーブル名：		member									*/
/*	id:					団員ID（自動付与）						*/
/*	member_info_id:		団員データ（個人情報テーブルID）		*/
/*	license_no:			ライセンス番号（MAX20）					*/
/*	team_id:			チーム（チームテーブルID）				*/
/*	role_id:			係り（係りテーブルID）					*/
/*	school_id:			学校（学校テーブルID）					*/
/*	school_year_id:		学年（学年テーブルID）					*/
/*	hogosya1_id			保護者１データ（個人情報テーブルID）	*/
/*	hogo1zokugara_id	保護者１続柄（続柄テーブルID）			*/
/*	hogosya2_id			保護者２データ（個人情報テーブルID）	*/
/*	hogo2zokugara_id	保護者２続柄（続柄テーブルID）			*/
/*																*/
/****************************************************************/
/*	団員テーブルアクセス関数軍									*/
/****************************************************************/

/* 団員テーブル作成処理 */
function	make_member_table($pdo)
{
	/* 団員テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS member(id int auto_increment primary key, member_info_id int, license_no varchar(20), team_id int,  role_id int, school_id int, school_year_id int, hogosya1_id int, hogo1zokugara_id int, hogosya2_id int, hogo2zokugara_id int, foreign key(member_info_id) references chardata(id) on update cascade on delete set null, foreign key(team_id) references team(id) on update cascade on delete set null, foreign key(role_id) references role(id) on update cascade on delete set null, foreign key(school_id) references school(id) on update cascade on delete set null, foreign key(school_year_id) references school_year(id) on update cascade on delete set null, foreign key(hogosya1_id) references chardata(id) on update cascade on delete set null, foreign key(hogo1zokugara_id) references relationship(id) on update cascade on delete set null, foreign key(hogosya2_id) references chardata(id) on update cascade on delete set null, foreign key(hogo2zokugara_id) references relationship(id) on update cascade on delete set null)");
}

/* 団員リスト取得処理 */
function	get_member_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from member");
	$i = 0;
	foreach ($result as $row) {
		$data['member_list'][$i++] = $row;
	}
}

/* 団員データ取得処理 */
function	get_member_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM member WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM member WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['member_data'] = $result->fetch();
}

/* 団員データ追加処理 */
function	insert_member_data(&$data, $member_info_id, $license_no, $team_id, $role_id, $school_id, $school_year_id, $hogosya1_id, $hogo1zokugara_id, $hogosya2_id, $hogo2zokugara_id)
{
	$result = $data['pdo']->prepare("INSERT INTO member (member_info_id, license_no, team_id, role_id, school_id, school_year_id, hogosya1_id, hogo1zokugara_id, hogosya2_id, hogo2zokugara_id) VALUES (:member_info_id, :license_no, :team_id, :role_id, :school_id, :school_year_id, :hogosya1_id, :hogo1zokugara_id, :hogosya2_id, :hogo2zokugara_id)");
	$result->bindParam(':member_info_id', $member_info_id, PDO::PARAM_INT);
	$result->bindParam(':license_no', $license_no, PDO::PARAM_STR);
	$result->bindParam(':team_id', $team_id, PDO::PARAM_INT);
	$result->bindParam(':role_id', $role_id, PDO::PARAM_INT);
	$result->bindParam(':school_id', $school_id, PDO::PARAM_INT);
	$result->bindParam(':school_year_id', $school_year_id, PDO::PARAM_INT);
	$result->bindParam(':hogosya1_id', $hogosya1_id, PDO::PARAM_INT);
	$result->bindParam(':hogo1zokugara_id', $hogo1zokugara_id, PDO::PARAM_INT);
	$result->bindParam(':hogosya2_id', $hogosya2_id, PDO::PARAM_INT);
	$result->bindParam(':hogo2zokugara_id', $hogo2zokugara_id, PDO::PARAM_INT);
	$result->execute();
}

/* 団員データ更新処理 */
function	update_member_data(&$data, $record, $member_info_id, $license_no, $team_id, $role_id, $school_id, $school_year_id, $hogosya1_id, $hogo1zokugara_id, $hogosya2_id, $hogo2zokugara_id)
{
	$result = $data['pdo']->prepare("UPDATE member SET member_info_id = :member_info_id, license_no = :license_no, team_id = :team_id, role_id = :role_id, school_id = :school_id, school_year_id = :school_year_id, hogosya1_id = :hogosya1_id, hogo1zokugara_id = :hogo1zokugara_id, hogosya2_id = :hogosya2_id, hogo2zokugara_id = :hogo2zokugara_id WHERE id = :id");
	$result->bindParam(':member_info_id', $member_info_id, PDO::PARAM_INT);
	$result->bindParam(':license_no', $license_no, PDO::PARAM_STR);
	$result->bindParam(':team_id', $team_id, PDO::PARAM_INT);
	$result->bindParam(':role_id', $role_id, PDO::PARAM_INT);
	$result->bindParam(':school_id', $school_id, PDO::PARAM_INT);
	$result->bindParam(':school_year_id', $school_year_id, PDO::PARAM_INT);
	$result->bindParam(':hogosya1_id', $hogosya1_id, PDO::PARAM_INT);
	$result->bindParam(':hogo1zokugara_id', $hogo1zokugara_id, PDO::PARAM_INT);
	$result->bindParam(':hogosya2_id', $hogosya2_id, PDO::PARAM_INT);
	$result->bindParam(':hogo2zokugara_id', $hogo2zokugara_id, PDO::PARAM_INT);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 団員データ削除処理 */
function	delete_member_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM member WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 団員リスト取得処理 */
function	get_member_list(&$data)
{
	$result = $data['pdo']->query("SELECT member.*, chardata.family_name, chardata.first_name, team.team_name, role.role_name, school.school_name, school_year.school_year_name FROM member INNER JOIN chardata ON member.member_info_id = chardata.id INNER JOIN team ON member.team_id = team.id INNER JOIN role ON member.role_id = role.id INNER JOIN school ON member.school_id = school.id INNER JOIN school_year ON member.school_year_id = school_year.id");
	$i = 0;
	foreach ($result as $row) {
		$data['member_list'][$i++] = $row;
	}
}

/****************************************************************/
/*	個人情報テーブル情報										*/
/*	テーブル名：		chardata								*/
/*	id:					個人情報ID（自動付与）					*/
/*	family_name:		苗字（漢字）（MAX20）					*/
/*	first_name:			名前（漢字）（MAX20）					*/
/*	family_kana_name:	苗字（カナ）（MAX20）					*/
/*	first_kana_name:	名前（カナ）（MAX20）					*/
/*	zip:				郵便番号（MAX10）						*/
/*	address:			住所（MAX200）							*/
/*	tel:				電話番号（MAX20）						*/
/*	face_photo_url:		顔写真URL								*/
/*																*/
/****************************************************************/
/*	個人情報テーブルアクセス関数軍								*/
/****************************************************************/

/* 個人情報テーブル作成 */
function	make_character_table($pdo)
{
	$Family_name = "管理者";
	$First_name = "1";
	$Family_kana_name = "カンリシャ";
	$First_kana_name = "1";
	$Zip = "090-0837";
	$Address = "北見市中央三輪３丁目５４８ー２２";
	$Tel = "090-3110-0165";

	/* 個人情報テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS chardata(id int auto_increment primary key, family_name varchar(20), first_name varchar(20), family_kana_name varchar(20), first_kana_name varchar(20), zip varchar(10), address varchar(200), tel varchar(20), face_photo_url text)");

	/* 管理者用個人情報作成 */
	$result = $pdo->prepare("INSERT INTO chardata (family_name, first_name, family_kana_name, first_kana_name, zip, address, tel) VALUES (:family_name, :first_name, :family_kana_name, :first_kana_name, :zip, :address, :tel)");
	$result->bindParam(':family_name', $Family_name, PDO::PARAM_STR);
	$result->bindParam(':first_name', $First_name, PDO::PARAM_STR);
	$result->bindParam(':family_kana_name', $Family_kana_name, PDO::PARAM_STR);
	$result->bindParam(':first_kana_name', $First_kana_name, PDO::PARAM_STR);
	$result->bindParam(':zip', $Zip, PDO::PARAM_STR);
	$result->bindValue(':address', $Address, PDO::PARAM_STR);
	$result->bindParam(':tel', $Tel, PDO::PARAM_STR);
	$result->execute();
}

/* 個人情報リスト取得処理 */
function	get_character_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from chardata");
	$i = 0;
	foreach ($result as $row) {
		$data['character_list'][$i++] = $row;
	}
}

/* 個人情報データ取得処理 */
function	get_character_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM chardata WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM chardata WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['character_data'] = $result->fetch();
}

/* 個人情報データ追加処理 */
function	insert_character_data(&$data, $family_name, $first_name,
				$family_kana_name, $first_kana_name, $zip, $address, $tel, $face_photo_url)
{
	$result = $data['pdo']->prepare("INSERT INTO chardata (family_name, first_name, family_kana_name, first_kana_name, zip, address, tel, face_photo_url) VALUES (:family_name, :first_name, :family_kana_name, :first_kana_name, :zip, :address, :tel, :face_photo_url)");
	$result->bindParam(':family_name', $family_name, PDO::PARAM_STR);
	$result->bindParam(':first_name', $first_name, PDO::PARAM_STR);
	$result->bindParam(':family_kana_name', $family_kana_name, PDO::PARAM_STR);
	$result->bindParam(':first_kana_name', $first_kana_name, PDO::PARAM_STR);
	$result->bindParam(':zip', $zip, PDO::PARAM_STR);
	$result->bindParam(':address', $address, PDO::PARAM_STR);
	$result->bindParam(':tel', $tel, PDO::PARAM_STR);
	$result->bindParam(':face_photo_url', $face_photo_url, PDO::PARAM_STR);
	$result->execute();
}

/* 個人情報データ更新処理 */
function	update_character_data(&$data, $record, $family_name, $first_name,
				$family_kana_name, $first_kana_name, $zip, $address, $tel, $face_photo_url)
{
	$result = $data['pdo']->prepare("UPDATE chardata SET family_name = :family_name, first_name = :first_name, family_kana_name = :family_kana_name, first_kana_name = :first_kana_name, zip = :zip, address = :address, tel = :tel, face_photo_url = :face_photo_url WHERE id = :id");
	$result->bindParam(':family_name', $family_name, PDO::PARAM_STR);
	$result->bindParam(':first_name', $first_name, PDO::PARAM_STR);
	$result->bindParam(':family_kana_name', $family_kana_name, PDO::PARAM_STR);
	$result->bindParam(':first_kana_name', $first_kana_name, PDO::PARAM_STR);
	$result->bindParam(':zip', $zip, PDO::PARAM_STR);
	$result->bindParam(':address', $address, PDO::PARAM_STR);
	$result->bindParam(':tel', $tel, PDO::PARAM_STR);
	$result->bindParam(':face_photo_url', $face_photo_url, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 個人情報データ削除処理 */
function	delete_character_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM chardata WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 個人情報レコード数取得処理 */
function	get_character_count(&$data)
{
	$data['chardata_count'] = $data['pdo']->query("SELECT COUNT(*) chardata");
}

/* 個人情報名リスト取得処理 */
function	get_character_name_list(&$data)
{
	$result = $data['pdo']->query("SELECT chardata.id, chardata.family_name, chardata.first_name from chardata");
	$i = 0;
	foreach ($result as $row) {
		$data['character_list'][$i++] = $row;
	}
}


/****************************************************************/
/*	係りテーブル情報											*/
/*	テーブル名：		role									*/
/*	id:					係りID（自動付与）						*/
/*	role_name:			係り名（MAX100）						*/
/*	role_memo:			係りの説明								*/
/*																*/
/****************************************************************/
/*	係りテーブルアクセス関数軍									*/
/****************************************************************/

/* 係りテーブル作成 */
function	make_role_table($pdo)
{
	/* 学校テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS role(id int auto_increment primary key, role_name varchar(100), role_memo text)");
}

/* 係りリスト取得処理 */
function	get_role_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from role");
	$i = 0;
	foreach ($result as $row) {
		$data['role_list'][$i++] = $row;
	}
}

/* 係りデータ取得処理 */
function	get_role_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM role WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM role WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['role_data'] = $result->fetch();
}

/* 係りデータ追加処理 */
function	insert_role_data(&$data, $role_name, $role_memo)
{
	$result = $data['pdo']->prepare("INSERT INTO role (role_name, role_memo) VALUES (:role_name, :role_memo)");
	$result->bindParam(':role_name', $role_name, PDO::PARAM_STR);
	$result->bindParam(':role_memo', $role_memo, PDO::PARAM_STR);
	$result->execute();
}

/* 係りデータ更新処理 */
function	update_role_data(&$data, $record, $role_name, $role_memo)
{
	$result = $data['pdo']->prepare("UPDATE role SET role_name = :role_name, role_memo = :role_memo WHERE id = :id");
	$result->bindParam(':role_name', $role_name, PDO::PARAM_STR);
	$result->bindParam(':role_memo', $role_memo, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 係りデータ削除処理 */
function	delete_role_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM role WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 係り名リスト取得処理 */
function	get_role_name_list(&$data)
{
	$result = $data['pdo']->query("SELECT role.id, role.role_name from role");
	$i = 0;
	foreach ($result as $row) {
		$data['role_list'][$i++] = $row;
	}
}


/****************************************************************/
/*	学校テーブル情報											*/
/*	テーブル名：		school									*/
/*	id:					学校ID（自動付与）						*/
/*	school_name:		学校名（MAX20）							*/
/*	school_zip:			郵便番号（MAX10）						*/
/*	school_address:		住所（MAX100）							*/
/*	school_tel:			電話番号（MAX20）						*/
/*	school_map_url:		学校周辺地図のURL						*/
/*																*/
/****************************************************************/
/*	学校テーブルアクセス関数軍									*/
/****************************************************************/

/* 学校テーブル作成 */
function	make_school_table($pdo)
{
	/* 学校テーブル作成 */
	$pdo->query("CREATE TABLE IF NOT EXISTS school(id int auto_increment primary key, school_name varchar(20), school_zip varchar(10), school_address varchar(100), school_tel varchar(10), school_map_url text)");
}

/* 学校リスト取得処理 */
function	get_school_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from school");
	$i = 0;
	foreach ($result as $row) {
		$data['school_list'][$i++] = $row;
	}
}

/* 学校データ取得処理 */
function	get_school_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM school WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM school WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['school_data'] = $result->fetch();
}

/* 学校データ追加処理 */
function	insert_school_data(&$data, $school_name, $school_zip, $school_address,
				$school_tel, $school_map_url)
{
	$result = $data['pdo']->prepare("INSERT INTO school (school_name, school_zip, school_address, school_tel, school_map_url) VALUES (:school_name, :school_zip, :school_address, :school_tel, :school_map_url)");
	$result->bindParam(':school_name', $school_name, PDO::PARAM_STR);
	$result->bindParam(':school_zip', $school_zip, PDO::PARAM_STR);
	$result->bindParam(':school_address', $school_address, PDO::PARAM_STR);
	$result->bindParam(':school_tel', $school_tel, PDO::PARAM_STR);
	$result->bindParam(':school_map_url', $school_map_url, PDO::PARAM_STR);
	$result->execute();
}

/* 学校データ更新処理 */
function	update_school_data(&$data, $record, $school_name, $school_zip, $school_address,
				$school_tel, $school_map_url)
{
	$result = $data['pdo']->prepare("UPDATE school SET school_name = :school_name, school_zip = :school_zip, school_address = :school_address, school_tel = :school_tel, school_map_url = :school_map_url WHERE id = :id");
	$result->bindParam(':school_name', $school_name, PDO::PARAM_STR);
	$result->bindParam(':school_zip', $school_zip, PDO::PARAM_STR);
	$result->bindParam(':school_address', $school_address, PDO::PARAM_STR);
	$result->bindParam(':school_tel', $school_tel, PDO::PARAM_STR);
	$result->bindParam(':school_map_url', $school_map_url, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 学校データ削除処理 */
function	delete_school_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM school WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* 学校名リスト取得処理 */
function	get_school_name_list(&$data)
{
	$result = $data['pdo']->query("SELECT school.id, school.school_name from school");
	$i = 0;
	foreach ($result as $row) {
		$data['school_list'][$i++] = $row;
	}
}


/****************************************************************/
/*	チームテーブル情報											*/
/*	テーブル名：		team									*/
/*	id:					チームID（自動付与）					*/
/*	team_name:			チーム名（MAX20）						*/
/*	team_memo:			チーム説明								*/
/*																*/
/****************************************************************/
/*	チームテーブルアクセス関数軍								*/
/****************************************************************/

/* チームテーブル作成 */
function	make_team_table($pdo)
{
	$pdo->query("CREATE TABLE IF NOT EXISTS team(id int auto_increment primary key, team_name varchar(20), team_memo text)");
}

/* チームリスト取得処理 */
function	get_team_table_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from team");
	$i = 0;
	foreach ($result as $row) {
		$data['team_list'][$i++] = $row;
	}
}

/* チームデータ取得処理 */
function	get_team_data(&$data, $record, $lock=false)
{
	if($lock){
		$result = $data['pdo']->prepare("SELECT * FROM team WHERE id = :id FOR UPDATE");
	} else {
		$result = $data['pdo']->prepare("SELECT * FROM team WHERE id = :id");
	}
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['team_data'] = $result->fetch();
}

/* チームデータ追加処理 */
function	insert_team_data(&$data, $team_name, $team_memo)
{
	$result = $data['pdo']->prepare("INSERT INTO team (team_name, team_memo) VALUES (:team_name, :team_memo)");
	$result->bindParam(':team_name', $team_name, PDO::PARAM_STR);
	$result->bindParam(':team_memo', $team_memo, PDO::PARAM_STR);
	$result->execute();
}

/* チームデータ更新処理 */
function	update_team_data(&$data, $record, $team_name, $team_memo)
{
	$result = $data['pdo']->prepare("UPDATE team SET team_name = :team_name, team_memo = :team_memo WHERE id = :id");
	$result->bindParam(':team_name', $team_name, PDO::PARAM_STR);
	$result->bindParam(':team_memo', $team_memo, PDO::PARAM_STR);
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* チームデータ削除処理 */
function	delete_team_data(&$data, $record)
{
	$result = $data['pdo']->prepare("DELETE FROM team WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
}

/* チーム名リスト取得処理 */
function	get_team_name_list(&$data)
{
	$result = $data['pdo']->query("SELECT team.id, team.team_name from team");
	$i = 0;
	foreach ($result as $row) {
		$data['team_list'][$i++] = $row;
	}
}


/****************************************************************/
/*	権限テーブル情報（固定テーブル）							*/
/*	テーブル名：		attribute								*/
/*	id:					権限ID（自動付与）						*/
/*	attrib_name:		権限名（MAX20）							*/
/*	description:		説明									*/
/*																*/
/****************************************************************/
/*	権限テーブルアクセス関数軍									*/
/****************************************************************/

/* 権限テーブル作成 */
function	make_attribute_table($pdo)
{
	$pdo->query("CREATE TABLE IF NOT EXISTS attribute(id int auto_increment primary key, attrib_name varchar(20), description text)");
	$pdo->query("INSERT INTO attribute values (1, '管理者', '全ての設定変更が可能です'), (2, 'コーチ', '一部の設定変更が可能です'), (3, 'メンバー', '利用のみ可能で設定変更は不可能です')");
}

/* 権限リスト取得処理 */
function	get_attribute_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from attribute");
	$i = 0;
	foreach ($result as $row) {
		$data['attrib_list'][$i++] = $row;
	}
}

/* 権限レコード数取得処理 */
function	get_attribute_count(&$data)
{
	$data['attrib_count'] = $data['pdo']->query("SELECT COUNT(*) attribute");
}


/****************************************************************/
/*	続柄テーブル情報（固定テーブル）							*/
/*	テーブル名：		relationship							*/
/*	id:					続柄ID（自動付与）						*/
/*	relationship_name:	続柄名（MAX20）							*/
/*																*/
/****************************************************************/
/*	続柄テーブルアクセス関数軍									*/
/****************************************************************/

/* 続柄テーブル作成 */
function	make_relationship_table($pdo)
{
	$pdo->query("CREATE TABLE IF NOT EXISTS relationship(id int auto_increment primary key, relationship_name varchar(10))");
	$pdo->query("INSERT INTO relationship values (1, '母'), (2, '父'), (3, '祖父'), (4, '祖母')");
}

/* 続柄リスト取得処理 */
function	get_relationship_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from relationship");
	$i = 0;
	foreach ($result as $row) {
		$data['relationship_list'][$i++] = $row;
	}
}

/* チームデータ取得処理 */
function	get_relationship_data(&$data, $record)
{
	$result = $data['pdo']->prepare("SELECT * FROM relationship WHERE id = :id");
	$result->bindValue(':id', $record, PDO::PARAM_INT);
	$result->execute();
	$data['relationship_data'] = $result->fetch();
}


/****************************************************************/
/*	学年テーブル情報（固定テーブル）							*/
/*	テーブル名：		school_year								*/
/*	id:					学年ID（自動付与）						*/
/*	school_year_name:	学年（MAX20）							*/
/*																*/
/****************************************************************/
/*	学年テーブルアクセス関数軍									*/
/****************************************************************/

/* 学年テーブル作成 */
function	make_school_year_table($pdo)
{
	$pdo->query("CREATE TABLE IF NOT EXISTS school_year(id int auto_increment primary key, school_year_name varchar(10))");
	$pdo->query("INSERT INTO school_year values (1, '１年'), (2, '２年'), (3, '３年'), (4, '４年'), (5, '５年'), (6, '６年')");
}

/* 学年リスト取得処理 */
function	get_school_year_list(&$data)
{
	$result = $data['pdo']->query("SELECT * from school_year");
	$i = 0;
	foreach ($result as $row) {
		$data['school_year_list'][$i++] = $row;
	}
}


?>
