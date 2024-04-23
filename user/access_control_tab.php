<?
if($page_name=="") return;

?>
<!--tab 메뉴-->
<ul class="tab">
	<li class="<?if($page_tab_name=="access_control") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control.php'"><? echo trsLang('전체','alltext');?></li>
	<li class="<?if($page_tab_name=="access_control_pass") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_pass.php'"><? echo trsLang('임시출입증발급','tempoprary_pass_text');?></li>
	<? if(in_array("R1000",$_ck_user_mauth)){	//점검결과 권한이 있으면 보인다.?>
		<li class="<?if($page_tab_name=="access_control_file") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_file.php'"><? echo trsLang('파일반입','fileimport');?></li>
	<?}?>
	<li class="<?if($page_tab_name=="access_control_goods") echo "on"?>" onclick="location.href='<? echo $_www_server?>/user/access_control_goods.php'"><? echo trsLang('자산 반입"','bring_assets');?></li>
</ul>