if(SITE_NAME=="") SITE_NAME = '/wvcs';

/*
* 페이지 권한 제어
*/
$(function(){
	/*if(__user_edit_auth==false){	//등록/수정/삭제 권한이 없으면
		loadReadonlyForm();
	 }*/
	controllPageExecAuth();
});
function controllPageExecAuth(){
	if(__page_exec_auth.indexOf("U") == -1){
		$(".required-update-auth").each(function(){
			$(this).removeAttr("onclick");
			$(this).removeAttr("href");
			$(this).removeClass("text_link");
			if($(this).hasClass("hide")){
				$(this).hide();
			}
		});
	}

	if(__page_exec_auth.indexOf("C") == -1){
		$(".required-create-auth").each(function(){
			$(this).removeAttr("onclick");
			$(this).removeAttr("href");
			$(this).removeClass("text_link");
			if($(this).hasClass("hide")){
				$(this).hide();
			}
		});
	}

	if(__page_exec_auth.indexOf("D") == -1){
		$(".required-delete-auth").each(function(){
			$(this).removeAttr("onclick");
			$(this).removeAttr("href");
			$(this).removeClass("text_link");
			if($(this).hasClass("hide")){
				$(this).hide();
			}
		});
	}

	if(__page_exec_auth.indexOf("P") == -1){
		$(".required-print-auth").each(function(){
			$(this).removeAttr("onclick");
			$(this).removeAttr("href");
			$(this).removeClass("text_link");
			if($(this).hasClass("hide")){
				$(this).hide();
			}
		});
	}
}

/*텍스트 길이 리턴	
* $(id).textWidth();
*/
$.fn.textWidth = function(){
	 var text = $(this).val();
	if(text=="") text = $(this).text();

	 var calc = '<span style="display:none">' +text + '</span>';
	 $('body').append(calc);
	 var width = $('body').find('span:last').width();
	 $('body').find('span:last').remove();
	 return width;
};

//get url query string
function getUrlParams(urlOrQueryString) {
  
  var queryString = "";

  //alert(urlOrQueryString);

  if ((i = urlOrQueryString.indexOf('?')) >= 0) {
	queryString = urlOrQueryString.substring(i+1);
  }else queryString = urlOrQueryString;
  
  if (queryString) {
    return _mapUrlParams(queryString);
  }else  return {};
}
function _mapUrlParams(queryString) {
  return queryString    
    .split('&') 
    .map(function(keyValueString) { return keyValueString.split('=') })
    .reduce(function(urlParams, [key, value]) {
      if (Number.isInteger(parseInt(value)) && parseInt(value) == value) {
        urlParams[key] = value; //parseInt(value);
      } else {
        urlParams[key] = decodeURI(value);
      }
      return urlParams;
    }, {});
}
//querystring post로 전송하기
function sendPostForm(url){


	var _url = "";
	var _querystring = "";

	if(url.indexOf('?') > -1){
		_url = url.split('?')[0];
		_querystring = url.split('?')[1];
	}else{
		_url  = url;
	}

	var form = "<form name='frm' method='POST' action='"+_url+"'>";

	if(_querystring != ""){

		var params = getUrlParams(_querystring);
		for (var key in params) {

			//암호화된 querystring은 복호화
			if(key=="enc" && params[key] !=""){
				var querystring_dec = ParamDeCoding( params[key] );
				//alert(querystring_dec);
				var params2 = getUrlParams(querystring_dec);

				for (var key2 in params2) {
					form += "<input type='hidden' name='"+key2+"' value='"+params2[key2]+"'>";
				}
			
			}else{
				form += "<input type='hidden' name='"+key+"' value='"+params[key]+"'>";
			}
		}
	}
	form +="</form>";

	$(form).appendTo('body').submit();
	return false;
}

function closeModalWindow(id) {
	$(`#${id}`).hide()
}

//수정권한이 없을 경우 입력폼 비활성화
function loadReadonlyForm(){

	$("input.required_auth,select.required_auth").each(function(){
		var value = "<span>"+this.value+"</span>";
		$(this).next(".ui-datepicker-trigger").hide();
		$(this).after(value);
		$(this).remove();
	});

	$("a.required_auth,button.required_auth,.text_link.required_auth").each(function(){
		$(this).hide();
	});

}

function bindEventClearWarning(object){
	$(object).on("propertychange change keyup paste input", function() {
		if(this.value !=""){
			$(this).removeClass("warning-border");
			$(this).removeClass("warning-text");
			$(this).next().removeClass("warning-text");
		}
	});
}

/*날짜 유효성체크*/
function checkDayValidate(sdate, edate) {

	//var today = stringToDate(new Date(), 'YYYY-MM-DD');

	if($(sdate).val() == "") {
		//시작일을 설정하세요
		alert(setstartdate[lang_code]);
		return false;
	}else if($(edate).val() == "") {
		//종료일 설정하세요
		alert(setenddate[lang_code]);
		return false;
	}else if($(sdate).val() != "" && $(edate).val() != ""){
		
		if($(sdate).val() > $(edate).val()) {
			//날짜설정이 올바르지 않습니다.
			alert(incorrectdate[lang_code]);	
			//$(sdate).val('');
			//$(edate).val('');
			return false;
		}
	}
	
	return true;
}

/*데이터유효성체크*/
function checkValidData(object){
	
	var msg =$(object).attr("placeholder");
	var id = $(object).attr("id");
	var name = $("label[for='"+id+"']").text();
	var type = $(object).attr("type");
	var nodeName = $(object).prop('nodeName');
	var check_type = "";
	
	if(nodeName=='INPUT'){
		check_type = type;
	}else{
		check_type = nodeName;
	}
	
	if(check_type != undefined){
		check_type = check_type.toLowerCase();
	}

	if(name=="") name = corretdata[lang_code];

	if($(object).val()==""){
		
		$(object).focus();
		
		if(check_type=="text"){
			
			$(object).addClass("warning-border");

			if(msg=="" || msg==undefined) msg = inputdata[lang_code].replace("{#}",name);
			
		}else if(check_type=="checkbox"){
			
			$(object).next().addClass("warning-text");

			if(msg=="" || msg==undefined) msg = choosedata[lang_code].replace("{#}",name);
		
		}else if(check_type=="select"){

			$(object).addClass("warning-text");

			name = $(object).find("option:eq(0)[value='']").text();

			if(msg=="" || msg==undefined) msg = choosedata[lang_code].replace("{#}",name);

		}

		bindEventClearWarning(object);
		alert(msg);
		
		return false;
			
	}

	return true;

}

/*키오스크 입력폼 추가*/
function appendRow_CenterKiosk(){

	var kiosk_content = $("#tblCenterKiosk tr:eq(1)").html();
	var link_content = $("#tblCenterKiosk tr:eq(2)").html();

	$("#tblCenterKiosk tr:last").after("<tr>"+kiosk_content+"</tr><tr style='display:none'>"+link_content+"</tr>");

	var kiosk_row_index =$("#tblCenterKiosk tr:last").index()-1;
	var link_row_index =$("#tblCenterKiosk tr:last").index();

	var kiosk_row = $("#tblCenterKiosk tr:eq("+kiosk_row_index+")");
	var link_row = $("#tblCenterKiosk tr:eq("+link_row_index+")");

	//초기화
	resetRow_CenterKiosk(kiosk_row);
	resetRow_CenterKiosk(link_row);


}

//키오스크 입력폼 초기화
function resetRow_CenterKiosk(row){

	$(row).find("input[type='hidden']").val('');
	$(row).find("input[type='text']").val('');
	$(row).find("input[type='checkbox']").prop("checked",false);
	$(row).find("input[type='text']").removeClass('warning-border');
	$(row).find("label").removeClass('warning-text');
	$(row).find(".clsid_kiosk_link_count").text('0');
	$(row).find(".link_row:gt(0)").remove();
	//bindEventClearWarning();
}

//키오스크 입력폼 삭제
function removeRow_CenterKiosk(){
	
	var target = window.event.target;
	var kiosk_row_index = $(target).closest("tr").index();
	var link_row_index = $(target).closest("tr").index()+1;

	var kiosk_row = $("#tblCenterKiosk tr:eq("+kiosk_row_index+")");
	var link_row = $("#tblCenterKiosk tr:eq("+link_row_index+")");

	var row_cnt = $("#tblCenterKiosk tr").length;

	if(row_cnt > 3) {
		$(kiosk_row).remove();
		$(link_row).remove();
	}else{
		//resetRow_CenterKiosk(kiosk_row);
		//resetRow_CenterKiosk(link_row);
	}

}

//키오스크정보 삭제
function deleteCenterKiosk(proc){

	var kiosk_seq = $("#frmCenterKiosk tr:gt(0) .cbx:checked[value!='0']").map(function() {
			return this.value;
		}).get().join();

	if(kiosk_seq=="" || kiosk_seq=="0"){
		alert(choosedeletekiosk[lang_code]);
		return;
	}

	if(!confirm(qdeleteconfirm[lang_code])){
		return false;
	}

	
	var proc_name = getProcName();
	$("#frmCenterKiosk input[name='proc']").val(proc);

	$.post(
		SITE_NAME + '/manage/scan_center_kiosk_delete_process.php',
		{
			"kiosk_seq" : kiosk_seq,
			"proc_name": proc_name,
			"proc":proc
		},
		function (data) {
			
			if(data.status){
				location.reload();
			}
			alert(data.msg);
		},
		'json'
	);
}

/*키오스크 외부링크 입력폼 추가*/
function appendRow_KioskSubLink(){
	
	var target = window.event.target;
	var row = $(target).closest("tr");
	var content = $(target).closest("div").html();
	var link_row_cnt = $(row).find(".link_row").length;
	var link_row = $(target).closest(".link_row");
	
	$(row).find(".linkWrapper div:last").after("<div class='link_row' style='padding:5px;'>"+content+"</div>");
	
	var kiosk_row_index = $(row).index()-1;
	$("#tblCenterKiosk tr:eq("+kiosk_row_index+")").find(".clsid_kiosk_link_count").text(link_row_cnt+1);
	
	//초기화
	$(row).find(".linkWrapper div:last").find("input[type='text']").val('');
	$(row).find(".linkWrapper div:last").find("input[type='text']").removeClass('warning-border');

	return false;
}

/*키오스크 외부링크 입력폼 삭제*/
function removeRow_KioskSubLink(){

	var target = window.event.target;
	var row = $(target).closest("tr");
	var link_row_cnt = $(row).find(".link_row").length;
	var link_row = $(target).closest(".link_row");

	if(link_row_cnt==1) {
		//초기화
		$(link_row).find(".clsid_kiosk_link_name").val('');
		$(link_row).find(".clsid_kiosk_link_url").val('');
		$(row).hide();

	}else{
		$(link_row).remove();
	}

	var kiosk_row_index = $(row).index()-1;
	$("#tblCenterKiosk tr:eq("+kiosk_row_index+")").find(".clsid_kiosk_link_count").text(link_row_cnt-1);

}

