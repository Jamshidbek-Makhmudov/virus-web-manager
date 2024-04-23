<?
/*
* Description : 보안USB 회수처리 API
* 출입관리 - USB 회수 버튼을 누르면 호출한다.
* API 가이드 문서 
   - https://api.safeconsole.com/#538fe143-60a5-4748-b8f0-d250f5d326d9
   - https://api.safeconsole.com/#17ce81ee-5b7e-4d9c-88ba-f8ec87c9f827
*/

class SAFECONSOLE {

	private $serverUrl;
	private $actionUrl;
	private $headers;

	public function __construct(){
	
		$this->serverUrl = "https://safeconsole.kabang.io";	

		$this->headers = array( 
			"Accept:application/json"
			,"Content-Type: application/json;charset=UTF-8" 
			//,"Authorization: Bearer ".$this->token
			//,"Authorization: Basic ".$this->token
		);
		
	}

	private function execCurl($postData=''){
		
		/*개발 Test*/
		if(gethostname()=="dataprotecs"){
			global $_www_server;
			$this->serverUrl =$_www_server."/api/kabang/test_usb_return_result.php";
		}
		$url = $this->serverUrl.$this->actionUrl;

		$ch=curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		
		if($postData !=""){
			curl_setopt($ch, CURLOPT_POST, true);							//TRUE는 일반 HTTP POST를 수행.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		//0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);

		$status =false;
		
		if (!$response) {
			$result = curl_error($ch);
		} else {
		   //parsing http status code
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if($http_status=="200"){	//OK
				
				if (!is_null($headers)) {
					$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

					$header = substr($response, 0, $header_size);
					$result = substr($response, $header_size);
				} else {
					$result = $response;
				}
				
				$status = true;
				$result = json_decode($result,true);
			
			}else{
				$result ="api_error - http_status : {$http_status}";
			}
		}
	
		//var_dump($response);
		curl_close($ch);
		$retData = array("status"=>$status,"result"=>$result,"send_data"=>$postData);

		return $retData;
	}

	# USB 회수처리
	public function returnUSB($usb_id,$user_id){

		$this->actionUrl = "/safeconsole/admin?action=manage_user";

		$data = array(
			"action"=>"edit"
			,"userid"=>$user_id			//1
			,"oupath"=>"00.default" 
			,"username" => $usb_id		//euc_128_140
			,"email"=>$usb_id."@KABANG.IO"
		);
	
		$postData = json_encode($data);
		$response = $this->execCurl($postData);

		return $response;
	}

	# 회수처리 대상 USB 인지 체크한다 
	# ㄴ path가 08.zonebuild 인 usb만 회수처리
	public function checkUSB($user_id){

		$this->actionUrl = "/safeconsole/admin?action=get_ous";
		$response = $this->execCurl();
		//회수처리여부
		$bReturn = false;

		if($response['status']==true){
			
			$result = $response['result'];
			
			if($result['success']==true){
				
				foreach($result['response'] as $usbInfo){
					if($usbInfo['id']==$user_id && $usbInfo['path']=="08.zonebuild"){
						$bReturn = true;
						break;
					}
				}

			}
					
		}

		$retData = array("status"=>$bReturn,"result"=>$response);

		return $retData;		
	}

}
?>