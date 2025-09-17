<?php
class Entrada extends AppModel {
    public $useTable = 'entradas';

    public function listaTipo(){
        $dados = $this->find("list",
            array(
                "fields"=>"Entrada.tipo",
                "conditions"=>"Entrada.status=1",
                "group" => "Entrada.tipo",
				"order" => "Entrada.tipo"
            )
        );
        return $dados; 
    }

    public function listaDadosEntradas($enviaInfo){
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=1 and Entrada.nome LIKE '%$enviaInfo%' ",
            "order" => "Entrada.id",
            "limit" => 10,
			"recursive" => -1
		));
		return $return;
    }

    public function listaDadosEntradasCpf($enviaInfo){
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=1 and Entrada.cpf LIKE '%$enviaInfo%' ",
            "order" => "Entrada.id",

			"recursive" => -1
		));
		return $return;
    }


    public function listaDadosEntradasTipo($enviaInfo){
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=1 and Entrada.tipo = '$enviaInfo' ",
            "order" => "Entrada.id",            
			"recursive" => -1
		));
		return $return;
    }

    public function listaDadosEntradasGeral(){
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=1",
            "order" => "Entrada.id",            
			"recursive" => -1
		));
		return $return;
    }

    public function listaDadosEntradasGeralDesativada($conditions){
        return $this->find("all", array(
            "conditions" => $conditions,
            "order" => array("Entrada.id ASC"),
            "recursive" => -1
        ));
    }

    public function listaEntradasId($idEntradas){
        
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=1 and Entrada.id ='$idEntradas'",
            "order" => "Entrada.id",
            "limit" => 1,
			"recursive" => -1
		));
		return $return;        
    }

    public function dadosEntradasRealizadas(){
        $return = $this->find("all", array(
            "fields" => "Entrada.*",
            "conditions"=>"Entrada.status=0",
            "order" => "Entrada.id",
			"recursive" => -1
		));
		return $return;        

    }

    public function listarDadosOrigem(){
        $return = $this->find("list", array(
            "fields"    => "Entrada.origem",
            "conditions"=>"Entrada.origem <>'' ",
            "group"     =>"Entrada.origem", 
            "order"     => "Entrada.origem",
			"recursive" => -1
		));
		return $return;        

    }


}
?>