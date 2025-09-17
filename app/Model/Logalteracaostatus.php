<?php
	class Logalteracaostatus extends AppModel {
		public $useTable = 'log_alteracaostatus';
		
		public function totalCancelamento(){
			$x = $this->find("all", array(
				"fields" => "COUNT(id_atualizado) as total, novo_status",
				"group" => "id_atualizado"
			));
			
			return $x;
		}
	
	}