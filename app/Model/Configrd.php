<?php
class Configrd extends AppModel {
    public $useTable = 'configuracao';
    public $useDbConfig = 'sistemapib';

    public function get_token(){
		$data_expiracao = $this->find("first", array(
            "fields" => "Configrd.sValor",
            "conditions" => "Configrd.sNome = 'DATE_RDSTATION'"
        ));
		$data_hoje = date('Y-m-d');
		if($data_expiracao['Configrd']['sValor'] != $data_hoje){
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.rd.services/auth/token",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>"{\r\n  \"client_id\": \"43ec56c1-3b9e-45b4-92d3-25cbb67b3675\",\r\n  \"client_secret\": \"b39d0a5b4ccc472c8d31e912897a941e\",\r\n  \"refresh_token\": \"-Qkthj_0mrz3p0nQCQOlb5geae430wfzWyD1z66x-D4\"\r\n}",
			  CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
			  ),
			));
			$response = curl_exec($curl);
			$dados = json_decode($response);
			$token = $dados->access_token;
			if($token != ""){
				$this->updateAll(
					array(
						'sValor' => "'$token'",
					), array(
						'id' => 29
					)
				);
				$this->updateAll(
					array(
						'sValor' => "'$data_hoje'",
					), array(
						'id' => 30
					)
				);
				$file = fopen('../tmp/logs/rd.log', 'a');
				fwrite($file, "\r\n");
				fwrite($file, $response);
				$err = curl_error($curl);
				if ($err){
					fwrite($file, "cURL Error New Token #:" . $err);
					fwrite($file, "\r\n");
				}else {
					fwrite($file, "UPDATE configuracao SET sValor = '$token'  WHERE id = 29");
					fwrite($file, "\r\n");
					fwrite($file, "UPDATE configuracao SET sValor = '$data_hoje'  WHERE id = 30");
					fwrite($file, "\r\n");
					fwrite($file, "reesponse New Token cURL: ".$response);
					fwrite($file, "\r\n");
				}		
				fclose($file);
				curl_close($curl);		
			}
		}			
        $x = $this->find("first", array(
            "fields" => "Configrd.sValor",
            "conditions" => "Configrd.sNome = 'TOKEN_RDSTATION'"
        ));
		
        return $x['Configrd']['sValor'];
    }
	public function post_opportunity($emailDiscipulo){
		$token = $this->get_token();
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.rd.services/platform/events",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{
		  "event_type": "OPPORTUNITY",
		  "event_family":"CDP",
		  "payload": {
			"email": "'.$emailDiscipulo.'",
			"funnel_name": "default"
		  }
		}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Authorization: Bearer  $token"
		  ),
		));
		$response = curl_exec($curl);
		$file = fopen('../tmp/logs/rd.log', 'a');
		$err = curl_error($curl);
		if ($err){
			fwrite($file, "cURL Error Post Opportunity #: $emailDiscipulo " . $err);
			fwrite($file, "\r\n");
		}else {
			fwrite($file, "response Post Opportunity cURL: $emailDiscipulo ".$response);
			fwrite($file, "\r\n");
		}		
		fclose($file);
		curl_close($curl);
		$this->patch_caminhada("Discipulado Conectado",$emailDiscipulo);
		return "ok";
		
	}
	public function post_opportunity_lost($emailDiscipulo){
		$token = $this->get_token();
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.rd.services/platform/events",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{
		  "event_type": "OPPORTUNITY_LOST",
		  "event_family":"CDP",
		  "payload": {
			"email": "'.$emailDiscipulo.'",
			"funnel_name": "default",
			"reason":"Mudado para Frequentador, desistiu do discipulado"
		  }
		}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Authorization: Bearer  $token"
		  ),
		));
		$response = curl_exec($curl);
		$file = fopen('../tmp/logs/rd.log', 'a');
		$err = curl_error($curl);
		if ($err){
			fwrite($file, "cURL Error Post Opportunity_LOST #: $emailDiscipulo " . $err);
			fwrite($file, "\r\n");
		}else {
			fwrite($file, "response Post Opportunity_LOST cURL: $emailDiscipulo ".$response);
			fwrite($file, "\r\n");
		}		
		fclose($file);
		curl_close($curl);
		$this->patch_caminhada("Desistiu",$emailDiscipulo);
		return "ok";
		
	}
	public function patch_caminhada($passo, $emailDiscipulo){
		$token = $this->get_token();
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.rd.services/platform/contacts/email:$emailDiscipulo",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded",
			"Authorization: Bearer $token"
		  ),
		));

		$response = curl_exec($curl);
		$dados = json_decode($response);
		$uuid = $dados->uuid;
		$file = fopen('../tmp/logs/rd.log', 'a');
		$err = curl_error($curl);
		if ($err){
			fwrite($file, "cURL Error Get uuid #: $emailDiscipulo " . $err);
			fwrite($file, "\r\n");
		}else {
			fwrite($file, "response Get uuid cURL: $emailDiscipulo ".$response);
			fwrite($file, "\r\n");
		}		
		fclose($file);
		curl_close($curl);
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.rd.services/platform/contacts/'.$uuid,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PATCH',
		  CURLOPT_POSTFIELDS =>'{
		  
		  "cf_geral_caminhada": "'.$passo.'"
		}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$token,
			'Cookie: __rdsid=10a896df66c66310cc924c0a0333d0aa'
		  ),
		));
		$response = curl_exec($curl);
		$file = fopen('../tmp/logs/rd.log', 'a');
		$err = curl_error($curl);
		if ($err){
			fwrite($file, "cURL Error Patch caminhada #: $uuid " . $err);
			fwrite($file, "\r\n");
		}else {
			fwrite($file, "response Patch caminhada cURL: $uuid ".$response);
			fwrite($file, "\r\n");
		}		
		fclose($file);
		curl_close($curl);
		
		return "ok";
	}

}
?>
