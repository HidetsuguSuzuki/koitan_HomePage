<!-- お知らせリスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* お知らせの削除操作をした場合、ページがリロードされ削除するお知らせのIDが渡される */
	delete_info_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>お知らせ管理</h1>
<p>お知らせで表示する内容の登録・変更・削除が行えます。</p>
<p>古いお知らせは、削除してください。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=1&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_info_list($data);
	if(isset($data['info_list']) && ($data['info_list'] != NULL)) {
        /* お知らせデータがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>タイトル</th>
				<th>内容</th>
				<th>更新者</th>
				<th>更新日</th>
				<th>操作</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['info_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['info_title'] ?></td>
				<td><?php echo $row['info_memo'] ?></td>
				<td><?php echo $row['family_name'] . " " . $row['first_name']?></td>
				<td><?php echo $row['info_date'] ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=1&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=1&id=0', '<?php echo $row['id']?>');">削除</a>
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
<!-- 個人情報リスト表示 END -->

