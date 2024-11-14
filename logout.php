<?php
session_start();

// logout.php?logoutにアクセスしたユーザーをログアウトする
if(isset($_REQUEST['logout'])) {
	session_destroy();
	unset($_SESSION['user_id']);
	header("Location: index.html");
} else {
	header("Location: index.html");
}
?>
