<?php
class MembroVinculo extends AppModel {
    public $useTable = 'membros_vinculos';
    public $useDbConfig = 'sistemapib';    
	
    public function totalComVinculoD1(){
        // SELECT count(mv.id) FROM membros_vinculos as mv WHERE mv.nivel_discipulo = 2 AND mv.ativo = 1 AND mv.membro_id IN(SELECT membros.id FROM membros);
        $x = $this->find("count", array(
            "fields" => "DISTINCT(Membro.id)",
            "conditions" => "Membro.ativo = 1 AND Membro.liberado_entrevista = 0 AND (Membro.status_id = 29 OR Membro.status_id = 27 OR Membro.status_id = 26) AND MembroVinculo.nivel_discipulo = 1 AND MembroVinculo.ativo = 1 ",
			'joins' => array(
                array(
                    'type' => 'inner',
                    'table' => 'membros',
                    'alias' => 'Membro',
                    'conditions' => 'MembroVinculo.membro_id = Membro.id'
                )
            ),			
        ));        
        return $x;
    }

    public function totalComVinculoD2(){
        // SELECT count(mv.id) FROM membros_vinculos as mv WHERE mv.nivel_discipulo = 2 AND mv.ativo = 1 AND mv.membro_id IN(SELECT membros.id FROM membros);
        $x = $this->find("count", array(
            "fields" => "MembroVinculo.id",
            "conditions" => "MembroVinculo.nivel_discipulo = 2 AND MembroVinculo.ativo = 1 AND MembroVinculo.membro_id IN(SELECT membros.id FROM membros)"
        ));        
        return $x;
    }
    public function totalComVinculoD3(){
        // SELECT count(mv.id) FROM membros_vinculos as mv WHERE mv.nivel_discipulo = 2 AND mv.ativo = 1 AND mv.membro_id IN(SELECT membros.id FROM membros);
        $x = $this->find("count", array(
            "fields" => "MembroVinculo.id",
            "conditions" => "MembroVinculo.nivel_discipulo = 3 AND MembroVinculo.ativo = 1 AND MembroVinculo.membro_id IN(SELECT membros.id FROM membros)"
        ));        
        return $x;
    }
    public function totalComVinculoD4(){
        // SELECT count(mv.id) FROM membros_vinculos as mv WHERE mv.nivel_discipulo = 2 AND mv.ativo = 1 AND mv.membro_id IN(SELECT membros.id FROM membros);
        $x = $this->find("count", array(
            "fields" => "MembroVinculo.id",
            "conditions" => "MembroVinculo.nivel_discipulo = 4 AND MembroVinculo.ativo = 1 AND MembroVinculo.membro_id IN(SELECT membros.id FROM membros)"
        ));        
        return $x;
    }
    public function discipulos($id){
        $x = $this->find("all", array(
            "fields" => "Clientepib.id, Clientepib.status_id, Clientepib.nome, Clientepib.telefone_1, Clientepib.email, MembroVinculo.nivel_discipulo",
            "conditions" => "MembroVinculo.discipulador_id = ".$id." AND MembroVinculo.ativo = 1",
            'joins' => array(
                array(
                    'type' => 'inner',
                    'table' => 'membros',
                    'alias' => 'Clientepib',
                    'conditions' => 'Clientepib.id = MembroVinculo.membro_id'
                )
                ),
                'order' => 'Clientepib.nome',
                'group' => 'Clientepib.id'
        ));
        return $x;
    }
    //Discipulos ativos que ainda não foram liberados para entrevista (D1)
    public function discipulosAtivosNaoLiberados($id){
        $x = $this->find("all", array(
            "fields" => "Clientepib.id, Clientepib.status_id, Clientepib.nome, Clientepib.telefone_1, Clientepib.email, MembroVinculo.nivel_discipulo",
            "conditions" => "MembroVinculo.discipulador_id = ".$id." AND MembroVinculo.ativo = 1 AND Clientepib.liberado_entrevista = 0",
            'joins' => array(
                array(
                    'type' => 'inner',
                    'table' => 'membros',
                    'alias' => 'Clientepib',
                    'conditions' => 'Clientepib.id = MembroVinculo.membro_id'
                )
                ),
                'order' => 'Clientepib.nome',
                'group' => 'Clientepib.id'
        ));
        return $x;
    }
    //Discipulos ativos que ainda não foram liberados para entrevista (D1)
    public function discipulosAtivosLiberados($id){
        $x = $this->find("all", array(
            "fields" => "Clientepib.id, Clientepib.status_id, Clientepib.nome, Clientepib.telefone_1, Clientepib.email, MembroVinculo.nivel_discipulo",
            "conditions" => "MembroVinculo.discipulador_id = ".$id." AND MembroVinculo.ativo = 1 AND Clientepib.liberado_entrevista = 1",
            'joins' => array(
                array(
                    'type' => 'inner',
                    'table' => 'membros',
                    'alias' => 'Clientepib',
                    'conditions' => 'Clientepib.id = MembroVinculo.membro_id'
                )
                ),
                'order' => 'Clientepib.nome',
                'group' => 'Clientepib.id'
        ));
        return $x;
    }
}