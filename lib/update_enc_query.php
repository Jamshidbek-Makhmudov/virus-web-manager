<?
/*
* 개인정보 암호화 대상 컬럼 데이터 업데이트 쿼리 생성
* aes192 → ase256 으로 암호화
* 평문 → aes256 으로 암호화
*/
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

//aes256,aes192 암호화가 혼용된 경우
function aes_256_dec_mix($enc){

	global $_encryption_kind;
	global $_AES_KEY_256;
	global $_AES_KEY;
	global $_AES_IV;

	if($enc=="") return "";

	if($_encryption_kind=="2"){ //**aes256

		$dec = AES_Rijndael_Decript($enc,$_AES_KEY_256,$_AES_IV);
		$aes_256_enc = AES_Rijndael_Encript($dec,$_AES_KEY_256,$_AES_IV);

		if($enc == $aes_256_enc){	//aes256 으로 암호화 된 경우

			$rtn_dec = $dec;

		}else{	//aes192로 암호화된 경우

			$dec = AES_Rijndael_Decript($enc,$_AES_KEY,$_AES_IV);	
			$aes_192_enc = AES_Rijndael_Encript($dec,$_AES_KEY,$_AES_IV);	

			if($enc==$aes_192_enc){
				$rtn_dec = $dec;
			}else{	//암호화 안된 경우
				$rtn_dec = $enc;
			}
		}

	}else{	//**aes192
		
			$dec = AES_Rijndael_Decript($enc,$_AES_KEY,$_AES_IV);	
			$aes_192_enc = AES_Rijndael_Encript($dec,$_AES_KEY,$_AES_IV);	

			if($enc==$aes_192_enc){
				$rtn_dec = $dec;
			}else{	//암호화 안된 경우
				$rtn_dec = $enc;
			}

	}
	return $rtn_dec;
}

//ase256으로 암호화
function re_enc($data){
	$enc_data = aes_256_enc(aes_256_dec_mix($data));
	return $enc_data;
}

//업데이트 쿼리 생성
function get_update_query($tbl_name, $col_name){
	
	global $wvcs_dbcon;

	$sql = "select distinct {$col_name} from {$tbl_name} ";
	
	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		if($row[$col_name] != ""){
			$update_sql[] = "update {$tbl_name} set {$col_name} =  '".re_enc($row[$col_name])."' where {$col_name} = '".$row[$col_name]."'; ";
		}

	}

	return $update_sql;

}

//개인정보 암호화 대상 컬럼
$enc_col = array("user_name","v_user_name","manager_name","mngr_name","emp_name","admin_name"
,"wvcs_authorize_name","approver_name","v_phone","phone_no","user_phone","v_email","email");


for($i = 0 ; $i < count($enc_col) ; $i++){

	$sql = "
		 SELECT
			  T.name AS table_name, C.name AS column_name
			  ,'select '+C.name+' from '+ T.name as select_query
		   FROM
			  sys.tables AS T
		   INNER JOIN
			  sys.columns AS C
		   ON
			  T.object_id = C.object_id
		   WHERE
			  C.name = '".$enc_col[$i]."'
		";

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$update_sql =  get_update_query($row['table_name'],$row['column_name']);

		for($j =0 ; $j < count($update_sql) ; $j++) echo $update_sql[$j]."<BR>";

	}
	
	echo "<BR>";
}
?>