<?php
	class Discipuladorrede extends AppModel {
		public $useTable = 'discipuladores_redes';

		public function buscaRedeFilhaDiscipulador($idDiscipulador){			
			$x = $this->find("all", array(
				"fields" => "Discipuladorrede.rede_id",
				"conditions" => "Discipuladorrede.discipulador_id = ".$idDiscipulador."",
				"recursive" => -1
			));
			return $x;

		}

		public function buscaRedePaiDiscipulador($idDiscipulador, $redeFilha){

			$x = $this->find("all", array(
				"fields" => "Discipuladorrede.rede_contagem",
				"conditions" => "Discipuladorrede.rede_id = $redeFilha AND Discipuladorrede.discipulador_id = $idDiscipulador",
				"recursive" => -1
			));
			return $x;

		}





	}