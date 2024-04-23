<?
function AES_Rijndael_Decript($data, $key, $iv) {

		//AES256으로 암호화하기위해서는 KEY값은 32BYTE, iv값은  16BYTE로 해야된다.
		return base64_decode(@mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_CBC, $iv));
		
}

function AES_Rijndael_Encript($data, $key, $iv) {
       
		return base64_encode(@mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,base64_encode($data),MCRYPT_MODE_CBC,$iv));   

}
?>