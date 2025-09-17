<?php
App::uses('CakeEmail', 'Network/Email');
class Discipuladosd3Controller extends AppController
{
	public $uses = array(
		'Clientepib',
		'Euvim'
	);
	
	public function perfil()
	{
		$this->loadModel('Clientepib');
		$this->loadModel('Euvim');
		$this->loadModel('User');
		$this->loadModel('UsuariosPermissao');
		$this->loadModel('Discipulador');
		$this->loadModel('Historicoacompanhamentodiscipulo');
		$this->loadModel('MembroVinculo');
		
		$idMembro = $this->request->params['pass'][0];		        
		$verificaMembro = $this->Clientepib->find("first", array(
			"fields" => "Clientepib.status_id, Clientepib.id ",
			"conditions" => "Clientepib.id = " . $idMembro . " AND Clientepib.status_id = 5 AND Clientepib.ativo = 1"
		));
		
		if (empty($verificaMembro)) {
			$this->Session->setFlash('Não tem permissão de acessar o perfil dessa pessoa!', 'info');
			$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'vinculados'));
		} else {            		
			$isOperador = $this->Session->read('Auth.User.role');
			if ($isOperador == 'operador' || $isOperador == 'admin' || $isOperador == 'pastor') {
				$permissaoOperador = 1;
			} else {
				$permissaoOperador = 0;
			}
			
			$listaTimes = $this->User->listaTimes();
			
			$dadosMembro = $this->Clientepib->dadosDiscipulo($this->request->params['pass'][0]);
			if($dadosMembro['MembroVinculo']['ativo']){
				$statusDiscipulado = $dadosMembro['MembroVinculo']['ativo'];
			} else{
				$statusDiscipulado = 0;
			}
			$dadosVinculo = $this->MembroVinculo->find('first', array(
				'fields' => 'MembroVinculo.*',
				'conditions' => 'MembroVinculo.nivel_discipulo = 3 AND MembroVinculo.ativo = 1 AND MembroVinculo.membro_id = '.$this->request->params['pass'][0].''
			));
			
            

			#consulta dados via api lineker
			$cpfApi = $dadosMembro['Clientepib']['cpf'];
			$dadosCelulaApi = $this->Discipulador->dadosCelulaApi($cpfApi);

			
			if(empty($dadosVinculo)){
				$discipuladorCi = 0;
			}else{
				$discipuladorCi = $dadosVinculo['MembroVinculo']['discipulador_id'];
			}	
			
			$historicoAcompanhamentoDiscipulo = $this->Historicoacompanhamentodiscipulo->dadosHistorico($this->request->params['pass'][0]);
						
			
			$euvim = $this->Euvim->find('all', array(
				'fields' => 'Euvim.*, Turma.nome, Linha.nome, Aula.nome',
				'conditions' => 'Euvim.id_membro = ' . $this->request->params['pass'][0] . '',
				'joins' => array(
					array(
						'type' => 'INNER',
						'table' => 'turmas',
						'alias' => 'Turma',
						'conditions' => 'Turma.id = Euvim.id_turma'
					),
					array(
						'type' => 'INNER',
						'table' => 'linhas',
						'alias' => 'Linha',
						'conditions' => 'Linha.id = Euvim.id_linha'
					), array(
						'type' => 'LEFT',
						'table' => 'aulas',
						'alias' => 'Aula',
						'conditions' => 'Aula.id = Euvim.id_aula'
					)
				),
				'order' => "Euvim.nome_membro ASC",
				'recursive' => -1
			));
			
			$totalPresencasEuvimEstudosD3 = $this->Euvim->find('count', array(
				'conditions' => 'Euvim.id_membro = ' . $this->request->params['pass'][0] . ' AND Euvim.id_turma IN (6,8,24)',
				'recursive' => -1
			));						            

			$listaDiscipuladores = $this->User->getListDiscipuladores();
			
			//Total Frequencia Liderando uma célula saudavel
            $totalLiderandoCelula = $this->Euvim->totalFrequencia($this->request->params['pass'][0],6);

			$listaTodosDadosDiscipuladores = $this->Discipulador->find("all", array(
				"fields" => "Discipulador.*,
						Etaria.*,
						Rede.*,
						Membro.id,
						Membro.nome,
						Membro.data_nascimento,
						Membro.email,
						Membro.sexo,
						Membro.telefone_1,
						Membro.cidade,
						Membro.bairro,
						TipoRelacionamento.descricao,
						Discipuladorrede.rede_contagem,
						Discipuladorrede.rede_id,
						",
				
				"conditions" => "Discipulador.status = 1 AND Discipulador.nivel_discipulador = 4",
				"joins" => array(
					array(
						"type" => "LEFT",
						"table" => "discipuladores_redes",
						"alias" => "Discipuladorrede",
						"conditions" => "Discipuladorrede.discipulador_id = Discipulador.id_membro"
					),
					array(
						"type" => "LEFT",
						"table" => "redes",
						"alias" => "Rede",
						"conditions" => "Rede.id = Discipuladorrede.rede_id"
					), array(
						"type" => "LEFT",
						"table" => "etarias",
						"alias" => "Etaria",
						"conditions" => "Etaria.id = Discipulador.id_faixa"
					), array(
						"type" => "INNER",
						"table" => "sistemapib.membros",
						"alias" => "Membro",
						"conditions" => "Membro.id = Discipulador.id_membro"
					), array(
						"type" => "LEFT",
						"table" => "sistemapib.tiporelacionamentos",
						"alias" => "TipoRelacionamento",
						"conditions" => "Membro.tiporelacionamento_id = TipoRelacionamento.id"
					)
				),
				"order" => 'Membro.nome, Discipulador.total_vinculo ASC'
			));
			#original
			#$this->set(compact('listaTimes', 'dadosDiscipulador', 'listaDiscipuladores', 'listaTodosDadosDiscipuladores', 'dadosMembro', 'euvim', 'totalPresencasEuvim', 'totalPresencasEuvimDiscipulado', 'totalPresencasEuvimIgreja', 'historicoAcompanhamentoDiscipulo', 'dadosCelulaApi'));
			$this->set(compact('totalLiderandoCelula','statusDiscipulado','listaTimes', 'dadosDiscipulador', 'listaDiscipuladores', 'listaTodosDadosDiscipuladores', 'dadosMembro', 'euvim', 'totalPresencasEuvim', 'totalPresencasEuvimDiscipulado', 'totalPresencasEuvimIgreja', 'historicoAcompanhamentoDiscipulo', 'dadosCelulaApi'));
		}
		
	}
	
	public function vinculados()
	{
		$this->loadModel('MembroVinculo');
        $this->loadModel('Discipulador');
        $this->loadModel('Rede');
		$this->loadModel('User');
        $this->loadModel('Clientepib');
        $listaTimes = $this->User->listaTimes();
		$listaRedes = $this->Rede->getListAllRedes();	
		$listaRedesPais						= $this->Rede->getListRedePai();	
		$totalDiscipulosComVinculo = $this->MembroVinculo->totalComVinculoD3();								

		$where = '';
		//FILTRO
		if ($this->request->is('get')) {
			if ($this->params['url']['nome']) {
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
				$where .= ' AND Clientepib.nome LIKE "%' . $nome . '%"';
			}
			if ($this->params['url']['email']) {
				$email = $this->params['url']['email'];
				$where .= ' AND Clientepib.email LIKE "%' . $email . '%"';
			}
			if ($this->params['url']['contato']) {
				$contato = $this->params['url']['contato'];
				$where .= ' AND Clientepib.telefone_1 LIKE "%' . $contato . '%"';
			}
			if ($this->params['url']['dtcadastro']) {
				$dtcadastro = $this->params['url']['dtcadastro'];
				$where .= ' AND date_format(Clientepib.dtCadastro, "%Y-%m-%d") = "' . $dtcadastro . '"';
			}
			if ($this->params['url']['origem']) {
				$origem = $this->params['url']['origem'];
				$where .= ' AND Clientepib.origem = "' . $origem . '"';
			}
			if ($this->params['url']['id_discipulador']) {
				$discipulador = $this->params['url']['id_discipulador'];
				$where .= ' AND Clientepib.discipulador_ci LIKE "%' . $discipulador . '%"';
			}
			if ($this->params['url']['time']) {
				$time = $this->params['url']['time'];
				$where .= ' AND Clientepib.time_ci = "' . $time . '"';
			}
			if ($this->params['url']['id_rede']) {
				$rede = $this->params['url']['id_rede'];
				$where .= ' AND MembroVinculo.rede_discipulador_filha = ' . $rede . '';
			}
			if ($this->params['url']['id_rede_pai']) {
				$redePai = $this->params['url']['id_rede_pai'];
				$where .= ' AND MembroVinculo.rede_discipulador = ' . $redePai . '';
			}
			if ($this->params['url']['sexo']!="") {				
				$sexo = $this->params['url']['sexo'];
				$where .= ' AND Clientepib.sexo = "' . $sexo . '"';
			}
			if($this->params['url']['idadeinicial'] != '' && $this->params['url']['idadefinal'] != ''){	
				
				$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND "'.$this->params['url']['idadefinal'].'"';								
			}else{
				if($this->params['url']['idadeinicial'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND 99';
				}
				if($this->params['url']['idadefinal'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN 0 AND "'.$this->params['url']['idadefinal'].'"';
				}
			}
		}
		
		$this->paginate = array(
			'fields' => '
				DISTINCT(Clientepib.id), 
				Rede.nome as redesdiscipulador, 
				(SELECT centralintegradora.historico_acompanhamentos_discipulos.data 
				FROM centralintegradora.historico_acompanhamentos_discipulos
				WHERE centralintegradora.historico_acompanhamentos_discipulos.id_discipulo = MembroVinculo.membro_id ORDER BY centralintegradora.historico_acompanhamentos_discipulos.data DESC LIMIT 1) as ultimoHistorico,
				MembroVinculo.discipulador_id,				
				(SELECT membros.nome FROM membros WHERE membros.id = MembroVinculo.discipulador_id) as nomeDiscipulador, 
				Clientepib.status_id, 
				Clientepib.nome, 
				Clientepib.observacao_agendamento, 
				DATE_FORMAT(Clientepib.dtCadastro,"%d/%m/%Y") as datacadastro, 
				Clientepib.status_id, 
				Clientepib.telefone_1, 
				Clientepib.email, 
				Clientepib.time_ci, 				
				Clientepib.cidade,
				Rede.nome,
				MembroVinculo.origem, 
				MembroVinculo.data_vinculo,
				Discipulador.id, 
				Discipulador.nome,
				(YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade
			',	
			'conditions' => 'MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 3 AND Clientepib.ativo = 1 AND Clientepib.status_id = 5'.$where,			
			'joins' => array(
				array(
					'type' => 'inner',
					'table' => 'membros',
					'alias' => 'Clientepib',
					'conditions' => 'Clientepib.id = MembroVinculo.membro_id'
				),
				array(
					'type' => 'LEFT',
					'table' => 'centralintegradora.redes',
					'alias' => 'Rede',
					"conditions" => "Rede.id = MembroVinculo.rede_discipulador_filha"
				), array(
					'type' => 'INNER',
					'table' => 'membros',
					'alias' => 'Discipulador',
					'conditions' => 'Discipulador.id = MembroVinculo.discipulador_id'
				),
			),
			'limit' => 20,
			'order' => 'Clientepib.nome',
			'group' => 'Clientepib.id'
		);

		$discipulosVinculados = $this->paginate('MembroVinculo');		
		
		$listaDiscipuladores = $this->Discipulador->find("list", array(
			"fields" => "Membro.id, Membro.nome",
			"conditions" => "Membro.ativo = 1 AND Discipulador.nivel_discipulador = 4",
			"joins" => array(
				array(
					"TYPE" => "INNER",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Discipulador.id_membro"
				)
			),
			"order" => "Membro.nome",
			"recursive" => -1
		));
		
		$listaOrigens = $this->Clientepib->listaOrigem();
		
		
		$this->set(compact('listaRedesPais','totalDiscipulosComVinculo', 'listaDiscipuladores', 'discipulosVinculados', 'listaRedes', 'listaDiscipuladores', 'listaTimes', 'listaOrigens'));
	}
	
	public function naovinculados()
	{
        $this->loadModel('MembroVinculo');
        $this->loadModel('Discipulador');
        $this->loadModel('Rede');
		$this->loadModel('User');
        $this->loadModel('Clientepib');
        $listaTimes = $this->User->listaTimes();
		$listaRedes = $this->Rede->getListAllRedes();		
		$totalDiscipulosSemVinculo = $this->Clientepib->totalSemVinculoD3();								

		$where = '';
		//FILTRO
		if ($this->request->is('get')) {
			if ($this->params['url']['nome']) {
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
				$where .= ' AND Clientepib.nome LIKE "%' . $nome . '%"';
			}
			if ($this->params['url']['email']) {
				$email = $this->params['url']['email'];
				$where .= ' AND Clientepib.email LIKE "%' . $email . '%"';
			}
			if ($this->params['url']['contato']) {
				$contato = $this->params['url']['contato'];
				$where .= ' AND Clientepib.telefone_1 LIKE "%' . $contato . '%"';
			}
			if ($this->params['url']['dtcadastro']) {
				$dtcadastro = $this->params['url']['dtcadastro'];
				$where .= ' AND date_format(Clientepib.dtCadastro, "%Y-%m-%d") = "' . $dtcadastro . '"';
			}
			if ($this->params['url']['origem']) {
				$origem = $this->params['url']['origem'];
				$where .= ' AND Clientepib.origem = "' . $origem . '"';
			}
			if ($this->params['url']['id_discipulador']) {
				$discipulador = $this->params['url']['id_discipulador'];
				$where .= ' AND Clientepib.discipulador_ci LIKE "%' . $discipulador . '%"';
			}
			if ($this->params['url']['time']) {
				$time = $this->params['url']['time'];
				$where .= ' AND Clientepib.time_ci = "' . $time . '"';
			}
			if ($this->params['url']['sexo']!="") {				
				$sexo = $this->params['url']['sexo'];
				$where .= ' AND Clientepib.sexo = "' . $sexo . '"';
			}
			if($this->params['url']['idadeinicial'] != '' && $this->params['url']['idadefinal'] != ''){	
				
				$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND "'.$this->params['url']['idadefinal'].'"';								
			}else{
				if($this->params['url']['idadeinicial'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND 99';
				}
				if($this->params['url']['idadefinal'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) BETWEEN 0 AND "'.$this->params['url']['idadefinal'].'"';
				}
			}

		}
		//SELECT m.id, m.nome, mv.discipulador_id, (SELECT membros.nome FROM membros WHERE membros.id = mv.discipulador_id) as nomeDiscipulador 
		//FROM membros AS m 
		//INNER JOIN membros_vinculos AS mv ON mv.membro_id = m.id 
		//WHERE m.ativo = 1 AND m.status_id = 5 AND m.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 0 AND mv.nivel_discipulo = 2);
		
		$this->paginate = array(
			'fields' => 'DISTINCT(Clientepib.id), Clientepib.status_id, Clientepib.nome, Clientepib.observacao_agendamento, DATE_FORMAT(Clientepib.dtCadastro,"%d/%m/%Y") as datacadastro, Clientepib.status_id, Clientepib.telefone_1, Clientepib.email, Clientepib.time_ci , Clientepib.origem, Clientepib.cidade, (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade',
			'conditions' => 'Clientepib.status_discipulado = 3 AND Clientepib.is_discipulador = 1 AND Clientepib.ativo = 1 AND Clientepib.status_id = 5 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 3)'.$where,
			'limit' => 20,
			'order' => 'Clientepib.nome'
		);
		
		$listaNaoVinculadosD3 = $this->paginate('Clientepib');				

		$listaDiscipuladores = $this->Discipulador->find("list", array(
			"fields" => "Membro.id, Membro.nome",
			"conditions" => "Membro.ativo = 1 AND Discipulador.nivel_discipulador = 4",
			"joins" => array(
				array(
					"TYPE" => "INNER",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Discipulador.id_membro"
				)
			),
			"order" => "Membro.nome",
			"recursive" => -1
		));
		
		$listaOrigens = $this->Clientepib->listaOrigem();
		
		
		$this->set(compact('totalDiscipulosSemVinculo', 'listaDiscipuladores', 'listaNaoVinculadosD3', 'listaRedes', 'listaDiscipuladores', 'listaTimes', 'listaOrigens'));
	}
	
	public function addfrequencia()
	{
		$this->loadModel("Euvim");
		$this->loadModel("Euvimcadastro");
		$this->loadModel("Clientepib");
		$this->loadModel('Euvimturma');
		$this->loadModel("ClientepibEuvim");
		$this->loadModel("ClientepibEuvimCadastro");
		$this->loadModel('ClientepibEuvimTurma');
		//verificando no bd do eu vim se já existe o registro da pessoa através da id de membro
		$verifica = $this->Euvimcadastro->verificacadastro($this->request->data['Euvim']['id_membro']);
		if (!empty($verifica)) {			
			$this->Euvimcadastro->set('id', $verifica['Euvimcadastro']['id']);

			// Criado por: Leonardo Ianello Biagini
			// Data: 08/05/2023
			// Criado para migração de sistema, após finalizado migração apagar
			$this->ClientepibEuvimCadastro->set('id', $verifica['Euvimcadastro']['id']);
		} else {			
			$this->Euvimcadastro->create();

			// Criado por: Leonardo Ianello Biagini
			// Data: 08/05/2023
			// Criado para migração de sistema, após finalizado migração apagar
			$this->ClientepibEuvimCadastro->create();
		}

		//incluindo/atualizando registro na tabela de cadastros no banco de dados do eu vim(escola biblica)
		$this->Euvimcadastro->set('nome', $this->request->data['Euvim']['nome_membro']);
		$this->Euvimcadastro->set('cpf', $this->request->data['Euvim']['cpf_membro']);
		$this->Euvimcadastro->set('email', $this->request->data['Euvim']['email_membro']);
		$this->Euvimcadastro->set('id_membro', $this->request->data['Euvim']['id_membro']);
		$this->Euvimcadastro->save($this->request->data);

		// Criado por: Leonardo Ianello Biagini
		// Data: 08/05/2023
		// Criado para migração de sistema, após finalizado migração apagar
		$this->ClientepibEuvimCadastro->set('nome', $this->request->data['Euvim']['nome_membro']);
		$this->ClientepibEuvimCadastro->set('cpf', $this->request->data['Euvim']['cpf_membro']);
		$this->ClientepibEuvimCadastro->set('email', $this->request->data['Euvim']['email_membro']);
		$this->ClientepibEuvimCadastro->set('id_membro', $this->request->data['Euvim']['id_membro']);
		$this->ClientepibEuvimCadastro->save($this->request->data);
		
		
		$this->Euvim->create();
		$this->Euvim->set('id_membro', $this->request->data['Euvim']['id_membro']);
		$this->Euvim->set('nome_membro', $this->request->data['Euvim']['id_membro']);
		$this->Euvim->set('id_linha', 3);
		$this->Euvim->set('id_turma', $this->request->data['Euvim']['id_turma']);
		$this->Euvim->set('id_aula', $this->request->data['Euvim']['id_aula']);
		$this->Euvim->set('local_pai', 1);
		$this->Euvim->set('data_frequencia', $this->request->data['Euvim']['data_frequencia']);
		$this->Euvim->set('observacao', $this->request->data['Euvim']['observacao']);
		$this->Euvim->save($this->request->data);

		// Criado por: Leonardo Ianello Biagini
		// Data: 08/05/2023
		// Criado para migração de sistema, após finalizado migração apagar
		// $this->ClientepibEuvim->create();
		// $this->ClientepibEuvim->set('id_membro', $this->request->data['Euvim']['id_membro']);
		// $this->ClientepibEuvim->set('nome_membro', $this->request->data['Euvim']['id_membro']);
		// $this->ClientepibEuvim->set('id_linha', 3);
		// $this->ClientepibEuvim->set('id_turma', $this->request->data['Euvim']['id_turma']);
		// $this->ClientepibEuvim->set('id_aula', $this->request->data['Euvim']['id_aula']);
		// $this->ClientepibEuvim->set('local_pai', 1);
		// $this->ClientepibEuvim->set('data_frequencia', $this->request->data['Euvim']['data_frequencia']);
		// $this->ClientepibEuvim->set('observacao', $this->request->data['Euvim']['observacao']);
		// $this->ClientepibEuvim->save($this->request->data);

		//Total Frequencia Liderando uma célula saudavel		
		$totalLiderandoCelula = $this->Euvim->totalFrequencia($this->request->data['Euvim']['id_membro'],6);
		if($totalLiderandoCelula >= 8){
			$this->Clientepib->updateAll(
				array(
					'is_lider_celula' => 1,
				), array(
					'id' => $this->request->data['Euvim']['id_membro']
				)
			);
			//Enviar email do servir
			$dadosTurma = $this->Euvimturma->findById(141);					
			$linkReuniao = $dadosTurma['Turma']['link_reuniao'];
			$dataCompleto = $dadosTurma['Turma']['data_aula'];
			$partesData = explode(' ', $dataCompleto);
			$dataEua = $partesData[0];
			$dataBrasileiro = substr($dataEua, 8,2).'/'.substr($dataEua, 5,2).'/'.substr($dataEua, 0,4);
			$hora = substr($partesData[1], 0, 5); 

			$nomeAluno = $results['Clientepib']['nome'];
			// $emailAluno = $results['Clientepib']['email'];
			$emailAluno = 'henrique.biagini@pibcuritiba.org.br';
			$dadosHTML = array(
				'data' => $dataBrasileiro,
				'hora' => $hora,
				'link' => $linkReuniao
			);
			$Email = new CakeEmail('default');
			$Email->emailFormat('html')
					->template('conviteservir','template');			
			$Email->to($emailAluno, $nomeAluno);
			$Email->subject('PIB - Convite para reunião servir');
			$Email->viewVars($dadosHTML);
			$Email->send();
		}
		
		$this->Session->setFlash('Registro salvo com sucesso!', 'success');
		$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'perfil', $this->request->data['Euvim']['id_membro']));
	}
	
	public function vinculardiscipulador()
	{
		$this->loadModel('Clientepib');		
		$this->loadModel('Discipulador');
		$this->loadModel('MembroVinculo');
		if ($this->request->is('post')) {
			
			$hoje 					= date("Y-m-d");
			$hojeCompleto 			= date('Y-m-d H:i:s');
			//print_r($this->request->data); exit;
			$idDiscipulado 			= $this->request->data['idDiscipulado'];
			$dadosDiscipulo 		= $this->Clientepib->findById($idDiscipulado);
			$emailDiscipulo 		= $this->request->data['emailDiscipulo'];
			$nivelDiscipulo 		= $dadosDiscipulo['Clientepib']['status_discipulado'];
			$dadosDiscipulador 		= $this->request->data['Discipulador']['id_membro'];
			$pedacosDados 			= explode("-", $dadosDiscipulador);
			$idDiscipulador 		= $pedacosDados[0];
			$idrede 				= $pedacosDados[1];
			$idredefilha 			= $pedacosDados[2];
			$dadosBancoDiscipulador = $this->Clientepib->findById($idDiscipulador);
			$nivelDiscipulador		= $dadosBancoDiscipulador['Clientepib']['status_discipulado'];
			try {
				$this->MembroVinculo->create();
				$this->MembroVinculo->set('membro_id', $idDiscipulado);
				$this->MembroVinculo->set('nivel_discipulo', $nivelDiscipulo);
				$this->MembroVinculo->set('discipulador_id', $idDiscipulador);
				$this->MembroVinculo->set('nivel_discipulador', $nivelDiscipulador);
				$this->MembroVinculo->set('rede_discipulador', $idrede);
				$this->MembroVinculo->set('rede_discipulador_filha', $idredefilha);
				$this->MembroVinculo->set('data_vinculo', $hojeCompleto);
				$this->MembroVinculo->set('obs', 'Vínculo D3 criado via central integradora');
				$this->MembroVinculo->set('ativo', 1);
				$this->MembroVinculo->set('origem', 1);
				$this->MembroVinculo->save();
			} catch (Exception $e) {

				if(empty($idrede)){
					$this->Session->setFlash('Atenção, Discipulador sem rede, vincule a uma rede antes', 'info');
					$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'naovinculados'));
				}

				if(empty($idredefilha)){
					$this->Session->setFlash('Atenção, Discipulador sem rede Filha, vincule a uma rede antes', 'info');
					$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'naovinculados'));
				}

				
			}
			//Busco todos os vínculos ativos do discipulador na tabela de membros_vinculos
			$buscototalVinculos = $this->MembroVinculo->find('all', array(
				'fields' => 'COUNT(MembroVinculo.id) as total',
				'conditions' => 'MembroVinculo.discipulador_id = '.$idDiscipulador.' AND ativo = 1'
			));
			
			$totalAtualVinculos = $buscototalVinculos[0][0]["total"];
			//Atualizo o total de vínculos do discipulador
			$this->Discipulador->updateAll(
				array(
					'total_vinculo' => $totalAtualVinculos,
				), array(
					'id_membro' => $idDiscipulador
				)
			);
			
			
			$this->Session->setFlash('Vínculo realizado com sucesso!', 'success');
			$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'naovinculados'));
		}
	}
	
	public function cancelarvinculo()
	{
		$this->layout = false;
		$this->autoRender = false;
		$this->loadModel('Clientepib');
		$this->loadModel('Discipulador');
		$this->loadModel('MembroVinculo');

		if ($this->request->is('ajax')) {
			$hojeCompleto = date('Y-m-d H:i:s');
			$idDiscipulo = $this->request->data['idpib'];
			$dadosVinculo = $this->MembroVinculo->find('first', array(
				'fields' => 'MembroVinculo.*',
				'conditions' => 'MembroVinculo.ativo = 1'
			));
			//Cancelo o vínculo vigente
			$this->MembroVinculo->updateAll(
				array(
					'data_desvinculo' => '"'.$hojeCompleto.'"',
					'ativo' => 0
				), array(
					'id' => $dadosVinculo['MembroVinculo']['id']
				)
			);
			
			//Busco todos os vínculos ativos do discipulador na tabela de membros_vinculos
			$buscototalVinculos = $this->MembroVinculo->find('all', array(
				'fields' => 'COUNT(MembroVinculo.id) as total',
				'conditions' => 'MembroVinculo.discipulador_id = '.$idDiscipulador['Clientepib']['discipulador_ci'].' AND ativo = 1'
			));
			
			$totalAtualVinculos = $buscototalVinculos[0][0]["total"];
			//Atualizo o total de vínculos do discipulador
			$this->Discipulador->updateAll(
				array(
					'total_vinculo' => $totalAtualVinculos,
				), array(
					'id_membro' => $idDiscipulador['Clientepib']['discipulador_ci']
				)
			);

			$ok = 1;
			
			echo json_encode($ok);
		}
	}
	
	public function buscaaulas()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$this->loadModel('Aula');
		if ($this->request->is('ajax')) {
			$modulo = $this->request->data['modulo'];
			$listaAulas = $this->Aula->listaAulas($modulo);
			echo json_encode($listaAulas);
		}
	}
	
	public function enviard4()
	{
		$this->loadModel('Clientepib');
		$this->loadModel('MembroVinculo');
		$this->loadModel('Discipulador');		
		$this->layout = false;
		$this->autoRender = false;		
		
		if ($this->request->is('post')) {
			$observacao = $this->request->data['discipulado']['observacao'];
			if(empty($observacao) || $observacao == ""){
				$observacao = "Discipulado D3 finalizado direto via central integradora.";
			}
			$hojeCompleto = date("Y-m-d H:i:s");
			$id = $this->request->data['discipulado']['id_membro'];
			$hoje = date('Y-m-d H:i:s');

			//Busco o vínculo atual ativo
			$dadosVinculo = $this->MembroVinculo->find('first', array(
				'fields' => 'MembroVinculo.*',
				'conditions' => 'MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 3'
			));

			$idDiscipulador = $dadosVinculo['MembroVinculo']['discipulador_id'];

			//Atualizo o nível da pessoa
			$this->Clientepib->updateAll(
				array(
					'status_discipulado' => 4,
					'is_lider_celula' => 1
				), array(
					'id' => $id
				)
			);

			//Cancelo o vínculo vigente
			$this->MembroVinculo->updateAll(
				array(
					'data_desvinculo' => '"'.$hojeCompleto.'"',
					'ativo' => 0,
					'obs' => '"'.$observacao.'"'
				), array(
					'id' => $dadosVinculo['MembroVinculo']['id']
				)
			);

			//Busco todos os vínculos ativos do discipulador na tabela de membros_vinculos
			$buscototalVinculos = $this->MembroVinculo->find('all', array(
				'fields' => 'COUNT(MembroVinculo.id) as total',
				'conditions' => 'MembroVinculo.discipulador_id = '.$idDiscipulador.' AND ativo = 1'
			));
			
			$totalAtualVinculos = $buscototalVinculos[0][0]["total"];
			//Atualizo o total de vínculos do discipulador
			$this->Discipulador->updateAll(
				array(
					'total_vinculo' => $totalAtualVinculos,
				), array(
					'id_membro' => $idDiscipulador
				)
			);

			$executouNovo = $this->Clientepib->getAffectedRows();
			if ($executouNovo > 0) {
				$sucesso = 1;
			} else {
				$sucesso = 0;
			}
			
			if ($sucesso == 0) {
				$this->Session->setFlash('Erro ao liberar para o próximo nível, tente novamente', 'error');
				$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'perfil', $id));
			}
			else {
				$this->Session->setFlash('Liberado para o próximo nível!', 'success');
				$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'vinculados'));
			}
		}
	}
	
	
	
	#alterado para virada de chave em 30/04/2021
	public function definirtime(){
		$this->loadModel('Clientepibantigo');
		$this->loadModel('Clientepib');
		$this->layout = false;
		$this->autoRender = false;
		
		if($this->request->is('post')){
			$time = $this->request->data['discipulado']['time_ci'];
			$id = $this->request->data['discipulado']['id_membro'];
			$this->Clientepib->updateAll(
				array(
					'time_ci' => "'$time'",
				),  array(
					'id' => $id
				)
			);
		}
		
		$this->Session->setFlash('Time definido com sucesso!','success');
		$this->redirect(array('controller' => 'discipuladosd3', 'action' => 'perfil', $id));
	}
	
	public function registraracompanhamento(){

		
		$urlRecebida = $this->request->data['Historicoacompanhamentodiscipulo']['dados_url'];
		$urlAlterada = substr($urlRecebida,13);
		

		
		$this->loadModel("Historicoacompanhamentodiscipulo");
		
		if ($this->request->is('post')) {
			$this->Historicoacompanhamentodiscipulo->create();
			if ($this->Historicoacompanhamentodiscipulo->save($this->request->data)) {
				$this->Session->setFlash('Registro salvo com sucesso!','success');

				if($this->request->data['Historicoacompanhamentodiscipulo']['tipo_vinculo']==1){
					#$this->redirect(array('action' => 'vinculados'));
					$this->redirect(array('action' => $urlAlterada));
				}
				else{
					$this->redirect(array('action' => 'perfil', $this->request->data['Historicoacompanhamentodiscipulo']['id_discipulo']));
				}
				
			} else {
				$this->Session->setFlash('Registro não pode ser salvo, tente novamente.','error');
			}
		}
		
	}

	public function buscaInfosCompleta(){
		$this->loadModel('Clientepib');
		$this->layout = false;
		$this->autoRender = false;		
		if($this->request->is('ajax')){			
			$id = $this->request->data['id'];
			$dadosId = $this->Clientepib->buscaInfoCompleta($id);
			echo json_encode($dadosId);
		}
	}
	public function euvim(){
		$this->layout = false;
		$this->autoRender = false;
		$this->loadModel('Euvim');
		
		$idModulo = $this->request->data['idModulo'];
		$idMembro = $this->request->data['idPessoa'];

		$euvim = $this->Euvim->frequenciasAlunoTurma($idMembro, $idModulo);
		
		$totalPresenca = $this->Euvim->find('count', array(
			'conditions' => 'Euvim.id_membro = '.$idMembro.' AND Euvim.id_turma = '.$idModulo.'',
			'recursive' => -1
		));		
		//Retorno
		$html = '';
		if($euvim){
			$html .= '<table class="table">';
				$html .= '<thead>';
					$html .= '<th>Aula</th>';
					$html .= '<th>Data Realizada</th>';
				$html .= '</thead>';
				$html .= '<tbody>';
					foreach($euvim as $presenca){
						$html .= '<tr>';
							$html .= '<td>'.$presenca['Aula']['nome'].'</td>';
							$html .= '<td>'.$presenca[0]['data_frequencia'].'</td>';
						$html .= '</tr>';
					}		
				$html .= '</tbody>';
			$html .= '</table>';
		}else{
			$html .= '<p><b>Nenhum registro encontrado</b></p>';
		}
			$html .= '<input type="hidden" value="'.$totalPresenca.'" id="totalPresencaEuVim">';
		echo json_encode($html);
	}

	
}
