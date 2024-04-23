<?php

//=============DB Á¢¼Ó ========================

/*
echo $SMS_SERVER . "<br>";
echo $SMS_SERVER_ID . "<br>";
echo $SMS_SERVER_PWD . "<br>";
echo $SMS_SERVER_DB . "<br>";
*/

$dbmysql=@mysql_connect("$_sms_server","$_sms_id","$_sms_pwd");

@mysql_select_db("$_sms_db", $dbmysql);

?>