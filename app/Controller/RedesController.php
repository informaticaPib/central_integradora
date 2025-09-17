<?php
class RedesController extends AppController {
	public function index(){
		$this->loadModel("Rede");
		$listaRedes = $this->Rede->getListAllRedes();
		$todasRedes = $this->Rede->redespais();
		$listaRedesPais = $this->Rede->getListRedePai();
		$this->set(compact('listaRedes','todasRedes','listaRedesPais'));
	}

	public function buscasubredes($id = null){
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['id'];
			$dadosSubRede = $this->Rede->dadosSubRede($idRede);
			if(!empty($dadosSubRede)){
				$html .= '<table class="table table-condensed table-hover"><tbody>';
				foreach ($dadosSubRede as $subclasse){
					$html .= '
					<tr>
						<td><i class="far fa-circle"></i></td>
						<td>Editar</td>
						<td>'.$subclasse['Rede']['nome'].'</td>
						<td>'.$subclasse['Rede']['created'].'</td>
						<td>'.$subclasse['Rede']['modified'].'</td>
						
					</tr>
					';
				}
				$html .= '</tbody></table>';
			}else{
				$html ='<p>Não há sub-redes</p>';
			}
			echo json_encode($html);
		}
	}
	
	public function redesfilhas($id = null){
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['id'];
			$contador = $this->request->data['contador'];
			$dadosSubRede = $this->Rede->dadosSubRede($idRede);
			if(!empty($dadosSubRede)){
				$html .= '<label for="DiscipuladorIdRede">Rede '.$contador.'<span class="vermelho">*</span></label>';
				$html .= '<select name="data[Discipulador][id_rede][]" id="DiscipuladorIdRede'.$contador.'" class="form-control" required>';
					$html .= '<option>Selecione uma opção</option>';
				foreach ($dadosSubRede as $subclasse){
					$html .= '<option value="'.$subclasse['Rede']['id'].'">'.$subclasse['Rede']['nome'].'</option>';
				}
				$html .= '</select>';
			}else{
				$html ='<p>Não há sub-redes</p>';
			}
			echo json_encode($html);
		}
	}
	
	public function redesfilhasedit($id = null){
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['id'];
			$contador = $this->request->data['contador'];
			$redepai = $this->request->data['redepai'];
			$dadosSubRede = $this->Rede->dadosSubRede($idRede);
			if(!empty($dadosSubRede)){
				$html .= '<label for="DiscipuladorIdRede">Rede '.$contador.'<span class="vermelho">*</span></label>';
				$html .= '<select name="data[Discipulador][id_rede][]" id="DiscipuladorIdRede'.$contador.'" class="form-control EditDiscipuladorIdRede'.$contador.'" required>';
				$html .= '<option>Selecione uma opção</option>';
				foreach ($dadosSubRede as $subclasse){
					if($subclasse['Rede']['id'] == $redepai){
						$html .= '<option value="'.$subclasse['Rede']['id'].'" selected>'.$subclasse['Rede']['nome'].'</option>';
					}else{
						$html .= '<option value="'.$subclasse['Rede']['id'].'">'.$subclasse['Rede']['nome'].'</option>';
					}
				}
				$html .= '</select>';
			}else{
				$html ='<p>Não há sub-redes</p>';
			}
			echo json_encode($html);
		}
	}
	
	
	public function add() {
		$this->loadModel("Rede");
		if ($this->request->is('post')) {
			$this->Rede->create();
			if ($this->Rede->save($this->request->data)) {
				$this->Session->setFlash('Rede salvo com sucesso!','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Rede não pode ser salvo, tente novamente.','error');
			}
		}
	}
}
