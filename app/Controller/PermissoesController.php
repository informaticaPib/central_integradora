<?php
class PermissoesController extends AppController {
	public function index(){
		$this->loadModel('Permissao');
		$todasPermissoes = $this->Permissao->getAllPermissoes();

		$this->set(compact('todasPermissoes'));
	}

	public function add() {
		$this->loadModel('Permissao');
		if ($this->request->is('post')) {
			$this->Permissao->create();
			if ($this->Permissao->save($this->request->data)) {
				$this->Session->setFlash('Permissão salva com sucesso!','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Permissão não pode ser salva, tente novamente.','error');
			}
		}
	}
}
