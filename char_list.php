<!-- 個人情報リスト表示 START -->

<?php
if(isset($_REQUEST['record'])){
	/* 個人情報の削除操作をした場合、ページがリロードされ削除する個人情報のIDが渡される */
	get_character_data($data, $_REQUEST['record']);
	if($data['character_data']['face_photo_url'] <> NULL) {
		/* 顔写真ファイルを削除 */
		unlink($data['character_data']['face_photo_url']);
	}
	delete_character_data($data, $_REQUEST['record']);
}
?>

<div class="data_list">
<h1>個人情報リスト</h1>
<p>個人情報の作成・編集・削除を行います。</p>
<input type="button" onclick="location.href='member_index.php?page=10&sub_page=4&id=1'" value="新規作成">
<input type="button" name="print" value="印刷" onClick="javascript:window.print()">
<p></p>

<table border="3">
	<tr>
		<th>No.</th>
		<th>写真</th>
		<th>苗字（漢字）</th>
		<th>名前（漢字）</th>
		<th>苗字（カナ）</th>
		<th>名前（カナ）</th>
		<th>郵便番号</th>
		<th>住所</th>
		<th>電話番号</th>
		<th class="noPrint">操作</th>
	</tr>
<?php
	get_character_table_list($data);
	$number = 1;
	if(isset($data['character_list']) && ($data['character_list'] != NULL)) {
		foreach ($data['character_list'] as $row) {
?>
	<tr>
		<th><?php echo $number ?></th>
		<td><?php if(isset($row['face_photo_url'])){echo '<img class="face_photo" src="' . $row['face_photo_url'] . '" alt="アップロードされた画像">';}else{echo $row['face_photo_url'];}?></td>
		<td><?php echo $row['family_name'] ?></td>
		<td><?php echo $row['first_name'] ?></td>
		<td><?php echo $row['family_kana_name'] ?></td>
		<td><?php echo $row['first_kana_name'] ?></td>
		<td><?php echo $row['zip'] ?></td>
		<td><?php echo $row['address'] ?></td>
		<td><?php echo $row['tel'] ?></td>
		<td class="noPrint">
			<?php
			if($number <> 1) {
			?>
			<form name="form1_<?php echo $row['id']?>" action="member_index.php?page=10&sub_page=4&id=1" method="post">
			<input type="hidden" name="record" value="<?php echo $row['id']?>">
			</form>
			<a href="javascript:form1_<?php echo $row['id']?>.submit();">編集</a>
			<a href="javascript:conf_disp('member_index.php?page=10&sub_page=4&id=0', '<?php echo $row['id']?>');">削除</a>
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
