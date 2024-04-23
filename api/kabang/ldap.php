<?php

namespace ldap;

abstract class AuthStatus
{
    const FAIL = "Authentication failed";
    const OK = "Authentication OK";
    const SERVER_FAIL = "Unable to connect to LDAP server";
    const BIND_FAIL = "Error trying to bind";
	const SEARCH_FAIL = "Error in search query";
    const ANONYMOUS = "Anonymous log on";
}

// The LDAP server
class LDAP
{

    private $server = "ldaps://iam.kabang.io:4443";
    private $domain = "ou=Users,dc=kabang";
    private $admin = "uid=vcs,ou=People,dc=kabang";
    private $password = "kakaobank1!";

	private $ldap;

    public function __construct(){
		
    }

	//임직원 리스트 가져오기
    public function get_users()
    {       
       $this->ldap = @ldap_connect($this->server);

		if(!$this->ldap){
			return $this->return_result('fail',AuthStatus::SERVER_FAIL);
		}

        ldap_set_option($this->ldap, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		// ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 9);
		 ldap_set_option($this->ldap, LDAP_OPT_MATCHED_DN, 'ou=Users,dc=kabang');
		//ldap_set_option(null, LDAP_OPT_X_TLS_CACERTDIR, 'C:/Users/thdxo/Downloads/php-7.1.26-Win32-VC14-x86');
       ldap_set_option(null, LDAP_OPT_X_TLS_CACERTFILE, 'D:\DPTWebManager\Server\Apache\conf\2023_STAR.kabang.io_crt.pem');
		 

		$ldapbind = @ldap_bind($this->ldap, $this->admin, $this->password);
		if (!$ldapbind) {
			return $this->return_result('fail',AuthStatus::BIND_FAIL." : ".ldap_error($this->ldap));
		}
		
		$dn = "ou=Users,dc=kabang";
		$filter ="(objectClass=User)";
	//	$filter ="(uid=Stark.jo)";
		$attributes  = array("uid", "cn", "departmentcode", "departmentname"
				, "departmentcodepath", "departmentnamepath", "status","firstcreatedtime","lastmodifiedtime");
		$result = @ldap_search($this->ldap,$dn, $filter,$attributes);

		if(!$result){
			return $this->return_result('fail',AuthStatus::SEARCH_FAIL." : ".ldap_error($this->ldap));
		}

         $data = ldap_get_entries($this->ldap, $result);
        // print number of entries found
        //echo "Number of entries : " . ldap_count_entries($ldapconn, $result);        

        // SHOW ALL DATA
       
		//echo '<h1>Dump all data</h1><pre>';
       // print_r($data);    
        //echo '</pre>';
		


		$users = array();
        for ($i=0; $i<$data["count"]; $i++) {
		
			$user = array(
				"user_name"=> $data[$i]["cn"][0]
				,"user_id" => $data[$i]["uid"][0]  
				,"dept_name" => $data[$i]["departmentname"][0]
				,"dept_code" => $data[$i]["departmentcode"][0]
				,"dept_name_path" =>$data[$i]["departmentnamepath"][0]    
				,"dept_code_path" =>$data[$i]["departmentcodepath"][0]   
				,"status" => $data[$i]["status"][0]       
				,"create_time" => $data[$i]["firstcreatedtime"][0]			//최초등록일시 ex.20170131073614Z
				,"update_time" => $data[$i]["lastmodifiedtime"][0]		//마지막수정일시 ex.20190131073614Z
			);

			$users[] = $user;
			
        }

		return $this->return_result('ok','success',$users);

    }
	
	//결과값 리턴
	private function return_result($status,$msg,$data=''){

		if($this->ldap) {
			ldap_close($this->ldap);
		}
		
		$result = array('status'=>$status
			,'msg'=>$msg
			,'data'=>$data);		

		return $result;
	}
 }
?>