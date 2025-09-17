<?php
	class Etaria extends AppModel {
		public $useTable = 'etarias';
		
		public function getListEtarias(){
			$x = $this->find("list", array(
				"fields" => "Etaria.descricao"
			));
			
			return $x;
		}
		
	}