<?php
class Euvimcadastro extends AppModel {
	public $useTable = 'cadastros';
	public $useDbConfig = 'euvim';

	public function verificacadastro($id){
		$x = $this->find("first", array(
			'fields' =>	'Euvimcadastro.id',
			'conditions' =>	'Euvimcadastro.id_membro = '.$id.''
		));

		return $x;
	}
}
?>
