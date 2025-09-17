<?php
class User extends AppModel {
	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
	        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    return true;
	}
	
	public $virtualFields = array(
		'siglanome' => 'CONCAT(User.sigla, " - ", User.nome)'
	);
	
	public function findByUsername($username){
		$x = $this->find("first", array(
			'fields' => 'User.username, User.nome, User.id, User.image',
			'conditions' => 'User.username = "'.$username.'"',
			'recursive' => -1
		));

		return $x;
	}
	
	public function listaTimes(){
		$x = $this->find("list", array(
			"fields" => "User.sigla, User.siglanome",
			"conditions" => "User.sigla IS NOT NULL AND User.status = 1",
			"order" => "User.sigla"
		));
		
		return $x;
	}
	
	public function getListDiscipuladores(){
		$x = $this->find("list", array(
			"fields" => "User.nome",
			"conditions" => "User.status = 1",
			"joins" => array(
				array(
					"type" => "INNER",
					"table" => "usuario_permissoes",
					"alias" => "Permissao",
					"conditions" => "Permissao.id_usuario = User.id AND id_permissao = 6"
				)
			),
			"group" => "User.id",
			"order" => "User.nome"
		));

		return $x;
	}

	public function getTudoDiscipuladores(){

        $x = $this->find("all", array(
            "fields" => "User.nome, User.email, User.contato, User.id, Complementarusuario.*",
            "conditions" => "User.status = 1",
            "joins" => array(
                array(
                    "type" => "INNER",
                    "table" => "usuario_permissoes",
                    "alias" => "Permissao",
                    "conditions" => "Permissao.id_usuario = User.id AND id_permissao = 6"
                ),
                array(
                    "type" => "LEFT",
                    "table" => "complementar_usuarios",
                    "alias" => "Complementarusuario",
                    "conditions" => "Complementarusuario.id_usuario = User.id "
                )
            ),
            "group" => "User.id",
            "order" => "User.nome"
        ));
        return $x;
	}
	
	public function dadosUsuario(){
        $return = $this->find("all", array(
            "fields" => "User.*",
            "conditions"=>"User.id IN ('13','8','1')",
            "order" => "User.id",
			"recursive" => -1
		));
		return $return;
    }



}
