<?php
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'PHPExcel/Classes/PHPExcel');
class DiscipuladosController extends AppController
{
	public $uses = array(
		'Clientepib',
		'Euvim'
	);
	
	public function vinculados()
	{
		$this->loadModel('MembroVinculo');
        $this->loadModel('Discipulador');
        $this->loadModel('Rede');
		$this->loadModel('User');
        $this->loadModel('Clientepib');
		
		$listaTimes 						= $this->User->listaTimes();
		$listaRedes 						= $this->Rede->getListAllRedes();
		$listaRedesPais						= $this->Rede->getListRedePai();
		$listaDiscipuladores 				= $this->User->getListDiscipuladores();		
		$totalDiscipulosComVinculo 			= $this->MembroVinculo->totalComVinculoD1();
		$totalDiscipulosComVinculoStandBy 	= $this->Clientepib->totalComVinculoStandBy();
		//$dataInicioBusca = " AND Clientepib.dtCadastro >= '2019-01-01 00:00:00'";
		$where = '';
		$limit = 20;
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
			if ($this->params['url']['cidade']) {
				$cidade = $this->params['url']['cidade'];
				if ($cidade == 1) {
					$where .= ' AND Clientepib.cidade = "Curitiba"';
				} else {
					$where .= ' AND Clientepib.cidade != "Curitiba"';
				}
				
			}
			if ($this->params['url']['id_discipulador']) {
				$discipulador = $this->params['url']['id_discipulador'];
				$where .= ' AND MembroVinculo.discipulador_id = ' . $discipulador . '';
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
			if ($this->params['url']['trilha']) {
				$trilha = $this->params['url']['trilha'];
				$where .= ' AND Clientepib.status_id = ' . $trilha . '';
			}
			if ($this->params['url']['sexo']!="") {
				$sexo = $this->params['url']['sexo'];
				$where .= ' AND Clientepib.sexo = ' . $sexo . '';
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
			if ($this->params['url']['limite'] != '') {				
				$limite = $this->params['url']['limite'];
				if($limite == 0){
					$limit = 1500;
				}else{
					$limit = $limite;
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
				Clientepib.cpf, 
				Clientepib.observacao_agendamento, 
				DATE_FORMAT(Clientepib.dtCadastro,"%d/%m/%Y") as datacadastro, 
				Clientepib.status_id, 
				Clientepib.telefone_1, 
				Clientepib.email, 
				Clientepib.time_ci, 				
				Clientepib.cidade,
				Clientepib.origem,
				Rede.nome,
				MembroVinculo.origem, 
				MembroVinculo.data_vinculo,
				Discipulador.id, 
				Discipulador.nome,
				(YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade,
			',				
			'conditions' => 'MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1 AND Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND (Clientepib.status_id = 26 OR Clientepib.status_id = 27 OR Clientepib.status_id = 29 OR Clientepib.status_id = 30) '.$where.$dataInicioBusca,
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
			'limit' => $limit,
			'maxLimit' => 1500,
			'order' => 'Clientepib.nome',
			'group' => 'Clientepib.id'
		);
		$discipulosVinculados = $this->paginate('MembroVinculo');
		
		$listaDiscipuladores = $this->Discipulador->find("list", array(
			"fields" => "Membro.id, Membro.nome",
			"conditions" => "Membro.ativo = 1",
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
		
		$this->set(compact('listaRedesPais','totalDiscipulosComVinculoStandBy', 'totalDiscipulosComVinculo', 'listaDiscipuladores', 'discipulosVinculados', 'listaRedes', 'listaDiscipuladores', 'listaTimes', 'listaOrigens'));
	}

	public function geraPlanilha() {
		$this->loadModel('MembroVinculo');
        $this->loadModel('Discipulador');
        $this->loadModel('Rede');
		$this->loadModel('User');
        $this->loadModel('Clientepib');

		if ($this->request->is('post')) {
			$returnSql = $this->Clientepib->find("all", array(
				"fields" => 'DISTINCT(Clientepib.id), 
				Clientepib.status_id, 
				Clientepib.nome, 
				Clientepib.cpf, 
				Clientepib.observacao_agendamento, 
				(SELECT DATE_FORMAT(created,"%d/%m/%Y") FROM centralintegradora.entradas WHERE cpf = Clientepib.cpf ORDER BY id DESC LIMIT 1) as datacadastro, 
				Clientepib.status_id, 
				Clientepib.telefone_1, 
				Clientepib.email, 
				Clientepib.time_ci , 
				Clientepib.origem, 
				Clientepib.cidade, (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade',			
				'conditions' => 'Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1) AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26)' . $where,
				'limit' => 130,
				'order' => 'Clientepib.nome'
			));

			$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'TIME')
					->setCellValue('B1', 'REDE')
					->setCellValue('C1', 'DISCIPULADOR')
					->setCellValue('D1', 'DISCIPULO')
					->setCellValue('E1', 'EMAIL')
					->setCellValue('F1', 'CONTATO')
					->setCellValue('G1', 'DATA DE CADASTRO')
					->setCellValue('H1', 'IDADE')
					->setCellValue('I1', 'TRILHA')
					->setCellValue('J1', 'ORIGEM')
					->setCellValue('K1', 'CPF')
					;

					$row = 2; // Comece da segunda linha
					foreach ($returnSql as $dados ) {
						$nomediscipulador = 'Sem vínculo';
						$nomerede = 'Sem vínculo';

						if($dados['Clientepib']['status_id'] == 26){
							$status = "Ser Igreja";
						}
						elseif($dados['Clientepib']['status_id'] == 27){
							$status = "Batismo";
						}
						elseif($dados['Clientepib']['status_id'] == 30){
							$status = "standby";
						}
						else{
							$status = "Acompanhamento";
						}

						if(empty($dados['Clientepib']['origem']) || strlen($dados['Clientepib']['origem'])==1){
							if($dados['Clientepib']['origem'] == 9){
								$txtOrigem =  'Recad. MD';	
							}
							else{
								$txtOrigem = 'Origem não inserida';
							}
						}
						else{
							$txtOrigem = $dados['Clientepib']['origem'];
						}

						$objPHPExcel->getActiveSheet()
							->setCellValue('A' . $row, $dados['Clientepib']['time_ci'])
							->setCellValue('B' . $row, $nomerede)
							->setCellValue('C' . $row, $nomediscipulador)
							->setCellValue('D' . $row, $dados['Clientepib']['nome'])
							->setCellValue('E' . $row, $dados['Clientepib']['email'])
							->setCellValue('F' . $row, $dados['Clientepib']['telefone_1'])
							->setCellValue('G' . $row, $dados[0]['datacadastro'])
							->setCellValue('H' . $row, $dados[0]['idade'])
							->setCellValue('I' . $row, $status)
							->setCellValue('J' . $row, $txtOrigem)
							->setCellValue('K' . $row, $dados['Clientepib']['cpf']);
							$row++;
					}

				// Defino o cabeçalho do arquivo Excel
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="naovinculados.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit();
		}
	}



	public function naovinculados() {
		$this->loadModel('MembroVinculo');
        $this->loadModel('Discipulador');
        $this->loadModel('Rede');
		$this->loadModel('User');
        $this->loadModel('Clientepib');
		
		$listaTimes = $this->User->listaTimes();
		$listaRedes = $this->Rede->getListAllRedes();
		$listaDiscipuladores = $this->User->getListDiscipuladores();
		$totalDiscipulosSemVinculo = $this->Clientepib->totalSemVinculo();
		$totalDiscipulosSemVinculoComIniciais = $this->Clientepib->totalSemVinculoComIniciais();
		$totalDiscipulosSemVinculoSemIniciais = $this->Clientepib->totalSemVinculoSemIniciais();
		//$dataInicioBusca = " AND Clientepib.dtCadastro >= '2019-01-01 00:00:00'";
		$where = '';
		$limit = 20;
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
			if ($this->params['url']['limite'] != '') {				
				$limite = $this->params['url']['limite'];
				if($limite == 0){
					$limit = 1500;
				}else{
					$limit = $limite;
				}
				
			}
		}
		
		$this->paginate = array(
			'fields' => 'DISTINCT(Clientepib.id), 
			Clientepib.status_id, 
			Clientepib.nome, 
			Clientepib.cpf, 
			Clientepib.observacao_agendamento, 
			(SELECT DATE_FORMAT(created,"%d/%m/%Y") FROM centralintegradora.entradas WHERE cpf = Clientepib.cpf ORDER BY id DESC LIMIT 1) as datacadastro, 
			Clientepib.status_id, 
			Clientepib.telefone_1, 
			Clientepib.email, 
			Clientepib.time_ci , 
			Clientepib.origem, 
			Clientepib.cidade, (YEAR(NOW())-YEAR(Clientepib.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Clientepib.data_nascimento,5)) as idade',
			'conditions' => 'Clientepib.ativo = 1 AND Clientepib.liberado_entrevista = 0 AND Clientepib.id NOT IN(SELECT mv.membro_id FROM membros_vinculos AS mv WHERE mv.ativo = 1 AND mv.nivel_discipulo = 1) AND (Clientepib.status_id = 29 OR Clientepib.status_id = 27 OR Clientepib.status_id = 26)' . $where,
			'limit' => $limit,
			'maxLimit' => 1500,
			'order' => 'Clientepib.nome'
		);

		$listaAtendimentosNaoAgendados = $this->paginate('Clientepib');

		$listaDiscipuladores = $this->Discipulador->find("list", array(
			"fields" => "Membro.id, Membro.nome",
			"conditions" => "Membro.ativo = 1 AND Discipulador.nivel_discipulador = 3",
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

		$this->set(compact('totalDiscipulosSemVinculo', 'totalDiscipulosSemVinculoComIniciais', 'totalDiscipulosSemVinculoSemIniciais', 'listaDiscipuladores', 'listaAtendimentosNaoAgendados', 'listaRedes', 'listaDiscipuladores', 'listaTimes', 'listaOrigens'));
	}
	
	public function perfil()
	{
		$this->loadModel('Clientepib');
		$this->loadModel('Euvim');
		$this->loadModel('User');
		$this->loadModel('UsuariosPermissao');
		$this->loadModel('Discipulador');
		$this->loadModel('Historicoacompanhamentodiscipulo');
		
		$idMembro = $this->request->params['pass'][0];
				
		
		$verificaMembro = $this->Clientepib->find("first", array(
			"fields" => "Clientepib.status_id, Clientepib.liberado_entrevista, Clientepib.id ",
			"conditions" => "Clientepib.id = " . $idMembro . " AND (Clientepib.status_id = 26 OR Clientepib.status_id = 27 OR Clientepib.status_id = 29 OR Clientepib.status_id = 30)"
		));

		// echo '<pre>';
		// print_r();
		// exit;
		/*if($verificaMembro['Clientepib']['status_id'] == 29 || $verificaMembro['Clientepib']['status_id'] == 30){
			$this->Session->setFlash('O discípulo possui o status de Acompanhamento MD ou Stand-by favor, verificar', 'info');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
		}*/

		if (empty($verificaMembro)) {
			$this->Session->setFlash('Não tem permissão de acessar o perfil dessa pessoa!', 'info');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
		} 
		elseif($verificaMembro['Clientepib']['liberado_entrevista']==1) {
			$this->Session->setFlash('Essa pessoa ja foi liberada para a entrevista!', 'info');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
		}
		else {
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
			
			#consulta dados via api lineker
			$cpfApi = $dadosMembro['Clientepib']['cpf'];
			$dadosCelulaApi = $this->Discipulador->dadosCelulaApi($cpfApi);

			
			if(!$dadosMembro['MembroVinculo']['discipulador_id']){
				$discipuladorCi = 0;
			}else{
				$discipuladorCi = $dadosMembro['MembroVinculo']['discipulador_id'];
			}
			
			$dadosDiscipulador = $this->Clientepib->dadosDiscipulador($discipuladorCi);			
			#$dadosDiscipulador = $this->Clientepib->dadosDiscipulador(0);			
			
			$historicoAcompanhamentoDiscipulo = $this->Historicoacompanhamentodiscipulo->dadosHistorico($this->request->params['pass'][0]);
			
			if (($dadosMembro['MembroVinculo']['discipulador_id'] != $this->Session->read('Auth.User.id')) && ($permissaoOperador == 0)) {
				$this->Session->setFlash('Você não tem permissão de visualizar este perfil', 'error');
				$this->redirect(array('controller' => 'discipuladores', 'action' => 'index'));
			}
			
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
			
			$totalPresencasEuvimDiscipulado = $this->Euvim->find('count', array(
				'conditions' => 'Euvim.id_membro = ' . $this->request->params['pass'][0] . ' AND Euvim.id_turma = 4',
				'recursive' => -1
			));
			
			$totalPresencasEuvimIgreja = $this->Euvim->find('count', array(
				'conditions' => 'Euvim.id_membro = ' . $this->request->params['pass'][0] . ' AND Euvim.id_turma = 5',
				'recursive' => -1
			));
			
			$totalPresencasEuvim = $totalPresencasEuvimDiscipulado + $totalPresencasEuvimIgreja;
			
			$listaDiscipuladores = $this->User->getListDiscipuladores();
			
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
				
				"conditions" => "Discipulador.status = 1",
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
			$this->set(compact('statusDiscipulado','listaTimes', 'dadosDiscipulador', 'listaDiscipuladores', 'listaTodosDadosDiscipuladores', 'dadosMembro', 'euvim', 'totalPresencasEuvim', 'totalPresencasEuvimDiscipulado', 'totalPresencasEuvimIgreja', 'historicoAcompanhamentoDiscipulo', 'dadosCelulaApi'));
		}
		
	}

	public function addfrequencia()
	{
		$this->loadModel("Euvim");
		$this->loadModel("Euvimcadastro");
		$this->loadModel("ClientepibEuvim");
		$this->loadModel("ClientepibEuvimCadastro");
		
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
		$this->Euvim->set('email', $this->request->data['Euvim']['email_membro']);
		$this->Euvim->set('id_linha', 3);
		$this->Euvim->set('id_turma', $this->request->data['Euvim']['id_turma']);
		$this->Euvim->set('id_aula', $this->request->data['Euvim']['id_aula']);
		$this->Euvim->set('local_pai', 1);
		$this->Euvim->set('data_frequencia', $this->request->data['Euvim']['data_frequencia']);
		$this->Euvim->set('data_presenca_confirmada', $this->request->data['Euvim']['data_frequencia']);
		$this->Euvim->set('observacao', $this->request->data['Euvim']['observacao']);
		$this->Euvim->save($this->request->data);

		// Criado por: Leonardo Ianello Biagini
		// Data: 08/05/2023
		// Criado para migração de sistema, após finalizado migração apagar
		$this->ClientepibEuvim->create();
		$this->ClientepibEuvim->set('id_membro', $this->request->data['Euvim']['id_membro']);
		$this->ClientepibEuvim->set('nome_membro', $this->request->data['Euvim']['nome_membro']);
		$this->ClientepibEuvim->set('email', $this->request->data['Euvim']['email_membro']);
		$this->ClientepibEuvim->set('id_linha', 3);
		$this->ClientepibEuvim->set('id_turma', $this->request->data['Euvim']['id_turma']);
		$this->ClientepibEuvim->set('id_aula', $this->request->data['Euvim']['id_aula']);
		$this->ClientepibEuvim->set('local_pai', 1);
		$this->ClientepibEuvim->set('data_frequencia', $this->request->data['Euvim']['data_frequencia']);
		$this->ClientepibEuvim->set('data_presenca_confirmada', $this->request->data['Euvim']['data_frequencia']);
		$this->ClientepibEuvim->set('observacao', $this->request->data['Euvim']['observacao']);
		$this->ClientepibEuvim->save($this->request->data);
		
		$this->Session->setFlash('Registro salvo com sucesso!', 'success');
		$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $this->request->data['Euvim']['id_membro']));
	}
	
	public function vinculardiscipulador()
	{
		$this->loadModel('Clientepib');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		$this->loadModel('MembroVinculo');
		$this->loadModel('Discipulador');

		
		if ($this->request->is('post')) {
			
			$hoje = date("Y-m-d");
			$hojeCompleto = date('Y-m-d H:i:s');
			//print_r($this->request->data); exit;
			$idDiscipulado 		= $this->request->data['idDiscipulado'];
			$emailDiscipulo 	= $this->request->data['emailDiscipulo'];
			$dadosDiscipulador 	= $this->request->data['Discipulador']['id_membro'];
			$pedacosDados 		= explode("-", $dadosDiscipulador);
			$idDiscipulador 	= $pedacosDados[0];
			$idrede 			= $pedacosDados[1];
			$idredefilha 		= $pedacosDados[2];
			$dadosDiscipuladorBanco = $this->Discipulador->dadosDiscipulador($idDiscipulador);
			$nivelDiscipulador = $dadosDiscipuladorBanco['Discipulador']['nivel_discipulador'];
			
			try {
				$this->MembroVinculo->create();
				$this->MembroVinculo->set('membro_id', $idDiscipulado);
				$this->MembroVinculo->set('nivel_discipulo', 1);
				$this->MembroVinculo->set('discipulador_id', $idDiscipulador);
				$this->MembroVinculo->set('nivel_discipulador', $nivelDiscipulador);
				$this->MembroVinculo->set('rede_discipulador', $idrede);
				$this->MembroVinculo->set('rede_discipulador_filha', $idredefilha);
				$this->MembroVinculo->set('data_vinculo', $hojeCompleto);				
				$this->MembroVinculo->set('obs', 'Primeiro vínculo com discipulador criado via central integradora');
				$this->MembroVinculo->set('origem', 1);
				$this->MembroVinculo->set('ativo', 1);
				$this->MembroVinculo->save();
			} catch (Exception $e) {
				if(empty($idrede)){
					$this->Session->setFlash('Atenção, Discipulador sem rede, vincule a uma rede antes', 'info');
					$this->redirect(array('controller' => 'discipulados', 'action' => 'naovinculados'));
				}

				if(empty($idredefilha)){
					$this->Session->setFlash('Atenção, Discipulador sem rede Filha, vincule a uma rede antes', 'info');
					$this->redirect(array('controller' => 'discipulados', 'action' => 'naovinculados'));
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
			
			//RD Station -- aqui ele transforma o contato do discipulo em oportunidade no rd
			$opportunity = $this->Configrd->post_opportunity($emailDiscipulo);
			//escrever no banco a oportunidade e Discipulado Conectado
			if($opportunity == "ok"){
				$this->Logrd->insert_log(3,$idDiscipulado,$this->Session->read('Auth.User.id'));
				$this->Logrd->insert_log(9,$idDiscipulado,$this->Session->read('Auth.User.id'));
			}

			
			$this->Session->setFlash('Vínculo realizado com sucesso!', 'success');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'naovinculados'));
		}
	}
	
	public function cancelarvinculo()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$this->loadModel('Clientepib');
		$this->loadModel('MembroVinculo');
		$this->loadModel('Discipulador');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		if ($this->request->is('ajax')) {
			$hojeCompleto = date('Y-m-d H:i:s');
			$idDiscipulo = $this->request->data['idpib'];
			$dadosDiscipulador = $this->MembroVinculo->find('first', array(
				'fields' => 'Membro.nome, Membro.email, MembroVinculo.discipulador_id',
				'conditions' => 'MembroVinculo.membro_id = '.$idDiscipulo.' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
				'joins' => array(
					array(
						'type' => 'inner',
						'table' => 'membros',
						'alias' => 'Membro',
						'conditions' => 'Membro.id = MembroVinculo.discipulador_id'
					)
				)
			));
			$idDiscipulador = $dadosDiscipulador['MembroVinculo']['discipulador_id'];
			$nomeDiscipulador = $dadosDiscipulador['Membro']['nome'];
			$emailDiscipulador = $dadosDiscipulador['Membro']['email'];

			$this->MembroVinculo->updateAll(
				array(
					'data_desvinculo' => '"'.$hojeCompleto.'"',
					'obs' => "'Discipulado encerrado via central integradora'",
					'ativo' => 0
				), array(
					'membro_id' => $idDiscipulo,
					'nivel_discipulo' => 1					
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
			
			//RD Station -- aqui ele informa o RD sobre a passagem dele pelo Discipulado Desconectado
			$patch_caminhada = $this->Configrd->patch_caminhada("Discipulado Desconectado", $emailDiscipulador);
			//escrever no banco o log
			if($patch_caminhada == "ok"){
				$this->Logrd->insert_log(12,$idDiscipulo,$this->Session->read('Auth.User.id'));
			}

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
	
	public function enviarentrevista()
	{
		$this->loadModel('Clientepib');
		$this->loadModel('Rede');
		$this->loadModel('MembroVinculo');
		$this->loadModel('Discipulador');
		$this->loadModel('Linksxficha');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		$this->loadModel('LiberadoEntrevista');
		
		$this->layout = false;
		$this->autoRender = false;
		
		
		if ($this->request->is('post')) {
			
			$observacao = $this->request->data['discipulado']['observacao'];
			
			$hojeCompleto = date('Y-m-d H:i:s');
			$idDiscipulo = $this->request->data['discipulado']['id_membro'];
			$dadosDiscipulo = $this->Clientepib->findById($idDiscipulo);
			$nomeDiscipulo = $dadosDiscipulo['Clientepib']['nome'];
			$emailDiscipulo = trim($dadosDiscipulo['Clientepib']['email']);
			$telefoneDiscipulo = $dadosDiscipulo['Clientepib']['telefone_1'];
			$idLiberador = $this->Session->read('Auth.User.id');
			$acao = "Usuário " . $idLiberador ." liberou o discipulo ". $idDiscipulo." para entrevista ";	

			$this->LiberadoEntrevista->set(array("id_membro" => $idDiscipulo , "id_resp_liberacao"=>$idLiberador, "data_liberacao"=> $hojeCompleto, "acao"=>$acao, "origem"=>"centralIntegradora"));
			$this->LiberadoEntrevista->save();
			$dadosDiscipulador = $this->MembroVinculo->find('first', array(
				'fields' => 'Membro.id, Membro.nome, Membro.email, MembroVinculo.discipulador_id, MembroVinculo.rede_discipulador',
				'conditions' => 'MembroVinculo.membro_id = '.$idDiscipulo.' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
				'joins' => array(
					array(
						'type' => 'inner',
						'table' => 'membros',
						'alias' => 'Membro',
						'conditions' => 'Membro.id = MembroVinculo.discipulador_id'
					)
				)
			));
			$redeDiscipulador = $dadosDiscipulador['MembroVinculo']['rede_discipulador'];
			$idDiscipulador = $dadosDiscipulador['MembroVinculo']['discipulador_id'];
			$nomeDiscipulador = $dadosDiscipulador['Membro']['nome'];
			$emailDiscipulador = $dadosDiscipulador['Membro']['email'];
			
			//Atualizo tabela de membros com as informações de liberado para entrevista
			$this->Clientepib->updateAll(
				array(
					'obs_liberacao_entrevista' => "'$observacao'",
					'liberado_entrevista' => 1
				), array(
					'id' => $idDiscipulo
				)
			);
			
			$buscaLink = $this->Linksxficha->find("first", array(
				"fields" => "link",
				"conditions" => "id_membro = ".$idDiscipulo." AND (is_preenchido = 0 OR is_preenchido = 1)",
			));
			if(empty($buscaLink)){
				$quemGerou = 0;
				$this->Linksxficha->create();
				$this->Linksxficha->save(array(
					'link' => 'https://integracao.pibcuritiba.org.br/fichas/finalizar?cod='.md5($idDiscipulo),
					'id_membro' => $idDiscipulo,
					'quem_gerou' => $quemGerou
				));
			}else{
				if($dadosPessoa['Clientepib']['email'] != ''){
					$this->Linksxficha->updateAll(
						array(
							'is_preenchido' => 0,
						),  array(
							'id_membro' => $idDiscipulo
						)
					);
				}
			}
			$nomePastor = '';
			$dadosHTML = array(
				'nomeMembro' => $nomeDiscipulo,
				'nomePastor' => $nomePastor,
				'data' => $diaHoraEmail,
				'telefoneMembro' => $telefoneDiscipulo,
				'emailMembro' => $emailDiscipulo,
				'link' => 'https://integracao.pibcuritiba.org.br/fichas/finalizar?cod='.md5($idDiscipulo)
			);
			$preenchidoLink = $this->Linksxficha->find("first", array(					
				"conditions" => "id_membro = ".$idDiscipulo." AND (is_preenchido = 0 OR is_preenchido = 1)",
			));

			if($preenchidoLink['Linksxficha']['is_preenchido'] == 0){
				if(!empty($emailDiscipulo) || $emailDiscipulo != '' || $emailDiscipulo != null){
					$Email = new CakeEmail('default');
					$Email->emailFormat('html')->template('fichacadastro','template');
					$Email->to($emailDiscipulo, $nomeDiscipulo);
					$Email->subject('PIB - ficha de entrevista');
					$Email->viewVars($dadosHTML);
					//$Email->attachments(array('agenda.ics' => TMP . 'agenda.ics'));
					$Email->send();	
				}
			}
			
			//RD Station -- aqui ele informa o RD sobre a passagem dele para entrevista
			$patch_caminhada = $this->Configrd->patch_caminhada("Entrevista", $emailDiscipulo);
			//escrever no banco o log
			if($patch_caminhada == "ok"){
				$this->Logrd->insert_log(5,$idDiscipulo,$this->Session->read('Auth.User.id'));
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

			//Aviso ao líder da rede que uma pessoa foi liberada para entrevista
			//Created: 11/10/2022
			//By: Henrique T. Biagini
			$dadosLiderRede = $this->Rede->dadosLider($redeDiscipulador);
			$idLiderRede = $dadosLiderRede['Rede']['pastor_rede'];
			$emailLiderRede = $dadosLiderRede['Rede']['email_pastor_rede'];
			$dadosAvisoLiderRede = array(
				'nomeMembro' => $nomeDiscipulo
			);
			if(!empty($emailLiderRede) || $emailLiderRede != '' || $emailLiderRede != null){
				$Email = new CakeEmail('default');
				$Email->emailFormat('html')->template('centralintegradora_enviado_entrevista','template');
				$Email->to($emailLiderRede, $emailLiderRede);
				$Email->subject('PIB - liberado entrevista');
				$Email->viewVars($dadosAvisoLiderRede);
				//$Email->attachments(array('agenda.ics' => TMP . 'agenda.ics'));
				$Email->send();	
			}
			//Fim

			$executouNovo = $this->Clientepib->getAffectedRows();
			if ($executouNovo > 0) {
				$sucesso = 1;
			} else {
				$sucesso = 0;
			}
			
			if ($sucesso == 0) {
				$this->Session->setFlash('Erro ao liberar para entrevista, tente novamente', 'error');
				$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $id));
			}
			else {
				$this->Session->setFlash('Liberado para entrevista com sucesso!', 'success');
				$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
			}
		}
	}
	
	//antes dos 60%
	public function enviarentrevistaliberado()
	{
		/*echo "<pre>";
		print_r('sistema em manutenção');
		exit;*/
		$this->loadModel('Clientepib');
		$this->loadModel('Rede');
		$this->loadModel('Discipulador');
		$this->loadModel('Clientepibantigo');
		$this->loadModel('LiberadoEntrevista');
		$this->loadModel('Linksxficha');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		$this->loadModel('MembroVinculo');


		$this->layout = false;
		$this->autoRender = false;
				
		if ($this->request->is('post')) {
			$hojeCompleto = date("y-m-d H:i:s");
			#---------- inicio da gravação do log na tabela de liberacao para entrevista
			//gravar na tabela de integracao entrevista / liberacao as informações para log
			$idMembro 					= $this->request->data['discipulado']['id_membro'];
			$id_resp_liberacao  		= $this->request->data['discipulado']['idPessoaVinculador'];
			$idPessoaVinculadorMembro  	= $this->request->data['discipulado']['idPessoaVinculadorMembro'];
			$acao  						= "Usuário " . $idPessoaVinculadorMembro ." liberou o discipulo ". $idMembro." para entrevista ";			
			
			try {
				$this->LiberadoEntrevista->set(array("id_membro" => $idMembro , "id_resp_liberacao"=>$idPessoaVinculadorMembro, "data_liberacao"=> $hojeCompleto, "acao"=>$acao, "origem"=>"centralIntegradora"));
				$this->LiberadoEntrevista->save();
			} catch (Excecption $e) {
				$this->Session->setFlash('Erro ao liberar para entrevista, tente novamente', 'error');
				$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $id));
			}
			#---------- fim da gravação do log na tabela de liberacao para entrevista
			
			
			$observacao = $this->request->data['discipulado']['observacao'];
			if ($observacao == '') {
				$observacao = 'Liberado por ter finalizado 60% ou mais da classe igreja ou discipulado';
			}
			$id = $this->request->data['discipulado']['id_membro'];
			
			$idDiscipulador = $this->Clientepib->find("first", array(
				"fields" => "Clientepib.discipulador_ci, Clientepib.email",
				"conditions" => "Clientepib.id = " . $id . ""
			));
			$email = $idDiscipulador['Clientepib']['email'];
			$dadosDiscipulador = $this->MembroVinculo->find('first', array(
				'fields' => 'Membro.id, Membro.nome, Membro.email, MembroVinculo.discipulador_id, MembroVinculo.rede_discipulador',
				'conditions' => 'MembroVinculo.membro_id = '.$id.' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
				'joins' => array(
					array(
						'type' => 'inner',
						'table' => 'membros',
						'alias' => 'Membro',
						'conditions' => 'Membro.id = MembroVinculo.discipulador_id'
					)
				)
			));
			$redeDiscipulador = $dadosDiscipulador['MembroVinculo']['rede_discipulador']; 
			$idDiscipulador = $dadosDiscipulador['MembroVinculo']['discipulador_id'];
			$nomeDiscipulador = $dadosDiscipulador['Membro']['nome'];
			$emailDiscipulador = $dadosDiscipulador['Membro']['email'];

			$this->Clientepib->updateAll(
				array(
					'obs_liberacao_entrevista' => "'$observacao'",
					'liberado_entrevista' => 1
				), array(
					'id' => $id
				)
			);

			//Atualizo o registro do discipulado para finalizado
			// $this->MembroVinculo->updateAll(
			// 	array(
			// 		'data_desvinculo' => '"'.$hojeCompleto.'"',
			// 		'obs' => "'Discipulado finalizado com menos de 60% e enviado para entrevista via central integradora'",
			// 		'ativo' => 0
			// 	), array(
			// 		'membro_id' => $id,
			// 		'nivel_discipulo' => 1					
			// 	)
			// );
			
			//enviar ficha de integração para preencher
			//$idMembro 				= $idMembro;				

			$buscarIDMEMBRO = $this->Clientepib->find("first", array(					
				"conditions" => "id = ".$idMembro."",
			));


			$emailMembro 			= trim($buscarIDMEMBRO['Clientepib']['email']);
			$nomeMembro 			= $buscarIDMEMBRO['Clientepib']['nome'];
			$telefoneMembro 		= $buscarIDMEMBRO['Clientepib']['telefone_1'];								
			
			$buscaLink = $this->Linksxficha->find("first", array(
				"fields" => "link",
				"conditions" => "id_membro = ".$id." AND (is_preenchido = 0 OR is_preenchido = 1)",
			));
			

			if(empty($buscaLink)){
				$id_membro = 0;
				$this->Linksxficha->create();
				$this->Linksxficha->save(array(
					'link' => 'https://integracao.pibcuritiba.org.br/fichas/finalizar?cod='.md5($id),
					'id_membro' => $id,
					'quem_gerou' => $id_membro
				));
			}else{
				if($dadosPessoa['Clientepib']['email'] != ''){
					$this->Linksxficha->updateAll(
						array(
							'is_preenchido' => 0,
						),  array(
							'id_membro' => $id
						)
					);
				}
			}
			
			$nomePastor = '';
			$dadosHTML = array(
				'nomeMembro' => $nomeMembro,
				'nomePastor' => $nomePastor,
				'data' => $diaHoraEmail,
				'telefoneMembro' => $telefoneMembro,
				'emailMembro' => $emailMembro,
				'link' => 'https://integracao.pibcuritiba.org.br/fichas/finalizar?cod='.md5($id)
			);

			

			$preenchidoLink = $this->Linksxficha->find("first", array(					
				"conditions" => "id_membro = ".$id." AND (is_preenchido = 0 OR is_preenchido = 1)",
			));

			if($preenchidoLink['Linksxficha']['is_preenchido'] == 0){
				if(!empty($emailMembro) || $emailMembro != '' || $emailMembro != null){
					$Email = new CakeEmail('default');
					$Email->emailFormat('html')->template('fichacadastro','template');
					$Email->to($emailMembro, $nomeMembro);
					$Email->subject('PIB - ficha de entrevista');
					$Email->viewVars($dadosHTML);
					//$Email->attachments(array('agenda.ics' => TMP . 'agenda.ics'));
					$Email->send();	
				}
			}
			//fim do envio da ficha
			
			//RD Station -- aqui ele informa o RD sobre a passagem dele para entrevista
			$patch_caminhada = $this->Configrd->patch_caminhada("Entrevista", $email);
			//escrever no banco o log
			if($patch_caminhada == "ok"){
				$this->Logrd->insert_log(5,$idMembro,$this->Session->read('Auth.User.id'));
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

			//Aviso ao líder da rede que uma pessoa foi liberada para entrevista
			//Created: 11/10/2022
			//By: Henrique T. Biagini
			$dadosLiderRede = $this->Rede->dadosLider($redeDiscipulador);
			$idLiderRede = $dadosLiderRede['Rede']['pastor_rede'];
			$emailLiderRede = $dadosLiderRede['Rede']['email_pastor_rede'];
			$dadosAvisoLiderRede = array(
				'nomeMembro' => $nomeMembro
			);
			if(!empty($emailLiderRede) || $emailLiderRede != '' || $emailLiderRede != null){
				$Email = new CakeEmail('default');
				$Email->emailFormat('html')->template('centralintegradora_enviado_entrevista','template');
				$Email->to($emailLiderRede, $emailLiderRede);
				$Email->subject('PIB - liberado entrevista');
				$Email->viewVars($dadosAvisoLiderRede);
				//$Email->attachments(array('agenda.ics' => TMP . 'agenda.ics'));
				$Email->send();	
			}
			//Fim

			$executouNovo = $this->Clientepib->getAffectedRows();
			if ($executouNovo > 0) {
				$sucesso = 1;
			} else {
				$sucesso = 0;
			}
			
			if ($sucesso == 0) {
				$this->Session->setFlash('Erro ao liberar para entrevista, tente novamente', 'error');
				$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $id));
			} else {
				$this->Session->setFlash('Liberado para entrevista com sucesso!', 'success');
				$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
			}
		}
	}
	
	
	public function alterarintegracao(){
		$this->loadModel('Clientepibantigo');
		$this->loadModel('Clientepib');
		$this->loadModel('Logalteracaostatus');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		$this->loadModel("MembroVinculo");
		
		$this->layout = false;
		$this->autoRender = false;
		
		
		
		if($this->request->is('post')){
			$hojeCompleto = date('Y-m-d H:i:s');
			$status = $this->request->data['discipulado']['status_id'];
			$observacao = $this->request->data['discipulado']['observacao'];
			$id = $this->request->data['discipulado']['id_membro'];
			$idDiscipulador = $this->Clientepib->find("first", array(
				"fields" => "Clientepib.discipulador_ci, Clientepib.email",
				"conditions" => "Clientepib.id = " . $id . ""
			));
			$email = $idDiscipulador['Clientepib']['email'];
			#alterado para virada de chave em 30/04/2021
			if($status == 2){
				$this->Clientepib->updateAll(
					array(
						'status_id' => $status,
						'dtAlteracao' => '"'.$hojeCompleto.'"',
						'discipulador_ci' => 0,
						'rede_discipulador' => 0,
						'rede_discipulador_filha' => 0,
						'observacao' => '"'.$observacao.'"'
					),  array(
						'id' => $id
					)
				);
				//Verifico se existe Vinculo com ele, caso não é uma pessoa sem discipulador
				$verificaVinculo = $this->MembroVinculo->find('first', array(
					'conditions' => 'MembroVinculo.membro_id = '.$id.' AND MembroVinculo.nivel_discipulo = 1 AND MembroVinculo.ativo = 1'
				));
				if($verificaVinculo){
					//Atualizo o registro do discipulado para cancelado devido a alteração de status
					$this->MembroVinculo->updateAll(
						array(
							'data_desvinculo' => '"'.$hojeCompleto.'"',
							'obs' => 'Discipulado finalizado pois seu status foi alterado para frequentador',
							'ativo' => 0
						), array(
							'membro_id' => $id,
							'nivel_discipulo' => 1					
						)
					);
				}
				
				//RD Station -- aqui ele transforma o contato do discipulo em oportunidade perdida no rd
				$opportunity = $this->Configrd->post_opportunity_lost($email);
				//escrever no banco a oportunidade e Discipulado perdido
				if($opportunity == "ok"){
					$this->Logrd->insert_log(8,$id,$this->Session->read('Auth.User.id'));
					$this->Logrd->insert_log(10,$id,$this->Session->read('Auth.User.id'));
				}
			}
			else{
				$this->Clientepib->updateAll(
					array(
						'status_id' => $status,
					),  array(
						'id' => $id
					)
				);
			}
			
			$hojeLog = date("Y-m-d H:i:s");
			$this->Logalteracaostatus->create();
			$this->Logalteracaostatus->set('data', $hojeLog);
			$this->Logalteracaostatus->set('novo_status', $status);
			$this->Logalteracaostatus->set('id_atualizado', $id);
			$this->Logalteracaostatus->set('quem_atualizou', $this->Session->read('Auth.User.id'));
			$this->Logalteracaostatus->save($this->request->data);
			
			
			
		}
		if($status == 2){
			$this->Session->setFlash('Status alterado com sucesso!','success');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'vinculados'));
		}else{
			$this->Session->setFlash('Status alterado com sucesso!','success');
			$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $id));
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
		$this->redirect(array('controller' => 'discipulados', 'action' => 'perfil', $id));
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
			$dadosId = $this->Clientepib->dadosDiscipulo($id);
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
