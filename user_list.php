<!-- ログインユーザリスト表示 START -->

<?php
if(isset($_REQUEST['record'])){
	/* ログインユーザの削除操作をした場合、ページがリロードされ削除するログインユーザのIDが渡される */
	delete_user_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>ログインユーザリスト</h1>
<p>ログインユーザの作成・編集・削除を行います。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=3&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>

<table border="3">
	<tr>
		<th>No.</th>
		<th>ログインID</th>
		<th>名前</th>
		<th>権限</th>
		<th>操作</th>
	</tr>
<?php
	get_loginuser_list($data);
	$number = 1;
	if(isset($data['user_list']) && ($data['user_list'] != NULL)) {
		foreach ($data['user_list'] as $row) {
?>
	<tr>
		<th><?php echo $number ?></th>
		<td><?php echo $row['login_id'] ?></td>
		<td><?php echo $row['family_name'] . ' ' . $row['first_name'] ?></td>
		<td><?php echo $row['attrib_name'] ?></td>
		<td>
			<?php
			if($number <> 1) {
			?>
			<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=3&id=1" method="post">
			<input type="hidden" name="record" value="<?php echo $row['id']?>">
			</form>
			<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
			<a href="javascript:conf_disp('member_index.php?page=10&sub_page=3&id=0', '<?php echo $row['id']?>');">削除</a>
			<?php
			}
			?>
		</td>
	</tr>
<?php
			$number++;
		}
	}
?>
</table>
</div>
<!-- 個人情報リスト表示 END -->
