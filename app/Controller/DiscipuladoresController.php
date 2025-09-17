<?php

App::uses('excelphp','Plugin/excelphp');
class DiscipuladoresController extends AppController {
    public $uses = array(
        'Clientepib',
        'sistemapib'
    );

    public function index(){
        $this->loadModel('Clientepib');
        $this->loadModel('User');
        $this->loadModel('Rede');
        $this->loadModel('Discipulador');
		$this->loadModel('Etaria');
		$this->loadModel('Estadocivil');
		$this->loadModel('Discipuladorede');
		
		$where = '';
		
		$idUsuario = $this->Session->read("Auth.User.id");
		
		
		
        $listaRedes = $this->Rede->getListAllRedes();
        $listaEstadoCivil = $this->Estadocivil->getListaCivil();
        $listaRedesPais = $this->Rede->getListRedePai();
		$listaRedesPaisSecundaria = $this->Rede->getListRedePaiSecundaria();
        $listaEtarias = $this->Etaria->getListEtarias();
        
        $listaDiscipuladoresFiltro = $this->Discipulador->find("list", array(
			"fields" => "Discipulador.id_membro, Membro.nome",
			"conditions" => "Membro.ativo = 1",
			"joins" => array(
				array(
					"type" => "inner",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Discipulador.id_membro"
				)
			),
			"order" => "Membro.nome"
		));

        
        //FILTRO
        if ($this->request->is('get')) {
            if($this->params['url']['nome']){
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
                $where .= ' AND Membro.nome LIKE "%'.$nome.'%"';
               // $where .= ' AND Discipulador.status = 1';
            }
            if($this->params['url']['email']){
                $email = $this->params['url']['email'];
                $where .= ' AND Membro.email LIKE "%'.$email.'%"';
            }
            if($this->params['url']['contato']){
                $contato = $this->params['url']['contato'];
                $where .= ' AND Membro.telefone_1 LIKE "%'.$contato.'%"';
            }
			if($this->params['url']['status_disponibilidade'] != ''){
				$disponibilidade = $this->params['url']['status_disponibilidade'];
				if($disponibilidade == 2){
					$where .= ' AND Discipulador.is_liberado = 0';
				}else{
					$where .= ' AND Discipulador.status_disponibilidade = '.$disponibilidade.'';
				}
			}
            if($this->params['url']['dtcadastro']){
                $dtcadastro = $this->params['url']['dtcadastro'];
                $where .= ' AND date_format(Membro.dtCadastro, "%Y-%m-%d") = "'.$dtcadastro.'"';
            }
			if($this->params['url']['discipulador']){
				$discipulador = $this->params['url']['discipulador'];
				$where .= ' AND Membro.id = '.$discipulador.'';
			}
			if($this->params['url']['id_rede'] != ''){
				$rede = $this->params['url']['id_rede'];
				$where .= ' AND Discipuladorrede.rede_id = '.$rede.'';
				
			}
			if($this->params['url']['estadocivil'] != ''){
				$rede = $this->params['url']['estadocivil'];
				$where .= ' AND TipoRelacionamento.id = '.$rede.'';
		
			}
			if($this->params['url']['dispcasal'] != ''){
				$dispcasal = $this->params['url']['dispcasal'];
				$where .= ' AND Discipulador.disponib_casal = '.$dispcasal.'';	
			}
			if($this->params['url']['sexo'] != ''){
				$sexo = $this->params['url']['sexo'];
				$where .= ' AND Membro.sexo = '.$sexo.'';	
			}
			if($this->params['url']['total_vinculo'] != ''){
				$total_vinculo = $this->params['url']['total_vinculo'];
				if($total_vinculo > 0){
					$where .= ' AND Discipulador.total_vinculo > 0';	
				}
				else{
					$where .= ' AND Discipulador.total_vinculo = 0';	
				}
				
			}
			if($this->params['url']['idadeinicial'] != '' && $this->params['url']['idadefinal'] != ''){	
				
				$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND "'.$this->params['url']['idadefinal'].'"';																
			}else{
				if($this->params['url']['idadeinicial'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND 99';
				}
				if($this->params['url']['idadefinal'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN 0 AND "'.$this->params['url']['idadefinal'].'"';
				}
			}
        }
		//Retorna id da rede que ele é líder
		$isLider = $this->Rede->isLider($idUsuario);
		//Caso ele seja líder, adiciono na condição where a id da rede para trazer apenas os seus discipuladores
		if(!empty($isLider) && $rede == ''){
			$where = " AND Discipulador.id_rede = ".$isLider['Rede']['id']."";
		}
        $this->paginate = array(
			'fields' => 'Discipulador.*,
						Membro.id,
						Membro.nome, 
						Membro.observacao_agendamento, 
						DATE_FORMAT(Membro.dtCadastro, "%d/%m/%Y") as datacadastro, 
						DATE_FORMAT(Membro.data_nascimento, "%d/%m/%Y") as dtnascimento, 
						Membro.origem, 
						Membro.telefone_1, 
						Membro.email,
						TipoRelacionamento.descricao,
						Discipuladorrede.rede_id,
						GROUP_CONCAT(`Rede`.`nome` SEPARATOR "/") AS redesdiscipulador,
						GROUP_CONCAT(`Rede`.`id` SEPARATOR "/") AS idredesdiscipuladores,
						GROUP_CONCAT(`Rede`.`id_pai` SEPARATOR "/") AS idredespais,
						(YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) as idade,
						',
            "conditions" => "Discipulador.status = 1 ".$where,
            "joins" => array(
                array(
                    "type" => "INNER",
                    "table" => "sistemapib.membros",
                    "alias" => "Membro",
                    "conditions" => "Membro.id = Discipulador.id_membro"
				),
				array(
                    "type" => "LEFT",
                    "table" => "sistemapib.tiporelacionamentos",
                    "alias" => "TipoRelacionamento",
                    "conditions" => "Membro.tiporelacionamento_id = TipoRelacionamento.id"
                ),
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
                )
            ),
            'group' => 'Membro.id',
            'limit' => 30,
            'order' => 'Membro.nome, Discipulador.total_vinculo ASC'
        );
		$listaDiscipuladores = $this->paginate('Discipulador');

        $this->set(compact('listaRedesPaisSecundaria','listaEstadoCivil','listaEtarias', 'listaDiscipuladores','listaRedes', 'listaDiscipuladoresFiltro', 'listaRedesPais'));
    }
	public function exportExcel($filtros = 0){
		$this->loadModel("Discipulador");
		$campos = explode("&", $filtros);
		$where = "";
		
		foreach($campos as $campo){
			$expld = explode("=",$campo);
			$param[$expld[0]] = $expld[1];
			
		}
		//echo "<pre>";
		//print_r($param);exit;
		//var_dump($param);
		
		if ($this->request->is('get')) {
            if($param['nome']){
				$nome = str_replace(" ", "%", $param['nome']);
                $where .= ' AND Membro.nome LIKE "%'.$nome.'%"';
                $where .= ' AND Discipulador.status = 1';
            }
            if($param['email']){
                $email = $param['email'];
                $where .= ' AND Membro.email LIKE "%'.$email.'%"';
            }
            if($param['contato']){
                $contato = $param['contato'];
                $where .= ' AND Membro.telefone_1 LIKE "%'.$contato.'%"';
            }
			if($param['status_disponibilidade'] != ''){
				$disponibilidade = $param['status_disponibilidade'];
				if($disponibilidade == 2){
					$where .= ' AND Discipulador.is_liberado = 0';
				}else{
					$where .= ' AND Discipulador.status_disponibilidade = '.$disponibilidade.'';
				}
			}
            if($param['dtcadastro']){
                $dtcadastro = $param['dtcadastro'];
                $where .= ' AND date_format(Membro.dtCadastro, "%Y-%m-%d") = "'.$dtcadastro.'"';
            }
			if($param['discipulador']){
				$discipulador = $param['discipulador'];
				$where .= ' AND Membro.id = '.$discipulador.'';
			}
			if($param['id_rede'] != ''){
				$rede = $param['id_rede'];
				$where .= ' AND DiscipuladorRede.rede_id = '.$rede.'';
				
			}
			if($param['estadocivil'] != ''){
				$rede = $param['estadocivil'];
				$where .= ' AND TipoRelacionamento.id = '.$rede.'';
		
			}
			if($param['dispcasal'] != ''){
				$dispcasal = $param['dispcasal'];
				$where .= ' AND Discipulador.disponib_casal = '.$dispcasal.'';	
			}
			if($param['sexo'] != ''){
				$sexo = $param['sexo'];
				$where .= ' AND Membro.sexo = '.$sexo.'';	
			}
			if($param['total_vinculo'] != ''){
				$total_vinculo = $param['total_vinculo'];
				if($total_vinculo > 0){
					$where .= ' AND Discipulador.total_vinculo > 0';	
				}
				else{
					$where .= ' AND Discipulador.total_vinculo = 0';	
				}
				
			}
        }
		//echo $where;exit;
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
						TipoRelacionamento.*,
						GROUP_CONCAT(`Rede`.`nome` SEPARATOR '/') AS redesdiscipulador
						",
						
				"conditions" => "Discipulador.status = 1 $where",
				"joins" => array(
                    array(
						"type" => "INNER",
						"table" => "sistemapib.membros",
						"alias" => "Membro",
						"conditions" => "Membro.id = Discipulador.id_membro"
                    ),
					array(
                    "type" => "LEFT",
                    "table" => "sistemapib.tiporelacionamentos",
                    "alias" => "TipoRelacionamento",
                    "conditions" => "Membro.tiporelacionamento_id = TipoRelacionamento.id"
					),
                    array(
						"type" => "LEFT",
						"table" => "discipuladores_redes",
						"alias" => "DiscipuladorRede",
						"conditions" => "DiscipuladorRede.discipulador_id = Discipulador.id_membro"
                    ),
					array(
						"type" => "LEFT",
						"table" => "redes",
						"alias" => "Rede",
						"conditions" => "Rede.id = DiscipuladorRede.rede_id"
					), array(
						"type" => "LEFT",
						"table" => "etarias",
						"alias" => "Etaria",
						"conditions" => "Etaria.id = Discipulador.id_faixa"
					)
				),
				'group' => 'Membro.id',
				"order" => 'Membro.nome ASC'
			));
            $xlsx = new excelphp();
            
