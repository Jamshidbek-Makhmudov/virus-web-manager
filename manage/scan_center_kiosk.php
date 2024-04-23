<?
 if(!$wvcs_dbcon) return;

$Model_manage = new Model_manage();

$linkRowFormat = array("link_seq"=>"0", "link_name"=>"", "link_url"=>"");
$kioskRowFormat = array("kiosk_seq"=>"0", "kiosk_id"=> "", "kiosk_name"=>"", "kiosk_ip"=>"","kiosk_comment"=>"","kiosk_menu"=>"", "kiosk_link"=>array($linkRowFormat));

if($scan_center_code != ""){
	
	$args = array("search_sql"=> " and k.scan_center_code = '{$scan_center_code}' ");
	$result = $Model_manage->getScanCenterKioskLink($args);

	if($result){
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
		$linkRow = array(
				"link_seq"=>$row['kiosk_link_seq']
				, "link_name"=>$row['link_name']
				, "link_url"=>$row['link_url']
			);

			$kiosk_link[$row['kiosk_seq']][] = $linkRow;
		}
	}

	
	$Model_manage->SHOW_DEBUG_SQL= false;
	$args = array("search_sql"=> " and scan_center_code='".$scan_center_code."' ");
	$result = $Model_manage->getScanCenterKiosk($args);
	

	if($result){
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			$_kiosk_link = is_array($kiosk_link[$row['kiosk_seq']]) ? $kiosk_link[$row['kiosk_seq']] : array($linkRowFormat);
			$_kiosk_link_count =  is_array($kiosk_link[$row['kiosk_seq']]) ? sizeof($kiosk_link[$row['kiosk_seq']]) : 0;

			$kioskRow = array(
				"kiosk_seq"=>$row['kiosk_seq']
				, "kiosk_id"=>$row['kiosk_id']
				, "kiosk_name"=>$row['kiosk_name']
				, "kiosk_ip"=>$row['kiosk_ip_addr']
				,"kiosk_comment"=>$row['memo']
				,"kiosk_menu"=>$row['kiosk_menu']
				,"kiosk_link"=>$_kiosk_link
				,"kiosk_link_count"=> $_kiosk_link_count
			);

			$CenterKiosk[] = $kioskRow;

		}
	}

	if(is_array($CenterKiosk)==false) 	$CenterKiosk[] = $kioskRowFormat;

}else{
	
	$CenterKiosk[] = $kioskRowFormat;
}
?>

<div class="sub_tit"  style='line-height:30px;'> > KIOSK <? echo trsLang('설정','settingtext'); ?></div>
<FORM name='frmCenterKiosk' id='frmCenterKiosk'>
<input type='hidden' name='scan_center_code2' id='scan_center_code2'>
<input type='hidden' name='proc_name'>
<input type='hidden' name='proc'>
<div id='wrapper1' class="wrapper">
  <div id='div1' style='height:1px;'></div>
