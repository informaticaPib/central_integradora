<?php
class Euvim extends AppModel {
    public $useTable = 'frequencias';
    public $useDbConfig = 'euvim';
    
    public function listaAulas($modulo){
        $x = $this->find("list", array(
            "fields" => "Aula.nome",
            "joins" => array(
                array(
                    "type" => "inner",
                    "table" => "aulas",
                    "alias" => "Aula",
                    "conditions" => "Aula.id_turma = ".$modulo."",
                    "order" => "Aula.nome"
                )
            )
        ));
        
        return $x;
    }
    public function frequenciasAlunoTurma($idMembro, $idModulo){
        $x = $this->find('all', array(
            'fields' => 'Euvim.*, DATE_FORMAT(Euvim.data_frequencia, "%d/%m/%Y") as data_frequencia, Turma.nome, Linha.nome, Aula.nome',
			'conditions' => 'Euvim.id_membro = '.$idMembro.' AND Euvim.id_turma = '.$idModulo.'',
			'joins' => array(
				array(
					'type' => 'INNER',
					'table' => 'turmas',
					'alias' => 'Turma',
					'conditions' => 'Turma.id = Euvim.id_turma'
				),
				array(
					'type' => 'INNER',
					'table' => 'linhas',
					'alias' => 'Linha',
					'conditions' => 'Linha.id = Euvim.id_linha'
				), array(
					'type' => 'LEFT',
					'table' => 'aulas',
					'alias' => 'Aula',
					'conditions' => 'Aula.id = Euvim.id_aula'
				)
			),
			'order' => "Euvim.data_frequencia ASC",
			'recursive' => -1
        ));

        return $x;
    }
    public function totalFrequencia($idMembro, $idTurma){
        $x = $this->find('count', array(
            'conditions' => 'Euvim.id_membro = '.$idMembro.' AND Euvim.id_turma = '.$idTurma.'',
        ));

        return $x;
    }
}
?>