            $retorno[0] = array('0'=>'Hrs. Disp.','1'=>'Hrs. Usadas','2'=>'Hrs. Que Disponibilizei','3'=>'Nome','4'=>'E-mail','5'=>'Telefone','6'=>'Data Nasc.','7'=>'Redes','8'=>'Disp. Casal','9'=>'Estado Civil');

            $contador = 1;

            //$listaDadosEntradas      = $this->Entrada->listaDadosEntradas($enviaInfo);
            foreach ($listaTodosDadosDiscipuladores as $listaDados) {
				if($listaDados['Discipulador']['disponib_casal'] == 1){
					$disponivelCasal = "Sim";
				}else{
					$disponivelCasal = "Não";
				}
                $retorno[$contador] = array(($listaDados['Discipulador']['horas_semanais']-$listaDados['Discipulador']['total_vinculo']), $listaDados['Discipulador']['total_vinculo'], $listaDados['Discipulador']['horas_semanais'], $listaDados['Membro']['nome'], $listaDados['Membro']['email'], $listaDados['Membro']['telefone_1'], date("d/m/Y", strtotime(($listaDados['Membro']['data_nascimento']))),$listaDados[0]['redesdiscipulador'],$disponivelCasal,$listaDados['TipoRelacionamento']['descricao']);
                $contador ++;
                
            }
                        
