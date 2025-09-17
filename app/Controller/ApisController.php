<?php 

class ApisController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('apientradas');
    }
    
    public function apientradas(){}
    
    

}



?>