<!-- 学校リスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* 学校の削除操作をした場合、ページがリロードされ削除する学校のIDが渡される */
	delete_school_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>学校管理</h1>
<p>学校の登録・変更・削除が行えます。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=6&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_school_table_list($data);
	if(isset($data['school_list']) && ($data['school_list'] != NULL)) {
        /* 学校データがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>学校名</th>
				<th>郵便番号</th>
				<th>住所</th>
				<th>電話番号</th>
				<th>学校周辺地図のURL</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['school_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['school_name'] ?></td>
				<td><?php echo $row['school_zip'] ?></td>
				<td><?php echo $row['school_address'] ?></td>
				<td><?php echo $row['school_tel'] ?></td>
				<td><?php echo $row['school_map_url'] ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=6&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=6&id=0', '<?php echo $row['id']?>');">削除</a>
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
<!-- 学校リスト表示 END -->