            $xlsx = excelphp::fromArray( $retorno );
            $xlsx->downloadAs('dados_discipuladores.xlsx');
            $this->redirect(array('controller' => 'discipuladores', 'action' => 'index'));#
        //}        
    }   
	public function discipuladoresaguardando(){
		$this->loadModel('Clientepib');
		$this->loadModel('User');
		$this->loadModel('Rede');
		$this->loadModel('Discipulador');
		$this->loadModel('Etaria');
		$this->loadModel('Estadocivil');
		
		$where = '';
		
		$idUsuario = $this->Session->read("Auth.User.id");

		$listaRedes 				= $this->Rede->getListAllRedes();
        $listaEstadoCivil 			= $this->Estadocivil->getListaCivil();
        $listaRedesPais 			= $this->Rede->getListRedePai();
		$listaRedesPaisSecundaria 	= $this->Rede->getListRedePaiSecundaria();
        $listaEtarias 				= $this->Etaria->getListEtarias();
		
		$listaDiscipuladoresFiltro 	= $this->Discipulador->find("list", array(
			"fields" => "Discipulador.id_membro, Membro.nome",
			"conditions" => "Membro.ativo = 1",
			"joins" => array(
				array(
					"type" => "inner",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Discipulador.id_membro"
				)
			),
			"order" => "Membro.nome"
		));
		
		
		//FILTRO
		if ($this->request->is('get')) {
			if($this->params['url']['nome']){
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
				$where .= ' AND Membro.nome LIKE "%'.$nome.'%"';
			}
			if($this->params['url']['email']){
				$email = $this->params['url']['email'];
				$where .= ' AND Membro.email LIKE "%'.$email.'%"';
			}
			if($this->params['url']['contato']){
				$contato = $this->params['url']['contato'];
				$where .= ' AND Membro.telefone_1 LIKE "%'.$contato.'%"';
			}
			if($this->params['url']['status_disponibilidade'] != ''){
				$disponibilidade = $this->params['url']['status_disponibilidade'];
				$where .= ' AND Discipulador.status_disponibilidade = '.$disponibilidade.'';
			}
			if($this->params['url']['dtcadastro']){
				$dtcadastro = $this->params['url']['dtcadastro'];
				$where .= ' AND date_format(Membro.dtCadastro, "%Y-%m-%d") = "'.$dtcadastro.'"';
			}
			if($this->params['url']['origem']){
				$origem = $this->params['url']['origem'];
				$where .= ' AND Membro.origem = "'.$origem.'"';
			}
			if($this->params['url']['discipulador']){
				$discipulador = $this->params['url']['discipulador'];
				$where .= ' AND Membro.id = '.$discipulador.'';
			}
			if($this->params['url']['id_rede'] != ''){
				$rede = $this->params['url']['id_rede'];
				$where .= ' AND Discipuladorrede.rede_id = '.$rede.'';
				
			}
			if($this->params['url']['idadeinicial'] != '' && $this->params['url']['idadefinal'] != ''){	
				
				$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND "'.$this->params['url']['idadefinal'].'"';																
			}else{
				if($this->params['url']['idadeinicial'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN "'.$this->params['url']['idadeinicial'].'" AND 99';
				}
				if($this->params['url']['idadefinal'] != ''){
					$where .= ' AND (YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) BETWEEN 0 AND "'.$this->params['url']['idadefinal'].'"';
				}
			}
		}
		
		$this->paginate = array(
			'fields' => 'Discipulador.*,
						Rede.nome,						
						Membro.id,
						Membro.id,
						Membro.nome,
						Membro.observacao_agendamento,
						DATE_FORMAT(Membro.dtCadastro, "%d/%m/%Y") as datacadastro,
						DATE_FORMAT(Membro.data_nascimento, "%d/%m/%Y") as dtnascimento,
						Membro.origem,
						Membro.telefone_1,
						Membro.email,
						TipoRelacionamento.descricao,
						Discipuladorrede.rede_id,
						GROUP_CONCAT(`Rede`.`nome` SEPARATOR "/") AS redesdiscipulador,
						GROUP_CONCAT(`Rede`.`id` SEPARATOR "/") AS idredesdiscipuladores,
						GROUP_CONCAT(`Rede`.`id_pai` SEPARATOR "/") AS idredespais,
						(YEAR(NOW())-YEAR(Membro.data_nascimento)) - (RIGHT(NOW(),5)<RIGHT(Membro.data_nascimento,5)) as idade,',
			"conditions" => "1=1 AND Discipulador.status = 0".$where,
			"joins" => array(
				array(
					"type" => "LEFT",
					"table" => "sistemapib.membros",
					"alias" => "Membro",
					"conditions" => "Membro.id = Discipulador.id_membro"
				),
				array(
					"type" => "LEFT",
					"table" => "sistemapib.tiporelacionamentos",
					"alias" => "TipoRelacionamento",
					"conditions" => "Membro.tiporelacionamento_id = TipoRelacionamento.id"
				),
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
				)
			),
			'group' => 'Membro.nome',
			'limit' => 30,
			'order' => 'Discipulador.total_vinculo, Membro.nome ASC'
		);
		$listaDiscipuladores = $this->paginate('Discipulador');
		
		$this->set(compact('listaRedesPaisSecundaria','listaEstadoCivil','listaEtarias', 'listaDiscipuladores','listaRedes', 'listaDiscipuladoresFiltro', 'listaRedesPais'));
		#$this->set(compact('listaEtarias', 'listaDiscipuladores','listaRedes', 'listaDiscipuladoresFiltro', 'listaRedesPais'));
	}
 
	public function add() {
		$this->loadModel('Discipulador');
		$this->loadModel('Discipuladorrede');
		if ($this->request->is('post')) {
			
			$idUsuario = $this->Session->read("Auth.User.id");
			
			$this->Discipulador->create();
			$this->Discipulador->set('cpf', $this->request->data['Discipulador']['cpf']);
			$this->Discipulador->set('id_membro', $this->request->data['Discipulador']['id_membro']);
			$this->Discipulador->set('horas_semanais', $this->request->data['Discipulador']['horas_semanais']);
			$this->Discipulador->set('status_disponibilidade', $this->request->data['Discipulador']['status_disponibilidade']);
			$this->Discipulador->set('disponib_casal', $this->request->data['Discipulador']['disponib_casal']);
			$this->Discipulador->set('id_faixa', $this->request->data['Discipulador']['id_faixa']);
			$this->Discipulador->set('is_liberado', $this->request->data['Discipulador']['is_liberado']);
			$this->Discipulador->save($this->request->data);
			$totalRedes = count($this->request->data['Discipulador']['id_rede']);
			for($i=0; $i<$totalRedes; $i++){
				
				$this->Discipuladorrede->create();
				$this->Discipuladorrede->set('discipulador_id', $this->request->data['Discipulador']['id_membro']);
				$this->Discipuladorrede->set('rede_id', $this->request->data['Discipulador']['id_rede'][$i]);
				$this->Discipuladorrede->set('rede_contagem', $this->request->data['Discipulador']['rede_contagem'][$i]);
				$this->Discipuladorrede->set('adicionado_por', $idUsuario);
				$this->Discipuladorrede->save($this->request->data);
			}
			
			$this->Session->setFlash('Discipulador salvo com sucesso!','success');
			$this->redirect(array('action' => 'index'));

		}
	}
	
	public function edit($id = null) {
		$this->loadModel('Discipulador');
		$this->loadModel('Discipuladorrede');
		$this->Discipulador->id = $this->request->data['Discipulador']['id'];
		
		if (!$this->Discipulador->exists()) {
			throw new NotFoundException(__('Discipulador inválido'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			$this->Discipuladorrede->deleteAll(array('Discipuladorrede.discipulador_id'=>$this->request->data['Discipulador']['id_membro']));
			
			$idUsuario = $this->Session->read("Auth.User.id");
			$totalRedes = count($this->request->data['Discipulador']['id_rede']);
			for($i=0; $i<$totalRedes; $i++) {
				$this->Discipuladorrede->create();
				$this->Discipuladorrede->set('discipulador_id', $this->request->data['Discipulador']['id_membro']);
				$this->Discipuladorrede->set('rede_id', $this->request->data['Discipulador']['id_rede'][$i]);
				$this->Discipuladorrede->set('rede_contagem', $this->request->data['Discipulador']['rede_contagem'][$i]);
				$this->Discipuladorrede->set('adicionado_por', $idUsuario);
				$this->Discipuladorrede->save($this->request->data);
			}
			if ($this->Discipulador->save($this->request->data)) {
				$this->Session->setFlash('Discipulador editado com sucesso!','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Discipulador não pode ser editado, tente novamente!','error');
			}
		}
	}

    public function perfil(){
		$this->loadModel('MembroVinculo');
        $this->loadModel("Discipulador");
        $this->loadModel("Clientepib");
		$this->loadModel("Rede");
		$this->loadModel("Historicoacompanhamento");
		$this->loadModel('CelulasParticipantes');
				
        $idDiscipulador = $this->request->params['pass'][0];
        $hrsDisponiveis = $this->request->params['pass'][1];

        $dadosDiscipuladorCentral = $this->Discipulador->dadosDiscipulador($idDiscipulador);
		
        /*
        $nomeLiderRede = $this->Clientepib->find("first", array(
        	"fields" => "Clientepib.nome",
			"conditions" => "Clientepib.id = ".$dadosDiscipuladorCentral['Rede']['pastor_rede'].""
		));
		*/

		#consulta em qual célula a pessoa se encontra
		$dadosCelulaApi = $this->CelulasParticipantes->listaCelulasParticipantes($idDiscipulador);
		


        $dadosDiscipuladorPib = $this->Clientepib->dadosDiscipulador($idDiscipulador);
		$historicoAcompanhamento = $this->Historicoacompanhamento->dadosHistorico($dadosDiscipuladorCentral['Discipulador']['id']);
        
        $discipulos = $this->MembroVinculo->discipulosAtivosNaoLiberados($dadosDiscipuladorCentral['Discipulador']['id_membro']);        
        $discipulosLiberados = $this->MembroVinculo->discipulosAtivosLiberados($dadosDiscipuladorCentral['Discipulador']['id_membro']);        

        $this->set(compact("nomeLiderRede", "dadosDiscipuladorCentral", "dadosDiscipuladorPib", "discipulos", "historicoAcompanhamento", "hrsDisponiveis", "discipulosLiberados", "dadosCelulaApi"));

    }
    
    public function registraracompanhamento(){
    	$this->loadModel("Historicoacompanhamento");
	
		if ($this->request->is('post')) {
			$this->Historicoacompanhamento->create();
			if ($this->Historicoacompanhamento->save($this->request->data)) {
				$this->Session->setFlash('Registro salvo com sucesso!','success');
				$this->redirect(array('action' => 'perfil'));
			} else {
				$this->Session->setFlash('Registro não pode ser salvo, tente novamente.','error');
			}
		}
  
	}
 
	public function verificacpfexiste(){
		$this->loadModel("Discipulador");
		$this->loadModel("Clientepib");
		$this->autoRender = false;
		
		if ($this->request->is("post")) {
			//Variáveis
			$cpf = $this->request->data["cpf"];
			
			//Verifico se já é um discipulador
			$isDiscipulador = $this->Discipulador->isDiscipulador($cpf);
			if($isDiscipulador > 0){
				$retorno = 1;
				echo json_encode($retorno);
			}else{
				//Busco todos os dados dele atravé do cpf na tabela de membros
				$buscaIdMembro = $this->Clientepib->buscaDadosCpf($cpf);
				if(empty($buscaIdMembro)){
					$retorno = 2;
					echo json_encode($retorno);
				}else{
					$retorno = $buscaIdMembro['Clientepib']['id'];
					echo json_encode($retorno);
					
				}
			}
			
			
		}
	}
	/*
	Data de criação: 09/09/2020
		Autor: Henrique Teodoroski Biagini
		Descrição: Função usada para alterar status do discipulador para acompanhado ou não
		Retorno: int
	*/
	
	public function atualizarstatusdiscipulador(){
    	$this->LoadModel("Discipulador");
    	$this->autoRender = false;
		if ($this->request->is("post")) {
			if(
				$this->Discipulador->updateAll(
					array(
						'is_liberado' => $_POST['liberado']
					),  array(
						'id' => $_POST['id']
					)
				)
			){
				$retorno = 1;
				echo json_encode($retorno);
			}else{
				echo json_encode($retorno);
			}
		}
	}
	public function liberaracesso(){
		$this->LoadModel("Discipulador");
		$this->autoRender = false;
		if ($this->request->is("post")) {
			if(
			$this->Discipulador->updateAll(
				array(
					'status' => $_POST['liberado']
				),  array(
					'id' => $_POST['id']
				)
			)
			){
				$retorno = 1;
				echo json_encode($retorno);
			}else{
				echo json_encode($retorno);
			}
		}
	}

	public function delete(){
		$this->layout = false;
		$this->autoRender = false;
		$this->LoadModel("Discipulador");
		$this->LoadModel("Discipuladorrede");
		$this->loadModel("Clientepib");

		if ($this->request->is("post")) {
			$id = $this->request->data["id"];
			//Excluindo todos os vínculos existentes do discipulador na tabela de membros
			if(
				$this->Clientepib->updateAll(
					array(
						'rede_discipulador' => 0,
						'rede_discipulador_filha' => 0,
						'discipulador_ci' => 0
					),  array(
						'discipulador_ci' => $id
					)
				)
			){
				if(
					//Excluindo discipulador das redes em que ele pertence 
					$this->Discipuladorrede->deleteAll(
						[
							'Discipuladorrede.discipulador_id' => $id
						],
						false
					)
				){
					if(
						//Excluindo registro do discipulador 
						$this->Discipulador->deleteAll(
							[
								'Discipulador.id_membro' => $id
							],
							false
						)
					){
						$retorno = 1;
					}else{
						$retorno = 0;
					}
				}else{
					$retorno = 0;
				}
			}else{
				$retorno = 0;
			}

			echo json_encode($retorno);

		}

	}
	
}
