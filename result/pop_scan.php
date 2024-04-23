<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$checkinout_flag = $_REQUEST['checkinout_flag'];

if($checkinout_flag=="") $checkinout_flag = "N";


?>
<script language="javascript">
	
	$("document").ready(function(){

		$(document).keyup(function(e) {
			 if (e.keyCode == 27) { //esc
				fullScreenButtonToggle();
			}
		});

		var elem = window.top.document;

		elem.onfullscreenchange = function(event){fullScreenButtonToggle();}
		elem.onwebkitfullscreenchange = function(event){fullScreenButtonToggle();}
		elem.onmozfullscreenchange = function(event){fullScreenButtonToggle();}
		elem.onMSFullscreenChange = function(event){fullScreenButtonToggle();}

		$("#barcode").focus(function(){
			
			var _event = jQuery._data($("#barcode").get(0), "events")["focusout"];
			
			<? /*바코드확인로그보기 버튼 클릭하면 팝업창에 focus 맞추기 위해 focusout 이벤트 삭제 -> 팝업종료후 다시 barcode 입력창에 focus 고정처리*/ ?>
			if(typeof _event=='undefined'){	

				$('#barcode').bind("focusout",function(){
					$("#barcode").focus();
				});
			}

		});

		window.onresize = function(event) {
			popScanResize();
		};

		LoadScanVcsInfo('');
		LoadDiskInfo('');
		LoadVaccineInfo('');
		
	});
</script>
<div id="mark">
	<div class="content">
		<div name='scanheader'>
			<div class='tit'>
				<div class='txt'><?=$_LANG_TEXT["barcodescantext"][$lang_code];?></div>
				<div class='right'>
					<span id='fullscreen' >
						<a href="javascript:" name='requestfullscreen' onclick="popScanToggleFullScreen()"><div id='fullscreenbutton' class="prev_page"><span name='exit' style='display:none'>Exit</span> <span>FullScreen</span></div></a>
					</span>
					<div class='close' onClick="ClosepopScanContent();"></div>
				</div>
			</div>
			<!--바코드 입력창-->
			<div class='scan_box'>
				<span name='scan_box_title' class="label1 <?=($checkinout_flag=="Y")?"checkin":"search"?>">
					Barcode 
					<span id='barcode_search_txt' <?if($checkinout_flag=="Y"){ echo "style='display:none'";}?>><?=$_LANG_TEXT['searchtext'][$lang_code]?></span>
					<span id='barcode_checkin_txt' <?if($checkinout_flag=="N"){ echo "style='display:none'";}?>><?=$_LANG_TEXT['inouttext'][$lang_code]?></span>
				</span>
				<div class="field">
					<input type="text" name="barcode" id="barcode" class="frm_input" onkeydown="ScanBarcode(this);" style="ime-mode:disabled;" maxlength='20'><img src="<?php echo $_www_server; ?>/images/scann_box_icon.png">
				</div>
				<div class='barcode'> Barcode : <span id='str_barcode'><?=$barcode?></span></div>
				<div class='btn_wrap' onClick="event.stopPropagation();">
					<input type="hidden" id="checkinout_flag" name="checkinout_flag" value="<?=$checkinout_flag?>">
				<?if($_ck_user_level=="SECURITOR_S"){?>
				<?}else{?>
					<span id='barcode_mode' class='btn_mode' onclick='BarcodeModeToggle();'>
						<div name='btn_mode' id='btnchangetocheckin' class='btn50' <?if($checkinout_flag=="Y"){echo "style='display:none'";}?>><?=$_LANG_TEXT['btnchangemodecheckinout'][$lang_code]?></div>
						<div name='btn_mode' id='btnchangetosearch' class='btn50' <?if($checkinout_flag=="N"){echo "style='display:none'";}?>><?=$_LANG_TEXT['btnchangemodesearch'][$lang_code]?></div>
					</span>
				<?}?>
					<a class='btn50' href="javascript:" onclick="popViewBarcodeLog()" ><?=$_LANG_TEXT['btncheckinscanlog'][$lang_code]?></a>
				</div>
				
			</div>
			<!--바코드 입력창//-->
		</div>

		<div class='wrapper2'>

			<!--점검결과-->
			<div id='scan_vcs_info' style='position:relative;'></div>
			<!--디스크정보-->
			<div class='tab_tit'><?=$_LANG_TEXT['diskinfotext'][$lang_code]?></div>
			<div id='disk_info'></div>
			<!--악성코드-->
			<div class='tab_tit'><?=$_LANG_TEXT['virustext'][$lang_code]?></div>
			<div id='vaccine_info'></div>

		</div><!--<div class='wrapper2'>-->

	</div><!--<div class="content">-->

</div><!--<div id="mark">-->
