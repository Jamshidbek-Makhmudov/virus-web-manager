<?
require_once "ldap.php";

$ldap = new ldap\LDAP;

$users = $ldap->get_users();

//임직원목록
print_r($users);

?>