//키오스크 외부링크 입력폼 Show
function showRow_KioskSubLink(){
	var target = window.event.target;
	var kiosk_row = $(target).closest("tr");
	var link_row_index = $(kiosk_row).index()+1;
	var link_row = $("#tblCenterKiosk tr:eq("+link_row_index+")");
	var link_row_cnt = $(link_row).find(".link_row").length;
	
	$(kiosk_row).find(".clsid_kiosk_link_count").text(link_row_cnt);
	$(link_row).toggle();
}

//키오스크 정보 저장
function saveCenterKiosk(proc){

	var proc_name = getProcName();
	$("#frmCenterKiosk input[name='proc_name']").val(proc_name);
	$("#frmCenterKiosk input[name='proc']").val(proc);

	if($("#scan_center_code").val()==""){
		alert(centerinforegist[lang_code]);
		$("#scan_center_code").focus();
		return false;
	}else{
		$("#scan_center_code2").val($("#scan_center_code").val());
	}

	var data_valid = true;

	//키오스크아이디 중복 체크
	$("#frmCenterKiosk").find(".clsid_kiosk_id").each(function(){
		
		var val = this.value;
		var duplicated = false;
		var duplicate_check = 0;
		
		if(val !=""){

			$("#frmCenterKiosk").find(".clsid_kiosk_id").each(function(){

				if(val==this.value) duplicate_check++
				
				if(duplicate_check > 1){
					duplicated = true;
					return false;
				}
			})

		}

		if(duplicated) {
			alert(duplicatedata[lang_code]+"(KIOSK ID="+val+")");
			data_valid = false; 
			return false;
		}

	});


	if(!data_valid) return false;

	$("#frmCenterKiosk table tr:gt(0)").each(function(){
		data_valid = checkCenterKiosk(this);
		if(!data_valid) return false;

		//link data 변수담기
		var jsonStr = "";
		var linkData = new Array();

		$(this).find(".linkWrapper .link_row").each(function(idx){
			
			var data ={name: "", url: ""};
			data["name"] = $(this).find(".clsid_kiosk_link_name").val();
			data["url"] = $(this).find(".clsid_kiosk_link_url").val();

			linkData[idx] = data;
			
		});
		var jsonStr = JSON.stringify(linkData);
		$(this).find(".clsid_center_kiosk_link").val(jsonStr);

	});


	if(!data_valid) return false;

	$.post(
		SITE_NAME + '/manage/scan_center_kiosk_process.php',
		$('#frmCenterKiosk').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.reload(true);
			}
		},
		'json'
	);
	
}

//키오크스 정보 유효성 체크
function checkCenterKiosk(form){

	var kiosk_id = $(form).find(".clsid_kiosk_id");
	var kiosk_name = $(form).find(".clsid_kiosk_name");
	var kiosk_ip = $(form).find(".clsid_kiosk_ip");

	if(checkValidData(kiosk_id)==false) return false;
	if(checkValidData(kiosk_name)==false) return false;
	if(checkValidData(kiosk_ip)==false) return false;

	if($(form).find(".clsnm_service").length > 0){
		var kiosk_menu = 
			$(form).find(".clsnm_service:checked").map(function() {
				return this.value;
			}).get().join();


		if(kiosk_menu==""){
			$(form).find(".clsnm_service").next().addClass("warning-text");
			alert(checkmemu[lang_code]);	//메뉴를 선택하세요.
			return false;
		}
	}
		
	var link_data_vaild = true;

	//외부링크체크
	
	if($(form).find(".clsid_link")) {

		$(form).find(".linkWrapper .link_row").each(function(){

			var link_name = $(this).find(".clsid_kiosk_link_name");
			var link_url = $(this).find(".clsid_kiosk_link_url");
			
			if($(link_name).val().length > 0 || $(link_url).val().length > 0){
				if(checkValidData(link_name)==false) {
					link_data_vaild = false;
					return false;
				}
				if(checkValidData(link_url)==false) {
					link_data_vaild = false;
					return false;
				}
			}

		});

		if(link_data_vaild==false) return false;
	}
	
	$(form).find(".clsid_center_kiosk_menu").val(kiosk_menu);

	return true;
}

//외부링크 입력폼 추가
function appendRow_KioskLink2(){
	
	var content = $("#frmCenterKioskLink tr:eq(2)").html();

	$("#frmCenterKioskLink tr:last").after("<tr>"+content+"</tr>");

	var row = $("#frmCenterKioskLink tr:last");
	//초기화
	resetKioskLink(row);
}

//외부링크 입력폼 초기화
function resetKioskLink(row){
	//초기화
	$(row).find("input[type='hidden']").val('');
	$(row).find("input[type='text']").val('');
	//bindEventClearWarning();
}

//외부링크 입력폼 삭제
function removeRow_KioskLink2(){
	
	var target = window.event.target;
	var row = $(target).closest("tr");

	var row_cnt = $("#tblCenterKioskLink tr:gt(0)").length;
	
	if(row_cnt==1){
		resetKioskLink(row);
	}else{
		$(row).remove();
	}
	
}

//외부링크 정보 저장 유효성 체크
function checksaveKioskLink(){
	var kiosk_seq = $("#frmCenterKioskLink  .cbx_kiosk:checked").map(function() {
			return this.value;
		}).get().join();

	if(kiosk_seq=="" || kiosk_seq=="0"){
		alert(chooseadapkiosk[lang_code]);
		return false;
	}
	
	
	var link_check_cnt = $("#frmCenterKioskLink .cbx_link:checked").length;

	if(link_check_cnt==0){
		alert(chooselink[lang_code]);
		return false;
	}

	return true;
}

//외부링크 정보 저장
function saveKioskLink(){

	if(!checksaveKioskLink()) return false;

	var proc_name = getProcName();
	$("#frmCenterKioskLink input[name='proc_name']").val(proc_name);
	$("#frmCenterKioskLink input[name='proc']").val("UPDATE");

	var data_vaild = true;

	var link_kiosk_seq = [];
	var link_checked = [];
	var link_name = [];
	var link_url = [];

	$("#tblCenterKioskLink tr:gt(0)").each(function(){
		var cbx_link_checked = $(this).find(".cbx_link").is(":checked");
		var link_name_f = $(this).find(".clsid_kiosk_link_name");
		var link_url_f = $(this).find(".clsid_kiosk_link_url");
		
		if(cbx_link_checked){
			data_vaild = checkValidData(link_name_f);
			if(data_vaild==false) return false;

			data_vaild = checkValidData(link_url_f);
			if (data_vaild == false) return false;
			
			var checked = $(this).find(".cbx_link").val();
			var name = $(this).find(".clsid_kiosk_link_name").val();
			var url = $(this).find(".clsid_kiosk_link_url").val();

			link_checked.push(checked);
			link_name.push(name);
			link_url.push(url);
		}	
	});

	var kiosk_chk = $("#frmCenterKioskLink input[name='link_kiosk_seq[]']");

	kiosk_chk.each(function() {
		var kiosk_checked = $(this).is(":checked");

		if (kiosk_checked) {
			link_kiosk_seq.push($(this).val());
		}
	})

	var postData = {
		'link_kiosk_seq': link_kiosk_seq,
		'link_checked': link_checked,
		'link_name': link_name,
		'link_url': link_url
	};

	if(!data_vaild) return false;

	$.post(
		SITE_NAME + '/manage/scan_center_kiosk_link_process.php',
		// $("#frmCenterKioskLink").serialize(),
		postData,
		function (data) {
			
			if(data.status){
				location.reload();
			}
			alert(data.msg);
		},
		'json'
	);
}

//외부링크  정보 삭제 유효성 체크
function checkdeleteKioskLink(){
	
	var kiosk_seq = $("#frmCenterKioskLink  .cbx_kiosk:checked").map(function() {
			return this.value;
		}).get().join();

	if(kiosk_seq=="" || kiosk_seq=="0"){
		alert(choosedeletelinkkiosk[lang_code]);
		return false;
	}

	var link_check_cnt = $("#frmCenterKioskLink .cbx_link:checked").length;

	if(link_check_cnt==0){
		alert(choosedeletelink[lang_code]);
		return false;
	}

	if(link_check_cnt==1){
		var link_name = $("#frmCenterKioskLink .clsid_kiosk_link_name").val();
		var link_url = $("#frmCenterKioskLink .clsid_kiosk_link_url").val();

		if(link_name =="" && link_url=="") return false;
	}

	return true;
}

//외부링크 정보 삭제
function deleteKioskLink(proc){

	if(!checkdeleteKioskLink()) return false;

	var proc_name = getProcName();
	$("#frmCenterKioskLink input[name='proc_name']").val(proc_name);
	$("#frmCenterKioskLink input[name='proc']").val(proc);

	if(!confirm(qdeleteconfirm[lang_code])){
		return false;
	}

	$.post(
		SITE_NAME + '/manage/scan_center_kiosk_link_delete_process.php',
		$("#frmCenterKioskLink").serialize(),
		function (data) {
			
			if(data.status){
				location.reload();
			}
			alert(data.msg);
		},
		'json'
	);

}
//예외파일정책 파일추가 입력폼 보이기
function showFileListForm(){
	
	var file_div = $("#file_div").val();

	if(file_div=="FILE"){
		$("#FileListWrap").show();
	}else{
		$("#FileListWrap").hide();
	}
}

//예외파일정책 파일추가 입력 Row 초기화
function resetRow_FilePolicy(row){
	$(row).find("input").val('');
	//bindEventClearWarning();
}
//예외파일정책 파일추가 입력 Row 추가
function appendRow_FilePolicy(){

	var target = window.event.target;
	var row = $(target).closest("tr");

	$("#tblFileList tr:last").after("<tr>"+$(row).html()+"</tr>");
	resetRow_FilePolicy($("#tblFileList tr:last"));
}

//예외파일정책 파일추가 입력 Row 삭제
function removeRow_FilePolicy(){

	var target = window.event.target;
	var row = $(target).closest("tr");
	var row_count = $("#tblFileList tr:gt(0)").length;

	if(row_count==1){
		resetRow_FilePolicy(row);
	}else{
		$(row).remove();
	}
}

//예외파일정책 저장하기 데이터유효성체크
function checkDataFilePolicy(){
	
	if(!checkValidData($("#policy_name"))) return false;
	if(!checkDayValidate($('#start_date'),$('#end_date'))) return false;
	if(!checkValidData($("#target"))) return false;

	var today = new Date().dateformat('yyyy-mm-dd');
	
	//시작일을 지난일자로 설정할 수 없습니다.
	if(today > $('#start_date').val()){
		alert(wrongstartdate[lang_code]);
		return false;
	}

	if($("#target_search_wrap").is(":visible")){
		if(!checkValidData($("#target_name"))) return false;
	}
	
	//예외적용파일이 지정파일인 경우 지정파일 입력값 체크
	var data_valid = true;
	if($("#file_div").val()=="FILE"){

		$(".clsid_file_hash").each(function(){
			if(!checkValidData(this)) {
				data_valid = false;
				return false;
			}
		});

	}

	if(!data_valid) return false;
	
	return true;
}

