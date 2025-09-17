<?php
class MenusController extends AppController {
var $name = 'Menus';

    function index() {
        if (isset($this->params['requested']) && $this->params['requested'] == true) {
        
        } else {
            
            $this->set('menus', $this->Menu->find('all'));
        }
    }

    function add() {
        if ($this->request->is('post')) {
            $this->Menu->create();
            if ($this->Menu->save($this->request->data)) {
                //$this->data['Menu']['status'] = 1;
                $this->Flash->success(__('Menu salvo com sucesso'));
                //$this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('Menu não pode ser salvo, tente novamente.'));
            }
        }
    }

    // Build out additional CRUD functionality,
    // for example edit / view / delete, as desired.

}
?>
