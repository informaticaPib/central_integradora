<?php

class BuscasController extends AppController
{
    public function index(){
        $this->loadModel('Clientepib');
        if ($this->request->is('get')) {
			if ($this->params['url']['nome']) {
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
				$where .= ' AND Clientepib.nome LIKE "%' . $nome . '%"';
			}
        }

        $this->paginate = array(
			'fields' => '
                DISTINCT(Clientepib.id), 
                Clientepib.status_id, 
                Clientepib.nome, 
                Clientepib.observacao_agendamento, 
                DATE_FORMAT(Clientepib.dtCadastro,"%d/%m/%Y") as datacadastro, 
                Clientepib.status_id, 
                Clientepib.telefone_1, 
                Clientepib.email,
                Clientepib.time_ci, 
                Clientepib.origem, 
                Clientepib.cidade,
                Clientepib.status_discipulado,
                Clientepib.liberado_entrevista,
                Rede.nome,
                MembroVinculo.*,
                (SELECT membros.nome FROM membros WHERE membros.id = MembroVinculo.discipulador_id) as nomeDiscipulador,
                Discipulador.id, 
				Discipulador.nome,
                (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade
            ',                
			'conditions' => '(Clientepib.status_discipulado = 1 OR Clientepib.status_discipulado = 2 OR Clientepib.status_discipulado = 3 OR Clientepib.status_discipulado = 4) AND Clientepib.ativo = 1'.$where,
            'joins' => array(
				array(
					'type' => 'LEFT',
					'table' => 'membros_vinculos',
					'alias' => 'MembroVinculo',
					'conditions' => 'MembroVinculo.membro_id = Clientepib.id AND MembroVinculo.ativo = 1',
				),
				array(
					'type' => 'LEFT',
					'table' => 'centralintegradora.redes',
					'alias' => 'Rede',
					"conditions" => "Rede.id = MembroVinculo.rede_discipulador_filha"
				), array(
					'type' => 'LEFT',
					'table' => 'membros',
					'alias' => 'Discipulador',
					'conditions' => 'Discipulador.id = MembroVinculo.discipulador_id'
				),
			),
			'limit' => 20,
			'order' => 'Clientepib.nome',
			'group' => 'Clientepib.id'
		);
		$retornoBusca = $this->paginate('Clientepib');
        $this->set(compact('retornoBusca'));
    }
}