//예외파일정책 저장하기
function saveFilePolicy(){

	var policy_file_in_seq = $("#policy_file_in_seq").val();
	var proc = (policy_file_in_seq=="" ? "CREATE" : "UPDATE");

	$("#proc").val(proc);
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	
	if(!checkDataFilePolicy()) return false;

		$.post(
			SITE_NAME + '/manage/policy_file_import_process.php',
			$("#frmPolicy").serialize(),
		function (data) {

			if(data.status){
				location.href = SITE_NAME + '/manage/policy_file_import_reg.php?enc='+ParamEnCoding('policy_file_in_seq='+data.result)
			}
			alert(data.msg);
		},
		'json'
	);
}
function deleteFilePolicy(){

	var url =SITE_NAME + '/manage/policy_file_import.php';
	_deleteFilePolicy(url);
}
function deleteFilePolicy_Row(seq){

	var target = window.event.target;
	var policy_file_in_seq = $(target).closest("td").find(".clsid_policy_file_in_seq").val();

	$("#policy_file_in_seq").val(policy_file_in_seq);

	_deleteFilePolicy('');
}
function _deleteFilePolicy(url){


	if(!confirm(qdeleteconfirm[lang_code])){
		return false;
	}

	$("#proc").val('DELETE');
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$("#proc").val('DELETE');
	
		$.post(
			SITE_NAME + '/manage/policy_file_import_process.php',
			$("#frmPolicy").serialize(),
		function (data) {
			
			alert(data.msg);

			if(url==""){
				location.reload();
			}else{
				location.href = "policy_file_import.php";
			}
			
		},
		'json'
	);
}

function changePolicyTarget(){
	
	var target = $("#target").val();

	$("#target_name").val('');
	$("#target_value").val('');

	if(target=="ALL" || target==""){
		$("#target_search_wrap").hide();
	}else{
		$("#target_search_wrap").show();
	}
}

