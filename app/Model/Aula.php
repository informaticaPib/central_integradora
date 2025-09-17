<?php
	class Aula extends AppModel {
		public $useTable = 'aulas';
		public $useDbConfig = 'euvim';
		
		public function listaAulas($modulo){
			$x = $this->find("list", array(
				"fields" => "Aula.nome",
				"conditions" => "Aula.id_turma = ".$modulo."",
			));
			
			return $x;
		}
		
	}