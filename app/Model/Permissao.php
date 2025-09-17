<?php
class Permissao extends AppModel {
	public $useTable = 'permissoes';

	public function getAllPermissoes(){
		$x = $this->find("all", array(
			"fields" => "Permissao.*",
			"order" => "Permissao.descricao",
			"recursive" => -1
		));

		return $x;
	}

	public function getListAllPermissoes(){
		$x = $this->find("list", array(
			"fields" => "Permissao.descricao",
			"order" => "Permissao.descricao",
			"recursive" => -1
		));

		return $x;
	}

}
