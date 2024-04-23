<?php
/*
* Api Class
*/
class Model_Api extends Model
{
		/*
		* Client 에서 전송한 데이터 저장하기
		*/
		function saveBridgeData($args){
			$this->args = $args;

			$data_enc = base64_encode($args['data']);
			
			$sql = "Insert into tb_bridge(data) values (N'$data_enc') ";
			
			$seq = $this->fetchIdentity($sql);	

			return $seq;
		}
}