function popSyncEmployee() {

	var target = $("#target").val();

	$.post(
		SITE_NAME + '/manage/pop_employee_search.php?enc=' + ParamEnCoding('target=' + target),
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}
function closePopSyncEmployee(){
	$('#popContent').hide();
}

function popSearchFormSubmit() {
		var proc_name = getProcName();
	$("#proc_name").val(proc_name);

	var msg = $("#popsearchkey").attr("placeholder");

	if(msg=="" || msg==undefined) msg = qsearchkeywordinput[lang_code];
	
	if($("#popsearchkey").val().length < 2){
		alert(msg+" ("+charater2more[lang_code]+")");
		$("#popsearchkey").focus();
		return false;
	}

	$.post(
		SITE_NAME + '/manage/pop_employee_search_result.php',
		$("#popSearchForm").serialize(),
		function (data) {
			$('#popContent .pop_search_result').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}
//정책적용대상 선택
function selectPolicyTarget(){

	var target = window.event.target;
	var target_value = $(target).closest("tr").find(".clsid_target_value").val();
	var target_name = $(target).closest("tr").find(".clsid_target_name").val();

	
	$("#target_value").val(target_value);
	$("#target_name").val(target_name);

	$('#popContent').hide();

}
//폼입력초기화
function resetForm(frm){
	
	$(frm).find("input[type='text']").not('.hasDatepicker').val('');
	$(frm).find("select option[value='']").prop("selected",true);
	$(frm).find("input[type='checkbox']").prop("checked",false);
}

/*방문객 출입정보 - 파일검사 로그 보기*/
function popUserVcsLog_Visit(v_user_list_seq) {
	$.post(
		SITE_NAME + '/result/pop_user_check_log.php',
		{
			"v_user_list_seq": v_user_list_seq
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}


//동의서 form 설정
function setAgreeConfig(agree_div){

	var target = window.event.target;
	
	 $("#page_title small").text($(target).text());
	
	$(".tab li").removeClass("on");
	
	if(agree_div.indexOf("RENT") ==-1){
		$("#row_rent_item").hide();
		$(".clsid_"+agree_div.toLowerCase()).addClass("on");
	}else{
		$("#row_rent_item").show();
		if(agree_div=="RENT") $("#rent_item").val('');
		$(".clsid_rent").addClass("on");
	}

	if($(target).hasClass("content_replace")){
		$(".row_content_replace").show();
	}else{
		$(".row_content_replace").hide();
	}
	 
	 $("#agree_div").val(agree_div);
	getAgreeContent();
}
//동의서내용가져오기
function getAgreeContent(){
	
	var agree_div = $("#agree_div").val();
	
	$.post(
		SITE_NAME + '/manage/get_agree_content.php',
		{
			"agree_div" : agree_div
		},
		function (data) {
			if (data.status) {
				if (data.result != null) {
					$("#agree_config_seq").val(data.result['agree_config_seq']);
					$("#agree_title").val(data.result['agree_title']);
					$("#agree_content").val(data.result['agree_content']);

					var checked = data.result['request_consent_yn']=="Y";
					$("#request_consent_yn").prop("checked",checked);

					var use_yn = data.result['use_yn'];
					$("input[name='use_yn'][value='"+use_yn+"']").prop("checked",true);
				}
			}
		},
		'json'
	);
}
//동의서내용저장하기
function saveAgreeContent(){

	//base64 encoding해서 데이터 전송
	var agree_title_enc = btoa(unescape(encodeURIComponent($("#agree_title").val())));
	var agree_content_enc = btoa(unescape(encodeURIComponent($("#agree_content").val())));
	var agree_bottom_enc = btoa(unescape(encodeURIComponent($("#agree_bottom").val())));

	$("#agree_title_enc").val(agree_title_enc);
	$("#agree_content_enc").val(agree_content_enc);
	$("#agree_bottom_enc").val(agree_bottom_enc);

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$("#proc").val("UPDATE");
	
	$.post(
		SITE_NAME + '/manage/agree_config_process.php',
		$("#frmAgree").serialize(),
		function (data) {
			if (data.status) {
				$("#agree_config_seq").val(data.result);
			}
			alert(data.msg);

		},
		'json'
	);
}

function downloadExcel(total, url,down_name) {

	var button = window.event.target || window.event.srcElement;

	//button class
	 $(button).addClass('loading');
	originalButtonText = $(button).text();
	$(button).text('Downloading...');

	var start = 0;
	var ymdhis = new Date().dateformat('yyyymmddhhmmss');
	var file_name = down_name+"_"+ymdhis+".xls";

	_downloadExcel(total,start,url,down_name,file_name,button);
}

function _downloadExcel(total,start,url,down_name,file_name,button){

	var record_size = 5000;
	var loop_size = Math.ceil(total/record_size);
	var loop_step = 5;

	var end = start+loop_step-1;

	$.ajax({
		type: 'POST',
		url: url,
		dataType: 'JSON',
		data: { 
			record_start: start
			,record_end: end
		},
		success: function (response) {
			console.log('Response received:', response);
			try {
				exportExcel(response,down_name,file_name);
			} catch (error) {
				console.log('Error exporting HTML: ', error);
			} finally {
               $(button).text(end); // Reset the button text
				
				if(loop_size > end){
					var next_start = start+loop_step;
					_downloadExcel(total,next_start,url,down_name,file_name,button);
				}else{
					//다운로드 끝
					$(button).text('다운로드끝');	
				}
				
            }
		},
		error: function (xhr, status, error) {
			console.log('AJAX request error:', error);
			console.log('Response Text:', xhr.responseText); // Log the response for debugging.
			
			// Re-enable the button in case of an error
			$(button).removeClass('loading');
			$(button).text('엑셀파일로 저장하기');
			
		},
	});
}

function exportExcel(response,down_name,file_name) {
	
	$(response).each(function (index) {
		var excelContent = response[index];

		var excelFileData =
			"<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
		excelFileData += '<head>';
		excelFileData += '<!--[if gte mso 9]>';
		excelFileData += '<xml>';
		excelFileData += '<x:ExcelWorkbook>';
		excelFileData += '<x:ExcelWorksheets>';
		excelFileData += '<x:ExcelWorksheet>';
		excelFileData += '<x:Name>';
		excelFileData += down_name;
		excelFileData += '</x:Name>';
		excelFileData += '<x:WorksheetOptions>';
		excelFileData += '<x:DisplayGridlines/>';
		excelFileData += '</x:WorksheetOptions>';
		excelFileData += '</x:ExcelWorksheet>';
		excelFileData += '</x:ExcelWorksheets>';
		excelFileData += '</x:ExcelWorkbook>';
		excelFileData += '</xml>';
		excelFileData += '<![endif]-->';
		excelFileData += '</head>';
		excelFileData += '<body>';
		excelFileData += excelContent;
		excelFileData += '</body>';
		excelFileData += '</html>';

		var sourceHTML = excelFileData + response[index];

		var source = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(sourceHTML);
		var fileDownload = document.createElement('a');
		document.body.appendChild(fileDownload);
		fileDownload.href = source;

		fileDownload.download = file_name;
		fileDownload.click();
		document.body.removeChild(fileDownload);
	});
}

/*임시출입증회수처리*/
function visitorPassReturn(v_user_list_seq){
	if(!confirm(qcollectconfirm[lang_code])){
		return false;
	}

	if(!CheckBlankData($('#pass_card_no'), inputpasscardno[lang_code])) return false;

	_visitorPassReturn(v_user_list_seq,'return');
}
/*임시출입증회수처리취소*/
function visitorPassReturnCancel(v_user_list_seq){
	if(!confirm(qcancelconfirm[lang_code])){
		return false;
	}

	_visitorPassReturn(v_user_list_seq,'cancel');
}

function _visitorPassReturn(v_user_list_seq,proc){

	var proc_name = getProcName();
	
	$.post(
		SITE_NAME + '/user/access_control_return_process.php',
		{
			"item" : "pass"
			,"v_user_list_seq" : v_user_list_seq
			,"proc" : proc
			,"proc_name" : proc_name
		},
		function (data) {
			alert(data.msg);
			if(data.status){
				location.reload(true);
			}
		},
		'json'
	);

	return false;
}



/*사내USB회수처리*/
function visitorUsbReturn(v_user_list_seq){
	if(!confirm(qcollectconfirm[lang_code])){
		return false;
	}

	_visitorUsbReturn(v_user_list_seq,'return');
}
/*사내USB회수처리취소*/
function visitorUsbReturnCancel(v_user_list_seq){
	
	// alert('USB 회수 취소는 담당 임직원에게 문의바랍니다.');

	if(!confirm(qcancelconfirm[lang_code])){
		return false;
	}

	_visitorUsbReturn(v_user_list_seq,'cancel');
}

function _visitorUsbReturn(v_user_list_seq,proc){

	var proc_name = getProcName();

	$.post(
		SITE_NAME + '/user/access_control_usb_return_process.php',
		{
			"v_user_list_seq" : v_user_list_seq
			,"proc" : proc
			,"proc_name" : proc_name
		},
		function (data) {
			alert(data.msg);
			if(data.status){
				location.reload(true);
			}
		},
		'json'
	);

	return false;
}
function fileDownLoad(v_wvcs_seq){
	$.post(
		SITE_NAME + '/result/get_file.php',
		{
			"v_wvcs_seq" : v_wvcs_seq
		},
		function (data) {
			if(data.status){
				var file_path = data.result.file_path;
				var file_down_name = data.result.file_down_name;
				_fileDownLoad(file_path,file_down_name);
			}else{
				alert(data.msg);
			}
		},
		'json'
	);

	return false;
}

function _fileDownLoad(file,filename){
	//ajax 다운로드 progress bar 작업필요!!
	//alert(SITE_NAME + '/common/download.php?file='+file+"&filename="+filename);
	location.href=SITE_NAME + '/common/download.php?file='+file+"&filename="+filename;

}

//임시출입증정보 업데이트
function passInfoUpdate() {

	var proc_name = getProcName();
	$("#frmPass input[name='proc_name']").val(proc_name);
	$("#frmPass input[name='proc']").val("UPDATE");

	$.post(
		'user_pass_info_process.php',
		$("#frmPass").serialize(),
			function (data) {
				alert(data.msg);
				location.reload();
			},
		'json'
	);
}

//자산반입정보 팝업창
function popImportGoodsInfo(){
	
	var target = window.event.target;
	var row = $(target).closest("tr");
	
	var v_user_list_goods_seq = $.trim($(row).find("span[data-name='v_user_list_goods_seq']").text());
	var g_name = $.trim($(row).find("span[data-name='g_name']").text());
	var g_mgt_no = $.trim($(row).find("span[data-name='g_mgt_no']").text());
	var g_doc_no= $.trim($(row).find("span[data-name='g_doc_no']").text());
	var g_model = $.trim($(row).find("span[data-name='g_model']").text());
	var g_sn = $.trim($(row).find("span[data-name='g_sn']").text());
	var g_out_schedule_date = $.trim($(row).find("span[data-name='g_out_schedule_date']").text());
	var g_memo = $.trim($(row).find("span[data-name='g_memo']").text());
	
	$("#v_user_list_goods_seq").val(v_user_list_goods_seq);
	$("#g_name").val(g_name);
	$("#g_mgt_no").val(g_mgt_no);
	$("#g_doc_no").val(g_doc_no);
	$("#g_model").val(g_model);
	$("#g_sn").val(g_sn);
	$("#g_out_schedule_date").val(g_out_schedule_date);
	$("#g_memo").val(g_memo);

	
	$(".modal").show();
}

//자산반입정보수정
function submitFrmGoods(){

	var proc_name = getProcName();
	$("#frmGoods input[name='proc_name']").val(proc_name);
	$("#frmGoods input[name='proc']").val('UPDATE');


	if(!checkValidData($('#g_name'))) return false;
	if(!checkValidData($('#g_mgt_no'))) return false;
	if(!checkValidData($('#g_doc_no'))) return false;
	if(!checkValidData($('#g_model'))) return false;
	if(!checkValidData($('#g_sn'))) return false;

	$.post(
		'user_import_goods_info_process.php',
		$("#frmGoods").serialize(),
			function (data) {
				alert(data.msg);
				if(data.status){
					location.reload();
				}
			},
		'json'
	);

}

function CheckManagerInfoSubmit(proc) {
	if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

	var admin_level = $('#admin_level').val();
	var admin_auth_type = $("#admin_auth_type").val();
	var org_id = $('#org_id').val();

	if (!admin_auth_type) {
		alert(chooseaccessauthcheck[lang_code]);
		$("#admin_auth_type").focus();
		return false;
	}

	if ((admin_level != '') && (admin_level != 'SUPER')) {
		//관리기관 체크
		//if (org_id != '') {
			var user_org_check = $("input[name='mng_org[]']:checked").length;

			if (user_org_check == 0) {
				alert(chooseorgancheck[lang_code]);
				$("input[name='mng_org[]']").focus();
				return false;
			}
		//}
			
		//관리검사장 체크
		var user_center_check = $("input[name='mng_scan_center[]']:checked").length;

		if(user_center_check==0){
			alert(choosecentercheck[lang_code]);
			$("input[name='mng_scan_center[]']").focus();
			return false;
		}
	}

	return true;
}

function ManagerInfoSubmit(proc) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$("#proc").val(proc);

	if(proc=="UPDATE"){
		if (!CheckManagerInfoSubmit()) return false;
	}else if(proc=="DELETE"){
		if(!confirm(qdeleteconfirm[lang_code])){
			return false;
		}
	}

	if (doubleSubmitCheck()) return;

	$.post(
		SITE_NAME+'/manage/kabang_emp_process.php',
		$('#frmMgr').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				if(proc=='UPDATE'){
					location.reload();
				}else{
					location.href = SITE_NAME+"/manage/kabang_emp_list.php";
				}
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}

function LoginKabangSubmit() {
	var login_id = document.getElementById('login_id').value;
	var login_pw = document.getElementById('login_pw').value;

	if($("#login_id").val()==""){
		alert($("#login_id").attr("placeholder"));
		$("#login_id").focus();
		return false;
	}

	if($("#login_pw").val()==""){
		alert($("#login_pw").attr("placeholder"));
		$("#login_pw").focus();
		return false;
	}

	$.post(
		'login_kabang_process.php',
		$('#frmLogin').serialize(),
		function (data) {
			if (data.status) {
				if (data.result != null) {
					SetMenuFolder('menu_folder', 'show');
					location.href = data.result;
				}
			} else {
				alert(data.msg);
			}
		},
		'json'
	);

	return false;
}
function searchKabangEmpReset(){
	$("#emp_name").val('');
	$("#dept_name").val('');
	$("#emp_id").val('');
	$("#emp_id").prop("readonly",false);
	$("#btnEmpsearch").text('조회');
	$("#btnEmpsearch").removeAttr("onclick");
	$("#btnEmpsearch").attr("onclick",'searchKabangEmp()');
}
function searchKabangEmp(){

		if(!checkValidData($('#emp_id'))) return false;
		
		$.post(
		SITE_NAME + '/manage/get_kabang_emp_info.php',
		{
			"emp_id" : $("#emp_id").val()
		},
		function (data) {
			
			if(data.status){
				$("#emp_name").val(data.result['emp_name']);
				$("#dept_name").val(data.result['dept_name']);
				$("#emp_id").prop("readonly",true);
				$("#btnEmpsearch").text('다시조회');
				$("#btnEmpsearch").removeAttr("onclick");
				$("#btnEmpsearch").attr("onclick",'searchKabangEmpReset()');
			}else{
				alert(data.msg);
			}
		},
		'json'
	);

}
function CheckfrmEmpAccountSubmit() {

	if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

	var admin_level = $('#admin_level').val();

	if(!checkValidData($('#emp_id'))) return false;

	//관리기관 체크
	var user_org_check = $("input[name='mng_org[]']:checked").length;

	if (user_org_check == 0) {
		alert(chooseorgancheck[lang_code]);
		$("input[name='mng_org[]']").focus();
		return false;
	}

	//접근권한 체크
	var admin_auth_type = $("#admin_auth_type").val();

	if (!admin_auth_type) {
		alert(chooseaccessauthcheck[lang_code]);
		$("input[name='menu[]']").focus();
		return false;
	}
	
	//관리검사장 체크
	var user_center_check = $("input[name='mng_scan_center[]']:checked").length;

	if(user_center_check==0){
		alert(choosecentercheck[lang_code]);
		$("input[name='mng_scan_center[]']").focus();
		return false;
	}

	return true;
}

function CheckfrmEmpAccountListSubmit() {

	var proc = $("#proc").val();

	if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

	var admin_level = $('#admin_level').val();
	var admin_auth_type = $("#admin_auth_type").val();

	//접근권한 체크
	if (!admin_auth_type) {
		alert(chooseaccessauthcheck[lang_code]);
		$("#admin_auth_type").focus();
		return false;
	}

	if(admin_level != 'SUPER'){
		//관리기관 체크
		var user_org_check = $("input[name='mng_org[]']:checked").length;

		if (user_org_check == 0) {
			alert(chooseorgancheck[lang_code]);
			$("input[name='mng_org[]']").focus();
			return false;
		}
		
		//관리검사장 체크
		var user_center_check = $("input[name='mng_scan_center[]']:checked").length;

		if(user_center_check==0){
			alert(choosecentercheck[lang_code]);
			$("input[name='mng_scan_center[]']").focus();
			return false;
		}
		
	}

	
	//임직원정보 선택 체크
	if($(".clsid_cbx_emp:checked").length==0){
		alert(chooseEmpInfo[lang_code]);
		return false;
	}


	return true;
}

/*카뱅 vcs 계정생성 (개별 등록)*/
function frmEmpAccountSubmit() {

	if (!CheckfrmEmpAccountSubmit()) return false;
	if (doubleSubmitCheck()) return;

	$.post(
		SITE_NAME+'/manage/kabang_emp_process.php',
		$('#frmEmpAccount').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}

/*카뱅 vcs 계정생성(선택 일괄등록)*/
function frmEmpAccountListSubmit() {

	if (!CheckfrmEmpAccountListSubmit()) return false;
	if (doubleSubmitCheck()) return;

	var proc = $("#proc").val();
	var admin_level = $("#admin_level").val();


	var proc_name = getProcName();
	$("#frmEmpAccountList input[name='proc_name']").val(proc_name);

	$.post(
		SITE_NAME+'/manage/kabang_emp_process.php',
		$('#frmEmpAccountList').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				if(proc=="CREATE"){
					location.reload();
				}
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}
function CheckfrmEmpAuthSubmit() {

	if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

	var admin_level = $('#admin_level').val();
	var admin_auth_type = $("#admin_auth_type").val();

	//접근권한 체크
	if (!admin_auth_type) {
		alert(chooseaccessauthcheck[lang_code]);
		$("#admin_auth_type").focus();
		return false;
	}

	if (admin_level != 'SUPER') {
		//관리기관 체크
		var user_org_check = $("input[name='mng_org[]']:checked").length;

		if (user_org_check == 0) {
			alert(chooseorgancheck[lang_code]);
			$("input[name='mng_org[]']").focus();
			return false;
		}
		
		//관리검사장 체크
		var user_center_check = $("input[name='mng_scan_center[]']:checked").length;

		if(user_center_check==0){
			alert(choosecentercheck[lang_code]);
			$("input[name='mng_scan_center[]']").focus();
			return false;
		}

	}

	
	var emp_list = $("#emp_seq_list").val();

	if (!emp_list) {
		var all = confirm(pageauthapplytoall[lang_code]);

		if (!all) {
			return false;
		}
	}

	return true;
}

//카뱅 접근권한설정 정보 전송
function frmEmpAuthSubmit() {

	if (!CheckfrmEmpAuthSubmit()) return false;
	if (doubleSubmitCheck()) return;

	var proc_name = getProcName();
	$("#frmEmpAccount input[name='proc_name']").val(proc_name);

	$.post(
		SITE_NAME+'/manage/kabang_emp_auth_process.php',
		$('#frmEmpAccount').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}

//카뱅 임직원 vcs 계정생성
function registKabangEmp(){

	var target = window.event.target;
	var title = $(target).text();

	$("#modal_account .modal-title").text(title);
	$("#tbl_create_account").show();
	$("#tbl_auth_account").hide();
	$("#modal_account").show();
	$("#btnSave").removeAttr("onclick");
	$("#btnSave").attr("onclick"," return frmEmpAccountSubmit()");
}

//카뱅 부서 조직도 찾기
function searchKabankDepartment() {
	loadKabankDepartment("N");
	
	$("#modal_department_search .modal-title").html(finddepartment[lang_code]);
	$("#modal_department_search").show();
}

//카뱅 부서 조직도로 직원 찾기
function searchKabankDepartmentEmployee() {
	loadKabankDepartment("Y");
	
	$("#modal_department_search .modal-title").html(finddepartmentemp[lang_code]);
	$("#modal_department_search").show();
}

function loadKabankDepartment(findEmployee) {
	$.get(`${SITE_NAME}/manage/get_kabang_emp_department.php?find_employee=${findEmployee}`, function (data) {
		$("#department_tree").html(data);
	});
}

function setDeptNapePath(path) {
	let fake = path.replaceAll(';', ' > ')

	let yesno = confirm(`[${fake}]\n\n해당 부서를 선택하시겠습니까?`);

	if (yesno) {
		$("#dept_name_path").val(path);
		$("#fake_name_path").val(fake);

		$("#modal_department_search").hide();
	}
}

function resetDeptNapePath() {
	$("#dept_name_path").val('');
	$("#fake_name_path").val('');

}

function searchKabankEmp(frm) {
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);

	if (frm.searchkey.value != '' && frm.searchopt.value == '') {
		frm.searchopt.focus();
		alert(qsearchoptionchoose[lang_code]);
		return false;
	} else if (frm.searchopt.value != '' && frm.searchkey.value == '') {
		frm.searchkey.focus();
		alert(qsearchkeywordinput[lang_code]);
		return false;
	} else {
		frm.submit();
	}
}

//카뱅 임직원 접근권한설정
function setAuthKabangEmp(){
	var target = window.event.target;
	var title = $(target).text();
	
	$("#tbl_create_account").hide();
	$("#tbl_auth_account_one").hide();

	$("#txt_name").text('');
	$("#txt_dept").text('');
	$("#txt_admin_level").text('');
	$("#txt_auth_name").text('');
	$("#emp_seq").val('');
	$("#admin_auth_type option[value=CUSTOMIZE]").hide();

	var emp_check_count = $("input[name='emp_seq[]']:checked").length;
	var emp_seq_list = $("input[name='emp_seq[]']:checked").map(function(){
		return this.value;
	}).get().join();

	if(emp_check_count > 0 ){
		$("#emp_seq_list").val(emp_seq_list);
		$(".clsid_emp_count").html('<div>'+emp_check_count+' 명</div>');

		if (emp_check_count == 1) {
			let emp_checked = $("input[name='emp_seq[]']:checked");
			let enc_param = emp_checked.data("enc")

			$("#txt_name").text(emp_checked.data("name"));
			$("#txt_dept").text(emp_checked.data("dept"));
			$("#txt_admin_level").text(emp_checked.data("admin-level-text"));
			$("#txt_auth_name").text(emp_checked.data("auth-name"));

			$("#page_auth_detail .btn").data("emp-seq", emp_checked.val())

			let admin_level = emp_checked.data("admin-level");

			$("#admin_level").val(admin_level);
			$("#enc_param").val(enc_param);
			$("#emp_seq").val(emp_checked.val());
			$("#admin_auth_type option[value=CUSTOMIZE]").show();

			SetAdminAuth();
			loadAuthKabankEmpOne();

			$("#tbl_auth_account").hide();
			$("#tbl_auth_account_one").show();
		} else {
			$("input[name='mng_org[]'").prop("checked", false);
			$("input[name='mng_scan_center[]'").prop("checked", false);
			$("input[name='menu[]'").prop("checked", false);
			$("#admin_level").val('');

			SetAdminAuth();

			$("#tbl_auth_account").show();
			$("#tbl_auth_account_one").hide();
		}
	} else {
		$("#tbl_auth_account").show();
		$("#tbl_auth_account_one").hide();
	}

	$("#modal_account .modal-title").text(title);
	$("#modal_account").show();
	$("#btnEmpListSave").removeAttr("onclick");
	$("#btnEmpListSave").attr("onclick"," return frmEmpAuthSubmit()");
	$("#btnSaveDetailAuth").removeAttr("onclick");
	$("#btnSaveDetailAuth").attr("onclick"," return frmAdminAuthDetailsSubmit("+loadAuthKabankEmpOne+")");
}

function loadKabankEmpOne() {
	let enc_param = $("#enc_param").val();
	
	$("input[name='mng_scan_center[]'").removeClass("checked");
	$("input[name='menu[]'").removeClass("checked");
	$("input[name='menu[]'").removeClass("indeterminate");
	
	$.get(`${SITE_NAME}/manage/get_kabang_emp_auth.php?enc=${enc_param}`, function (data) {
		let emp_auth = data.result;

		emp_auth.org.map((code) => {
			$(`#mng_org_${code}`).prop("checked", "checked");
		})

		if (emp_auth.auth.auth_type == "PRESET") {
			$(`#admin_auth_type option[data-preset-seq=${emp_auth.auth.auth_preset_seq}]`).attr("selected", "selected");
		}else {
			$(`#admin_auth_type option[value=CUSTOMIZE]`).attr("selected", "selected");
		}

		changeAdminAuthPresetType();

		if (emp_auth.auth.auth_type == "CUSTOMIZE") {
			emp_auth.scan_center.map((code) => {
				$(`#mng_scan_center_${code}`).prop("checked", true);
				$(`#mng_scan_center_${code}`).addClass("checked");
			})

			emp_auth.menu.map((menu) => {
				let checktype =  (menu.page_code == "all") ? "checked" : "indeterminate";
				$(`#menu_${menu.menu_code}`).prop(checktype, true);
				$(`#menu_${menu.menu_code}`).addClass(checktype);
			})
		}

	}, 'json');
}

function loadAuthKabankEmpOne() {
	let enc_param = $("#enc_param").val();
	
	$("input[name='mng_scan_center[]'").removeClass("checked");
	$("input[name='menu[]'").removeClass("checked");
	$("input[name='menu[]'").removeClass("indeterminate");
	
	$.get(`${SITE_NAME}/manage/get_kabang_emp_auth.php?enc=${enc_param}`, function (data) {
		let emp_auth = data.result;

		emp_auth.org.map((code) => {
			$(`#mng_org_${code}`).prop("checked", "checked");
		})

		if (emp_auth.auth.auth_type == "PRESET") {
			$(`#admin_auth_type option[data-preset-seq=${emp_auth.auth.auth_preset_seq}]`).attr("selected", "selected");
		}else {
			$(`#admin_auth_type option[value=CUSTOMIZE]`).attr("selected", "selected");
		}

		changeAdminAuthPresetType();

		if (emp_auth.auth.auth_type == "CUSTOMIZE") {
			emp_auth.scan_center.map((code) => {
				$(`#mng_scan_center_${code}`).prop("checked", true);
				$(`#mng_scan_center_${code}`).addClass("checked");
			})

			emp_auth.menu.map((menu) => {
				let checktype =  (menu.page_code == "all") ? "checked" : "indeterminate";
				$(`#menu_${menu.menu_code}`).prop(checktype, true);
				$(`#menu_${menu.menu_code}`).addClass(checktype);
			})
		}

	}, 'json');
}
function LoadBadFileInfo(v_wvcs_seq) {

	$.post(
		SITE_NAME + '/result/get_bad_file.php', {
			v_wvcs_seq: v_wvcs_seq
		},
		function(data) {
			$('#file_bad_list').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadFailFileInfo(v_wvcs_seq) {
	$.post(
		SITE_NAME + '/result/get_fail_file.php', {
			v_wvcs_seq: v_wvcs_seq
		},
		function(data) {
			$('#file_fail_list').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadImportFileInfo(v_wvcs_seq) {

	$.post(
		SITE_NAME + '/result/get_import_file.php', {
			v_wvcs_seq: v_wvcs_seq
		},
		function(data) {
			$('#file_import_list').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadScanFileInfo(v_wvcs_seq) {

	$.post(
		SITE_NAME + '/result/get_scan_file.php', {
			v_wvcs_seq: v_wvcs_seq
		},
		function(data) {
			$('#file_scan_list').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadFileApplyInfo(v_wvcs_seq) {

	$.post(
		SITE_NAME + '/result/get_user_file_apply_list.php', {
			v_wvcs_seq: v_wvcs_seq
		},
		function(data) {
			$('#file_apply_list').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

/*일별출입현황*/
function loadStatisticsVisitStatusDailyChart() {
	
	
	$.post(
		SITE_NAME + '/stat/k_visit_stat_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {
				
					bindStatisticsVisitStatusDailyChart('chartVisitDay',data);
					
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

/*일별출입현황-차트 바인딩*/
function bindStatisticsVisitStatusDailyChart(chart_id,data){
	
	var data_value = data.result.data_value;

	var data_label = data.result.data_label;

	var data_link = data.result.link;

	var barChartData = {
		labels: data_label,
		datasets: [],
	};

	var color = Chart.helpers.color;

	var arr1 = {
		label: bringouttext[lang_code],
		backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
		borderColor: '#fecd6e',
		borderWidth: 1,
		data: data_value,
		data_label: data_label,
		data_link: data_link,
	};

	barChartData['datasets'].push(arr1);

	bindBarChart(chart_id, barChartData);

	//chart datatable
	var $_charttable = $('#'+chart_id+'_DataTable');

	if ($_charttable.length > 0) {
		$_charttable.html(ChartdataToTable(barChartData));
	}

}

/*월별출입현황*/
function loadStatisticsVisitStatusMonthlyChart() {
	
	$.post(
		SITE_NAME + '/stat/k_visit_stat_month_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {

					var data_value = data.result.data_value;

					var data_label = data.result.data_label;

					var data_link = data.result.link;

					var barChartData = {
						labels: data_label,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: bringouttext[lang_code],
						backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
						borderColor: '#fecd6e',
						borderWidth: 1,
						data: data_value,
						data_label: data_label,
						data_link: data_link,
					};

					barChartData['datasets'].push(arr1);

					bindBarChart('chartVisitMonth', barChartData);

					//chart datatable
					var $_charttable = $('#chartVisitMonth_DataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

/*일별점검현황*/
function loadStatisticsVcsStatusDailyChart() {
	
	$.post(
		SITE_NAME + '/stat/k_vcs_stat_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {

				bindStatisticsVcsStatusDailyChart('chartVcsDay',data);

			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

//점검결과(보안취약점,악성코드)현황
function loadStatisticsVcsResultChart(){

	$.post(
		SITE_NAME + '/stat/k_vcs_result_stat_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {

				var arr_virus_data = data.result.virus_data;
				var arr_weak_data = data.result.weak_data;

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var colors = null;
					var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				bindDoughnutChart('chartPcCheckWEAK', weakChartData);

			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

//일별 점검현황 차트 바인딩
function bindStatisticsVcsStatusDailyChart(chart_id,data){

	var data_value = data.result.data_value;

	var data_label = data.result.data_label;

	var data_link = data.result.link;

		var barChartData = {
			labels: data_label,
			datasets: [],
		};

		var color = Chart.helpers.color;

		var arr1 = {
			label: scantimestext[lang_code],
			backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
			borderColor: '#fecd6e',
			borderWidth: 1,
			data: data_value,
			data_label: data_label,
			data_link: data_link,
	};


	barChartData['datasets'].push(arr1);
	bindBarChart('chartVcsDay', barChartData);

	//chart datatable
	var $_charttable = $('#chartVcsDay_DataTable');

	if ($_charttable.length > 0) {
		$_charttable.html(ChartdataToTable(barChartData));
	}

	
}

/*월별점검현황*/
function loadStatisticsVcsStatusMonthlyChart() {
	
	$.post(
		SITE_NAME + '/stat/k_vcs_stat_month_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {

					var data_value = data.result.data_value;

					var data_label = data.result.data_label;

				var data_link = data.result.link;
				
				var arr_virus_data = data.result.virus_data;
				var arr_weak_data = data.result.weak_data;

					var barChartData = {
						labels: data_label,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: scantimestext[lang_code],
						backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
						borderColor: '#fecd6e',
						borderWidth: 1,
						data: data_value,
						data_label: data_label,
						data_link: data_link,
					};

				barChartData['datasets'].push(arr1);
				
				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var colors = null;
					var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindBarChart('chartVcsMonth', barChartData);
				bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				bindDoughnutChart('chartPcCheckWEAK', weakChartData);

				//chart datatable
				var $_charttable = $('#chartVcsMonth_DataTable');

				if ($_charttable.length > 0) {
					$_charttable.html(ChartdataToTable(barChartData));
				}

			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

/*일별대여현황*/
function loadStatisticsRentalStatusDailyChart() {
	
	$.post(
		SITE_NAME + '/stat/k_rental_stat_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {
					
					var color = Chart.helpers.color;
					var barChartData = {
						labels: data_label,
						datasets: [],
					};

					var color = dynamicChartColors(window.chartColors, 0.5, data.result.length);
					//console.log(color);

					for(var i = 0 ; i < data.result.length ; i++){
						
						var label = data.result[i].label;
						var item_data = data.result[i].dataset;

						var data_value = item_data.data_value;
						var data_label = item_data.data_label;
						var data_link = item_data.link;

						var arr = {
							label: label,
							backgroundColor: color[i],
							borderColor: color[i],
							borderWidth: 1,
							data: data_value,
							data_label: data_label,
							data_link: data_link,
						};

						barChartData['datasets'].push(arr);
					}

					barChartData['labels'] = data_label;

					//console.log(barChartData['datasets']);

					bindBarChart('chartRentalDay', barChartData);

					//chart datatable
					var $_charttable = $('#chartRentalDay_DataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

/*월별대여현황*/
function loadStatisticsRentalStatusMonthlyChart() {
	
	$.post(
		SITE_NAME + '/stat/k_rental_stat_month_process.php',
		$("#searchForm").serialize(),
		function (data) {
			if (data.status) {
					
					var color = Chart.helpers.color;
					var barChartData = {
						labels: data_label,
						datasets: [],
					};

					var color = dynamicChartColors(window.chartColors, 0.5, data.result.length);
					//console.log(color);

					for(var i = 0 ; i < data.result.length ; i++){
						
						var label = data.result[i].label;
						var item_data = data.result[i].dataset;

						var data_value = item_data.data_value;
						var data_label = item_data.data_label;
						var data_link = item_data.link;

						var arr = {
							label: label,
							backgroundColor: color[i],
							borderColor: color[i],
							borderWidth: 1,
							data: data_value,
							data_label: data_label,
							data_link: data_link,
						};

						barChartData['datasets'].push(arr);
					}

					barChartData['labels'] = data_label;

					//console.log(barChartData['datasets']);

					bindBarChart('chartRentalMonth', barChartData);

					//chart datatable
					var $_charttable = $('#chartRentalMonth_DataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}
function setUpdatePatchDate(){
	var patch_dt_div = $("#patch_dt_div").val();

	if(patch_dt_div=="fix"){
		$("#set_patch_time").hide();
		$("#set_patch_date").show();
	}else{
		$("#set_patch_time").show();
		$("#set_patch_date").hide();
	}
}

function changeAppFileType(id) {
	var target = window.event.target;
	var accept = $(target).find("option:selected").data("file-accept");

	$(`#${id}`).prop("accept", accept)
}
function checkAppUpdateFile(id) {
	var target = window.event.target;
	var files = $(target).prop('files');
	var file_name = $(`#${id}`).find("option:selected").data("file-name");

	if (files.length > 0) {
		if (file_name != files[0].name) {
			$(target).val('');
			alert(wrongFileSelected[lang_code]);
		}
	}
}

function showHiddenInfo(id,str){
	
	var hidden_value = $("#"+id).val();

	var target = window.event.target;

	var class_name = $(target).attr('class');
	
	$(target).removeClass();
	var visible=false;
	if(class_name =="fa fa-eye-slash"){	
		$(target).addClass("fa fa-eye");
		visible = true;
	}else{
		$(target).addClass("fa fa-eye-slash");
		visible = false;
	}

	if(visible){
		$.post(
			SITE_NAME + '/common/decrypt.php',
			{"enc" : str},
			function (data) {
				$("#"+id).attr("original-data",hidden_value);
				$("#"+id).val(data);
			},
			'text'
		);
	}else{
		hidden_value = $("#"+id).attr("original-data");
		$("#"+id).val(hidden_value);
	}

}
function execKabangEmpSync(){

	$('#viewLoading').css('position', 'absolute');
	$('#viewLoading').css('margin', '2px');
	$('#viewLoading').css('left', $('#btn_sync').offset().left);
	$('#viewLoading').css('top', $('#btn_sync').offset().top);
	$('#viewLoading').css('width', $('#btn_sync').css('width'));
	$('#viewLoading').css('height', $('#btn_sync').css('height'));
	$('#viewLoading').fadeIn(500);

	if (doubleSubmitCheck()) {
		alert(processingtext[lang_code]);	//처리중입니다.
		return;
	}

	$.ajax({
		type: 'POST',
		url: SITE_NAME + '/manage/kabang_emp_sync.php',
		dataType: 'JSON',
		success: function (response) {
			try {
				if(response.result=="success"){
					alert(successtext[lang_code]);
				}else{
					alert(failtext[lang_code]);
				}
			} catch (error) {
				doubleSubmitFlag = false;
				$('#viewLoading').fadeOut(500);
			} finally {
               doubleSubmitFlag = false;
			   $('#viewLoading').fadeOut(500);
            }
		},
		error: function (xhr, status, error) {
			alert(failtext[lang_code]);
			doubleSubmitFlag = false;
			$('#viewLoading').fadeOut(500);
		},
	});
}
function callQueryResult(){

	var target = window.event.target;
	var param_enc = $(target).attr("data-param-enc");

	var url = SITE_NAME+'/manage/custom_query.php?enc='+param_enc;
	sendPostForm(url);
}

//리포트 - 일별 출입통계
function LoadReportVisitStat(){

	var start_date = $("#printdate1").val();
	var end_date = $("#printdate2").val();
	var scan_center_code = $("#scan_center_code").val();
	var mode = $("#mode").val();

	$.post(
		SITE_NAME + '/stat/k_visit_stat_process.php',
		{
			"start_date" : start_date
			,"end_date" : end_date
			,"scan_center_code" : scan_center_code
		},
		function (data) {
			if (data.status) {
					bindStatisticsVisitStatusDailyChart('chartVisitDay',data);
			} else {
				alert(data.msg);
			} 
		},
		'json'
	);
	
}

//리포트 - 일별 점검통계
function LoadReportVcsStat(){

	var start_date = $("#printdate1").val();
	var end_date = $("#printdate2").val();
	var scan_center_code = $("#scan_center_code").val();
	var mode = $("#mode").val();

	$.post(
		SITE_NAME + '/stat/k_vcs_stat_process.php',
		{
			"start_date" : start_date
			,"end_date" : end_date
			,"scan_center_code" : scan_center_code
		},
		function (data) {
			if (data.status) {

				var callback;
					if(mode=="print") {
						callback = function(){
							renderImage('DAILY_VCS_STAT');
						}
					}

				bindStatisticsVcsStatusDailyChart('chartVcsDay',data,callback);
			} else {
				alert(data.msg);
			} 
		},
		'json'
	);

}

//리포트 - 점검결과통계
function LoadReporVcsResultStat(){

	var start_date = $("#printdate1").val();
	var end_date = $("#printdate2").val();
	var scan_center_code = $("#scan_center_code").val();
	var mode = $("#mode").val();

	$.post(
		SITE_NAME + '/stat/k_vcs_result_stat_process.php',
		{
			"start_date" : start_date,
			"end_date" : end_date,
			"scan_center_code" : scan_center_code
		},
		function (data) {
			if (data.status) {

				var arr_virus_data = data.result.virus_data;
				var arr_weak_data = data.result.weak_data;

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var colors = null;
					var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindDoughnutChart('chartVcsVirus', virusChartData);
				bindDoughnutChart('chartVcsBadExt', weakChartData);

				var callback;
				if(mode=="print") {
					callback = function(){
						renderImage('VCS_RESULT_STAT');
					}
				}

			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function Report2ExcelDown(flag){

	if(checkDateDiff($("#printdate1").val(),$("#printdate2").val(),31)==false){
		return false;
	}

	if(flag != "") {

		$('#viewLoading').css('position', 'absolute');
		$('#viewLoading').css('margin', '2px');
		$('#viewLoading').css('left', $('#'+flag).offset().left);
		$('#viewLoading').css('top', $('#'+flag).offset().top);
		$('#viewLoading').css('width', $('#'+flag).css('width'));
		$('#viewLoading').css('height', $('#'+flag).css('height'));
		$('#viewLoading').fadeIn(500);
	}
	
	var frm = document.frmPrint;

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);

	frm.method = "POST";
	frm.action = SITE_NAME+"/stat/k_report_excel.php";
	frm.submit();

	if(flag != "") {
		$('#viewLoading').fadeOut(500);
	}

}

function renderImage(name){

	var checked = $("#OPT_"+name).is(":checked");
	html2canvas(document.getElementById(name+"_wrap"), {
		onrendered: function (canvas) {
			var data = canvas.toDataURL('image/png');
			alert(data);
			//$("#"+name+"_IMG_DATA").val(data);
			$("#"+name+"_wrap").html("<img src='"+data+"'>");
		}     
	});

}

function checkDateDiff(dt1,dt2,max_days){

	var start = new Date(dt1); 
	var end = new Date(dt2);

	var diffDate = (end - start) / (1000 * 60 * 60 * 24);
	var days = Math.round(diffDate)+1;

	if(max_days >= days){
		return true;
	}else {
		var msg = limtmaxdays[lang_code].replace("{#}",max_days);
		alert(msg);
		return false;
	}
}

function submitReport() {

	if(checkDateDiff($("#printdate1").val(),$("#printdate2").val(),31)==false){
		return false;
	}

	var proc_name = getProcName();

	$("#proc_name").val(proc_name);

	var frm = document.frmPrint;

	frm.action = SITE_NAME + '/stat/k_report.php';
	frm.target = '';
	frm.method = 'post';
	frm.submit();

}
function printReport(){

	if(checkDateDiff($("#printdate1").val(),$("#printdate2").val(),31)==false){
		return false;
	}

	var proc_name = getProcName();

	$("#proc_name").val(proc_name);

	$("#mode").val('print');

	var popup = window.open(
		'about:blank',
		'ReportPrint',
		'width=1280,height=1000,resizable=no, scrollbars=yes, status=no;'
	);
	var frm = document.frmPrint;
	frm.action = SITE_NAME + '/stat/k_report_print.php';
	//frm.action = SITE_NAME + '/stat/k_report_image_print.php';
	frm.target = 'ReportPrint';
	frm.method = 'post';
	frm.submit();

}

function loadReport(){

	$("#mode").val('preview');

	var printdate1 = $("#printdate1").val();
	var printdate2 = $("#printdate2").val();
	var scan_center_code = $("#scan_center_code").val();
	var param_enc = ParamEnCoding('start_date='+printdate1+'&end_date='+printdate2+'&scan_center_code='+scan_center_code);

	//일별 출입통계
	if(document.all.OPT_DAILY_VISIT_STAT.checked) {
		LoadReportVisitStat();
	}

	//출입내역
	if(document.all.OPT_VISIT_LIST.checked) {
		LoadPageDataList('visit_list',SITE_NAME+'/stat/get_report_visit_list.php',"enc="+param_enc);
	}

	//일별 점검통계
	if(document.all.OPT_DAILY_VCS_STAT.checked) {
		LoadReportVcsStat();
	}

	//점검내역
	if(document.all.OPT_VCS_LIST.checked) {
		LoadPageDataList('vcs_list',SITE_NAME+'/stat/get_report_vist_vcs_list.php',"enc="+param_enc);
	}

	//점검결과통계
	if(document.all.OPT_VCS_RESULT_STAT.checked) {
		LoadReporVcsResultStat();
	}

	//위변조의심내역
	if(document.all.OPT_BAD_FILE_LIST.checked) {
		LoadPageDataList('bad_file_list',SITE_NAME+'/stat/get_report_visit_bad_file_list.php',"enc="+param_enc);
	}

	//악성코드내역
	if(document.all.OPT_VIRUS_FILE_LIST.checked) {
		LoadPageDataList('virus_file_list',SITE_NAME+'/stat/get_report_visit_virus_file_list.php',"enc="+param_enc);
	}	
}

//입실처리
function procVisitIn(){
	
	var proc = "VISIT_IN_PROC";

	if(!confirm(requestVisitIn[lang_code])) return false;

	procVisitInout(proc);
}
//입실처리취소
function cancelVisitIn(){
	
	var proc = "VISIT_IN_PROC_CANCEL";

	if(!confirm(requestCancelVisitIn[lang_code])) return false;

	procVisitInout(proc);
}
//퇴실처리
function procVisitOut(){
	
	var proc = "VISIT_OUT_PROC";

	if(!confirm(requestVisitOut[lang_code])) return false;

	procVisitInout(proc);
}
//퇴실처리취소
function cancelVisitOut(){
	
	var proc = "VISIT_OUT_PROC_CANCEL";

	if(!confirm(requestCancelVisitOut[lang_code])) return false;

	procVisitInout(proc);
}

/*입/퇴실처리*/
function procVisitInout(proc){

	var target = window.event.target;
	var v_user_list_seq = $(target).attr("data-seq");

	var proc_name = getProcName();

	$.post(
		SITE_NAME + '/user/access_inout_process.php',
		{
			"proc" : proc
			,"proc_name" : proc_name
			,"v_user_list_seq" : v_user_list_seq
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload(true);
			}
			
		},
		'json'
	);

}
//정보보호서약서동의서보기
function popSecurityAgree() {

	var target = window.event.target;
	var v_user_list_seq = $(target).attr("data-seq");

	$.post(
		SITE_NAME + '/user/pop_security_agree.php',
		{
			'v_user_list_seq' : v_user_list_seq
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}
function printPage(area_id){
	var initBody;
	 window.onbeforeprint = function(){
	  initBody = document.body.innerHTML;
	  document.body.innerHTML =  document.getElementById(area_id).innerHTML;
	 };
	 window.onafterprint = function(){
	  document.body.innerHTML = initBody;
	 };
	 window.print();
	 return false;
}

function toggleAppUpdate(){

	var target = window.event.target;
	var kiosk_key = $(target).attr("data-kiosk-key");
	var kiosk_name = $(target).attr("title");

	$(".cirlce_box a").removeClass("on");
	$(target).addClass("on");
	$("#vaccine_update_title").text("("+kiosk_name+")");


	$("#file_app_update_list table").hide();
	$("#tbl_"+kiosk_key).show();
}

function deleteVisitInfo(){

	if(!confirm(qdeleteconfirm[lang_code])) return false;

	var proc_name = getProcName();
	$("#frmMemo input[name='proc_name']").val(proc_name);
	$("#frmMemo input[name='proc']").val('DELETE');

	$.post(
		'access_info_idc_process.php',
		$('#frmMemo').serialize(),
		function (data) {
			alert(data.msg);
			if(data.status){
				location.href = SITE_NAME+"/user/access_control_idc.php";
			}
		},
		'json'
	);
	
}
//임직원리스트 세부권한 일괄설정 팝업
function popAdminListPageAuth(callback){

	if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

	var emp_seq_list = $("#emp_seq_list").val();
	var admin_level = $("#admin_level").val();

	$("#modal_admin_auth_detail").show();
	$("#modal_admin_auth_detail .btn_wrap").show();
	$("#btnSaveDetailAuth").removeAttr("onclick");
	$("#btnSaveDetailAuth").attr("onclick"," return frmAdminAuthDetailsSubmit("+callback+")");


	$.post(
		SITE_NAME + '/manage/kabang_emp_list_reg_auth.php',
		{
			"emp_seq_list" : emp_seq_list
			,"admin_level" : admin_level
		},
		function (data) {
			$("#menu_auth_detail").html(data);
			controllPageExecAuth();
		},
		'text'
	);
	return false;
}
//세부권한설정 팝업
function popAdminPageAuth(callback){

	var target = window.event.target;
	var emp_seq = $(target).attr("data-emp-seq");

	if(emp_seq=="" || emp_seq==undefined){
		emp_seq = $("#emp_seq").val();
	}

	if(emp_seq =="" || emp_seq=="0"){
		alert(noEmpInfo[lang_code]);
		return false;
	}

	var admin_level = $("#admin_level").val();


	if(callback==undefined){
		var callback = function(){
			location.reload();
		}
	}

	$("#modal_admin_auth_detail").show();
	$("#modal_admin_auth_detail .btn_wrap").show();
	
	$("#btnSave").removeAttr("onclick");
	$("#btnSave").attr("onclick"," return frmAdminAuthDetailsSubmit("+callback+")");
	

	$.post(
		SITE_NAME + '/manage/admin_reg_auth.php',
		{
			"emp_seq" : emp_seq
			,"admin_level" : admin_level
		},
		function (data) {
			$("#menu_auth_detail").html(data);
			controllPageExecAuth();
		},
		'text'
	);
	return false;
}
//세부메뉴권한 설정 전송
function frmAdminAuthDetailsSubmit(callback){

	var proc_name = getProcName();
	$("#frmAuthDetails input[name='proc_name']").val(proc_name);
	$("#frmAuthDetails input[name='proc']").val("CREATE");

	$.post(
		SITE_NAME + '/manage/admin_reg_auth_process.php',
		$('#frmAuthDetails').serialize(),
		function (data) {
			alert(data.msg);
			if(data.status){
				$("#modal_admin_auth_detail").hide();
				if (typeof callback === 'function') {
					callback();
				}
			};
		},
		'json'
	);
}
//세부권한설정 - 페이지 체크
function setPageExecAuthAll(){

	var target = window.event.target;
	var menu_code = $(target).attr("data-menu-code");
	var page_code = $(target).val();
	var menu_page_code = menu_code+"_"+page_code;
	var checked =$(target).is(":checked");

	if(page_code=="all"){
		//전체 체크외 모두 선택 해제
		$(".mcode_"+menu_code).each(function(){
			if($(this).attr("data-page-code")=="all"){
				$(this).prop("checked",checked);
			}else{
				$(this).prop("checked",false);
			}
		})
	}else{
		//전체체크 선택 해제
		$(".mcode_"+menu_code).each(function(){
			if($(this).attr("data-page-code")=="all"){
				$(this).prop("checked",false);
				return;
			}
		})
	}
	
	//실행권한 체크
	$(".crud_"+menu_page_code).prop("checked",checked);
}

//세부권한설정 - 페이지 실행권한 체크
function setPageExecAuth(){

	var target = window.event.target;
	var menu_code = $(target).attr("data-menu-code");
	var page_code = $(target).attr("data-page-code");
	var checked =$(target).is(":checked");

	if(checked){
		$("#page_auth_"+menu_code+"_"+page_code).prop("checked",true);
		if(page_code=="all"){
			//전체 체크외 모두 선택 해제
			$(".mcode_"+menu_code).each(function(){
				if($(this).attr("data-page-code")!="all"){
					$(this).prop("checked",false);
				}
			})
		}else{
			//전체체크 선택 해제
			$(".mcode_"+menu_code).each(function(){
				if($(this).attr("data-page-code")=="all"){
					$(this).prop("checked",false);
					return;
				}
			})
		}
	}
}
/*정책설정 객체 이벤트 바인딩*/
function bindPolicyEvent(){

	//파일검사 적용	선택
	$("#file_scan_yn").on("change",function(){
		if($(this).val()=="Y"){
			$("#file_scan_device_wrap").show();
		}else{
			$("#file_scan_device_wrap").hide();
		}
	})

	//저장매체 반입파일 전송 선택
	$("#checkin_file_send_type").on("change",function(){
		if($(this).val()=="N"){
			$("#checkin_file_send_type_wrap").hide();
		}else{
			$("#checkin_file_send_type_wrap").show();
		}
	})
	
	//저파일검사 적용 장치 체크
	$("input[name='file_scan_device[]']").on("click",function(){
		if(this.checked && this.value=="ALL"){
			$("input[name='file_scan_device[]'][value!='ALL']").prop("checked",false);
		}else{
			$("input[name='file_scan_device[]'][value='ALL']").prop("checked",false);
		}
	})

	//저장매체 반입파일 전송 장치 체크
	$("input[name='checkin_file_send_device[]']").on("click",function(){
		if(this.checked && this.value=="ALL"){
			$("input[name='checkin_file_send_device[]'][value!='ALL']").prop("checked",false);
		}else{
			$("input[name='checkin_file_send_device[]'][value='ALL']").prop("checked",false);
		}
	})
}



	// IDC 문서관리 문서 설정
	function setDocumentConfig(formDiv) {
		try {
			let clsid = formDiv.toLowerCase();
			let target = $(`.clsid_${clsid}`);

			$("#page_title small").text(target.text());
			$(".tab li").removeClass("on");
			$("#form_div").val(formDiv);
			$(`.clsid_${clsid}`).addClass("on");

			if (clsid == "mgr_idc_report") {
				$(".report_checklist").show();
				$('.report_input_type option').each((index, item) => {
					if($(item).val() == 'CHECKLIST'){
						$(item).show()
					}else{
						$(item).hide()
					}
				});
				$('.report_input_type option:first').show()
			} else {
				$(".report_checklist").hide();
				$('.report_input_type option').each((index, item) => {
					if($(item).val() == 'CHECKLIST'){
						$(item).hide()
					}else{
						$(item).show()
					}
				});
			}

			getDocumentContent();
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 내용 가져오기
	function getDocumentContent() {
		try {
			let formDiv = $("#form_div").val();

			$.post(
				SITE_NAME + '/manage/get_document_content.php',
				{
					"form_div": formDiv
				},
				(data) => {
					if (data.status) {
						if (data.result != null) {
							let formSeq = data.result['form_seq']
							let formTitle = data.result['form_title'];
							let formContent = data.result['form_content'];
							let useYN = data.result['use_yn'];

							$("#form_seq").val(formSeq);
							$("#form_title").val(formTitle);
							$(`input[name='use_yn'][value='${useYN}']`).prop("checked", true);

							try{
								let contents = JSON.parse(formContent);

								buildDocumentRows('task', contents.tasks)
								buildDocumentRows('item', contents.lists)
							}catch(e){
								e=null
							}
						}
					}
				},
				'json'
			);
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 내용 저장하기
	function saveDocumentContent() {
		const toBase64String = (content) => {
			return btoa(unescape(encodeURIComponent(content)))
		}

		try {
			let formDiv = $("#form_div").val();
			let tasks = $(`#tblDocumentTasks tr.report_item`)
			let items = $(`#tblDocumentItems tr.report_item`)
			let contents = {
				tasks: [],
				lists: []
			}

			if (formDiv == "MGR_IDC_REPORT") {
				tasks.each((index, item) => {
					let row = $(item);
					let text = row.find('.report_input_item');

					if ((text.val() == "")) {
						text.addClass("warning-border");
						alert(qcontentinput[lang_code]);
						return false;
					} else {
						text.removeClass("warning-border");
					}

					contents.tasks.push(text.val())
				})

				if (tasks.length != contents.tasks.length) {
					return false;
				}
			}

			items.each((index, item) => {
				let row = $(item);
				let text = row.find('.report_input_item');
				let type = row.find('.report_input_type');

				if ((text.val() == "")) {
					text.addClass("warning-border");
					alert(qcontentinput[lang_code]);
					return false;
				} else {
					text.removeClass("warning-border");
				}

				if ((type.val() == "")) {
					alert(choosetypememu[lang_code]);
					type.addClass("warning-text");
					return false;
				} else {
					text.removeClass("warning-text");
				}

				contents.lists.push({
					"text": text.val(),
					"type": type.val()
				})
			})

			if (items.length != contents.lists.length) {
				return false;
			}

			//base64 encoding해서 데이터 전송
			let procName = toBase64String(getProcName());
			let encTitle = toBase64String($("#form_title").val());
			let ecnContent = toBase64String(JSON.stringify(contents));

			$("#form_title").attr("disabled", true);
			$("#form_title_enc").val(encTitle);
			$("#form_content_enc").val(ecnContent);
			$("#proc_name").val(procName);
			$("#proc").val("UPDATE");

			let frmData = $("#frmDocument").serialize();

			$("#form_title").attr("disabled", false);

			$.post(
				SITE_NAME + '/manage/document_process.php',
				frmData,
				function (data) {
					if (data.status) {
						$("#form_seq").val(data.result);
					}
					alert(data.msg);
				},
				'json'
			);
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 내용 폼 생성
	function buildDocumentRows(type, contents) { 
		try { 
			let table = (type == 'task') ? $("#tblDocumentTasks") : $("#tblDocumentItems")
			let items = table.find(`tr.report_item`);
			let first = items.first().html();

			items.each((index, item) => {
				let row = $(item);
				if (index > 0) {
					row.remove();
				} else {
					resetDocumentRow(row);
				}
			})

			contents.forEach((content, index) => {
				let items = table.find(`tr.report_item`)
				let row = null;

				try {
					if (index == 0) {
						row = items.first()
					} else {
						row = $(`<tr>${first}</tr>`).addClass("report_item");
						items.last().after(row);
					}

					if (type == "task") {
						row.find(".report_input_item").val(content);
					} else {
						row.find(".report_input_item").val(content.text);
						row.find(".report_input_type").val(content.type);
					}
				} catch(e) {
					console.log(e);
				}
			});
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 입력폼 추가
	function addendDocumentRow() {
		try {
			let target = $(window.event.target);
			let table = target.closest("table");
			let items = table.find(`tr.report_item`);

			let content = items.first().html();
			let rowAdded = $(`<tr>${content}</tr>`).addClass("report_item");

			items.last().after(rowAdded);

			//초기화
			resetDocumentRow(rowAdded);
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 입력폼 초기화
	function resetDocumentRow(row) {
		try {
			$(row).find("input[type='hidden']").val('');
			$(row).find("input[type='text']").val('');
			$(row).find("input[type='checkbox']").prop("checked", false);
			$(row).find("input[type='text']").removeClass('warning-border');
			$(row).find("label").removeClass('warning-text');
			$(row).find(".type_default_checklist").val("CHECKLIST");
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 입력폼 삭제
	function removeDocumentRow() {
		try {
			let target = $(window.event.target);
			let table = target.closest("table");
			let row = target.closest("tr");
			let items = table.find(`tr.report_item`);

			if (items.length > 1) {
				row.remove();
			} else {
				resetDocumentRow(row);
			}
		} catch (e) {
			console.log(e)
		}
	}
	
	// IDC 문서관리 점검항목 등록
	function setDocumentChecklist() {
		try {
			const frm = $("#frmChecklist")

			frm.find("#code_seq").val("");
			frm.find("#code_name").val("");
			frm.find("#code_sort").val("");
			frm.find("#code_use_yn").val("Y").prop("selected", true);

			$("#modal_idc_report_checklist").show();
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 점검항목 수정
	function modifyDocumentChecklist() {
		try {
			let target = $(window.event.target);
			let row = target.closest("tr");
			let codeSeq = row.find(".code_seq").val();
			let codeName = row.find(".code_name").val();
			let sort = row.find(".sort").val();
			let useYN = row.find(".use_yn").val();

			const frm = $("#frmChecklist")

			frm.find("#code_seq").val(codeSeq);
			frm.find("#code_name").val(codeName);
			frm.find("#code_sort").val(sort);
			frm.find("#code_use_yn").val(useYN).prop("selected", true);

			$("#modal_idc_report_checklist").show();
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 점검항목 삭제
	function deleteDocumentChecklist(codeSeq, codeKey) {
		try {
			if (confirm(qdeleteconfirm[lang_code])) {
				let procName = "문서관리 점검항목 - 삭제";
				let procExec = "DELETE";
				let formData = {
					"code_seq": codeSeq,
					"code_key": codeKey,
					"proc_exec": procExec,
					"proc_name": procName
				}

				$.post(
					SITE_NAME + '/manage/document_code_process.php',
					formData,
					successDocumentChecklist,
					'json'
				);
			}
		} catch (e) {
			console.log(e)
		}

		return false;
	}

	// IDC 문서관리 점검항목 저장하기
	function saveDocumentChecklist() {
		try {
			const frm = $("#frmChecklist")
			let codeSeq = frm.find("#code_seq")
			let codeName = frm.find("#code_name")
			let sort = frm.find("#sort")

			let procName = getProcName();
			let procExec = (codeSeq.val()) ? "UPDATE":"CREATE"

			if ((codeName.val() == "")) {
				codeName.addClass("warning-border");
				alert(qcontentinput[lang_code]);	// 내용을 입력하세요.
				return false;
			}

			if ((sort.val() == "")) {
				sort.addClass("warning-border");
				alert(qsortinput[lang_code]);	// 정렬번호를 입력하세요
				return false;
			}

			frm.find("#checklist_proc_name").val(procName);
			frm.find("#checklist_proc_exec").val(procExec);

			let formData = $("#frmChecklist").serialize();

			$.post(
				SITE_NAME + '/manage/document_code_process.php',
				formData,
				successDocumentChecklist,
				'json'
			);
		} catch (e) {
			console.log(e)
		}
	}

	// IDC 문서관리 점검항목 변경 완료시 처리
	function successDocumentChecklist(data) {
		alert(data.msg);
		if (data.status) {
			try{
				$("#modal_idc_report_checklist").hide();
				let formDiv = $("#frmDocument #form_div").val();
				window.location.href=`document_list.php?div=${formDiv}`;
			} catch (e) {
				console.log(e)
			}
		}
	}
	
	//코드분류변경이벤트
	function changeCodeKey(){
		let target = $(window.event.target);
		var code_key = $(target).val();

		if(code_key=='IDC_CENTER'){
			$("#wrap_scan_center_code").show();
		}else{
			$("#wrap_scan_center_code").hide();
		}
	}