<?php
	class Historicoacompanhamentodiscipulo extends AppModel {
		public $useTable = 'historico_acompanhamentos_discipulos';
		
		public function dadosHistorico($id){
			$x = $this->find("all", array(
				"fields" => "DATE_FORMAT(Historicoacompanhamentodiscipulo.data, '%d/%m/%Y') as datacadastro, Historicoacompanhamentodiscipulo.observacao, Usuario.nome",
				"conditions" => "Historicoacompanhamentodiscipulo.id_discipulo = ".$id."",
				"joins" => array(
					array(
						"type" => "INNER",
						"table" => "users",
						"alias" => "Usuario",
						"conditions" => "Usuario.id = Historicoacompanhamentodiscipulo.id_registrante"
					)
				),
				"order" => "Historicoacompanhamentodiscipulo.data DESC"
			));
			
			return $x;
		}
	}
	