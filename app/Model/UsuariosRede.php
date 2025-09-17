<?php
class UsuariosRede extends AppModel {
	public $useTable = 'usuarios_redes';

	public function FindByIdUser($id){
		$x = $this->find("all", array(
			"fields" => "Rede.nome, Rede.id, Permissao.descricao",
			"conditions" => "UsuariosRede.id_usuario = $id",
			"joins" => array(
				array(
					'type' => 'INNER',
					'table' => 'redes',
					'alias' => 'Rede',
					'conditions' => 'Rede.id = UsuariosRede.id_rede'
				),
				array(
					'type' => 'INNER',
					'table' => 'usuario_permissoes',
					'alias' => 'UserPermissao',
					'conditions' => 'UserPermissao.id_usuario = UsuariosRede.id_rede'
				),
				array(
					'type' => 'INNER',
					'table' => 'permissoes',
					'alias' => 'Permissao',
					'conditions' => 'UserPermissao.id_permissao = Permissao.id'
				)
			),
			'recursive' => -1
		));

		return $x;
	}

	public function checkRegisterUser($id, $idRede){
		$x = $this->find("first", array(
			'fields' => 'UsuariosRede.id',
			'conditions' => 'UsuariosRede.id_usuario = '.$id.' AND UsuariosRede.id_rede = '.$idRede.'',
			'recursive' => -1
		));
		if(!empty($x)){
			return $x;
		}else{
			return 0;
		}
	}

}

