<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/* セッションの開始 */
session_start();

/* PHP関数群インクルード */
require_once "./core/commonfunc.php";
require_once "./core/config.php";
require_once "./core/initialaize.php";
require_once "./core/userfunc.php";
require_once "./core/itemfunc.php";

/* データベースへの接続 */
$data['pdo'] = db_connect();

/* テーブルがなければ作成する(PHP8.0 例外対応) */
init_db($data['pdo']);

/* セッションユーザがなければログイン画面に移行 */
if( isset($_SESSION['user_id']) == "") {
	header("Location: login.php");
}

/* データベースオブジェクトをコピー */
$data['page'] = $_SESSION['page'];
$data['sub_page'] = $_SESSION['sub_page'];
$data['id'] = $_SESSION['id'];
$data['select'] = $_SESSION['select'];

/* ページ情報整理 */
if(isset($_REQUEST["page"])){
	$page = $_REQUEST["page"];
}else{
	$page = 0;
}
if(isset($_REQUEST["sub_page"])){
	$sub_page = $_REQUEST["sub_page"];
}else{
	$sub_page = 0;
}
if(isset($_REQUEST["id"])){
	$id = $_REQUEST["id"];
}else{
	$id = 0;
}
if(isset($_REQUEST["select"])){
	$select = $_REQUEST["select"];
}else{
	$select = 0;
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<!-- 文字エンコーディングの指定 -->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- ビューポートの設定（モバイルデバイスの表示最適化） -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Skyhigh Hidetsugu.suzuki">
		<meta name="description" content="小泉・端野ミニバスケットボール少年団のホームページです。">
		<meta name="keywords" ontent="ミニバスケット,少年団,北見,小泉,端野,東相内,三輪,西地区">
		<title>小泉・端野ミニバスケットボール少年団</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Noto+Serif+JP&display=swap" rel="stylesheet">
 		<link rel="stylesheet" href="css/style2.css">
<!-- Bootstrap読み込み（スタイリングのため） -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- Custom styles for this template -->
		<script src="js/jquery-3.7.1.min.js"></script>
<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 8]>
		<style>
			articale section	{width: 250px}
			articale section figure	{margin-left: 0}
		</style>
<![endif]-->
	</head>
	<body>
<?php
	/* ヘッダー読み込み */
/*	include (dirname(__FILE__).'/header.php');*/
	/* メニューの読み込み */
	include (dirname(__FILE__).'/menu.php');
?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
<?php
print("page=".$page." : ");
print("sub_page=".$sub_page." : ");
print("id=".$id." : ");
print("select=".$data['select']."\n");
?>
				<h4>こんにちは、<?php echo $_SESSION['user_name'];?>さん</h4>
			</div>
		</div>
<?php
if($page == 0){
	/* マイページ */
	include (dirname(__FILE__).'/my_page.php');
}else if($page == 1){
	/* 団員リストページ */
	include (dirname(__FILE__).'/member_page.php');
}else if($page == 2){
	/* 備品リストページ */
	include (dirname(__FILE__).'/item_page.php');
}else if($page == 3){
	/* ダウンロードページ */
	include (dirname(__FILE__).'/download_page.php');
} else if($page == 10){
	/* データ管理ページ */
	if($sub_page == 0){
		/* データ管理メニュー */
		include (dirname(__FILE__).'/admin_menu.php');
	}else if($sub_page == 1){
		/* お知らせ管理 */
		if($id == 0){
			/* お知らせリスト（編集用） */
			include (dirname(__FILE__).'/info_list.php');
		} else if($id == 1){
			/* お知らせ編集ページ */
			include (dirname(__FILE__).'/info_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	}else if($sub_page == 2){
		/* 団員データ管理 */
		if($id == 0){
			/* 団員リスト（編集用） */
			include (dirname(__FILE__).'/member_list.php');
		} else if($id == 1){
			/* 団員編集ページ */
			include (dirname(__FILE__).'/member_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 3){
		/* ログインユーザ管理 */
		if($id == 0){
			/* ログインユーザリスト（編集用） */
			include (dirname(__FILE__).'/user_list.php');
		} else if($id == 1){
			/* ログインユーザ編集ページ */
			include (dirname(__FILE__).'/user_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 4){
		/* 個人情報管理 */
		if($id == 0){
			/* 個人情報リスト（編集用） */
			include (dirname(__FILE__).'/char_list.php');
		} else if($id == 1){
			/* 個人情報編集ページ */
			include (dirname(__FILE__).'/char_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 5){
		/* 係り管理 */
		if($id == 0){
			/* 係りリスト（編集用） */
			include (dirname(__FILE__).'/role_list.php');
		} else if($id == 1){
			/* 係りページ */
			include (dirname(__FILE__).'/role_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 6){
		/* 学校管理 */
		if($id == 0){
			/* 学校リスト（編集用） */
			include (dirname(__FILE__).'/school_list.php');
		} else if($id == 1){
			/* 学校編集ページ */
			include (dirname(__FILE__).'/school_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 7){
		/* チーム管理 */
		if($id == 0){
			/* チームリスト（編集用） */
			include (dirname(__FILE__).'/team_list.php');
		} else if($id == 1){
			/* チーム編集ページ */
			include (dirname(__FILE__).'/team_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 8){
		/* 備品管理 */
		if($id == 0){
			/* 備品リスト（編集用） */
			include (dirname(__FILE__).'/item_list.php');
		} else if($id == 1){
			/* 備品編集ページ */
			include (dirname(__FILE__).'/item_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else if($sub_page == 9){
		/* ダウンロード管理 */
		if($id == 0){
			/* ダウンロードリスト（編集用） */
			include (dirname(__FILE__).'/document_list.php');
		} else if($id == 1){
			/* ダウンロード編集ページ */
			include (dirname(__FILE__).'/document_edit.php');
		} else {
			print("<h4>ページエラー：</h4>");
		}
	} else {
		print("<h4>ページエラー：</h4>");
	}
}
?>
	</div>
	<br />
<?php
	/* フッター読み込み */
/*	include (dirname(__FILE__).'/footer.php');*/
?>

	<script src="./js/function.js"></script>
	</body>
</html>

<?php
# MySQLを切断
$pdo = null;
?>
