<?php
/* セッションの開始 */
session_start();

/* エラーメッセージ初期化 */
$errorMessage = "";

require_once "./core/config.php";
require_once "./core/initialaize.php";
require_once "./core/userfunc.php";

if(isset($_REQUEST['login']))
{
	if (empty($_REQUEST["userid"])) {
		$errorMessage = "ユーザIDが未入力です。";
	} else if (empty($_REQUEST["password"])) {
		$errorMessage = "パスワードが未入力です。";
	} 

	if (!empty($_REQUEST["userid"]) && !empty($_REQUEST["password"])) {
		/* MySQLへ接続 */
		$data['pdo'] = db_connect();

		/* ユーザIDでユーザを取得 */
		get_loginuser_data($data, $_REQUEST["userid"]);
		if($data['loginuser_data'] == false)
		{
			$errorMessage = "ログインIDあるいはパスワードに誤りがあります。";
		} else {
			// ハッシュ化されたパスワードがマッチするかどうかを確認
			if (password_verify($_REQUEST['password'], $data['loginuser_data']['login_pw'])) {
				session_regenerate_id(true);
				$_SESSION['user_id'] = $data['loginuser_data']['id'];
				$_SESSION['user_name'] = $data['loginuser_data']['family_name'] . ' ' . $data['loginuser_data']['first_name'];
				$_SESSION['attribute'] = $data['loginuser_data']['attribute_id'];
				$_SESSION['user_info'] = $data['loginuser_data']['user_info_id'];
				$_SESSION['page'] = 0;
				$_SESSION['sub_page'] = 0;
				$_SESSION['id'] = 0;
				$_SESSION['select'] = 0;
				header("Location: member_index.php");
				exit;
			} else {
				$errorMessage = "ログインIDあるいはパスワードに誤りがあります。";
			}
		}

		// MySQLを切断
		$pdo = null;
	}
}
?>

<!DOCTYPE HTML>
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
?>
<div class="col-xs-6 col-xs-offset-3">
<form method="post">
	<h1>ログインフォーム</h1>
<?php
	if($errorMessage != "") {
?>
		<div class="alert alert-danger" role="alert"><?php echo $errorMessage ?></div>
<?php
	}
?>
	<div class="form-group">
		<input type="test"  class="form-control" name="userid" placeholder="ユーザID" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" required />
	</div>
	<button type="submit" class="btn btn-default" name="login">ログイン</button>
</form>
</div>

<?php
	/* フッター読み込み */
/*	include (dirname(__FILE__).'/footer.php');*/
?>

	</body>
</html>
