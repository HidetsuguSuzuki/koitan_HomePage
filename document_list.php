<!-- ダウンロード資料リスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* ダウンロード資料の削除操作をした場合、ページがリロードされ削除するダウンロード資料のIDが渡される */
	get_document_data($data, $_REQUEST['record']);
	if($data['document_data']['document_url'] <> NULL) {
		/* ドキュメントファイルを削除 */
		unlink($data['document_data']['document_url']);
	}
	delete_document_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>ダウンロード資料管理</h1>
<p>ダウンロード資料の登録・変更・削除が行えます。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=9&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_document_table_list($data);
	if(isset($data['document_list']) && ($data['document_list'] != NULL)) {
        /* ダウンロード資料データがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>書類名</th>
				<th>書類説明</th>
				<th>書類ファイル名</th>
				<th>操作</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['document_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['document_name'] ?></td>
				<td><?php echo $row['document_memo'] ?></td>
				<td><?php echo pathinfo($row['document_url'], PATHINFO_BASENAME) ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=9&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=9&id=0', '<?php echo $row['id']?>');">削除</a>
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
<!-- ダウンロード資料リスト表示 END -->

