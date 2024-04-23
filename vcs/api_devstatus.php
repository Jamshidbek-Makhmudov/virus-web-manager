 <?php
  

	$UserID = $_GET['userid'];

	$str = '{';
	$str = $str .  '"char":"charset=utf-8",';
	$str = $str .  '"Result":"SUCC",';
    $str = $str .  '"ruserid":"testuser",';
    $str = $str .  '"rusername":"테스터 ",';

    $str = $str .  '"rdevstatus":"WAIT",';
    $str = $str .  '"rdevnetstatus":"5G",';

	$str = $str .  '"rdevrentstart":"",';
	$str = $str .  '"rdevrentend":"",';

    $str = $str .  '"rdevpermitcode":"",';
    $str = $str .  '"rdevpermitname":"",';

    $str = $str .  '"rdevmaxrentdate":"20230930",';
	$str = $str .  '"rdevnotice":"노트북 분실 주의!!!"';

	$str = $str .  '}';

	echo $str;

?>
