<!-- 団員リスト表示 START -->
<?php
if(isset($_REQUEST['record'])){
	/* 団員の削除操作をした場合、ページがリロードされ削除する団員のIDが渡される */
	delete_member_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>団員管理</h1>
<p>団員の登録・変更・削除が行えます。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=2&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>
<?php
	get_member_list($data);
	if(isset($data['member_list']) && ($data['member_list'] != NULL)) {
        /* 団員データがある場合 */
?>

		<table border="3">
			<tr>
				<th>No.</th>
				<th>名前</th>
				<th>ライセンス番号</th>
				<th>チーム</th>
				<th>係り</th>
				<th>学校</th>
				<th>学年</th>
				<th>保護者１</th>
				<th>続柄（保護者１）</th>
				<th>保護者２</th>
				<th>続柄（保護者２）</th>
				<th>操作</th>
			</tr>
<?php
		$number = 1;
		foreach ($data['member_list'] as $row) {
?>
			<tr>
				<td><?php echo $number ?></td>
				<td><?php echo $row['family_name'] . " " . $row['first_name'];?></td>
				<td><?php echo $row['license_no'] ?></td>
				<td><?php echo $row['team_name'];?></td>
				<td><?php echo $row['role_name'];?></td>
				<td><?php echo $row['school_name'];?></td>
				<td><?php echo $row['school_year_name'];?></td>
				<td><?php get_character_data($data, $row['hogosya1_id']); echo $data['character_data']['family_name'] . " " . $data['character_data']['first_name'] ?></td>
				<td><?php get_relationship_data($data, $row['hogo1zokugara_id']); echo $data['relationship_data']['relationship_name'] ?></td>
				<td><?php get_character_data($data, $row['hogosya2_id']); echo $data['character_data']['family_name'] . " " . $data['character_data']['first_name'] ?></td>
				<td><?php get_relationship_data($data, $row['hogo2zokugara_id']); echo $data['relationship_data']['relationship_name'] ?></td>
				<td>
					<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=2&id=1" method="post">
					<input type="hidden" name="record" value="<?php echo $row['id']?>">
					</form>
					<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
					<a href="javascript:conf_disp('member_index.php?page=10&sub_page=2&id=0', '<?php echo $row['id']?>');">削除</a>
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
<!-- 団員リスト表示 END -->
