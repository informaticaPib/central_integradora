<?php
class RelatoriosController extends AppController {
	public function previsaofinalizacao(){
		$this->loadModel("Euvim");
		$this->loadModel("Clientepibantigo");
		
		$igreja1dia = 0;
		$igreja2dia = 0;
		$igreja3dia = 0;
		$igreja4dia = 0;
		$igreja5dia = 0;
		$igreja6dia = 0;
		$igreja7dia = 0;
		$discipulado1dia = 0;
		$discipulado2dia = 0;
		$discipulado3dia = 0;
		$discipulado4dia = 0;
		$discipulado5dia = 0;
		$discipulado6dia = 0;
		$discipulado7dia = 0;
		$discipulado8dia = 0;
		$discipulado9dia = 0;
		$discipulado10dia = 0;
		$discipulado11dia = 0;
		$discipulado12dia = 0;
		
		
		$discipulos = $this->Clientepibantigo->find("all", array(
			"fields" => "Clientepibantigo.id",
			"conditions" => "Clientepibantigo.discipulador_ci != 0 AND Clientepibantigo.liberado_entrevista = 0 AND Clientepibantigo.agendado = 0 AND (Clientepibantigo.idStatus = 27 OR Clientepibantigo.idStatus = 26 OR Clientepibantigo.idStatus = 29)",
		));
		$totalDiscipulos = $this->Clientepibantigo->totalComVinculo();
		foreach ($discipulos as $discipulo) {
			
			$euvim = $this->Euvim->find('all', array(
				'fields' => 'COUNT(Euvim.id) as totalfrequencia, Euvim.id_membro, Euvim.id_turma',
				'conditions' => 'Euvim.id_membro = ' . $discipulo['Clientepibantigo']['id'] . ' AND (Euvim.id_turma = 4 OR Euvim.id_turma = 5)',
				'group' => "Euvim.id_turma",
				'recursive' => -1
			));
			foreach ($euvim as $total){
				if($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 1){
					$igreja1dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 2){
					$igreja2dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 3){
					$igreja3dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 4){
					$igreja4dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 5){
					$igreja5dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 6){
					$igreja6dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 5 && $total[0]["totalfrequencia"] == 7){
					$igreja7dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 1){
					$discipulado1dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 2){
					$discipulado2dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 3){
					$discipulado3dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 4){
					$discipulado4dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 5){
					$discipulado5dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 6){
					$discipulado6dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 7){
					$discipulado7dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 8){
					$discipulado8dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 9){
					$discipulado9dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 10){
					$discipulado10dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 11){
					$discipulado11dia ++;
				}
				elseif ($total["Euvim"]["id_turma"] == 4 && $total[0]["totalfrequencia"] == 12){
					$discipulado12dia ++;
				}
			}
		}
		$this->set(compact(
			"igreja1dia",
			"igreja2dia",
			"igreja3dia",
			"igreja4dia",
			"igreja5dia",
			"igreja6dia",
			"igreja7dia",
			"discipulado1dia",
			"discipulado2dia",
			"discipulado3dia",
			"discipulado4dia",
			"discipulado5dia",
			"discipulado6dia",
			"discipulado7dia",
			"discipulado8dia",
			"discipulado9dia",
			"discipulado10dia",
			"discipulado11dia",
			"discipulado12dia",
			"totalDiscipulos"
		));
	}
	
	public function listagempessoasfinalizacao(){
		$this->loadModel("Euvim");
		$this->loadModel("Clientepibantigo");
		
		$this->autoRender = false;
		
		$filtroturma = $this->request->data['turma'];
		$filtrodia = $this->request->data['dias'];
		$html = '';
		$contador = 0;
		$discipulos = $this->Clientepibantigo->find("all", array(
			"fields" => "Clientepibantigo.id, Clientepibantigo.sNome",
			"conditions" => "Clientepibantigo.discipulador_ci != 0 AND Clientepibantigo.liberado_entrevista = 0 AND Clientepibantigo.agendado = 0 AND (Clientepibantigo.idStatus = 27 OR Clientepibantigo.idStatus = 26 OR Clientepibantigo.idStatus = 29)",
		));
		
		$html .= "<table class='table'>";
			$html .= "<thead><th>Nome</th><th>Ações</th></thead>";
			$html .= "<tbody>";
			foreach ($discipulos as $discipulo) {
				
				$euvim = $this->Euvim->find('all', array(
					'fields' => 'COUNT(Euvim.id) as totalfrequencia, Euvim.id_membro, Euvim.nome_membro',
					'conditions' => 'Euvim.id_membro = ' . $discipulo['Clientepibantigo']['id'] . ' AND Euvim.id_turma = '.$filtroturma.'',
					'group' => "Euvim.id_membro",
					'recursive' => -1
				));
				if(!empty($euvim)){
					foreach ($euvim as $total) {
						if ($total[0]["totalfrequencia"] == $filtrodia) {
							$html .= "<tr><td>" . $total['Euvim']['nome_membro'] . "</td>";
							$html .= "<td><a href='/discipulados/perfil/" . $total['Euvim']['id_membro'] . "' class='btn btn-default' target='_blank'>Acessar perfil</a></td></tr>";
						}
					}
				}
			}
				//$html .= "<tr><td colspan='2'>Nenhum registro</td>";
			$html .= "</tbody>";
		$html .= "</table>";
		
		echo json_encode($html);
	}
	
}