<?php
class UsuariosPermissao extends AppModel {
	public $useTable = 'usuario_permissoes';

	public function isOperador($id){
		$x = $this->find("all", array(
			"fields" => "UsuariosPermissao.id_permissao",
			"conditions" => "UsuariosPermissao.id_usuario = ".$id." AND UsuariosPermissao.id_permissao =7",
			'group' => 'UsuariosPermissao.id_permissao',
			'recursive' => -1
		));

		return $x;
	}

	public function getAllPermissoesById($id){
		$x = $this->find("all", array(
			"fields" => "Rede.nome, Rede.id, Permissao.descricao, UsuariosPermissao.id_permissao",
			"conditions" => "UsuariosPermissao.id_usuario = $id",
			"joins" => array(
				array(
					'type' => 'INNER',
					'table' => 'redes',
					'alias' => 'Rede',
					'conditions' => 'Rede.id = UsuariosPermissao.id_rede'
				),
				array(
					'type' => 'INNER',
					'table' => 'permissoes',
					'alias' => 'Permissao',
					'conditions' => 'UsuariosPermissao.id_permissao = Permissao.id'
				)
			),
			'group' => 'UsuariosPermissao.id_rede, UsuariosPermissao.id_permissao',
			'recursive' => -1
		));
		return $x;
	}

}
