<?php
	class Estadocivil extends AppModel {
		public $useTable = 'tiporelacionamentos';
		public $useDbConfig = 'sistemapib';
		
		public function getListaCivil(){
			$x = $this->find("list", array(
				"fields" => "Estadocivil.descricao",
				"order" => "Estadocivil.descricao",
				"recursive" => -1
			));
			
			return $x;
		}
	
	}