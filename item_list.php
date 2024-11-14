<!-- 備品リスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* 備品の削除操作をした場合、ページがリロードされ削除する備品のIDが渡される */
	get_item_data($data, $_REQUEST['record']);
	if($data['item_data']['item_pos_photo'] <> NULL) {
		/* 顔写真ファイルを削除 */
		unlink($data['item_data']['item_pos_photo']);
	}
	delete_item_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>備品管理</h1>
<p>備品の登録・変更・削除が行えます。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=8&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_item_table_list($data);
	if(isset($data['item_list']) && ($data['item_list'] != NULL)) {
        /* 備品データがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>品名</th>
				<th>数量</th>
				<th>保管場所</th>
				<th>保管場所写真</th>
				<th>備品説明</th>
				<th>操作</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['item_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['item_name'] ?></td>
				<td><?php echo $row['item_count'] ?></td>
				<td><?php echo $row['item_pos'] ?></td>
				<td><?php if(isset($row['item_pos_photo'])){echo '<img class="item_photo" src="' . $row['item_pos_photo'] . '" alt="アップロードされた画像">';}else{echo $row['item_pos_photo'];}?></td>
				<td><?php echo $row['item_memo'] ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=8&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=8&id=0', '<?php echo $row['id']?>');">削除</a>
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
<!-- 備品リスト表示 END -->

