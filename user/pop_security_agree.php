<?php
$_section_name = "pop_user_vcs_device";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_list_seq = $_REQUEST[v_user_list_seq];

$Model_User = new Model_User;
$args = array("v_user_list_seq"=>$v_user_list_seq);
$Model_User->SHOW_DEBUG_SQL = false;
$result = $Model_User->getUserSecurityAgreeInfo($args);

//동의서정보
if($result){
	while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$security_agree_yn = $row[security_agree_yn];
		
		if($security_agree_yn=='Y'){
			$v_purpose = $row[v_purpose];
			$v_user_name = aes_256_dec($row[v_user_name]);
			$v_user_belong = $row[v_user_belong];
			$security_agree_date = $row[security_agree_date];
			
			$agree_date = date_create($security_agree_date);
		}
	}
}

$Model_manage = new Model_manage;
$args = array("agree_div"=>"SECURITY","agree_lang"=>$lang_code);
$Model_manage->SHOW_DEBUG_SQL = false;
$result = $Model_manage->getAgreeContent($args);

//출력 css
$print_css['header'] = "margin:30px;text-align:center;font-size:23px;font-weight:bold;";
$print_css['content'] = "text-align:left;font-size:14px;line-height:21px;";
$print_css['bottom'] = "margin-top:30px;text-align:center;font-size:15px; line-height:43px;";
$print_css['tmpl_input'] = "width:100px;font-size:14px;border-bottom:1px solid #e1e1e1;margin:0px 2px;text-align:center;padding-bottom:5px;";

if($result){
	while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$request_consent_yn = $row['request_consent_yn'];

		$TITLE_HTML = str_replace("\n","<BR>",html_entity_decode($row['agree_title']));
		$CONTENTS_HTML = str_replace("\n","<BR>",html_entity_decode($row['agree_content']));
		$BOTTOM_HTML = str_replace("\n","<BR>",html_entity_decode($row['agree_bottom']));

		$SECURITY_AGREE = array("TITLE"=>$TITLE_HTML,"CONTENTS"=>$CONTENTS_HTML,"BOTTOM"=>$BOTTOM_HTML);
	}

	$TITLE_HTML_VISIT = $SECURITY_AGREE['TITLE'];
	$CONTENTS_HTML_VISIT = $SECURITY_AGREE['CONTENTS'];
	$CONTENTS_HTML_VISIT = str_replace("{#VISIT_PURPOSE}","<input type='text' id='agree_visit_purpose' name='agree_visit_purpose' style='".$print_css['tmpl_input']."'  value='".$v_purpose."'>",$CONTENTS_HTML_VISIT);
	//$BOTTOM_HTML_VISIT = $SECURITY_AGREE['BOTTOM'];
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#pop_security_agree .header").html(htmlspecialchars_decode("<?=$TITLE_HTML_VISIT?>"));
		$("#pop_security_agree .content").html(htmlspecialchars_decode("<?=$CONTENTS_HTML_VISIT?>"));
		
		//길이 자동조절
		$("#print_area input").each(function(idx){
			var width = $(this).textWidth();
			if(width > 0){
				$(this).width(width+20);
			}
		});
		$("#print_area input").on("focus",function(){
			$(this).css("background-color","#e7e7e7");
		});
		$("#print_area input").on("focusout",function(){
			$(this).css("background-color","#fff");
		});
	});
</script>
<div id="mark">
	<div class="content" style='width:1080px;max-height:900px;'>
		<div class='tit'>
			<div class='txt'><? echo trsLang('정보보호서약서','informationprotectionpledge');?></div>
			<div class='right'>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div id='print_area' class='wrapper2' >
			<div id='pop_security_agree' style='padding:0px 30px;width:980px'>
				<div class='header' style='<? echo $print_css['header']?>'><!--동의서제목--></div>
				<div class='content'  style='<? echo $print_css['content']?>'><!--동의서내용--></div>
				<div style='<? echo $print_css['bottom']?>'>
					<div><input type='text' id='agree_year' name='agree_year' style='<? echo $print_css['tmpl_input']?>' value='<?php echo date_format($agree_date, 'Y'); ?>' maxlength='4'>년 <input type='text' id='agree_month' style='<? echo $print_css['tmpl_input']?>' value='<?php echo date_format($agree_date, 'm'); ?>' maxlength='2' name='agree_month' >월 <input type='text' id='agree_day' style='<? echo $print_css['tmpl_input']?>' maxlength='2' value='<?php echo date_format($agree_date, 'd'); ?>' name='agree_day' >일</div>
					<div>
						<span>회 사 명 : <input type='text' id='agree_user_company' name='agree_user_company'  style='<? echo $print_css['tmpl_input']?>' value='<? echo $v_user_belong?>'></span>
						<span>성&nbsp;&nbsp;&nbsp;명 : <input type='text' id='agree_user_name'  name='agree_user_name'  style='<? echo $print_css['tmpl_input']?>' value='<? echo $v_user_name?>'></span>
					</div>
				</div>
			</div>
		</div>
		<div class="btn_wrap" style='margin-right:20px'>
			<div class='right'>
				<!--<a href="javascript:void(0)" class="btn" id=""><? echo trsLang('수정','btnupdate');?></a>&nbsp;-->
				<a href="javascript:void(0)" class="btn required-print-auth hide" onclick="printPage('print_area')"><? echo trsLang('인쇄','btnprint2');?></a>
			</div>
		</div>
	</div>
</div>
