<?php
class Discipulador extends AppModel {
    public $useTable = 'discipuladores';

    public function todosDiscipuladores(){
        $x = $this->find("all", array(
            "fields" => "Discipulador.*",
            "recursive" => -1
        ));

        return $x;
    }
	
    public function cadastrosMes(){
    	$x = $this->find("all", array(
    		"fields" => "MONTH(Discipulador.created) as mes, COUNT(Discipulador.id) as total",
			"group" => "MONTH(Discipulador.created)",
			"order" => "MONTH(Discipulador.created)",
			"recursive" => -1
		));
    	
    	return $x;
	}
    
    public function dadosDiscipuladorUserId($id){
        $x = $this->find("first", array(
            "fields" => "Discipulador.*, Etaria.*, Rede.*, User.nome",
            "conditions" => "Discipulador.id_user = ".$id."",
            "joins" => array(
                array(
                    "type" => "LEFT",
                    "table" => "redes",
                    "alias" => "Rede",
                    "conditions" => "Rede.id = Discipulador.id_rede"
                ), array(
                    "type" => "LEFT",
                    "table" => "etarias",
                    "alias" => "Etaria",
                    "conditions" => "Etaria.id = Discipulador.id_faixa"
                ), array(
                    "type" => "INNER",
                    "table" => "users",
                    "alias" => "User",
                    "conditions" => "User.id = Discipulador.id_user"
                )
            )
        ));

        return $x;
    }
    
    public function dadosDiscipulador($id){
        $x = $this->find("first", array(
            "fields" => "Discipulador.*, Etaria.*, Rede.*",
            "conditions" => "Discipulador.id_membro = ".$id."",
            "joins" => array(
				array(
					"type" => "LEFT",
					"table" => "discipuladores_redes",
					"alias" => "Discipuladorrede",
					"conditions" => "Discipuladorrede.discipulador_id = Discipulador.id_membro"
				),
                array(
                    "type" => "LEFT",
                    "table" => "redes",
                    "alias" => "Rede",
                    "conditions" => "Rede.id = Discipuladorrede.rede_id"
                ), array(
                    "type" => "LEFT",
                    "table" => "etarias",
                    "alias" => "Etaria",
                    "conditions" => "Etaria.id = Discipulador.id_faixa"
                )
            )
        ));

        return $x;
    }

    public function dadosCelulaApi($idDiscipulador)    {
        #echo '<pre>';print_r($idCpf);echo '</pre>';#chega aqui id
        // $dados = $this->find("all", array(
        //     "fields" => "Discipulador.id",
        //     "joins" => array(
		// 		array(
		// 			"type"       => "INNER",
		// 			"table"      => "celulas_grupo_participantes",
		// 			"alias"      => "CelGruPar",
		// 			"conditions" => "CelGruPar.membro_id = Discipulador.id"
		// 		),
        //     ),
        //     "conditions" => "Discipulador.id_membro = ".$idDiscipulador." ",
        //     #AND CelulasParticipantes.ativo = 1 AND CelulasParticipantes.membro_id = '.$idDiscipulador.'
        // ));
        # return $dados;
        // $curl = curl_init();
		// curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://us-central1-appcelulas-c3c42.cloudfunctions.net/auth_FindPersonByCPF?churchId=NeQb98wHplRoh5RGZwKv&cpf=".$idCpf,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
		// ));
		// $response = curl_exec($curl);
		// curl_close($curl);
        // $dados = json_decode($response);
		// $this->personId = $dados->personId;
		// $this->isLeader = $dados->isLeader;
        // $this->smallgroups = $dados->smallgroups;
		// $cont = 0;
		// foreach ($this->smallgroups as $dado) {
		// 	if($cont > 0){
		// 		$this->Celula .= ", ".strtoupper($dado);
		// 	}
		// 	else{
		// 		$this->Celula .= strtoupper($dado);
		// 	}
		// 	$cont++;
		// }
		// if(is_null($this->Celula) || $this->Celula == ""){
		// 	$this->Celula = 'Não pertence a nenhuma Célula';
        // }
        // return($this->Celula);
        #echo json_decode($this->personId, $this->isLeader,$this->Celula);
    }


    
    public function isDiscipulador($cpf){
    	$x = $this->find("count", array(
    		"fields" => "Discipulador.id_membro",
    		"conditions" => "Discipulador.cpf = '".$cpf."'"
		));
    	
    	return $x;
	}

    public function buscaHoraDiscipulador($idDiscipulador){

        $x = $this->find("all", array(
            "fields" => "Discipulador.total_vinculo",
            "conditions" => "Discipulador.id_membro = ".$idDiscipulador."",
            "recursive" => -1
        ));
        return $x;
    }

    public function atualizaHorasDiscipulador($idDiscipulador, $qtdHoras){

        $this->updateAll(array("total_vinculo"=>"$qtdHoras"),array("id_membro"=>"$idDiscipulador"));
            
    }
}
