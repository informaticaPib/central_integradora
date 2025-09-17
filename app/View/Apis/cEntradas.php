
<?php

class Entradas{


    private $conn;
    public $id;
    public $nome;
    public $email;
    public $cpf;
    public $telefone_1;

    public function __construct($db){
        $this->conn = $db;
    }

    public function inserePessoa($obj){

            $nome               = $obj->nome;
            $cpf                = $obj->cpf; 
            $email              = $obj->email;
            $celular            = $obj->celular;
            $data_nascimento    = $obj->data_nascimento;
            $tipo               = $obj->tipo;
            $origem             = $obj->origem;
            $status             = $obj->status;
            $dataCriacao        = date('Y-m-d');


            $query = "INSERT INTO `entradas` ( `nome`, `cpf`, `email`, `celular`, `dt_nascimento`, `sexo`, `cep`, `pais`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `nome_discipulador`, `contato_discipulador`, `membro`, `tipo`, `trilha`, `conhecer`, `origem`, `created`, `status`) VALUES ( '$nome', '$cpf', '$email', '$celular', '$data_nascimento', '', '', '', '', '', '', '', '', '', '', '', '0', '$tipo', '0', '0', '$origem', '$dataCriacao', $status)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;

    }


}

?>