</div>
<div id='wrapper2' class="wrapper">
	<table class="list"  id='tblCenterKiosk' style="min-width:1400px;">
		<tr style='padding:5px;font-weight:bold'>
			<th style='width:30px;'><input type="checkbox" onclick="$('.cbx').prop('checked',this.checked)"></th>
			<th style='width:175px;'>KIOSK ID</th>
			<th style='width:175px;'>KIOSK <? echo trsLang('이름','nametext');?></th>
			<th style='width:145px;'>KIOSK IP</th>
			<th style='width:220px;'><? echo trsLang('설명','descriptiontext');?></th>
			<th style='width:550px;text-align:left;min-width:550px;'>KIOSK 메뉴</th>
			<th style='min-width:80px;text-align:left'><? echo trsLang('추가/삭제','addnremove');?></th>
		</tr>

		<?
		for($i = 0 ; $i < sizeof($CenterKiosk) ; $i++){
			$kioskInfo = $CenterKiosk[$i];

			$kiosk_seq = $kioskInfo['kiosk_seq'];
			$kiosk_id = $kioskInfo['kiosk_id'];
			$kiosk_name = $kioskInfo['kiosk_name'];
			$kiosk_ip = $kioskInfo['kiosk_ip'];
			$kiosk_comment = $kioskInfo['kiosk_comment'];
			$kiosk_menu = explode(",",$kioskInfo['kiosk_menu']);
			$kiosk_link = 	$kioskInfo['kiosk_link'];
			$kiosk_link_count = 	$kioskInfo['kiosk_link_count'];
			
			if($kiosk_link_count=="") $kiosk_link_count =0;

		?>
		<tr class='kiosk_row' style='padding:5px;'>
			<td>
				<input type="checkbox" name='kiosk_seq[]' class='cbx clsid_kiosk_seq' value='<? echo $kiosk_seq;?>'>
			</td>
			<td>
				<input type='text' class='frm_input check_valid_data clsid_kiosk_id ' name='kiosk_id[]'   placeholder="ID를 입력하세요" maxlength="10" style='width:150px;' value='<? echo $kiosk_id?>'>
			</td>
			<td>
				<input type='text' class='frm_input check_valid_data clsid_kiosk_name ' name='kiosk_name[]'   placeholder="이름을 입력하세요" maxlength="50" style='width:150px;' value='<? echo $kiosk_name?>'>
			</td>
			<td>
				<input type='text' class='frm_input check_valid_data clsid_kiosk_ip' name='kiosk_ip[]'  placeholder="IP를 입력하세요" maxlength="15" style='width:120px;' value='<? echo $kiosk_ip?>'>
			</td>
			<td>
				<input type='text' class='frm_input' placeholder="코멘트를 입력하세요" name='kiosk_memo[]' maxlength="50" style='width:200px;' value='<? echo $kiosk_comment?>'>
			</td>
			<td style='text-align:left;width:750px;'>
					<input type='hidden' name='kiosk_menu[]' class='clsid_center_kiosk_menu'>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_visit' value='VISIT' <? if(in_array('VISIT',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;' >출입관리</label>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_vcs' value='VCS'  <? if(in_array('VCS',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;'>파일반입</label>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_visit_idc' value='VISIT_IDC' <? if(in_array('VISIT_IDC',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;' >IDC 출입관리</label>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_vcs_idc' value='VCS_IDC'  <? if(in_array('VCS_IDC',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;'>IDC 파일반입</label>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_rent' value='RENT'  <? if(in_array('RENT',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;'>물품대여</label>
					<input type='checkbox' class='check_valid_data clsnm_service clsid_parking' value='PARKING'  <? if(in_array('PARKING',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;'>주차권지급</label>	
					<input type='checkbox' class='check_valid_data clsnm_service clsid_train' value='TRAIN'  <? if(in_array('TRAIN',$kiosk_menu)!==false) echo "checked" ?>> 
					<label for='' style='vertical-align:baseline;'>외부인 정보보호교육</label>	
					<input type='checkbox' class='check_valid_data  clsnm_service clsid_link'  value='LINK' <? if(in_array('LINK',$kiosk_menu)!==false) echo "checked" ?> > 
					<a href='javascript:void(0)' onclick="showRow_KioskSubLink()" class='text_link' style='vertical-align:baseline;'> <? echo trsLang('외부링크','outerlinktext')?>(<span class='clsid_kiosk_link_count'><? echo $kiosk_link_count;?></span>)</a>
			</td>
			<td style='text-align:left'>
				<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="appendRow_CenterKiosk()">+</a>
				<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="removeRow_CenterKiosk()">-</a>
			</td>
		</tr>
		<tr class='kiosk_link_row' style='display:none;'>
			<td colspan="8" style='text-align:left;padding-left:20px;'>
				<div class="linkWrapper" >
					<input type="hidden" name='kiosk_link[]' class='clsid_center_kiosk_link'>
					<?
					for($j = 0 ; $j < sizeof($kiosk_link) ; $j++){
						$linkInfo = $kiosk_link[$j];
					
						$link_seq = $linkInfo['link_seq'];
						$link_name = $linkInfo['link_name'];
						$link_url = $linkInfo['link_url'];
					?>
					<div  class='link_row'  style='padding:5px;'>
						<i class='fa fa-angle-right'></i> <? echo trsLang('외부링크추가','addouterlinktext');?>
						<input type='text' class='frm_input check_valid_data clsid_kiosk_link_name' placeholder="<? echo trsLang('링크제목을 입력하세요','inputlinktitle');?>" maxlength="100" style='width:250px;' value='<? echo $link_name?>'>
						<input type='text' class='frm_input check_valid_data clsid_kiosk_link_url' placeholder="<? echo trsLang('링크주소를입력하세요','inputlinkurl');?>" maxlength="200" style='width:400px;' value='<? echo $link_url?>'>
						<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="appendRow_KioskSubLink()">+</a>
						<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="removeRow_KioskSubLink()">-</a>
					</div>
					<?
					}
					?>
				</div>
			</td>
		</tr>
		<?}?>
	</table>
</div>
</FORM>
<div class="btn_wrap right">


	<?
		$kiosk_save_event_title = "KIOSK ".trsLang('설정','settingtext')." ".trsLang('저장','btnsave');
		$kiosk_delete_event_title = "KIOSK ".trsLang('설정','settingtext')." ".trsLang('삭제','btndelete');
	?>
	<a href="javascript:void(0)" title="<? echo $kiosk_save_event_title;?>" class="btn required-update-auth hide" onclick="saveCenterKiosk('UPDATE')"><? echo trsLang('저장','btnsave');?></a>
	<a href="javascript:void(0)" title="<? echo $kiosk_delete_event_title;?>"  class="btn required-delete-auth hide"  onclick="deleteCenterKiosk('DELETE')"><? echo trsLang('삭제','btndelete');?></a>
</div>

<!--외부링크 추가-->
<div class="sub_tit" style='line-height:30px;margin-top:60px;'> > KIOSK <? echo trsLang('외부링크','outerlinktext'); ?> <? echo trsLang('설정','settingtext'); ?></div>
<FORM name='frmCenterKioskLink' id='frmCenterKioskLink'>
	<input type='hidden' name='proc_name'>
	<input type='hidden' name='proc'>
	<table class='view'>
		<tr>
			<th><? echo trsLang('적용키오스크','adaptkiosk');?></th>
			<td>
				<?
					for($i = 0 ; $i < sizeof($CenterKiosk) ; $i++){
						
						$kioskInfo = $CenterKiosk[$i];
						$kiosk_seq = $kioskInfo['kiosk_seq'];
						$kiosk_name = $kioskInfo['kiosk_name'];

						if($kiosk_seq=="" || $kiosk_seq=="0") {
							echo trsLang('등록된키오스크정보가없습니다.','notexistkioskinfo');
							break;
						}
					?>
					<input type='checkbox' name='link_kiosk_seq[]' class='cbx_kiosk' value="<? echo $kiosk_seq?>" id='k<? echo $kiosk_seq;?>'>
					<label for='k<? echo $kiosk_seq;?>' style='vertical-align:middle;'><? echo $kiosk_name;?></label>&nbsp;
					<?
					}
				?>
			</td>
		</tr>
	</table>
	<table class="list"  id='tblCenterKioskLink'>
		<tr style='font-weight:bold'  >
			<th><input type="checkbox" onclick="$('.cbx_link').prop('checked',this.checked)"></th>
			<th style='width:250px;'>Link <? echo trsLang('제목','titletext');?></th>
			<th style='text-align:left;width:500px;'>Link Url</th>
			<th style='text-align:left;min-width:80px'><? echo trsLang('추가/삭제','addnremove');?></th>
		</tr>

		<?
		$args = array();
		$Model_manage->SHOW_DEBUG_SQL = false;
		$result = $Model_manage->getDistinctKioskLink($args);

		$link_data[0] = array("link_url"=>"","link_name"=>"");
		if($result){
			$idx = 0;
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$link_data[$idx] = $row;
				$idx++;
			}
		}
		for($i = 0 ; $i < sizeof($link_data) ; $i++){
			$link_url = $link_data[$i]['link_url'];
			$link_name = $link_data[$i]['link_name'];
		?>
		<tr>
			<td style='width:30px;'><input type="checkbox" name='link_checked[]' class='cbx_link' value='Y'></td>
			<td style='width:250px;'>
				<input type='text' name='link_name[]' class='frm_input check_valid_data clsid_kiosk_link_name' placeholder="<? echo trsLang('링크제목을 입력하세요','inputlinktitle');?>" maxlength="100" style='width:250px;' value='<? echo $link_name?>'>
			</td>
			<td style='text-align:left'>
				<input type='text' name='link_url[]' class='frm_input check_valid_data clsid_kiosk_link_url' placeholder="<? echo trsLang('링크주소를입력하세요','inputlinkurl');?>" maxlength="200" style='width:500px;' value='<? echo $link_url?>'></td>
			<td style='text-align:left'>
				<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="appendRow_KioskLink2()">+</a>
				<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="removeRow_KioskLink2()" >-</a>
			</td>
		</tr>
			<?
			}
			?>
	</table>
</FORM>

<div class="btn_wrap right">
	<?
		$link_save_event_title = "KIOSK ".trsLang('외부링크','outerlinktext')." ".trsLang('설정','settingtext')." ".trsLang('저장','btnsave');
		$link_delete_event_title = "KIOSK ".trsLang('외부링크','outerlinktext')." ".trsLang('설정','settingtext')." ".trsLang('삭제','btndelete');
	?>
	<a href="javascript:void(0)" title="<? echo $link_save_event_title;?>" class="btn required-update-auth hide" onclick="saveKioskLink('UPDATE')"><? echo trsLang('저장','btnsave');?></a>
	<a href="javascript:void(0)" title="<? echo $link_delete_event_title;?>" class="btn required-delete-auth hide"  onclick="deleteKioskLink('DELETE')"><? echo trsLang('삭제','btndelete');?></a>
</div>
