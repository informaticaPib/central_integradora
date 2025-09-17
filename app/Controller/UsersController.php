<?php
class UsersController extends AppController {

    //O que ele deve fazer antes de tudo
	public function beforeFilter() {
        parent::beforeFilter();
        //Ações permitidas sem login
        $this->Auth->allow('login', 'logout');
    }

    //Geralmente quando é inicio, página inicial, eu coloco index
    public function index() {
        $where = "";
        //FILTRO
        if ($this->request->is('get')) {
            if($this->params['url']['nome']){
				$nome = str_replace(" ", "%", $this->params['url']['nome']);
                $where .= ' AND User.nome LIKE "%'.$nome.'%"';
               // $where .= ' AND Discipulador.status = 1';
            }
        }
        $this->paginate = array(
            'fields' => 'User.*',
            'conditions' => '1=1'.$where,
            'limit' => 15,
            'order' => array('User.nome' => 'asc')
        );

        $listaUsuarios = $this->paginate('User');

        //Este é o comando de enviar para a view todas as minhas variáveis
        $this->set(compact('listaUsuarios'));
    }

    public function view($id = null) {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        $this->set('user', $this->User->findById($id));
    }
    //Função de adicionar usuário na base de dados
    public function add() {
        //Verifico se a requisição é um POST (pode ser get, ajax, post)
        if ($this->request->is('post')) {
            //create = criar novo objeto de USER
            $this->User->create();
            //save = salvar informações no banco de dados
            //formulario é do tipo User, com isso o cake entende que os campos são para ele, ai o NAME dos inputs ficam da seguinte forma: data[User][username]
            if ($this->User->save($this->request->data)) {
                //Passando mensagem para a view
                $this->Session->setFlash('Usuário salvo com sucesso!','success');
                //Para onde eu quero que o usuário seja redirecionado
                $this->redirect(array('controller' => 'users', 'action' => 'index'));
            } else {
                //deu erro
                $this->Session->setFlash('Usuário não pode ser salvo, tente novamente.','error');
            }
        }
    }

