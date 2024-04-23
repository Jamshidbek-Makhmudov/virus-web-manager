<?
if($page_name=="") return;

?>
<!--tab 메뉴-->
<ul class="tab">
	<li class="<?if($page_tab_name=="access_control_idc") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_idc.php'"><? echo trsLang('출입내역','entryExitHistory');?></li>
	<? if(in_array("R1000",$_ck_user_mauth)){	//점검결과 권한이 있으면 보인다.?>
		<li class="<?if($page_tab_name=="access_control_file_idc") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_file_idc.php'"><? echo trsLang('파일반입','fileimport');?></li>
	<?}?>
	<li class="<?if($page_tab_name=="access_control_goods_idc") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_goods_idc.php'"><? echo trsLang('자산 반입"','bring_assets');?></li>
</ul>