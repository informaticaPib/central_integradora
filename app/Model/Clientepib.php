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
    public function dadosDiscipulo($id){
        $x = $this->find("first", array(
            "fields" => "Clientepib.*, MembroVinculo.*",
            "conditions" => "Clientepib.id = ".$id."",
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'membros_vinculos',
                    'alias' => 'MembroVinculo',
                    'conditions' => 'MembroVinculo.membro_id = Clientepib.id'
                )
            ),
            'order' => 'MembroVinculo.id DESC'
        ));

        return $x;
    }
    
    public function totalComVinculoStandBy(){
        $x = $this->find("count", array(
            "fields" => "DISTINCT(Clientepib.id)",
            "conditions" => "Clientepib.liberado_entrevista = 0 AND Clientepib.status_id = 30 AND Clientepib.id IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1)",
			'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'membros_vinculos',
                    'alias' => 'MembroVinculo',
                    'conditions' => 'MembroVinculo.membro_id = Clientepib.id'
                )
            ),
        ));
        
        return $x;
    }
    
    public function totalSemVinculo(){         
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26) AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1)",
			"group" => "Clientepib.cpf"
        ));
    
        return $x;
    }
    public function totalSemVinculoComIniciais(){         
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.ativo = 1 AND Clientepib.time_ci != '' AND Clientepib.liberado_entrevista = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26) AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1)",
			"group" => "Clientepib.cpf"
        ));
    
        return $x;
    }
    public function totalSemVinculoSemIniciais(){         
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.ativo = 1 AND (Clientepib.time_ci = '' OR Clientepib.time_ci IS NULL) AND Clientepib.liberado_entrevista = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26) AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1)",
			"group" => "Clientepib.cpf"
        ));
    
        return $x;
    }
    public function totalSemVinculoD2(){
        // SELECT COUNT(DISTINCT(m.id)) as total FROM membros AS m WHERE m.status_id = 5 AND m.ativo = 1 AND m.status_discipulado = 2 AND m.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 2);; 
        $retorno = $this->find('count', array(
            'fields' => 'DISTINCT(Clientepib.id)',
            'conditions' => 'Clientepib.status_id = 5 AND Clientepib.ativo = 1 AND Clientepib.status_discipulado = 2 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 2)'
        ));        
        return $retorno;
    }
    public function totalSemVinculoD3(){
        // SELECT COUNT(DISTINCT(m.id)) as total FROM membros AS m WHERE m.status_id = 5 AND m.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1); 
        $retorno = $this->find('count', array(
            'fields' => 'DISTINCT(Clientepib.id)',
            'conditions' => 'Clientepib.status_discipulado = 3 AND Clientepib.status_id = 5 AND Clientepib.ativo = 1 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 3)'
        ));        
        return $retorno;
    }
    public function totalSemVinculoD4(){
        // SELECT COUNT(DISTINCT(m.id)) as total FROM membros AS m WHERE m.status_id = 5 AND m.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1); 
        $retorno = $this->find('count', array(
            'fields' => 'DISTINCT(Clientepib.id)',
            'conditions' => 'Clientepib.status_discipulado = 4 AND Clientepib.status_id = 5 AND Clientepib.ativo = 1 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 4)'
        ));        
        return $retorno;
    }
    public function totalComVinculo(){
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.discipulador_ci != 0 AND Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26 OR Clientepib.status_id = 30)"
        ));
        
        return $x;
    }

    #alterado para virada de chave em 30/04/2021
    public function totalComVinculo2(){
        $x = $this->find("count", array(
            "fields" => "Clientepib.id",
            "conditions" => "Clientepib.discipulador_ci != 0 AND Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND (Clientepib.idStatus = 29 OR Clientepib.idStatus = 27 OR Clientepib.idStatus = 26)"
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
            "conditions" => "Clientepib.discipulador_ci = ".$idDiscipulador." AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND (Clientepib.status_id = 26 OR Clientepib.status_id = 27 OR Clientepib.status_id = 29)",
            "recursive" => -1
        ));        
        return $x;
    }

    public function buscaInfoCompleta($id){
        $dados = $this->find("first", array(
            "fields" => "Clientepib.*",
            "conditions" => "Clientepib.id = ".$id.""
        ));
        return $dados; 
        
    }

    public function buscaCpfMembros($cpfPessoa){
        $dados = $this->find("all", array(
            "fields" => "Clientepib.*",
            "conditions" => "Clientepib.cpf = '".$cpfPessoa."'"
        ));
        return $dados; 
    }

    public function buscaEmailAcesso($cpfMembro){
        
        $x = $this->find("first", array(
            "fields" => "Clientepib.*",
            "conditions" => "Clientepib.cpf = '".$cpfMembro."'"
        ));
        return $x;
    }



}
?>
