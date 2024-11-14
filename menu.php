<!-- メニュー -->
<nav class="navbar navbar-default">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#gnavi">
			<span class="sr-only">メニュー</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>

<?php
	if($_SESSION['attribute'] == 1 || $_SESSION['attribute'] == 2) {
    /* 管理者用メニュー */
?>
  <div id="gnavi" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li><a href="member_index.php?page=0&sub_page=0&id=0">マイページ</a></li>
      <li><a href="member_index.php?page=1&sub_page=0&id=0">団員リスト</a></li>
      <li><a href="member_index.php?page=2&sub_page=0&id=0">備品リスト</a></li>
      <li><a href="member_index.php?page=3&sub_page=0&id=0">規約・書式ダウンロード</a></li>
      <li><a href="member_index.php?page=10&sub_page=0&id=0">データ管理</a></li>
      <li><a href="logout.php?logout">ログアウト</a></li>
    </ul>
  </div>
<?php
	} else if($_SESSION['attribute'] == 3) {
    /* メンバー管理者用メニュー */
    ?>
  <div id="gnavi" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li><a href="member_index.php?page=0&sub_page=0&id=0">マイページ</a></li>
      <li><a href="member_index.php?page=1&sub_page=0&id=0">団員リスト</a></li>
      <li><a href="member_index.php?page=2&sub_page=0&id=0">備品リスト</a></li>
      <li><a href="member_index.php?page=3&sub_page=0&id=0">規約・書式ダウンロード</a></li>
      <li><a href="logout.php?logout">ログアウト</a></li>
    </ul>
  </div>
<?php
	}
?>
</nav>
