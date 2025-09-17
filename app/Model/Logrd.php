<?php
class Logrd extends AppModel {
    public $useTable = 'log_rd';
	
	public function insert_log($status_id, $membro_id, $operador_id){
		$this->query( "INSERT INTO log_rd (membro_id,status_id,operador_id,created,modified) VALUES ('$membro_id','$status_id','$operador_id',NOW(),'0000-00-00 00:00:00')" );
	}

}
?>