    public function edit($id = null) {
        $this->User->id = $this->request->data['User']['id'];        

        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Usuário editado com sucesso!','success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Usuário não pode ser esditado, tente novamente!','error');
            }
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        if ($this->User->updateAll(
            array(
                'status' => 0
            ),  array(
                'id' => $id
            )
        )){
            $this->Session->setFlash('Usuário deletado com sucesso','success');
            $this->redirect(array('action' => 'index'));
        }else{
            $this->Flash->error(__('Usuário não pode ser deletado, tente novamente.'));
            $this->redirect(array('action' => 'index'));
        }
        
    }

    public function login() {
        $this->loadModel('User');
        $this->layout = 'login';

        if($this->request->is('post')){
            if(is_array($this->request->data['User']) && count($this->request->data['User'])>0){
                if(array_key_exists('username', $this->request->data['User']) && array_key_exists('password', $this->request->data['User'])){
                    //$ldapId = $this->ldapLogin($this->request->data['User']['username'], $this->request->data['User']['password']);
                    $membroId = $this->loginAd($this->request->data['User']['username'], $this->request->data['User']['password']);                    
                    $where = "";
                    if($membroId>0){
                        $where = "User.membro_id=".(int)$membroId;
                    }else{
                        if($this->request->data['User']['username'] != ''){
                           $where = "User.username='".$this->request->data['User']['username']."' AND User.password='".AuthComponent::password($this->request->data['User']['password'])."'";
                        }else{
                            $this->Session->setFlash('Erro ao efetuar o login, tente novamente','error');
                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                        }
                    }
                    if(!empty($where)){
                        $dadosAutenticacao = $this->User->find('first', array(
                            'fields'=> 'User.id, User.role, User.membro_id, User.username',
                            'conditions'=>$where.' AND User.status = 1',
                            'recursive'=>-1
                        ));
                        if(is_array($dadosAutenticacao) && count($dadosAutenticacao)>0){
                            if(!empty($dadosAutenticacao['User']['id'])){
								$this->Session->write('Auth.User.username', $dadosAutenticacao['User']['username']);
								$this->Session->write('Auth.User.id', $dadosAutenticacao['User']['id']);
                                
                                $this->Session->write('membro_id', $dadosAutenticacao['User']['membro_id']);
                                
                                $this->Session->write('Auth.User.role', $dadosAutenticacao['User']['role']);
                                //if ($this->Auth->login()) {
                                    $this->redirect(array('controller' => 'discipulados', 'action' => 'naovinculados'));
                                //}
                            }else{
                                $this->Session->setFlash('Erro ao efetuar o login, tente novamente','error');
                                $this->redirect(array('controller' => 'users', 'action' => 'login'));
                            }
                        }else{
                            $this->Session->setFlash('Nenhum usuário encontrado.','info');
                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                        }
                    }
                }
            }
        }
    }
	private function loginAd($login, $senha){
        //Master
        $usuarioMaster = 'sistemas@pibcuritiba.local';
        $senhaMaster = 'zaq1xsw2cde3vfr4';
        // Parâmetros de login
        $dominio = '@pibcuritiba.local';
        //Informações de conexão
        // Endereços do servidor LDAP
        $hosts = array(
            '172.16.3.11',
            '172.16.3.12',
        );
        $porta = 389; // Porta LDAP (geralmente 389 para não seguro e 636 para seguro)
        $dn = 'OU=PIB,DC=pibcuritiba,DC=local'; // DN base do diretório
        $filtro = "(&(objectCategory=person)(sAMAccountName=$login))";
        $atributos = array("samaccountname","postOfficeBox","userPassword"); // Atributo que você deseja recuperar
        //Variável de retorno
        $retorno = '';

        foreach ($hosts as $host) {
    
            // Tentativa de conexão
            $conexao = ldap_connect($host, $porta);
            // Verifica se a conexão foi estabelecida com sucesso
            if ($conexao) {                
                
                // Defina as opções de LDAP
                ldap_set_option($conexao, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($conexao, LDAP_OPT_REFERRALS, 0);
        
                // Tentativa de login no AD
                $loginMaster = ldap_bind($conexao, $usuarioMaster, $senhaMaster);
                
                // Verifica se a ligação foi bem sucedida
                if ($loginMaster) {                    
                    // Realiza a busca pelo DN do usuário
                    $pesquisa = ldap_search($conexao, $dn, $filtro, $atributos);
                    $dados = ldap_get_entries($conexao, $pesquisa);

                    // echo '<pre>'; print_r($dados); exit;

                    // Verifica se foi retornado pelo menos um resultado
                    if ($dados['count'] > 0) {                        
                        // Tentativa de login com a senha padrão do usuário
                        $loginUsuario = ldap_bind($conexao, $login.$dominio, $senha);                        
                        if($loginUsuario){
                            //Login efetuado com sucesso
                            $retorno = $dados[0]['postofficebox'][0];
                            return $retorno;
                        }else{
                            //Não efetuou login, tenta 2° senha
                            $segundaSenhaAd = $dados[0]['userpassword'][0];
                            if ($senha === $segundaSenhaAd) {
                                //Senha são iguais, login efetuado com sucesso
                                $retorno = $dados[0]['postofficebox'][0];
                                return $retorno;
                            }else{
                                return false;
                            }
                        }                    
                    } else {
                        return false;                    
                    }
                    // Fechar conexão LDAP
                    ldap_unbind($conexao);
                    
                    // Adiciona break para sair do loop após o primeiro sucesso
                    break;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    private function ldapLogin($login, $senha){
        if(!function_exists('ldap_connect')){
            return false;
        }
        //LDAP
        define ( "LDAP_HOST", "172.16.3.13"); //IP do servidor LDAP
        define ( "LDAP_DOMAIN", "ou=usuarios,dc=pibcuritiba,dc=org,dc=br");
        define ( "LDAP_DN", "cn=admin,dc=pibcuritiba,dc=org,dc=br");
        define ( "LDAP_PASSWORD", "Servicos#pib*");

        $DS = ldap_connect(LDAP_HOST);

        if($DS)
        {
            $BIND = @ldap_bind($DS, LDAP_DN, LDAP_PASSWORD);
            if(!$BIND){
                return false;
            }
            $SEARCH = ldap_search($DS, LDAP_DOMAIN, "uid=".$login."");
            $LDAP_INFO = ldap_get_entries($DS, $SEARCH);

            if( array_key_exists(0,  $LDAP_INFO)){
                $U_DN = $LDAP_INFO[0]["dn"];
                $U_PASSWD = $LDAP_INFO[0]["userpassword"][0];
                $U_MEMBERID = $LDAP_INFO[0]["employeenumber"][0];
                $U_BIND =@ldap_bind($DS, $U_DN, $senha);
            }
            if ($U_BIND && $U_PASSWD && !empty($senha))
            {

                return $U_MEMBERID;
            }
        }
        return false;
    }

    public function logout() {
		$this->Session->destroy();
		$this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    public function permissoes($id = null){
		$this->loadModel("UsuariosPermissao");
		$this->loadModel("UsuariosRede");
		$this->loadModel("Permissao");
		$this->loadModel("Rede");

		if ($this->request->is('post')) {
			$id = $this->request->data['UsuariosPermissao']['id_usuario'];
			$idRede = $this->request->data['UsuariosPermissao']['id_rede'];

			$verificaRegistro = $this->UsuariosRede->checkRegisterUser($id, $idRede);


			if($verificaRegistro == 0){
				$this->UsuariosRede->create();
				$this->UsuariosRede->set('id_usuario', $id);
				$this->UsuariosRede->set('id_rede', $idRede);
				$this->UsuariosRede->save($this->request->data);
			}

			$this->UsuariosPermissao->create();
			if ($this->UsuariosPermissao->save($this->request->data)) {
				$this->Session->setFlash('Permissão a rede salva com sucesso!','success');
				$this->redirect(array('action' => 'permissoes', $id));
			} else {
				$this->Session->setFlash('Permissão a rede não pode ser salvo, tente novamente.','error');
				$this->redirect(array('action' => 'permissoes', $id));
			}
		}else{
			$this->User->id = $id;
			$listaRedes = $this->Rede->getListAllRedes();
			$listaPermissoes = $this->Permissao->getListAllPermissoes();
			$permissoesUsuario = $this->UsuariosPermissao->getAllPermissoesById($id);
		}

		$this->set(compact('permissoesUsuario','id', 'listaRedes', 'listaPermissoes'));

	}

}
