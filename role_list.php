<!-- 係りリスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* 係りの削除操作をした場合、ページがリロードされ削除する係りのIDが渡される */
	delete_role_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>係り管理</h1>
<p>係りの登録・変更・削除が行えます。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=5&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_role_table_list($data);
	if(isset($data['role_list']) && ($data['role_list'] != NULL)) {
        /* 係りデータがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>係り</th>
				<th>内容</th>
				<th>操作</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['role_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['role_name'] ?></td>
				<td><?php echo $row['role_memo'] ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=5&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=5&id=0', '<?php echo $row['id']?>');">削除</a>
				</td>
			</tr>
<?php
			$number++;
		}
?>
		</table>

<?php
    }
?>

</div>
<!-- 係りリスト表示 END -->

