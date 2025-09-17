<?php
App::uses('excelphp','Plugin/excelphp');
App::uses('CakeEmail', 'Network/Email');
class EntradasController extends AppController {

    //O que ele deve fazer antes de tudo
	public function beforeFilter() {
        parent::beforeFilter();
        //Ações permitidas sem login
        $this->Auth->allow('index');
    }

    public $uses = array(
        'Clientepib',
        'sistemapib'
    );

    public function index(){
        $this->loadModel("Entrada");
        $this->layout = "entrada";

        #parte de registro de infos
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->autoRender = false;  
            $this->Entrada->create();
            if ($this->Entrada->save($this->request->data)) {
                //$retorno = $this->Entrada->getLastInsertId();                
                echo json_encode('ok');
            } else{                
                echo json_encode('Erro ao salvar os dados1');
            }
            
        }
    }

    public function listaEntradas(){
            $this->loadModel("Entrada");
            $this->loadModel("User");
            $this->loadModel('Discipulador');
            $this->loadModel('Discipuladorrede');

            $where = "";
            if ($this->request->is('get')) {
                if($this->params['url']['nome']){
                    $nome = str_replace(" ", "%", $this->params['url']['nome']);
                    $where .= ' AND Entrada.nome LIKE "%'.$nome.'%"';
                }
                if($this->params['url']['cpf']){
                    $cpf = str_replace(" ", "%", $this->params['url']['cpf']);
                    $where .= ' AND Entrada.cpf LIKE "%'.$cpf.'%"';
                }
                if($this->params['url']['tipo']){
                    $tipo = str_replace(" ", "%", $this->params['url']['tipo']);
                    $where .= ' AND Entrada.tipo = "'.$tipo.'"';
                }
                if($this->params['url']['origem']){
                    #somente para campanha jesus é a resposta, é add "resposta"
                    if($this->params['url']['origem']=='campanha-jesus-e-a-resposta'){
                        $origem = str_replace(" ", "%", $this->params['url']['origem']);
                        $where .= ' AND Entrada.origem in ("'.$origem.'", "resposta" )';
                    }
                    else{
                        $origem = str_replace(" ", "%", $this->params['url']['origem']);
                        $where .= ' AND Entrada.origem = "'.$origem.'"';
                    }

                    #add o group by para agrupara nomes que se repetem em mais de 1 vez somente no "JORNADA"
                    if($this->params['url']['origem']=='jornada'){
                        $origem = str_replace(" ", "%", $this->params['url']['origem']);                        
                        $where .= ' AND Entrada.origem = "'.$origem.'" GROUP BY Entrada.nome';
                    }
                    else{
                        $origem = str_replace(" ", "%", $this->params['url']['origem']);
                        $where .= ' AND Entrada.origem = "'.$origem.'"';
                    }
                }
                if($this->params['url']['dadosCompletos']){
                    $infoDados = str_replace(" ", "%", $this->params['url']['dadosCompletos']);
                    if($infoDados==1){
                        $where .= ' AND Entrada.cpf != "000.000.000-00" AND Entrada.celular!="(99) 99999-9999" ';
                    }
                    else{
                        $where .= ' AND Entrada.cpf = "000.000.000-00" AND Entrada.celular ="(99) 99999-9999" ';
                    }
                }
            }

            $entradas = $this->paginate('Entrada', 
                                        array("Entrada.status=1 ".$where),
                                        array('limit'=>'15')
                                    );


            $listaTodosDadosDiscipuladores = $this->Discipulador->find("all", array(
				"fields" => "Discipulador.*,
						Etaria.*,
                        Rede.*,
						Membro.id,
						Membro.nome,
						Membro.data_nascimento,
						Membro.email,
						Membro.sexo,
						Membro.telefone_1",
				"conditions" => "Discipulador.status = 1",
				"joins" => array(
                    array(
						"type" => "INNER",
						"table" => "sistemapib.membros",
						"alias" => "Membro",
						"conditions" => "Membro.id = Discipulador.id_membro"
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
				'group' => 'Membro.nome',
				"order" => 'Discipulador.total_vinculo, Membro.nome ASC'
			));

            $listasTipo         = $this->Entrada->listaTipo();
            $usuarios           = $this->User->dadosUsuario();
            $infosReabilitar    = $this->Entrada->dadosEntradasRealizadas();
            $listaOrigem        = $this->Entrada->listarDadosOrigem();
			
			
			$this->set(compact('entradas','listasTipo','usuarios', 'listaTodosDadosDiscipuladores', 'infosReabilitar', 'listaOrigem'));
			
    }

    public function listaDesativadas(){
        $this->loadModel("Entrada");
        $this->loadModel("User");

        $conditions = array('Entrada.status' => 0);

        if ($this->request->is('get')) {

            $nome = str_replace(" ", "%", $this->request->query['nome']);
            if (!empty($nome)) {
                $conditions['Entrada.nome LIKE'] = '%' . $nome . '%';
            }

            $cpf = $this->request->query['cpf'];
            if (!empty($cpf)) {
                $conditions['Entrada.cpf LIKE'] = '%' . $cpf . '%';
            }

            $tipo = $this->request->query['tipo'];
            if (!empty($tipo)) {
                $conditions['Entrada.tipo'] = $tipo;
            }

            $origem = $this->request->query['origem'];
            if (!empty($origem)) {
                $conditions['Entrada.origem'] = $origem;
            }

            $dtIni = $this->request->query['dtIni'];
            if (!empty($dtIni)) {
                $conditions['Entrada.created >='] = $dtIni . ' 00:00:01';
            }

            $dtFim = $this->request->query['dtFim'];
            if (!empty($dtFim)) {
                $conditions['Entrada.created <='] = $dtFim . ' 23:59:59';
            }
        }
        
        $this->Paginator->settings = array(
                'Entrada' => array(
                    'conditions' => $conditions,
                    'limit' => 5000,
                    'maxLimit' => 5000, // força o máximo também
                    'order' => array('Entrada.id' => 'DESC')
                )
            );

        $infosReabilitar = $this->Paginator->paginate('Entrada');
        $this->set('infosReabilitar', $infosReabilitar);

    }
    


    #-------------------- INICO EXPORT EXCEL
    public function exportExcelNome($enviaInfo){
        $this->loadModel("Entrada");
        //if ($this->request->is('post')) {
            $xlsx = new excelphp();
            
            $retorno[0] = array('0'=>'id','1'=>'nome','2'=>'cpf','3'=>'email','4'=>'celular','5'=>'dt_nascimento','6'=>'sexo','7'=>'cep','8'=>'nome_discipulador','9'=>'contato_discipulador','10'=>'membro','11'=>'tipo','12'=>'trilha','13'=>'conhecer');

            $contador = 1;

            $listaDadosEntradas      = $this->Entrada->listaDadosEntradas($enviaInfo);
            foreach ($listaDadosEntradas as $listaDados) {
                $retorno[$contador] = array($listaDados['Entrada']['id'], $listaDados['Entrada']['nome'], $listaDados['Entrada']['cpf'], $listaDados['Entrada']['email'], $listaDados['Entrada']['celular'], date("d/m/Y", strtotime(($listaDados['Entrada']['dt_nascimento']))),$listaDados['Entrada']['sexo'],$listaDados['Entrada']['cep'],$listaDados['Entrada']['nome_discipulador'],$listaDados['Entrada']['contato_discipulador'],$listaDados['Entrada']['membro'],$listaDados['Entrada']['tipo'],$listaDados['Entrada']['trilha'],$listaDados['Entrada']['conhecer'],);
                $contador ++;
                
            }
            $xlsx = excelphp::fromArray( $retorno );
            $xlsx->downloadAs('dados_entrada.xlsx');
            $this->redirect(array('controller' => 'entradas', 'action' => 'listaEntradas'));#
        //}        
    }   

    public function exportExcelCpf($enviaInfo){
        $this->loadModel("Entrada");
        //if ($this->request->is('post')) {
            $xlsx = new excelphp();
            $retorno[0] = array('0'=>'id','1'=>'nome','2'=>'cpf','3'=>'email','4'=>'celular','5'=>'dt_nascimento','6'=>'sexo','7'=>'cep','8'=>'nome_discipulador','9'=>'contato_discipulador','10'=>'membro','11'=>'tipo','12'=>'trilha','13'=>'conhecer');
            $contador = 1;

            $listaDadosEntradas      = $this->Entrada->listaDadosEntradasCpf($enviaInfo);
            foreach ($listaDadosEntradas as $listaDados) {
                $retorno[$contador] = array($listaDados['Entrada']['id'], $listaDados['Entrada']['nome'], $listaDados['Entrada']['cpf'], $listaDados['Entrada']['email'], $listaDados['Entrada']['celular'], date("d/m/Y", strtotime(($listaDados['Entrada']['dt_nascimento']))),$listaDados['Entrada']['sexo'],$listaDados['Entrada']['cep'],$listaDados['Entrada']['nome_discipulador'],$listaDados['Entrada']['contato_discipulador'],$listaDados['Entrada']['membro'],$listaDados['Entrada']['tipo'],$listaDados['Entrada']['trilha'],$listaDados['Entrada']['conhecer'],);
                $contador ++;
                
            }
                        
            $xlsx = excelphp::fromArray( $retorno );
            $xlsx->downloadAs('dados_entrada.xlsx');
            $this->redirect(array('controller' => 'entradas', 'action' => 'listaEntradas'));#
        //}        
    }   
    

    public function exportExcelTipo($enviaInfo){
        $this->loadModel("Entrada");
        //if ($this->request->is('post')) {
            $xlsx = new excelphp();
            $retorno[0] = array('0'=>'id','1'=>'nome','2'=>'cpf','3'=>'email','4'=>'celular','5'=>'dt_nascimento','6'=>'sexo','7'=>'cep','8'=>'nome_discipulador','9'=>'contato_discipulador','10'=>'membro','11'=>'tipo','12'=>'trilha','13'=>'conhecer');
            $contador = 1;

            $listaDadosEntradas      = $this->Entrada->listaDadosEntradasTipo($enviaInfo);
            foreach ($listaDadosEntradas as $listaDados) {
                $retorno[$contador] = array($listaDados['Entrada']['id'], $listaDados['Entrada']['nome'], $listaDados['Entrada']['cpf'], $listaDados['Entrada']['email'], $listaDados['Entrada']['celular'], date("d/m/Y", strtotime(($listaDados['Entrada']['dt_nascimento']))),$listaDados['Entrada']['sexo'],$listaDados['Entrada']['cep'],$listaDados['Entrada']['nome_discipulador'],$listaDados['Entrada']['contato_discipulador'],$listaDados['Entrada']['membro'],$listaDados['Entrada']['tipo'],$listaDados['Entrada']['trilha'],$listaDados['Entrada']['conhecer'],);
                $contador ++;
            }

            $xlsx = excelphp::fromArray( $retorno );
            $xlsx->downloadAs('dados_entrada.xlsx');
            $this->redirect(array('controller' => 'entradas', 'action' => 'listaEntradas'));#
        //}        
    } 
    
    public function exportExcelGeral(){
        $this->loadModel("Entrada");
        //if ($this->request->is('post')) {
            $xlsx = new excelphp();
            $retorno[0] = array('0'=>'id','1'=>'nome','2'=>'cpf','3'=>'email','4'=>'celular','5'=>'dt_nascimento','6'=>'sexo','7'=>'cep','8'=>'nome_discipulador','9'=>'contato_discipulador','10'=>'membro','11'=>'tipo','12'=>'trilha','13'=>'conhecer', '14' => 'origem');
            $contador = 1;

            $listaDadosEntradas      = $this->Entrada->listaDadosEntradasGeral();
            foreach ($listaDadosEntradas as $listaDados) {
                $retorno[$contador] = array($listaDados['Entrada']['id'], $listaDados['Entrada']['nome'], $listaDados['Entrada']['cpf'], $listaDados['Entrada']['email'], $listaDados['Entrada']['celular'], date("d/m/Y", strtotime(($listaDados['Entrada']['dt_nascimento']))),$listaDados['Entrada']['sexo'],$listaDados['Entrada']['cep'],$listaDados['Entrada']['nome_discipulador'],$listaDados['Entrada']['contato_discipulador'],$listaDados['Entrada']['membro'],$listaDados['Entrada']['tipo'],$listaDados['Entrada']['trilha'],$listaDados['Entrada']['conhecer'],$listaDados['Entrada']['origem']);
                $contador ++;
            }

            $xlsx = excelphp::fromArray( $retorno );
            $xlsx->downloadAs('dados_entrada.xlsx');
            $this->redirect(array('controller' => 'entradas', 'action' => 'listaEntradas'));#
        //}        
    } 

    public function exportExcelGeralDesativada(){
        $this->loadModel("Entrada");

        $nome   = isset($this->request->query['nome'])      ? $this->request->query['nome'] : '';
        $cpf    = isset($this->request->query['cpf'])       ? $this->request->query['cpf'] : '';
        $tipo   = isset($this->request->query['tipo'])      ? $this->request->query['tipo'] : '';
        $origem = isset($this->request->query['origem'])    ? $this->request->query['origem'] : '';
        $dtIni  = isset($this->request->query['dtIni'])     ? $this->request->query['dtIni'] : '';
        $dtFim  = isset($this->request->query['dtFim'])     ? $this->request->query['dtFim'] : '';

        $conditions = array();

        if (!empty($nome)) {
            $conditions['Entrada.nome LIKE'] = '%' . $nome . '%';
        }
        if (!empty($cpf)) {
            $conditions['Entrada.cpf LIKE'] = '%' . $cpf . '%';
        }
        if (!empty($tipo)) {
            $conditions['Entrada.tipo'] = $tipo;
        }
        if (!empty($origem)) {
            $conditions['Entrada.origem'] = $origem;
        }
        if (!empty($dtIni)) {
            $conditions['Entrada.created >='] = $dtIni . ' 00:00:00';
        }
        if (!empty($dtFim)) {
            $conditions['Entrada.created <='] = $dtFim . ' 23:59:59';
        }

        $retorno = array();
        $retorno[0] = array(
            'id', 'nome', 'cpf', 'email', 'celular', 'dt_nascimento', 'sexo',
            'cep', 'nome_discipulador', 'contato_discipulador', 'membro', 'tipo',
            'trilha', 'conhecer', 'origem', 'created'
        );

        $contador = 1;
        $listaDadosEntradas = $this->Entrada->listaDadosEntradasGeralDesativada($conditions);

        foreach ($listaDadosEntradas as $listaDados) {
            $retorno[$contador] = array(
                $listaDados['Entrada']['id'],
                $listaDados['Entrada']['nome'],
                $listaDados['Entrada']['cpf'],
                $listaDados['Entrada']['email'],
                $listaDados['Entrada']['celular'],
                !empty($listaDados['Entrada']['dt_nascimento']) ? date("d/m/Y", strtotime($listaDados['Entrada']['dt_nascimento'])) : '',
                $listaDados['Entrada']['sexo'],
                $listaDados['Entrada']['cep'],
                $listaDados['Entrada']['nome_discipulador'],
                $listaDados['Entrada']['contato_discipulador'],
                $listaDados['Entrada']['membro'],
                $listaDados['Entrada']['tipo'],
                $listaDados['Entrada']['trilha'],
                $listaDados['Entrada']['conhecer'],
                $listaDados['Entrada']['origem'],
                $listaDados['Entrada']['created']
            );
            $contador++;
        }

        $xlsx = excelphp::fromArray($retorno);
        $xlsx->downloadAs('dados_entrada.xlsx');
    
        // Não é necessário redirecionar após download
        $this->autoRender = false;
        $this->response->send();
    }

#-------------------- FIM EXPORT EXCEL


#---------------------------------------- DELETAR ITENS
    public function deletarItem(){
        $this->loadModel("Entrada");
        $this->layout = false;
        $this->autoRender = false;  

        #parte de registro de infos
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $this->Entrada->updateAll(array("status"=>"0"),array("Entrada.id"=>"$id"));
            echo json_encode($id);                
        }
    }


#---------------------------- INI PESQUISA CPF
    public function pesquisaCpf(){
        $this->loadModel('Clientepib');        
        $this->loadModel('StatusMembro');
        $this->layout       = false;
        $this->autoRender   = false;  

        #parte de registro de infos 
        if ($this->request->is('ajax')) {
            
            $cpf    = $this->request->data['cpf'];
            $cpfFormatado = "";
            $somenteNumeros = preg_replace('/[^0-9]/', '', $cpf);
            if(strlen($somenteNumeros)==11){
                $cpf 		  = $somenteNumeros;
                $parte_um     = substr($cpf, 0, 3);
                $parte_dois   = substr($cpf, 3, 3);
                $parte_tres   = substr($cpf, 6, 3);
                $parte_quatro = substr($cpf, 9, 2);
                $cpfFormatado = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";
            }


            $nome   = $this->request->data['nome'];

            #buca as informações no banco do sistema pib na tabela membro onde o status_id = '5' e que ENCONTRE por CPF
            #CONDITIONS estava verificando status 5 onde é igual a membro
            #"conditions" => "Clientepib.ativo = 1 and Clientepib.cpf = '$cpf' AND Clientepib.status_id='5' ",                
            $dadosMembro = $this->Clientepib->find("all", array(
                "fields" => "Clientepib.*, StatusM.descricao",  
                "joins" => array(
					array(
						"type" => "LEFT",
						"table" => "statusmembros",
						"alias" => "StatusM",
						"conditions" => "StatusM.id = Clientepib.status_id"
                    ),           
                ),     
                "conditions" => "Clientepib.ativo = 1 and Clientepib.cpf = '$cpfFormatado'",
                "order" => "Clientepib.nome"
            ));

            $html = "";

                if(!empty($dadosMembro)){
                    if($dadosMembro[0]['Clientepib']['status_id']!=5){
                        foreach($dadosMembro as $dados){
                            $html.="<tr>
                                        <th scope='row'>".$dados['StatusM']['descricao']."</th>
                                        <td>".$dados['Clientepib']['nome']."<input type='hidden' value='membro' id='idStatusMembro'></td>";
                                        if($dados['Clientepib']['data_nascimento']=='0000-00-00'){
                                            $html.="<td>".date("d/m/Y", strtotime(($dados['Clientepib']['data_nascimento'])))."</td>";
                                        }
                                        else{
                                            $html.="<td>não informada</td>";
                                        }
                                        
                                        if($dados['Clientepib']['cpf']){
                                            $html.="<td>".$dados['Clientepib']['cpf']."</td>
                                                        <td style='white-space: nowrap; text-align: center;'>
                                                            <button class='btn btn-primary' aria-hidden='true'  title='A mesma pessoa' alt='A mesma pessoa' data-toggle='modal' data-target='.mesmaPessoa' onclick='comparaUsuarios(".$dados['Clientepib']['id'].")' ><i class='fas fa-arrows-alt-h'></i></button>
                                                        </td>
                                    </tr>";
                                        }
                                        else{
                                            $html.="<td>".$dados['Clientepib']['cpf']."</td>
                                                        <td style='white-space: nowrap; text-align: center;'>
                                                            <button class='btn btn-primary' aria-hidden='true'  title='Manter na lista' alt='Manter na lista' onclick='fecharModal()'><i class='fas fa-align-justify'></i></button>
                                                    </td>
                                    </tr>";
        
                                        }
                                        
                        }
                        
                        echo json_encode(array(0=>$html, "tipo"=>'membro', "statusPessoa"=>$dados['Clientepib']['status_id'], "idPesquisa"=>$dados['Clientepib']['id'] ));
                    }
                    //alteracao realizada 09/02/21 - felipe macedo
                    else{
                        if($dadosMembro[0]['Clientepib']['status_id']==5){
                            //retorna que a pessoa ja é membra
                            echo json_encode(array(0=>$html, "tipo"=>"membro", "statusPessoa"=>5));
                        }
                    }
            }
            //realiza a busca por NOME utilizando o Match
            else{

                $dadosMembro = $this->Clientepib->query("SELECT m.id, st.descricao,  
                                                                m.nome,
                                                                m.cpf,
                                                                m.data_nascimento,
                                                                m.status_id,  MATCH(nome) AGAINST ('".$nome."' IN BOOLEAN MODE) AS score
                                                            FROM membros AS m
                                                            LEFT JOIN statusmembros as st ON (st.id = m.status_id )
                                                            WHERE (MATCH(m.nome) AGAINST ('".$nome."' IN BOOLEAN MODE)) AND m.ativo = 1 
                                                            ORDER BY score
                                                            DESC LIMIT 10");
                foreach ($dadosMembro as $dados) {
                        if($dados['m']['data_nascimento']=='0000-00-00'){
                            $dtNasci = "data não informada";
                        }
                        else{
                            $dtNasci = date("d/m/Y", strtotime(($dados['m']['data_nascimento'])));
                        }
					$html.="<tr>
								<th scope='row'>".$dados['st']['descricao']."</th>
								<td>".$dados['m']['nome']."</td>
								<td>".$dtNasci."</td>
								<td>".$dados['m']['cpf']."</td>";
                                $html.="<td style='white-space: nowrap;'>                                            
                                            <button class='btn btn-primary' aria-hidden='true'  title='A mesma pessoa' alt='A mesma pessoa' data-toggle='modal' data-target='.mesmaPessoa' onclick='comparaUsuarios(".$dados['m']['id'].")' >
                                                <i class='fas fa-arrows-alt-h'></i>
                                            </button>
									    </td>
							</tr>";
				}
				echo json_encode(array(0=>$html, "tipo"=>"", "statusPessoa"=>$dados['m']['status_id'] ));
			}//fim else
        }//fim if ajax
    }
    #----------------------------- FIM PESQUISA CPF 



    # -------------------------------- CARREGO INFOS NA PARTE DA MODAL SUPERIOR 
    public function listaEntradasId(){

            $this->loadModel('Clientepibantigo');        
            $this->loadModel('Clientepib');        
            $this->loadModel("Entrada");
            $this->layout = false;
            $this->autoRender = false;  

            #parte de registro de infos
            if ($this->request->is('ajax')) {
                
                $id             = $this->request->data['id'];                   
                $tipo           = $this->request->data['tipo'];
                $cpf            = $this->request->data['cpf'];                
                $nomeEnviado    = $this->request->data['nome'];
                $nmDisci        = $this->request->data['nmDisci'];
                $contatoDisci   = $this->request->data['contatoDisci'];
                $trilha         = $this->request->data['trilhas'];
                $origem         = $this->request->data['origem'];

                        if(($tipo == 'conhecer_jesus') || ($tipo == 'ser_igreja')){

                            if( ($nmDisci!="") || ($contatoDisci!="") ){
                                
                                $listasTipo     = $this->Entrada->listaEntradasId($id);
                                $dadosMembros   = $this->Clientepib->buscaCpfMembros($cpf);                            
                                $cpfMembros     = $dadosMembros[0]['Clientepib']['cpf'];

                                $html = "";
                                foreach($listasTipo as $lista){                                    
                                    $html .= "
                                            <table class='table'>
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>CPF</th>
                                                        <th>Email</th>                        
                                                        <th>Dt.Nascimento</th>
                                                        <th>Celular</th>
                                                        <th>Sexo</th>
                                                        <th>Cep</th>
                                                        <th>Tipo</th>
                                                        <th>Origem</th>
                                                        ";
                                                        if($nmDisci){
                                                            $html.="<th> Discipulador <br/> Sugerido</th>";
                                                        }
                                                        $html.="<th><center>Ações</center></th>
                                                    </tr>
                                                </thead>
                                                <tbody style='font-size: 13px;'>
                                                    <tr>
                                                        <td>".$lista['Entrada']['nome']."
                                                            <input type='hidden' value=".$lista['Entrada']['id']." id='idUsuarioEntrada'>
                                                            <input type='hidden' value=".$lista['Entrada']['trilha']." id='idUsuarioEntrada'>
                                                            <input type='hidden' value=".$lista['Entrada']['origem']." id='idUsuarioOrigem'>
                                                        </td>
                                                        <td>".$lista['Entrada']['cpf']."</td>
                                                        <td>".$lista['Entrada']['email']."</td>
                                                        <td>".date("d/m/Y", strtotime(($lista['Entrada']['dt_nascimento'])))."</td>
                                                        <td>".$lista['Entrada']['celular']."</td>
                                                        <td>".$lista['Entrada']['sexo']."</td>
                                                        <td>".$lista['Entrada']['cep']."</td>
                                                        <td>".$lista['Entrada']['tipo']."</td>
                                                        <td>".$origem."</td>
                                                        ";
                                                        if($nmDisci){
                                                            $html.="<th><b style='color:red'>".$nmDisci."</b></th>";
                                                        }
                                                        // 
                                                $html.="<td style='white-space: nowrap;' >
                                                            <button class='btn btn-danger'  aria-hidden='true'  title='Excluir' onclick='excluirItem(".$lista['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>";
                                                        $html.="
                                                                    <button class='btn btn-success' aria-hidden='true'  title='Vincular discipulador' alt='Vincular Discipulador' data-target='#vincularDiscipulador' data-toggle='modal' onclick='vincularDetalhes(".$lista['Entrada']['id'].",".$lista['Entrada']['trilha'].",\"{$lista['Entrada']['nome_discipulador']}\")'>
                                                                        <i class='far fa-comments'></i>
                                                                    </button>
                                                                ";
                                                                if($cpf != $cpfMembros){    
                                                                $html.="<button class='btn btn-warning' aria-hidden='true' title='Não encontrei a pessoa na lista' alt='Não encontrei a pessoa na lista' data-toggle='modal' data-target='.naoEncontrado' onclick='detalhesListaEntradas(".$lista['Entrada']['id'].")'>
                                                                        <i class='fas fa-user-cog'></i>
                                                                    </button>";
                                                                }

                                                $html.="</td>
                                                    </tr>
                                                </tbody>
                                            </table>";
                                    }
                                    echo json_encode(array(0=>$html, "idDisicpulador"=>$discipulador_id ));  
                                    exit;
                            }
                            else{
                                
                                    //entra aqui quando não tem o nome do  discipulador 
                                    //entra qui quando não tem o contato do discipulador 

                                     //não esta preenchido o nome e nem o contato do discipulador
                                    #buca as informações no banco do sistema pib na tabela membro onde o status_id = '5' e que ENCONTRE por CPF
                                    #CONDITIONS estava verificando status 5 onde é igual a membro                                
                                    $dadosMembro = $this->Clientepib->find("all", array(
                                        "fields" => "Clientepib.*, MembroVinculo.*",
                                        "joins"  =>array(
                                            array(
                                                "type"      =>"LEFT",
                                                "table"     =>"membros_vinculos",
                                                "alias"     =>"MembroVinculo",
                                                "conditions"=>"MembroVinculo.membro_id = Clientepib.id"
                                            )
                                        ),
                                        "conditions" => "Clientepib.cpf = '$cpf'",
                                        "order" => "Clientepib.nome"
                                    ));

                                    #alterado para virada de chave em 30/04/2021                
                                    $idDiscipuladorVinculador = $dadosMembro[0]['MembroVinculo']['discipulador_id'];

                                    #alterado para virada de chave em 30/04/2021
                                    $discipuladorEscolhido = $this->Clientepib->find("all", array(
                                        "fields" => "Clientepib.*",                    
                                        "conditions" => "Clientepib.id = '$idDiscipuladorVinculador'",
                                        "order" => "Clientepib.nome"
                                    ));

                                    #alterado para virada de chave em 30/04/2021
                                    if(!empty($dadosMembro)){
                                        foreach($dadosMembro as $dados){
                                            $tipoPessoaBase  = $dados['Clientepib']['status_id'];//pega o status de informação sobre o que a pessoa é na base de membros
                                            $discipulador_id = $dados['MembroVinculo']['discipulador_id'];//pega o id do discipulador caso a pessoa ja tenha um vinculo
                                        }
                                    }

                                    $listasTipo = $this->Entrada->listaEntradasId($id);

                                    $html = "";
                                    foreach($listasTipo as $lista){

                                        #alterado para virada de chave em 30/04/2021
                                        if(!empty($dadosMembro[0]['MembroVinculo']['discipulador_id'])){

                                            $msgDiscipulador = "<span class='alert alert-danger col-md-6' role='alert'><b>Atenção! </b>Essa pessoa ja esta vinculada a um discipulador, favor tratar no painel de operador!<b> Discipulador Registrado:</b> ". substr($discipuladorEscolhido[0]['Clientepib']['nome'],0,15)."</span>";
                                        }
                                        else{
                                            #não exibe nada
                                        }

                                        $html .= "
                                                $msgDiscipulador
                                                <table class='table'>
                                                    <thead>
                                                        <tr>
                                                            <th>Nome</th>
                                                            <th>CPF</th>
                                                            <th>Email</th>                        
                                                            <th>Dt.Nascimento</th>
                                                            <th>Celular</th>
                                                            <th>Sexo</th>
                                                            <th>Cep</th>
                                                            <th>Tipo</th>
                                                            <th>Origem</th>
                                                            ";
                                                            if($nmDisci){
                                                                $html.="<th> Discipulador <br/> Sugerido</th>";
                                                            }
                                                            $html.="<th>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style='font-size: 13px;'>
                                                        <tr>
                                                            <td>".$lista['Entrada']['nome']."
                                                                <input type='hidden' value=".$lista['Entrada']['id']." id='idUsuarioEntrada'>
                                                                <input type='hidden' value=".$lista['Entrada']['trilha']." id='idUsuarioEntrada'>
                                                                <input type='hidden' value=".$lista['Entrada']['origem']." id='idUsuarioOrigem'>
                                                            </td>
                                                            <td>".$lista['Entrada']['cpf']."</td>
                                                            <td>".$lista['Entrada']['email']."</td>
                                                            <td>".date("d/m/Y", strtotime(($lista['Entrada']['dt_nascimento'])))."</td>
                                                            <td>".$lista['Entrada']['celular']."</td>
                                                            <td>".$lista['Entrada']['sexo']."</td>
                                                            <td>".$lista['Entrada']['cep']."</td>
                                                            <td>".$lista['Entrada']['tipo']."</td>
                                                            <td>".$origem."</td>";
                                                            if($nmDisci){
                                                                $html.="<th><b style='color:red'>".$nmDisci."</b></th>";
                                                            }
                                                            
                                                    $html.="<td style='white-space: nowrap;' >
                                                                <button class='btn btn-danger'  aria-hidden='true'  title='Excluir' onclick='excluirItem(".$lista['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>";
                                                                
                                                            if(($tipo == 'conhecer_jesus') || ($tipo == 'ser_igreja') || ($tipo == 'reconciliar') || ($tipo == 'discipulado')){
                                                                if($tipoPessoaBase != 5){//so vai mostar o botão de não encontrado na base se a pessoa for diferente de 5 (diferente de membro)                                            
                                                                    if($dadosMembro){
                                                                        #entra caso encontre os dados do membro
                                                                        if($dadosMembro[0]['MembroVinculo']['discipulador_id'] == 0){
                                    
                                                                            if(empty($dadosMembro[0]['Clientepib']['cpf'])){
                                                                                $html.="&nbsp;<button class='btn btn-danger' aria-hidden='true'  title='Não encontrei a pessoa na lista' alt='Não encontrei a pessoa na lista' data-toggle='modal' data-target='.naoEncontrado' onclick='detalhesListaEntradas(".$lista['Entrada']['id'].")'><i class='fas fa-not-equal'></i></button>";
                                                                            }
                                                                            else{
                                                                                #não mostra nada por que ja encontrou no cpf na base antiga de membros
                                                                            }
                                                                            
                                                                        }
                                                                        else{
                                                                            if($lista['Entrada']['tipo']=='andamento'){
                                                                                $html.="&nbsp;<button class='btn btn-success' aria-hidden='true'  title='Vincular discipulador' alt='Vincular Discipulador' data-target='#vincularDiscipulador' data-toggle='modal' onclick='vincularDetalhes(".$lista['Entrada']['id'].",".$lista['Entrada']['trilha'].",\"{$lista['Entrada']['nome_discipulador']}\")'><i class='far fa-comments'></i></button>";
                                                                            }
                                                                        }
                                                                    }
                                                                    #exibe o botão de não encontrado somente se o retorno da informação for diferente de preenchido
                                                                    else{
                                                                        $html.="&nbsp;<button class='btn btn-danger' aria-hidden='true'  title='Não encontrei a pessoa na lista' alt='Não encontrei a pessoa na lista' data-toggle='modal' data-target='.naoEncontrado' onclick='detalhesListaEntradas(".$lista['Entrada']['id'].")'><i class='fas fa-not-equal'></i></button>";
                                                                    }
                                                                    
                                                                }
                                                            }
                                                            else{
                                    
                                                                if(!$discipulador_id){
                                                                    if( ($lista['Entrada']['tipo']=='andamento') || ($lista['Entrada']['tipo']=='discipulo') ){
                                                                        $html.="&nbsp;<button class='btn btn-success' aria-hidden='true'  title='Vincular discipulador' alt='Vincular Discipulador' data-target='#vincularDiscipulador' data-toggle='modal' onclick='vincularDetalhes(".$lista['Entrada']['id'].",".$lista['Entrada']['trilha'].",\"{$lista['Entrada']['nome_discipulador']}\")'><i class='far fa-comments'></i></button>";
                                                                    }
                                                                }
                                                                else{
                                                                    //ja vinculado a um discipulador
                                                                }
                                                            }

                                                    $html.="</td>
                                                        </tr>
                                                    </tbody>
                                                </table>";
                                    }
                                    echo json_encode(array(0=>$html, "idDisicpulador"=>$discipulador_id ));   

                            }
                        }
                        else{
                                    //não esta preenchido o nome e nem o contato do discipulador
                                    #buca as informações no banco do sistema pib na tabela membro onde o status_id = '5' e que ENCONTRE por CPF
                                    #CONDITIONS estava verificando status 5 onde é igual a membro                                
                                    $dadosMembro = $this->Clientepib->find("all", array(
                                        "fields" => "Clientepib.*, MembroVinculo.*",
                                        "joins"  =>array(
                                            array(
                                                "type"      =>"LEFT",
                                                "table"     =>"membros_vinculos",
                                                "alias"     =>"MembroVinculo",
                                                "conditions"=>"MembroVinculo.membro_id = Clientepib.id"
                                            )
                                        ),
                                        "conditions" => "Clientepib.cpf = '$cpf'",
                                        "order" => "Clientepib.nome"
                                    ));

                                    #alterado para virada de chave em 30/04/2021                
                                    $idDiscipuladorVinculador = $dadosMembro[0]['MembroVinculo']['discipulador_id'];

                                    #alterado para virada de chave em 30/04/2021
                                    $discipuladorEscolhido = $this->Clientepib->find("all", array(
                                        "fields" => "Clientepib.*",                    
                                        "conditions" => "Clientepib.id = '$idDiscipuladorVinculador'",
                                        "order" => "Clientepib.nome"
                                    ));

                                    #alterado para virada de chave em 30/04/2021
                                    if(!empty($dadosMembro)){
                                        foreach($dadosMembro as $dados){
                                            $tipoPessoaBase  = $dados['Clientepib']['status_id'];//pega o status de informação sobre o que a pessoa é na base de membros
                                            $discipulador_id = $dados['MembroVinculo']['discipulador_id'];//pega o id do discipulador caso a pessoa ja tenha um vinculo
                                        }
                                    }

                                    $listasTipo = $this->Entrada->listaEntradasId($id);

                                    $html = "";
                                    foreach($listasTipo as $lista){

                                        #alterado para virada de chave em 30/04/2021
                                        if(!empty($dadosMembro[0]['MembroVinculo']['discipulador_id'])){

                                            $msgDiscipulador = "<span class='alert alert-danger col-md-6' role='alert'><b>Atenção! </b>Essa pessoa ja esta vinculada a um discipulador, favor tratar no painel de operador!<b> Discipulador Registrado:</b> ". substr($discipuladorEscolhido[0]['Clientepib']['nome'],0,15)."</span>";
                                        }
                                        else{
                                            #não exibe nada
                                        }

                                        $html .= "
                                                $msgDiscipulador
                                                <table class='table'>
                                                    <thead>
                                                        <tr>
                                                            <th>Nome</th>
                                                            <th>CPF</th>
                                                            <th>Email</th>                        
                                                            <th>Dt.Nascimento</th>
                                                            <th>Celular</th>
                                                            <th>Sexo</th>
                                                            <th>Cep</th>
                                                            <th>Tipo</th>";
                                                            if($nmDisci){
                                                                $html.="<th> Discipulador <br/> Sugerido</th>";
                                                            }
                                                            $html.="<th>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style='font-size: 13px;'>
                                                        <tr>
                                                            <td>".$lista['Entrada']['nome']."
                                                                <input type='hidden' value=".$lista['Entrada']['id']." id='idUsuarioEntrada'>
                                                                <input type='hidden' value=".$lista['Entrada']['trilha']." id='idUsuarioEntrada'>
                                                            </td>
                                                            <td>".$lista['Entrada']['cpf']."</td>
                                                            <td>".$lista['Entrada']['email']."</td>
                                                            <td>".date("d/m/Y", strtotime(($lista['Entrada']['dt_nascimento'])))."</td>
                                                            <td>".$lista['Entrada']['celular']."</td>
                                                            <td>".$lista['Entrada']['sexo']."</td>
                                                            <td>".$lista['Entrada']['cep']."</td>
                                                            <td>".$lista['Entrada']['tipo']."</td>";
                                                            if($nmDisci){
                                                                $html.="<th><b style='color:red'>".$nmDisci."</b></th>";
                                                            }
                                                            
                                                    $html.="<td style='white-space: nowrap;' >
                                                                <button class='btn btn-danger'  aria-hidden='true'  title='Excluir' onclick='excluirItem(".$lista['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>";
                                                                
                                                            if(($tipo == 'conhecer_jesus') || ($tipo == 'ser_igreja') || ($tipo == 'reconciliar') || ($tipo == 'discipulado')){
                                                                if($tipoPessoaBase != 5){//so vai mostar o botão de não encontrado na base se a pessoa for diferente de 5 (diferente de membro)                                            
                                                                    if($dadosMembro){
                                                                        #entra caso encontre os dados do membro
                                                                        if($dadosMembro[0]['MembroVinculo']['discipulador_id'] == 0){
                                    
                                                                            if(empty($dadosMembro[0]['Clientepib']['cpf'])){
                                                                                $html.="&nbsp;<button class='btn btn-danger' aria-hidden='true'  title='Não encontrei a pessoa na lista' alt='Não encontrei a pessoa na lista' data-toggle='modal' data-target='.naoEncontrado' onclick='detalhesListaEntradas(".$lista['Entrada']['id'].")'><i class='fas fa-not-equal'></i></button>";
                                                                            }
                                                                            else{
                                                                                #não mostra nada por que ja encontrou no cpf na base antiga de membros
                                                                            }
                                                                            
                                                                        }
                                                                        else{
                                                                            if($lista['Entrada']['tipo']=='andamento'){
                                                                                $html.="&nbsp;<button class='btn btn-success' aria-hidden='true'  title='Vincular discipulador' alt='Vincular Discipulador' data-target='#vincularDiscipulador' data-toggle='modal' onclick='vincularDetalhes(".$lista['Entrada']['id'].",".$lista['Entrada']['trilha'].",\"{$lista['Entrada']['nome_discipulador']}\",\"{$lista['Entrada']['origem']}\")'><i class='far fa-comments'></i></button>";
                                                                            }
                                                                        }
                                                                    }
                                                                    #exibe o botão de não encontrado somente se o retorno da informação for diferente de preenchido
                                                                    else{
                                                                        $html.="&nbsp;<button class='btn btn-danger' aria-hidden='true'  title='Não encontrei a pessoa na lista' alt='Não encontrei a pessoa na lista' data-toggle='modal' data-target='.naoEncontrado' onclick='detalhesListaEntradas(".$lista['Entrada']['id'].")'><i class='fas fa-not-equal'></i></button>";
                                                                    }
                                                                    
                                                                }
                                                            }
                                                            else{
                                    
                                                                if(!$discipulador_id){
                                                                    if( ($lista['Entrada']['tipo']=='andamento') || ($lista['Entrada']['tipo']=='discipulo') ){
                                                                        $html.="&nbsp;<button class='btn btn-success' aria-hidden='true'  title='Vincular discipulador' alt='Vincular Discipulador' data-target='#vincularDiscipulador' data-toggle='modal' onclick='vincularDetalhes(".$lista['Entrada']['id'].",".$lista['Entrada']['trilha'].",\"{$lista['Entrada']['nome_discipulador']}\",\"{$lista['Entrada']['origem']}\")'><i class='far fa-comments'></i></button>";
                                                                    }
                                                                }
                                                                else{
                                                                    //ja vinculado a um discipulador
                                                                }
                                                            }

                                                    $html.="</td>
                                                        </tr>
                                                    </tbody>
                                                </table>";
                                    }
                                    echo json_encode(array(0=>$html, "idDisicpulador"=>$discipulador_id ));   
                        }
            }// fim do ajax
    }

    #---------------------------------------------------- PARTE DA IMPORTAÇÃO DA PLAN EM EXCEL
    public function importaExcel(){
            $this->loadModel("Entrada");
            $this->layout       = false;
            $this->autoRender   = false;  

        if ($this->request->is('post')) {
            # --------------------------- parte que importa o arquivo para dentro do sistema 
            $arquivo1 	= $this->request->data['Entrada']['arquivoExcel'];
            $this->request->data['Entrada']['arquivoExcel'] = $this->request->data['Entrada']['arquivoExcel']['name'];
            #arquivo 1
            $dataArq = date('Ymd_hss');
            
            $nomeTemporario1 = $arquivo1["tmp_name"];
            if ($nomeTemporario1 != ""){
                $nomeArquivo1 = $arquivo1["name"];                
                $arqCaminho = WWW_ROOT . 'files' . DS . $dataArq."_".$nomeArquivo1;
                move_uploaded_file($nomeTemporario1, WWW_ROOT . 'files' . DS . $dataArq."_".$nomeArquivo1);
            }

            # ----------------------------- INICIO DA LEITURA DO ARQUIVO -----------------------------
            $arquivo = fopen ($arqCaminho, 'r');

                $contador = 0;
                $erroArq = [];
                while(!feof($arquivo)){
                    // Pega os dados da linha
                    $linha = fgets($arquivo, 1024);

                    // Divide as Informaes das celular para poder salvar
                    $dados = explode(';', $linha);

                    if(@$dados){        
                        $col0 = $dados[0]; # nome
                        $col1 = $dados[1]; # cpf
                        $col2 = $dados[2]; # email
                        $col3 = $dados[3]; # celular
                        $col4 = $dados[4]; # dt_nacimento
                        $col5 = $dados[5]; # sexo
                        $col6 = $dados[6]; # cep
                        $col7 = $dados[7]; # pais
                        $col8 = $dados[8]; # estado
                        $col9 = $dados[9]; # cidade
                        $col10 = $dados[10]; # bairro
                        $col11 = $dados[11]; # rua
                        $col12 = $dados[12]; # numero
                        $col13 = $dados[13]; # complemento
                        $col14 = $dados[14]; # nome_discipulador
                        $col15 = $dados[15]; # contato_discipulador
                        $col16 = $dados[16]; # membro
                        $col17 = $dados[17]; # tipo
                        $col18 = $dados[18]; # trilha
                        $col19 = $dados[19]; # conhecer
                    }

                    
                    if($contador >=1){
                        #-------------------------------------- ini parte de validacoes --------------------------------------
                        $ano = substr($col4,6);
                        $mes = substr($col4,3,2);
                        $dia = substr($col4,0,2);
                        $dataNacimento = $ano."-".$mes."-".$dia; #transforma a data no formato padrao mysql
                        
                        
                        if(!empty($col0)){
                            # ----------------------- validacao da coluna sexo
                            switch ($col5) {
                                case 'M':                                
                                    $col5;
                                break;
                                case 'F':
                                    $col5;
                                break;
                                default:
                                    $erroArq1 =array('erro na linha '.$contador.' coluna Sexo esta com valor errado');
                                break;
                            }

                            # ----------------------- validacao da coluna membro
                            switch ($col16) {
                                case '0':
                                    $col16;
                                break;
                                case '1':
                                    $col16;
                                break;
                                
                                default:
                                    $erroArq2 =array('erro na linha '.$contador.' coluna Membro esta com valor errado');
                                break;
                            }

                            # ----------------------- validacao da coluna trilha
                            switch ($col18) {
                                case '0':
                                    $col18;
                                break;
                                case '1':
                                    $col18;
                                break;
                                default:
                                    $erroArq3 =array('erro na linha '.$contador.' coluna Trilha esta com valor errado');
                                break;
                            }

                        }
                        $return = array_merge($erroArq,$erroArq1,$erroArq2,$erroArq3);
                        if($dataNacimento=='--')$dataNacimento='2020-01-01';
                        
                            if(empty($col19)){ $col19 =0; }
                            if(empty($col18)){ $col18 =0; }
                            if(empty($col16)){ $col16 =0; }

                            $dataInsert = date('Y-m-d H:i:s');
                            try {

                                $this->Entrada->query("INSERT INTO `entradas` (`nome`, `cpf`, `email`, `celular`, `dt_nascimento`, `sexo`, `cep`, `pais`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `nome_discipulador`, `contato_discipulador`, `membro`, `tipo`, `trilha`, `conhecer`, `created`, `status`) VALUES ( '$col0', '$col1', '$col2', '$col3', '$dataNacimento', '$col5', '$col6', '$col7', '$col8', '$col9', '$col10', '$col11', '$col12', '$col13', '$col14', '$col15', '$col16', '$col17', '$col18', '$col19', '$dataInsert', 1)");
                            } 
                            catch (Exception $e) {
                                //erro
                            }
                            
                    }//fim if

                    $contador++;
                }//fim while
                // Fecha arquivo aberto
            fclose($arquivo);

            //entra aqui se tiver tudo certo
            $this->Entrada->query("DELETE FROM `entradas` WHERE `entradas`.`cpf`=''");
            $this->redirect(array('controller' => 'entradas', 'action' => 'listaEntradas', '?retornoErro=' => 'ok'));
            
            

        }    
    }

    public function infoEntradaId(){

        $this->loadModel("Entrada");
        $this->layout = false;
        $this->autoRender = false;  

        #parte de registro de infos
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];            
            $listasTipo = $this->Entrada->listaEntradasId($id);
            
            $html = "";
            foreach($listasTipo as $lista){                    
                $html.="<table class='table'>
                <thead>
                    <tr>
                        <td><b>Nome</b></td>
                        <td>".$lista['Entrada']['nome']."</td>
                    </tr>
                    <tr>
                        <td><b>CPF</b></td>
                        <td>".$lista['Entrada']['cpf']."</td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td>".$lista['Entrada']['email']."</td>
                    </tr>
                    <tr>
                        <td><b>Dt. Nascimento</b></td>
                        <td>".date("d/m/Y", strtotime(($lista['Entrada']['dt_nascimento'])))."</td>
                    </tr>
                    <tr>
                        <td><b>Celular</b></td>
                        <td>".$lista['Entrada']['celular']."</td>
                    </tr>";

                    if($lista['Entrada']['sexo']=='M'){
                        $html.="<tr>
                                    <td><b>Sexo</b></td>
                                    <td>Masculino</td>
                                </tr>";
                    }
                    else{
                        $html.="<tr>
                                    <td><b>Sexo</b></td>
                                    <td>Feminino</td>
                                </tr>";
                    }                    
                $html.="<tr>
                        <td><b>Cep</b></td>
                        <td>".$lista['Entrada']['cep']."</td>
                    </tr>
                    <tr>
                        <td><b>Tipo</b></td>
                        <td>".$lista['Entrada']['tipo']."</td>
                    </tr>
                </thead>
            </table>";
                }
            //echo json_encode($html);            
            echo json_encode(array(0=>$html, "result"=>$lista));    
        }    
    }

    public function cadastroNaoMembro(){

        $this->loadModel('LogEntradas');
        $this->loadModel("Entrada");
        $this->loadModel('Clientepib');
        $this->loadModel('Clientepibantigo');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
        $this->layout = false;
        $this->autoRender = false;  

        #parte que insere as informações das pessoas que não sao membro no banco de membros
        if ($this->request->is('ajax')) {

            $idPessoaCad        = $this->request->data['Entrada']['id'];
            $nome               = $this->request->data['Entrada']['nome'];
            $cpf                = $this->request->data['Entrada']['cpf'];
            $data_nascimento    = $this->request->data['Entrada']['dt_nacimento'];
            $celular            = $this->request->data['Entrada']['celular'];
            $email              = $this->request->data['Entrada']['email'];
            $sexo               = $this->request->data['Entrada']['sexo'];
            $cep                = $this->request->data['Entrada']['cep'];            
            $estado             = $this->request->data['Entrada']['estado'];
            $cidade             = $this->request->data['Entrada']['cidade'];
            $bairro             = $this->request->data['Entrada']['bairro'];
            $rua                = $this->request->data['Entrada']['rua'];
            $tipo               = $this->request->data['Entrada']['tipo'];
            $origem             = $this->request->data['Entrada']['origem'];

            //chama a func de validação de cpf
            $this->validaCPF($cpf);
            
            switch ($tipo) {
                case 'ser_igreja':      $status_id = 26; break; 
                case 'conhecer_jesus':  $status_id = 27; break;
                case 'reconciliar':     $status_id = 26; break;
                case 'discipulado':     $status_id = 27; break;
                case 'andamento':       $status_id = 26; break;
                default:                $status_id = 29; break;
            }
            $cpfFormatado = "";
            $user=$legendario['cpf'];
            $somenteNumeros = preg_replace('/[^0-9]/', '', $cpf);
            if(strlen($somenteNumeros)==11){
                $cpf 		  = $somenteNumeros;
                $parte_um     = substr($cpf, 0, 3);
                $parte_dois   = substr($cpf, 3, 3);
                $parte_tres   = substr($cpf, 6, 3);
                $parte_quatro = substr($cpf, 9, 2);
                $cpfFormatado = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";
            }
            
            try {
                #faz um insert na tabela de membros da pib com o novo "membro/pessoa"
                $dataCadastro = date('Y-m-d');
                $encontraCPF  = $this->Clientepib->buscaCpfMembros($cpfFormatado);
                if($encontraCPF[0]['Clientepib']['cpf']){
                    #caso TENHA encontrado o cpf na base então retorna com a msg de erro
                    echo json_encode(0);
                    exit;
                }
                else{

                    #caso nao tenha encontrado o cpf na base então insere
                    $ativo = 1;
                    
                    $this->Clientepib->create();
                    $this->Clientepib->set("nome", $nome);
                    $this->Clientepib->set("data_nascimento", $data_nascimento);
                    $this->Clientepib->set("sexo", $sexo);
                    $this->Clientepib->set("status_id", $status_id);
                    $this->Clientepib->set("dtCadastro", $dataCadastro);
                    $this->Clientepib->set("cpf", $cpfFormatado);
                    $this->Clientepib->set("cep", $cep);
                    $this->Clientepib->set("telefone_1", $celular);
                    $this->Clientepib->set("email", $email);
                    $this->Clientepib->set("bairro", $bairro);
                    $this->Clientepib->set("estado", $estado);
                    $this->Clientepib->set("cidade", $cidade);
                    $this->Clientepib->set("ativo", $ativo);
                    $this->Clientepib->set("origem", $origem);
                    $this->Clientepib->save();
                    $lastInsertID = $this->Clientepib->getLastInsertId();
                }

				$this->reenvioAcessoPessoas($lastInsertID,$cpfFormatado,$email);
                $dataUpdate = date('Y-m-d H:i:s');
                $membro_id  = $this->Session->read('membro_id');
                $id         = $this->Session->read('Auth.User.id');
                $this->LogEntradas->set(array("id_user" => $id, "id_membro" => $membro_id , "data"=> $dataUpdate,  "tipo" => 'insert' ,"id_alterado"=>$idPessoaCad, "function"=>"cadastroNaoMembro"));
                $this->LogEntradas->save();

				//RD Station -- aqui ele informa o RD sobre a passagem dele pela validação
				$patch_caminhada = $this->Configrd->patch_caminhada("Validação", $email);
				//escrever no banco o log
				if($patch_caminhada == "ok"){
					$this->Logrd->insert_log(2,$lastInsertID,$this->Session->read('Auth.User.id'));
				}

                $this->Entrada->updateAll(array("Entrada.status" => "0"), array("Entrada.id" => "$idPessoaCad"));
                echo json_encode(1);
            } 
            catch (Exception $e) {                
                echo json_encode('Exceção capturada: ',  $e->getMessage(), "\n");
            }
            #reforça a atualizacao do status da nova entrada para 0
            $this->Entrada->updateAll(array("Entrada.status" => "0"), array("Entrada.id" => "$idPessoaCad"));
        }        
    }

    public function vincularTopoEntradasId(){
        $this->loadModel("Entrada");
        $this->layout = false;
        $this->autoRender = false;  
        #parte de registro de infos
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];            
            
            $listasTipo = $this->Entrada->listaEntradasId($id);
            
            $html = "";
            foreach($listasTipo as $lista){                    
                $html .= "
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Email</th>                        
                                    <th>Dt.Nascimento</th>
                                    <th>Celular</th>
                                    <th>Sexo</th>
                                    <th>Cep</th>
                                    <th>Tipo</th>
                                    <th>Discipulador <br/> Sugerido</th>
                                </tr>
                            </thead>
                            <tbody style='font-size: 13px;'>
                                <tr>
                                    <td>".$lista['Entrada']['nome']."</td>
                                    <td>".$lista['Entrada']['cpf']."</td>
                                    <td>".$lista['Entrada']['email']."</td>
                                    <td>".date("d/m/Y", strtotime(($lista['Entrada']['dt_nascimento'])))."</td>
                                    <td>".$lista['Entrada']['celular']."</td>
                                    <td>".$lista['Entrada']['sexo']."</td>
                                    <td>".$lista['Entrada']['cep']."</td>
                                    <td>".$lista['Entrada']['tipo']."</td>
                                    <td><b style='color:red'>".$lista['Entrada']['nome_discipulador']."</b></td>
                                </tr>
                            </tbody>
                        </table>";
                }
            echo json_encode(array(0=>$html, "result"=>$lista));               
        }
    }
    
    public function vincularDiscipuladores(){


        $this->loadModel('Clientepib');
        $this->loadModel('Clientepibantigo');
        $this->loadModel("Entrada");
        $this->loadModel("Discipulador");
        $this->loadModel("Discipuladorrede");
        $this->loadModel('LogEntradas');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');
		$this->loadModel('MembroVinculo');

        $this->layout       = false;
        $this->autoRender   = false;  

        #parte de registro de infos
        if ($this->request->is('ajax')) {

            $idEntrada               = $this->request->data['Entrada']['id'];
            $nome                    = $this->request->data['Entrada']['nome'];
            $cpf                     = $this->request->data['Entrada']['cpf'];
            $data_nascimento         = $this->request->data['Entrada']['dt_nascimento'];
            $celular                 = $this->request->data['Entrada']['celular'];
            $email                   = $this->request->data['Entrada']['email'];
            $cep                     = $this->request->data['Entrada']['cep'];
            $tipo                    = $this->request->data['Entrada']['tipo'];
            $trilha                  = $this->request->data['Entrada']['trilha'];
            $origemEntrada           = $this->request->data['Entrada']['origem'];

            $this->validaCPF($cpf);

            $idDiscipuladorEscolha   = $this->request->data['Entrada']['idDiscipuladorSelecionado'];
            //verifica se o discipulador possui rede filha
            try{
                #seleciona a rede filha em que o discipulador esta cadastrado
                $redeFilhaSel   = $this->Discipuladorrede->buscaRedeFilhaDiscipulador($idDiscipuladorEscolha);
                $redeFilha      = $redeFilhaSel[0]['Discipuladorrede']['rede_id'];
            } 
            catch(Exception $e){
                echo json_encode('2');
                exit;
            }

            //verifica se o discipulador possui rede pai
            try {
                $redeMaster     = $this->Discipuladorrede->buscaRedePaiDiscipulador($idDiscipuladorEscolha,$redeFilha );
                $redePai        = $redeMaster[0]['Discipuladorrede']['rede_contagem'];
            }
            catch(Exception $e){
                echo json_encode('0');
                exit;
            }

            #busca o endereco via api
            $reg                = simplexml_load_file("http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep=" . $cep);
            $dados['sucesso']   = (string) $reg->resultado;
            $dados['rua']       = (string) $reg->tipo_logradouro . ' ' . $reg->logradouro;
            $dados['bairro']    = (string) $reg->bairro;
            $dados['cidade']    = (string) $reg->cidade;
            $dados['estado']    = (string) $reg->uf;
            $bairro             = $dados['bairro'];
            $cidade             = $dados['cidade'];
            $rua                = $dados['rua'];
            $estado             = $dados['estado'];

            $idDiscipulador     = $this->request->data['Entrada']['idDiscipuladorSelecionado'];
            $idRede             = $this->request->data['Discipulador']['id_rede'];
            $dataVinculo        = date("Y-m-d");

            if($this->request->data['Entrada']['sexo']== 'F'){ $sexo = 1;} else{ $sexo = 0; }

            switch ($tipo) {
                case 'ser_igreja':      $status_id = 26; break; 
                case 'conhecer_jesus':  $status_id = 27; break;
                case 'reconciliar':     $status_id = 26; break;
                case 'discipulado':     $status_id = 27; break;
                case 'andamento':       $status_id = 26; break;
                default:                $status_id = 29; break;
            }
            #Verifica o tipo = andamento para saber se a trilha é igual a ser igreja ou discipulado
            # ser_igreja = 26 e discipulado = 27
            if($tipo == 'andamento'){
                if($trilha==0){ $status_id = 27; } else{ $status_id = 26; }
            }

            try {
                #verifica o tipo da pessoa se é frenquentador ou não                
                $tipoPessoaBanco    = $this->Clientepib->query("SELECT membros.status_id, membros.id, membros.origem FROM membros WHERE membros.cpf = '$cpf' ");
                $idPessoa           = $tipoPessoaBanco[0]['membros']['id'];
                $origem             = $tipoPessoaBanco[0]['membros']['origem'];

                //caso não encontre um tipo de origem então
                /*if(empty($origem)){
                    $origem = "atemporal_discipulado";
                }*/
                $origem = $origemEntrada;

                #se o status for diferente de 5 (diferente de membro)
                if($tipoPessoaBanco[0]['membros']['status_id']!=5){

                    $dtVinculo   = date('Y-m-d');
                    //se existir id da pessoa então atualiza as informacoes se nao insere no banco as informacoes                     
                    if($idPessoa){
                        
                        $dtAlteracao = date('Y-m-d H:i:s');
                        #atualiza a tabela de membros nova
                        $this->Clientepib->query("UPDATE `membros` 
                                                    SET `nome`                      = '$nome',
                                                        `data_nascimento`           = '$data_nascimento',
                                                        `sexo`                      = '$sexo',
                                                        `telefone_1`                = '$celular',
                                                        `email`                     = '$email',
                                                        `cep`                       = '$cep',
                                                        `status_id`                 = '$status_id',
                                                        `dtAlteracao`               = '$dtAlteracao',
                                                        `origem`                    = '$origem', 
                                                        `ativo`                     = '1'  
                                                    WHERE `membros`.`id`            = $idPessoa");

                        #busca os dados de nivel do discipulador
                        $nivel              = $this->Clientepib->query("SELECT membros.status_discipulado FROM membros WHERE membros.id = '$idDiscipulador' ");
                        $nivelDiscipulador  = $nivel[0]['membros']['status_discipulado'];

                        #insere na tbl membros vinculos o novo discipulo como nivel = 1
                        $hoje = date("Y-m-d H:i:s");                            
                        $this->Clientepib->query("INSERT INTO membros_vinculos (membro_id, nivel_discipulo, discipulador_id, nivel_discipulador, rede_discipulador, rede_discipulador_filha, data_vinculo, obs, origem) VALUES ('$idPessoa', '1', '$idDiscipulador', '$nivelDiscipulador', '$redePai', '$redeFilha', '$hoje', 'Primeiro vínculo com discipulador criado via central integradora - Entradas', '1')");

                        #pega a quantidade de pessoas vinculadas
                        $retornoQtd = $this->Clientepib->query("SELECT COUNT(id) AS qtd FROM membros_vinculos WHERE membros_vinculos.discipulador_id = '$idDiscipulador'");                        
                        $qtdHoras   = $retornoQtd[0][0]['qtd'];

                        #aualiza as horas na tbl discipuladores
                        $this->Discipulador->query("UPDATE `centralintegradora`.`discipuladores` SET `total_vinculo`='".$qtdHoras."' WHERE `id_membro`=".$idDiscipulador."");

                        #PARTE QUE REALIZA O LOG DO ENTRADAS 2 update 
                        $dataUpdate = date('Y-m-d H:i:s');
                        $membro_id  = $this->Session->read('membro_id');
                        $id         = $this->Session->read('Auth.User.id');
                        $this->LogEntradas->set(array("id_user" => $id, "id_membro" => $membro_id , "data"=> $dataUpdate,  "tipo" => 'update' ,"id_alterado"=>$idPessoa, "function"=>"vincularDiscipuladores"));
                        $this->LogEntradas->save();

						//RD Station -- aqui ele transforma o contato do discipulo em oportunidade no rd
						$opportunity = $this->Configrd->post_opportunity($email);
						$patch_caminhada = $this->Configrd->patch_caminhada("Validação", $email);
						//escrever no banco a oportunidade e Discipulado Conectado
						if($opportunity == "ok"){
							$this->Logrd->insert_log(2,$idPessoa,$this->Session->read('Auth.User.id'));
							$this->Logrd->insert_log(3,$idPessoa,$this->Session->read('Auth.User.id'));
							$this->Logrd->insert_log(9,$idPessoa,$this->Session->read('Auth.User.id'));
						}
                    }
                    else{
                        
                        #insert tbl membros sistema novo
                        try {
                            $dataCadastro = date('Y-m-d');

                            $encontraCPF = $this->Clientepib->buscaCpfMembros($cpf);
                            if($encontraCPF[0]['Clientepib']['cpf']){
                                #caso TENHA encontrado o cpf na base de membros então retorna com a msg de erro
                                echo json_encode(4);
                                exit;
                            }
                            else{
                                #insere o usuario na tbl de membros
                                #caso nao tenha encontrado o cpf na base então insere
                                $ativo = 1;
                                $this->Clientepib->create();
                                $this->Clientepib->set("nome", $nome);
                                $this->Clientepib->set("data_nascimento", $data_nascimento);
                                $this->Clientepib->set("sexo", $sexo);
                                $this->Clientepib->set("status_id", $status_id);
                                $this->Clientepib->set("dtCadastro", $dataCadastro);
                                $this->Clientepib->set("cpf", $cpf);
                                $this->Clientepib->set("telefone_1", $celular);
                                $this->Clientepib->set("email", $email);
                                $this->Clientepib->set("cep", $cep);
                                $this->Clientepib->set("bairro", $bairro);
                                $this->Clientepib->set("estado", $estado);
                                $this->Clientepib->set("cidade", $cidade);
                                $this->Clientepib->set("origem", $origem);
                                $this->Clientepib->set("ativo", $ativo);
                                $this->Clientepib->save();
                                $idMembroVinculo = $this->Clientepib->getLastInsertId();

                                #################################################################################################################### 
                                ############################# parte que insere na tabela de membros_vinculos ####################################### 
                                ####################################################################################################################
                                $nivelDiscipuladorMembro = $this->Clientepib->query("SELECT membros.status_discipulado FROM membros WHERE membros.id = '$idDiscipulador'");
                                $nivelDiscipulador       = $nivelDiscipuladorMembro[0]['membros']['status_discipulado'];

                                //insere na tbl de membros_vinculos                          
                                $dataEntrada     = date("Y-m-d H:i:s");
                                $this->Clientepib->query("INSERT INTO `sistemapib`.`membros_vinculos` (`membro_id`, `nivel_discipulo`, `discipulador_id`, `nivel_discipulador`, `rede_discipulador`, `rede_discipulador_filha`, `data_vinculo`, `obs`, `origem`, `ativo`) VALUES ('$idMembroVinculo', '1', '$idDiscipulador', '$nivelDiscipulador', '$redePai', '$redeFilha', '$dataEntrada', 'Primeiro vínculo com discipulador criado via central integradora - Entradas', '1','1')");

                                //qtd total de pessoas ativas vinculadas pelo discipulador_ID da tabela membros_vinculos
                                $qtdPessoasVinculadas = $this->Clientepib->query("SELECT COUNT(id) as tt_discipulando FROM membros_vinculos WHERE discipulador_id = ".$idDiscipulador." AND membros_vinculos.ativo=1");
                                //up total vinculos
                                //ver essa parte por que esta errada a conexao com o banco
                                $totalAtualDiscipulando = $qtdPessoasVinculadas[0][0]['tt_discipulando'];
                                $this->Discipulador->query("UPDATE `centralintegradora`.`discipuladores` SET `total_vinculo`='".$totalAtualDiscipulando."' WHERE `id_membro`=".$idDiscipulador."");

                            }
                            
                        } 
                        catch (Exception $e) {
                            echo json_encode(0);
                            exit;
                        }
                        #PARTE QUE REALIZA O LOG DO ENTRADAS
                        $dataUpdate = date('Y-m-d H:i:s');
                        $membro_id  = $this->Session->read('membro_id');
                        $id         = $this->Session->read('Auth.User.id');
                        $this->LogEntradas->set(array("id_user" => $id, "id_membro" => $membro_id , "data"=> $dataUpdate,  "tipo" => 'insert' ,"id_alterado"=>$idPessoa, "function"=>"vincularDiscipuladores"));
                        $this->LogEntradas->save();
                        $this->Entrada->updateAll(array("Entrada.status" => 0), array("Entrada.id" => $idEntrada));
                        try {
                            //RD Station -- aqui ele transforma o contato do discipulo em oportunidade no rd
                            $opportunity = $this->Configrd->post_opportunity($email);
							$patch_caminhada = $this->Configrd->patch_caminhada("Validação", $email);
                            //escrever no banco a oportunidade e Discipulado Conectado
                            if($opportunity == "ok"){
                                $this->Logrd->insert_log(2,$idPessoa,$this->Session->read('Auth.User.id'));
                                $this->Logrd->insert_log(3,$idPessoa,$this->Session->read('Auth.User.id'));
                                $this->Logrd->insert_log(9,$idPessoa,$this->Session->read('Auth.User.id'));
                            }
                            echo json_encode("ok");
                        } 
                        catch (Exception $e) {
                            echo json_encode("ok");
                        }
                    }
					$this->reenvioAcessoPessoas($idPessoa,$cpf,$email);
                    $this->Entrada->updateAll(array("Entrada.status" => 0), array("Entrada.id" => $idEntrada));
                    echo json_encode("ok");
                    
                }
                echo json_encode("ok");
            } catch (Exception $e) {
                //echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                echo json_encode('Exceção capturada: ',  $e->getMessage(), "\n");
            }

        }
        
    }

    public function comparaTabela(){

        $this->loadModel('Clientepibantigo');
        $this->loadModel('Clientepib');
        $this->loadModel('Entrada');
        $this->layout = false;
        $this->autoRender = false;  
        if ($this->request->is('ajax')) {

            $idMembro = $this->request->data['idMembro'];
            $idEntrada = $this->request->data['idEntrada'];

            #1 - Consulta tabela de membros 
            $dadosMembro = $this->Clientepib->find("all", array(                
                "fields" => "Clientepib.*",  
                "conditions" => "Clientepib.id = '$idMembro'",
                "order" => "Clientepib.nome"
            ));

            #2 - consulta a tabela de entradas para comparar
            $dadosEntrada = $this->Entrada->find("all", array(
                "fields" => "Entrada.*",
                "conditions"=>"Entrada.status=1 and Entrada.id ='$idEntrada'",
                "order" => "Entrada.id",
                "limit" => 1,
                "recursive" => -1
            ));

            #mostrar de uma lado da tabela as entradas e do outro lado os registros da tabela Membro
            $html = "";
            $html.="<table class='table'>
            <thead>
                <tr>
                    <td colspan='2' style=text-align:center; bgcolor='#DCDCDC';>
                        <b>Dado da base de entradas</b>
                    </td>
                    <td colspan='2' style=text-align:center; bgcolor='#D3D3D3';>
                        <b>Dado da base de membros</b>
                    </td>
                </tr>
                <tr>
                    <td><b>Nome</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['nome']."</td>
                    <td><b>Nome</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['nome']."</td>
                </tr>
                <tr>
                    <td><b>CPF</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['cpf']."</td>
                    <td><b>CPF</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['cpf']."</td>
                </tr>
                <tr>
                    <td><b>Email</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['email']."</td>
                    <td><b>Email</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['email']."</td>
                </tr>
                <tr>
                    <td><b>Dt. Nascimento</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['dt_nascimento']."</td>
                    <td><b>Dt. Nascimento</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['data_nascimento']."</td>
                </tr>
                <tr>
                    <td><b>Celular</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['celular']."</td>
                    <td><b>Celular</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['telefone_1']."</td>
                </tr>
                    <td><b>Cep</b></td>
                    <td>".$dadosEntrada[0]['Entrada']['cep']."</td>
                    <td><b>Cep</b></td>
                    <td>".$dadosMembro[0]['Clientepib']['cep']."</td>
                </tr>
                </tr>
                    <td><input type='hidden' id='resultDadosEntrada'></td>
                </tr>                
            </thead>
        </table>";
        }
        echo json_encode(array(0=>$html, "dadosEntrada"=>$dadosEntrada));
    }

    public function confirmaMesmaPessoaMembro(){
        
        $this->loadModel('Clientepibantigo');
        $this->loadModel('Clientepib');
        $this->loadModel('Entrada');
        $this->loadModel('LogEntradas');
		$this->loadModel('Configrd');
		$this->loadModel('Logrd');

        $this->layout       = false;
        $this->autoRender   = false;  
        if ($this->request->is('ajax')) {

            $infos = $this->request->data['dados'];
            $dados = explode(";", $infos);

            if($dados[19]=='M'){ $sexo = 0; } else {$sexo = 1;}

            switch ($dados[20]) {
                case 'ser_igreja':      $status_id = 26; break; 
                case 'conhecer_jesus':  $status_id = 27; break;
                case 'discipulado':     $status_id = 27; break;
                default:                $status_id = 0; break;
            }

            try {
                    $nome           = $dados[14];
                    $dtNascimento   = $dados[9];
                    $dtAlteracao    = date('Y-m-d H:i:s');
                    $cpf            = $dados[7];
                    $idUsuario      = $dados[22];
                    $email          = $dados[10];
                    $celular        = $dados[1];
                    $cep            = $dados[2];
                    $origemEntrada  = $dados[23];

                    #se o usuario NAO informou na entrada o cep então pesquisa na base de membro se existe um cep com o email informador
                    if($dados[2]==""){                    
                        $dadosCepMembro = $this->Clientepib->query("SELECT membros.cep FROM membros WHERE membros.email = '$email' ");
                        $cep = $dadosCepMembro[0]['membros']['cep'];
                    }
                    else{
                        #se o usuario informou na entrada o cep então permanece o cep informado na entradas
                        $cep = $dados[2];
                    }

                    #busca o endereco via api
                    $reg                = simplexml_load_file("http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep=" . $cep);
                    $dados['sucesso']   = (string) $reg->resultado;
                    $dados['rua']       = (string) $reg->tipo_logradouro . ' ' . $reg->logradouro;
                    $dados['bairro']    = (string) $reg->bairro;
                    $dados['cidade']    = (string) $reg->cidade;
                    $dados['estado']    = (string) $reg->uf;
                    $bairro             = $dados['bairro'];
                    $cidade             = $dados['cidade'];
                    $rua                = $dados['rua'];
                    $estado             = $dados['estado'];

                    #fazer o update na tabela de membros da pib
                    #verifica se possui cpf, se existir então atualiza outros campos menos o cpf, caso contrario atualiza infos + cpf
                    if($dados[7]){
                        #verifica o tipo da pessoa se é frenquentador ou não                
                        $tipoPessoaBanco = $this->Clientepib->query("SELECT membros.status_id, membros.id, membros.origem, membros.cep, membros.email, membros.cpf, membros.telefone_1 FROM membros WHERE membros.cpf = '$cpf' ");
                        $idStatusPessoa  = $tipoPessoaBanco[0]['membros']['status_id'];
                        $origem          = $tipoPessoaBanco[0]['membros']['origem'];
                        $cepMembro       = $tipoPessoaBanco[0]['membros']['cep'];
                        $emailMembro     = $tipoPessoaBanco[0]['membros']['email'];
                        $cpfMembro       = $tipoPessoaBanco[0]['membros']['cpf'];
                        $telefoneMembro  = $tipoPessoaBanco[0]['membros']['telefone_1'];

                        //caso não encontre um tipo de origem então
                        // if(empty($origem)){
                        //     $origem = "origem não inserida";
                        // }
                        // else{
                            $origem = $origemEntrada;
                        //}

                        #se a pessoa for membro então não atualiza o status
                        if($idStatusPessoa==5){ $status_id = 5; } else { $status_id ;}

                        #alterado para virada de chave em 30/04/2021
                        if($status_id       == 0   ){ $status_id       = 27;       }
                        if($cepMembro       == "" || $cepMembro       == "0" ){ $cepMembro       = $cep;     } else{ $cepMembro       = $cepMembro;       }
                        if($emailMembro     == "" || $emailMembro     == "0" ){ $emailMembro     = $email;   } else{ $emailMembro     = $emailMembro;     }
                        if($telefoneMembro  == "" || $telefoneMembro  == "0" ){ $telefoneMembro  = $celular; } else{ $telefoneMembro  = $telefoneMembro;  }
                        if($cpfMembro       == ""  ){ $cpfMembro       = $cpf;     } else{ $cpfMembro       = $cpfMembro;       }

                        $this->Clientepib->query("UPDATE `membros` 
                                                    SET `nome`              = '$nome', 
                                                        `data_nascimento`   = '$dtNascimento',
                                                        `sexo`              = '$sexo', 
                                                        `status_id`         = '$status_id', 
                                                        `dtAlteracao`       = '$dtAlteracao', 
                                                        `origem`            = '$origem',
                                                        `telefone_1`        = '$telefoneMembro',
                                                        `cep`               = '$cepMembro',
                                                        `rua`               = '$rua',
                                                        `bairro`            = '$bairro',
                                                        `estado`            = '$estado',
                                                        `cidade`            = '$cidade',
                                                        `cpf`               = '$cpfMembro',
                                                        `email`             = '$emailMembro',
                                                        `ativo`             = '1' 
                                                    WHERE `membros`.`id`    = $idUsuario");

                        #PARTE QUE REALIZA O LOG DO ENTRADAS 1 
                        $dataUpdate = date('Y-m-d H:i:s');
                        $membro_id  = $this->Session->read('membro_id');
                        $id         = $this->Session->read('Auth.User.id');
                        $this->LogEntradas->set(array("id_user" => $id, "id_membro" => $membro_id , "data"=> $dataUpdate,  "tipo" => 'update' ,"id_alterado"=>$idUsuario, "function"=>"confirmaMesmaPessoaMembro"));
                        $this->LogEntradas->save();

						//RD Station -- aqui ele informa o RD sobre a passagem dele pela validação
						$patch_caminhada = $this->Configrd->patch_caminhada("Validação", $email);
						//escrever no banco o log
						if($patch_caminhada == "ok"){
							$this->Logrd->insert_log(2,$idUsuario,$this->Session->read('Auth.User.id'));
						}
                    }
                    else{
                        #verifica o tipo da pessoa se é frenquentador ou não  
                        $tipoPessoaBanco = $this->Clientepib->query("SELECT membros.status_id, membros.id, membros.origem, membros.cep, membros.email FROM membros WHERE membros.cpf = '$cpf' ");
                        $idStatusPessoa  = $tipoPessoaBanco[0]['membros']['status_id'];
                        $origem          = $tipoPessoaBanco[0]['membros']['origem'];
                        $cepMembro       = $tipoPessoaBanco[0]['membros']['cep'];
                        $emailMembro     = $tipoPessoaBanco[0]['membros']['email'];
                        $telefoneMembro  = $tipoPessoaBanco[0]['membros']['telefone_1'];

                        //caso não encontre um tipo de origem então
                        if(empty($origem)){
                            $origem = "atemporal_discipulado";
                        }

                        #se a pessoa for membro então não atualiza o status
                        if($idStatusPessoa==5){ $status_id = 5; } else{ $status_id ;}

                        if($cepMembro==""){ $cepMembro = $cep; } else{ $cepMembro = $cepMembro; }
                        if($emailMembro     == "" || $emailMembro     == "0" ){ $emailMembro     = $email;   } else{ $emailMembro     = $emailMembro;     }
                        if($telefoneMembro  == "" || $telefoneMembro  == "0" ){ $telefoneMembro  = $celular; } else{ $telefoneMembro  = $telefoneMembro;  } //atualização do telefone da pessoa

                        #atualiza a tabela de membros nova
                        $this->Clientepib->query("UPDATE `membros` 
                                                    SET `nome`              = '$nome', 
                                                        `data_nascimento`   = '$dtNascimento', 
                                                        `sexo`              = '$sexo', 
                                                        `status_id`         = '$status_id', 
                                                        `dtAlteracao`       = '$dtAlteracao',
                                                        `telefone_1`        = '$telefoneMembro', 
                                                        `origem`            = '$origem',
                                                        `cep`               = '$cepMembro',
                                                        `rua`               = '$rua',
                                                        `email`             = '$emailMembro',
                                                        `bairro`            = '$bairro',
                                                        `estado`            = '$estado',
                                                        `cidade`            = '$cidade'
                                                WHERE `membros`.`id`    = $idUsuario");

                        #PARTE QUE REALIZA O LOG DO ENTRADAS 1 
                        $dataUpdate = date('Y-m-d H:i:s');
                        $membro_id  = $this->Session->read('membro_id');
                        $id         = $this->Session->read('Auth.User.id');
                        $this->LogEntradas->set(array("id_user" => $id, "id_membro" => $membro_id , "data"=> $dataUpdate,  "tipo" => 'update' ,"id_alterado"=>$idUsuario, "function"=>"confirmaMesmaPessoaMembro"));
                        $this->LogEntradas->save();

						//RD Station -- aqui ele informa o RD sobre a passagem dele pela validação
						$patch_caminhada = $this->Configrd->patch_caminhada("Validação", $email);
						//escrever no banco o log
						if($patch_caminhada == "ok"){
							$this->Logrd->insert_log(2,$idUsuario,$this->Session->read('Auth.User.id'));
						}
                    }

                $idPessoaNaoCad = $dados[12];
                $this->Entrada->updateAll(array("Entrada.status" => "0"), array("Entrada.id" => "$idPessoaNaoCad"));

                $membro_id  = $this->Session->read('membro_id');
				$this->reenvioAcessoPessoas($idUsuario,$cpf,$email);
                echo json_encode(array(0=>"ok",1=>$membro_id));
            } 
            catch (Exception $e) {
                //echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                echo json_encode('Exceção capturada: ',  $e->getMessage(), "\n");
            }
        }
    }

    public function reabilitarUsuario(){
        
        $this->loadModel('Entrada');
        $this->layout = false;
        $this->autoRender = false;  
        
        if ($this->request->is('ajax')) {

            $id = $this->request->data['id'];

            $this->Entrada->updateAll(array("status"=>"1"),array("Entrada.id"=>"$id"));
            echo json_encode("ok");
        }
        
    }

    public function reenvioAcessoPessoas($idMembro,$cpfMembro,$emailMembro){

        $this->loadModel('Clientepib');
        $this->loadModel("Autenticacao");

        $this->layout       = false;
        $this->autoRender   = false;  

            $CaracteresAceitos  = 'abcdxywzABCDZYWZ123456789';
            $max                = strlen($CaracteresAceitos)-1;
            $password           = null;

            for($i=0; $i < 6; $i++) {
                $password .= $CaracteresAceitos{mt_rand(0, $max)};
            }

            #Caso nao tenha o id do membro
            $verificalogin = $this->Autenticacao->find("first", array(
                "conditions" => "Autenticacao.membro_id = ".$idMembro."",
                "recursive" => -1
            ));

            if(empty($verificalogin)){
                $this->Autenticacao->create();
                $this->Autenticacao->set("membro_id", $idMembro);
                $this->Autenticacao->set("sLogin", $idMembro);   
            }
            else{
                $this->Autenticacao->set("id", $verificalogin["Autenticacao"]["id"]);
            }

            $buscaEmail = $this->Clientepib->find("first", array(
                "fields" => "Clientepib.nome, Clientepib.email",
                "conditions" => "Clientepib.id = ".$idMembro.""
            ));

            $email = $buscaEmail["Clientepib"]["email"];
            $nome  = $buscaEmail["Clientepib"]["nome"];

            #alterado dia 14/12
            if(empty($email) || $email == "" || $email=="Não informado" || $email=="0" ){
                #busca todas as informações do novo usuario adicionado na base de membros
                #caso o email esteja vazio. 
                $dados = $this->Clientepib->buscaEmailAcesso($cpfMembro);
                $email = $dados["Clientepib"]["email"];
                $nome  = $dados["Clientepib"]["nome"];
            }
            else{
                //else
            }

            $this->Autenticacao->set("sSenha", md5($password));
            $this->Autenticacao->set("bExpirarSenha", 1);
            $this->Autenticacao->set("ativo", 1);

            if($this->Autenticacao->save()){
                //Alterou com sucesso os dados de autenticação, continuo.
			
                $login = $verificalogin["Autenticacao"]["sLogin"];

                if(empty($login)){
                    $idinserido = $this->Autenticacao->getLastInsertId();
                    $buscadados = $this->Autenticacao->find("first", array(
                        "fields" => "Autenticacao.*",
                        "conditions" => "Autenticacao.id = ".$idinserido.""
                    ));
                    $login = $buscadados["Autenticacao"]["sLogin"];
                }

                $dadosHTML = array(
                    'nome' => $nome,
                    'login' => $login,
                    'senha' => $password
                );

                if($email == "0"){
                    echo json_encode(2);
                }
                else{
                    $Email = new CakeEmail('default');
                    $Email->emailFormat('html')
                          ->template('emailvoltacentral','template');
                    $Email->to($email, $nome);
                    $Email->subject('Central de Membros PIB Curitiba - CI');
                    $Email->viewVars($dadosHTML);
                    $Email->send();
                    echo json_encode(3);
                }
            }
            else{
                //Erro ao alterar os dados de autenticação 
                echo json_encode(0);
            }
        
    }

    public function reenvioAcessoPessoasDisci1(){

        
        $idMembro = $this->request->data['id'];
        
        $this->loadModel('Clientepib');
        $this->loadModel("Autenticacao");

        $this->layout       = false;
        $this->autoRender   = false;  

            $CaracteresAceitos  = 'abcdxywzABCDZYWZ123456789';
            $max                = strlen($CaracteresAceitos)-1;
            $password           = null;

            for($i=0; $i < 6; $i++) {
                $password .= $CaracteresAceitos{mt_rand(0, $max)};
            }

            #Caso nao tenha o id do membro
            $verificalogin = $this->Autenticacao->find("first", array(
                "conditions" => "Autenticacao.membro_id = ".$idMembro."",
                "recursive" => -1
            ));

            if(empty($verificalogin)){
                $this->Autenticacao->create();
                $this->Autenticacao->set("membro_id", $idMembro);
                $this->Autenticacao->set("sLogin", $idMembro);   
            }
            else{
                $this->Autenticacao->set("id", $verificalogin["Autenticacao"]["id"]);
            }

            $buscaEmail = $this->Clientepib->find("first", array(
                "fields" => "Clientepib.nome, Clientepib.email",
                "conditions" => "Clientepib.id = ".$idMembro.""
            ));

            $email = $buscaEmail["Clientepib"]["email"];
            $nome  = $buscaEmail["Clientepib"]["nome"];

            #alterado dia 14/12
            if(empty($email) || $email == "" || $email=="Não informado" || $email=="0" ){
                #busca todas as informações do novo usuario adicionado na base de membros
                #caso o email esteja vazio. 
                $dados = $this->Clientepib->buscaEmailAcesso($cpfMembro);
                $email = $dados["Clientepib"]["email"];
                $nome  = $dados["Clientepib"]["nome"];
            }
            else{
                //else
            }

            $this->Autenticacao->set("sSenha", md5($password));
            $this->Autenticacao->set("bExpirarSenha", 1);
            $this->Autenticacao->set("ativo", 1);

            if($this->Autenticacao->save()){
                //Alterou com sucesso os dados de autenticação, continuo.
			
                $login = $verificalogin["Autenticacao"]["sLogin"];

                if(empty($login)){
                    $idinserido = $this->Autenticacao->getLastInsertId();
                    $buscadados = $this->Autenticacao->find("first", array(
                        "fields" => "Autenticacao.*",
                        "conditions" => "Autenticacao.id = ".$idinserido.""
                    ));
                    $login = $buscadados["Autenticacao"]["sLogin"];
                }

                $dadosHTML = array(
                    'nome' => $nome,
                    'login' => $login,
                    'senha' => $password
                );

                if($email == "0"){
                    echo json_encode(2);
                }
                else{
                    $Email = new CakeEmail('default');
                    $Email->emailFormat('html')
                          ->template('emailvoltacentral','template');
                    $Email->to($email, $nome);
                    $Email->subject('Central de Membros PIB Curitiba - CI');
                    $Email->viewVars($dadosHTML);
                    $Email->send();
                    echo json_encode(3);
                }
            }
            else{
                //Erro ao alterar os dados de autenticação 
                echo json_encode(0);
            }
        
    }


    function validaCPF($cpf) { 
       // Verifica se um número foi informado
        if(empty($cpf)) {
            echo json_encode(3);
            exit;
            //return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            //return false;
            echo json_encode(3);
            exit;
        }
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
                
            //return false;
            echo json_encode(3);
            exit;
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
        } else {   
            
            for ($t = 9; $t < 11; $t++) {
                
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    //return false;
                    echo json_encode(3);
                    exit;
                }
            }
                //return true;
                //echo json_encode(1);
        }
        //return true;
    }

}