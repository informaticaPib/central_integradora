<?php
	class Historicoacompanhamento extends AppModel {
		public $useTable = 'historico_acompanhamentos';
		
		public function dadosHistorico($id){
			$x = $this->find("all", array(
				"fields" => "DATE_FORMAT(Historicoacompanhamento.data, '%d/%m/%Y') as datacadastro, Historicoacompanhamento.observacao, Usuario.nome",
				"conditions" => "Historicoacompanhamento.id_discipulador = ".$id."",
				"joins" => array(
					array(
						"type" => "INNER",
						"table" => "users",
						"alias" => "Usuario",
						"conditions" => "Usuario.id = Historicoacompanhamento.id_registrante"
					)
				),
				"order" => "Historicoacompanhamento.data DESC"
			));
			
			return $x;
		}
	}
	