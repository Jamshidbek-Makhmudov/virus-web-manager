 <?php
  

	$UserID = $_GET['userid'];

	$str = '{';
	$str = $str .  '"char":"charset=utf-8",';
	$str = $str .  '"Result":"SUCC",';
    $str = $str .  '"userid":"testuser",';
    $str = $str .  '"username":"변경테스트",';

    $str = $str .  '"userposition":"임원",';
    $str = $str .  '"usergrade":"상무",';
	$str = $str .  '"userphone":"010-1212-2323",';
	$str = $str .  '"useruseyn":"Y",';
    $str = $str .  '"usermoddate":"2020-03-03",';

    $str = $str .  '"userparttopcode":"50",';
    $str = $str .  '"userpartcode":"55",';
	$str = $str .  '"userpartname":"개발부서",';

	$str = $str .  '"usermaxrentdate":"20230718",';
    $str = $str .  '"usernotice":"반출 노트북 분실에 주의 하여 주십시오."';
	$str = $str .  '}';

	echo $str;

?>