<?php
class CelulasParticipantes extends AppModel {
    public $useTable = 'celulas_grupo_participantes';
    public $useDbConfig = 'sistemapib';    

    public function listaCelulasParticipantes($idUsuario){

		// busca pelo id da pessoa o local ou celula em que ela esta
		// a pessoa pode esta em mais de uma CELULA 
		$dados = $this->find("all",
            array(
                "fields"=>"CelulasGrupo.name",
				"joins"=>array(
					array(
						"type" => "LEFT",
						"table" => "celulas_grupo",
						"alias" => "CelulasGrupo",
						"conditions" => "CelulasGrupo.supervision_id  = CelulasParticipantes.hash_celula"
					)
				),
                "conditions"=>array('CelulasGrupo.ativo = 1 
					AND CelulasParticipantes.ativo = 1
					AND CelulasParticipantes.membro_id = '.$idUsuario.'
					AND CelulasGrupo.hierarchy =3 
					AND CelulasGrupo.description = ""
					AND CelulasGrupo.reason = ""  ') 
            )
        );  
		return $dados;
	}


	public function dadosParticipantes($idMembro) {
			$dados = $this->find("list",
            array(
                "fields"	=>"CelulasGrupo.id, CelulasGrupo.name ",
				"joins"=>array(
					array(
						"type" => "INNER",
						"table" => "celulas_grupo",
						"alias" => "CelulasGrupo",
						"conditions" => "CelulasGrupo.id  = CelulasParticipantes.grupo_id"
					)
				),
                "conditions"=>array('CelulasParticipantes.membro_id = '.$idMembro.'
				AND CelulasParticipantes.ativo = 1
				AND CelulasGrupo.ativo = 1
				AND CelulasGrupo.hierarchy =3
				AND (CelulasGrupo.reason IS NULL OR CelulasGrupo.reason ="" ) 
				AND (CelulasGrupo.description IS NULL OR CelulasGrupo.description="")')
				#AND CelulasGrupo.id NOT IN ('.$idCelulaResp.')
            )
        );  
		return $dados;
	}


}