/* データ削除用確認画面表示 */
function conf_disp(link, record){
	// 「OK」時の処理開始 ＋ 確認ダイアログの表示
	str = "削除してもいいですか？";
	if(window.confirm(str)){
		var form = document.createElement('form');
		document.body.appendChild(form);
		var input = document.createElement('input');
		input.setAttribute('type','hidden');
		input.setAttribute('name','record');
		input.setAttribute('value',record);
		form.appendChild(input);
		form.setAttribute('action',link);
		form.setAttribute('method','POST');
		form.submit();
	}
}


/* 会員番号選択時に会員名を「client_name」にセットする */
/*
function change_client() {
	obj1 = document.chart_form.client_id;
	obj2 = document.chart_form;
	index = obj1.value;
	$.ajax({
		type: "POST",
		url: "./ajax/get_client.php",
		data: {"id": index},
		success: function(j_data){
			obj2.client_name.value = j_data['client_l_name']+" "+j_data['client_f_name'];
			obj2.client_sex_id.value = j_data['client_sex_id'];
			obj2.client_birthday.value = j_data['client_birthday'];
			obj2.client_age.value = j_data['client_age'];
			obj2.client_zip.value = j_data['client_zip'];
			obj2.client_address.value = j_data['client_address'];
			obj2.client_tel.value = j_data['client_tel'];
			obj2.client_tel2.value = j_data['client_tel2'];
		}
	});
}
*/
/* カルテ編集の保存処理 */
/*
function send_chartdata(){
	var PNG = document.getElementById('canvas').toDataURL('image/png').replace(/^.*,/, '');
	var form = document.chart_form;
//	document.body.appendChild(form);
	var input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('name','chart_img');
	input.setAttribute('value', PNG);
	form.appendChild( input );
	form.setAttribute('action','savechart.php');
	form.setAttribute('method','post');
//	form.setAttribute('name', 'regist');
	form.submit();
}
*/
/* カルテページのビューに顧客カルテを表示させる */
/*
function get_chartlist(client_id){
	$.ajax({
		type: "POST",
		url: "./ajax/chartlist_view.php",
		data: {"id": client_id},
		success: function(j_data){
//			alert(j_data);
			document.getElementById('chart_view').innerHTML = j_data;
		}
	});
}
*/
/* カルテページのビューに編集用カルテ表示を行う */
/*function get_chartedit(client_id, type, chart_id){
	$.ajax({
		type: "POST",
		url: "./ajax/chartedit.php",
		data: {"client_id": client_id, "type": type, "chart_id": chart_id},
		success: function(j_data){
			alert(j_data);
			document.getElementById('chart_view').innerHTML = j_data;
		}
	});
}
*/
/* カルテページのビューに顧客カルテを表示させる */
/*
function get_chartview(chart_id){
	$.ajax({
		type: "POST",
		url: "./ajax/chartview.php",
		data: {"id": chart_id},
		success: function(j_data){
			alert(j_data);
			document.getElementById('chart_view').innerHTML = j_data;
		}
	});
}
*/
