<?php
class JesusController extends AppController {

    #permitir antes de qualquer ação
    #informar qual a funcao
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }


    public function index(){        
        $this->loadModel("Entrada");
        $this->layout = "jesus";

        #parte de registro de infos
        if ($this->request->is('ajax')) {

                $this->layout = false;
                $this->autoRender = false;  

                $this->Entrada->create();
            if ($this->Entrada->save($this->request->data)) {
                //$retorno = $this->Entrada->getLastInsertId();                
                echo json_encode('ok');
            }
            else{                
                echo json_encode('não ok');
            }
        }
    }

}