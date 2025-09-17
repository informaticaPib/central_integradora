<?php
class Rede extends AppModel {
	public $useTable = 'redes';

	//Created: 11/10/2022
	//By: Henrique T. Biagini
	public function dadosLider($idRede){
		$x = $this->find("first", array(
			'conditions' => 'Rede.id = '.$idRede.''
		));
		return $x;
	}

	public function getListAllRedes(){
		$x = $this->find("list", array(
			"fields" => "Rede.nome",
			"conditions" => "Rede.id_pai != 0",
			"order" => "Rede.nome ASC",
			"recursive" => -1
		));

		return $x;
	}
	
	public function getListRedePai(){
		$x = $this->find("list", array(
			"fields" => "Rede.nome",
			"conditions" => "Rede.id_pai = 0",
			"order" => "Rede.nome ASC",
			"recursive" => -1
		));
		
		return $x;
	}
	public function getListRedePaiSecundaria(){
		$x = $this->find("all", array(
			"fields" => "Rede.id, Rede.nome",
			"conditions" => "Rede.id_pai = 0",
			"order" => "Rede.nome ASC",
			"recursive" => -1
		));
		
		return $x;
	}

	public function dadosSubRede($id){
		$x = $this->find("all", array(
			"fields" => "Rede.*",
			"conditions" => "Rede.id_pai = ".$id."",
			"order" => "Rede.nome ASC",
		));
		return $x;
	}

	public function getAllRedes(){
		$x = $this->find("all", array(
			"fields" => "Rede.*",
			"recursive" => -1
		));

		return $x;
	}
	
	public function redespaisfiltradas($rede){
		$x = $this->find("all", array(
			"fields" => "Rede.id, Rede.id_pai, Rede.nome, Membro.nome",
			"conditions" => "Rede.id_pai = ".$rede."",
			"joins" => array(
				array(
					"type" => "left",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Rede.pastor_rede"
				)
			),
			"order" => "Rede.nome",
			"recursive" => -1
		));
		
		return $x;
	}
	
	public function redespais(){
		$x = $this->find("all", array(
			"fields" => "Rede.id, Rede.nome, Membro.nome",
			"conditions" => "Rede.id_pai = 0",
			"joins" => array(
				array(
					"type" => "left",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Rede.pastor_rede"
				)
			),
			"order" => "Rede.nome",
			"recursive" => -1
		));
		
		return $x;
	}
	
	public function isLider($id){
		$x = $this->find("first", array(
			"fields" => "Rede.id",
			"conditions" => "Rede.pastor_rede = ".$id."",
			"recursive" => -1
		));
		
		return $x;
	}
	
}
