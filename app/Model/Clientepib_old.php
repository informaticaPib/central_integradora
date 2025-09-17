<?php
class Clientepib extends AppModel {
    public $useTable = 'membros';
    public $useDbConfig = 'sistemapib';

    public function dadosDiscipulador($id){
        $x = $this->find("first", array(
            "fields" => "Clientepib.*",
            "conditions" => "Clientepib.id = ".$id.""
        ));

        return $x;
    }
    
    public function totalComVinculoStandBy(){
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.discipulador_ci != 0 AND Clientepib.liberado_entrevista = 0 AND Clientepib.agendado = 0 AND Clientepib.status_id = 30"
        ));
        
        return $x;
    }
    
    public function totalSemVinculo(){
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND Clientepib.agendado = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26)"
        ));
    
        return $x;
    }
    
    public function totalComVinculo(){
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.discipulador_ci != 0 AND Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND Clientepib.agendado = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26 OR Clientepib.status_id = 30)"
        ));
        
        return $x;
    }


    
    public function cadastrosMes(){
        $x = $this->find("all", array(
            "fields" => "MONTH(Clientepib.dtCadastro) as mes, COUNT(Clientepib.id) as total",
            "conditions" => "Clientepib.liberado_entrevista = 0 AND Clientepib.atendimento_finalizado = 0 AND (Clientepib.status_id = 27 OR Clientepib.status_id = 26)",
            "group" => "MONTH(Clientepib.dtCadastro)",
            "order" => "MONTH(Clientepib.dtCadastro)",
            "recursive" => -1
        ));
        
        return $x;
    }
    
    public function discipulos($id){
        $x = $this->find("all", array(
            "fields" => "Clientepib.id, Clientepib.nome, Clientepib.telefone_1, Clientepib.email",
            "conditions" => "Clientepib.ativo = 1 AND Clientepib.liberado_entrevista=0 AND Clientepib.discipulador_ci = ".$id." AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26)"
        ));

        return $x;
    }
    public function dadosDiscipuladorVinculo($id){
        $x = $this->find("first", array(
            "fields" => "Clientepib.id, Clientepib.nome, Clientepib.email",
            "conditions" => "Clientepib.id = ".$id.""
        ));
    
        return $x;
    }
    
    public function buscaDadosCpf($cpf){
        $x = $this->find("first", array(
            "fields" => "Clientepib.*",
            "conditions" => "Clientepib.cpf = '".$cpf."'"
        ));
        
        return $x;
    }

    public function discipulosLiberados($id){
        $x = $this->find("all", array(
            "fields" => "Clientepib.id, Clientepib.nome, Clientepib.telefone_1, Clientepib.email, Clientepib.liberado_entrevista",
            "conditions" => "Clientepib.ativo = 1 AND Clientepib.liberado_entrevista=1 AND Clientepib.discipulador_ci = ".$id.""
        ));

        return $x;
    }

    public function listaOrigem()    {
        $dados = $this->find("all",
            array(
                "fields" => "Clientepib.origem",
                "conditions" => "Clientepib.origem NOT IN('0', '9')",
                "group" => "Clientepib.origem",
				"order" => "Clientepib.origem"
            )
        );
        return $dados; 
    }
    
    public function buscaHoraDiscipulador($idDiscipulador){
        
        $x = $this->find("all", array(
            "fields" => "COUNT(Clientepib.ID) AS tt_vinculo",
            "conditions" => "Clientepib.discipulador_ci = ".$idDiscipulador."",
            "recursive" => -1
        ));        
        return $x;
    }


}
?>
