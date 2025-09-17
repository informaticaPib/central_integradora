<?php
	App::uses('AppShell', 'Console/Command');
	App::uses('CakeEmail', 'Network/Email');
	
	class GestorShell extends AppShell{
		public $uses = array('Rede','Euvim','Clientepib','MembroVinculo','Clientepibantigo','Discipulador','Historicoacompanhamento','Historicoacompanhamentodiscipulo','Tabelagrupogestor','Discipuladorrede');
		public function main(){
			$this->layout = false;
			$this->autoRender = false;
			
			$hoje = date('Y-m-d H:i:s');
			$hojeBrasileiro = date("d/m/Y H:i:s");
			$html = '';
			$contadorAcompanhamento = 0;
			$mesAtual = date("m");
			$totalDiscipuladores = 0;
			$totalDiscipuladoresUnico = 0;
			$totalHorasRede = 0;
			$totalHorasGeral = 0;
			$totalIgreja = 0;
			$totalDiscipulado = 0;
			$totalAcompanhamento = 0;
			$totalRedeLiberado = 0;
			
			//Pegando todas as redes pais
			$redes = $this->Rede->redespais();
			$totalDiscipuladoresUnico = $this->Discipuladorrede->query('SELECT COUNT(DISTINCT(discipulador_id)) as total FROM discipuladores_redes');
			
			//Foreach rede por rede e salvando o total de discipuladores por rede
			foreach ($redes as $rede){
				
				$totalDiscipuladoresRede = $this->Discipuladorrede->find('all', array(
					"fields" => "COUNT(Discipuladorrede.discipulador_id) as total",
					"conditions" => "Discipuladorrede.rede_contagem = ".$rede['Rede']['id']."",
				));
				
				//adicionando ao array
				$dadosTabelaGerencial[$rede['Rede']['id']] = array('idrede' => $rede['Rede']['id'], 'nomerede' => $rede['Rede']['nome'], 'liderrede' => $rede['Membro']['nome'], 'totaldiscipuladoresrede' => $totalDiscipuladoresRede[0][0]['total']);
				$totalDiscipuladores = $totalDiscipuladores + $totalDiscipuladoresRede[0][0]['total'];
				
			}

	
			foreach ($redes as $rede){
				//Discipuladores da rede
				$DiscipuladoresRede = $this->Discipuladorrede->find('all', array(
					"fields" => "DISTINCT(Discipuladorrede.discipulador_id), Discipulador.horas_semanais",
					"conditions" => "Discipuladorrede.rede_contagem = ".$rede['Rede']['id']."",
					"joins" => array(
						array(
							"type" => "inner",
							"table" => "discipuladores",
							"alias" => "Discipulador",
							"conditions" => "Discipulador.id_membro = Discipuladorrede.discipulador_id"
						)
					)
				));
				
				$totalIgrejaRede = $this->MembroVinculo->find("count", array(
					"fields" => "DISTINCT(Clientepib.id)",
					"conditions" => "
						Clientepib.liberado_entrevista = 0						
						AND Clientepib.status_id = 26 
						AND Clientepib.ativo = 1
						AND MembroVinculo.rede_discipulador = ".$rede['Rede']['id']."
						AND MembroVinculo.nivel_discipulo = 1
						AND MembroVinculo.ativo = 1",
					'joins' => array(
						array(
							'type' => 'inner',
							'table' => 'membros',
							'alias' => 'Clientepib',
							'conditions' => 'Clientepib.id = MembroVinculo.membro_id' 
						)
					)
				));
				$totalDiscipuladoRede = $this->MembroVinculo->find("count", array(
					"fields" => "DISTINCT(Clientepib.id)",
					"conditions" => "
						Clientepib.liberado_entrevista = 0
						AND Clientepib.status_id = 27 
						AND Clientepib.ativo = 1
						AND MembroVinculo.rede_discipulador = ".$rede['Rede']['id']." 
						AND MembroVinculo.nivel_discipulo = 1
						AND MembroVinculo.ativo = 1",						
					'joins' => array(
						array(
							'type' => 'inner',
							'table' => 'membros',
							'alias' => 'Clientepib',
							'conditions' => 'Clientepib.id = MembroVinculo.membro_id' 
						)
					)
				));
				$totalAcompanhamentoRede = $this->MembroVinculo->find("count", array(
					"fields" => "DISTINCT(Clientepib.id)",
					"conditions" => "
						Clientepib.liberado_entrevista = 0					 
						AND Clientepib.status_id = 29 
						AND Clientepib.ativo = 1
						AND MembroVinculo.rede_discipulador = ".$rede['Rede']['id']."
						AND MembroVinculo.nivel_discipulo = 1
						AND MembroVinculo.ativo = 1",
					'joins' => array(
						array(
							'type' => 'inner',
							'table' => 'membros',
							'alias' => 'Clientepib',
							'conditions' => 'Clientepib.id = MembroVinculo.membro_id' 
						)
					)	
				));
				
				$totalRedeLiberado = $this->MembroVinculo->find("count", array(
					"conditions" => "
						Clientepib.liberado_entrevista = 1
						AND Clientepib.ativo = 1
						AND Clientepib.status_discipulado = 1		
						AND (Clientepib.status_id = 26 OR Clientepib.status_id = 27)		
						AND MembroVinculo.rede_discipulador = ".$rede['Rede']['id']." 						 
						",
					'joins' => array(
						array(
							'type' => 'inner',
							'table' => 'membros',
							'alias' => 'Clientepib',
							'conditions' => 'Clientepib.id = MembroVinculo.membro_id' 
						)
					)	
				));

				foreach ($DiscipuladoresRede as $discipulador){
					$totalHorasRede = $totalHorasRede + $discipulador['Discipulador']['horas_semanais'];
					
					//Discipulos da rede
					
					$discipulados = $this->MembroVinculo->find("all", array(
						"fields" => "Clientepib.id, Clientepib.status_id",
						"conditions" => "
							Clientepib.liberado_entrevista = 0 							
							AND MembroVinculo.discipulador_id = ".$discipulador['Discipuladorrede']['discipulador_id']." 
							AND MembroVinculo.rede_discipulador = ".$rede['Rede']['id']." 
							AND (
								Clientepib.status_id = 26 
								OR Clientepib.status_id = 27
								OR Clientepib.status_id = 29
							) 
						AND Clientepib.ativo = 1",
						'joins' => array(
							array(
								'type' => 'inner',
								'table' => 'membros',
								'alias' => 'Clientepib',
								'conditions' => 'Clientepib.id = MembroVinculo.membro_id' 
							)
						)	
					));
					
					foreach($discipulados as $discipulado){
						$acompanhamentosDiscipulador = $this->Historicoacompanhamentodiscipulo->find("count", arraY(
							"conditions" => "MONTH(Historicoacompanhamentodiscipulo.data) = '".$mesAtual."' AND Historicoacompanhamentodiscipulo.id_discipulo = ".$discipulado['Clientepib']['id']."",
							"recursive" => -1
						));
						if(!empty($acompanhamentosDiscipulador)){
							$contadorAcompanhamento = $contadorAcompanhamento + $acompanhamentosDiscipulador;
						}
					}
				}
				
				$dadosTabelaGerencial[$rede['Rede']['id']]['trilhafinalizada'] = $totalRedeLiberado;
				$dadosTabelaGerencial[$rede['Rede']['id']]['horas'] = $totalHorasRede;
				$dadosTabelaGerencial[$rede['Rede']['id']]['acompanhamento'] = $contadorAcompanhamento;
				/*
				array_push($dadosTabelaGerencial[$rede['Rede']['id']]['horas'], array(
					'totalacompanhamento' => $contadorAcompanhamento,
					'totalhorasrede' => $totalHorasRede
				));
				*/
				
				array_push($dadosTabelaGerencial[$rede['Rede']['id']], array(
					'totaligrejarede' => $totalIgrejaRede,
					'totaldiscipuladorede' => $totalDiscipuladoRede,
					'totalacompanhamentorede' => $totalAcompanhamentoRede
				));
				
				$totalRedeLiberado = 0;
				$totalIgrejaRede = 0;
				$totalDiscipuladoRede = 0;
				$totalAcompanhamentoRede = 0;
				$contadorAcompanhamento = 0;
				$totalHorasRede = 0;
			}
			
			$html .= '
			<table id="tabelaGGT" class="table table-bordered table-hover table-sm">
				<thead class="thead-dark">
					<tr>
						<th class="text-center" colspan="3">Última atualização: '.$hojeBrasileiro.'</th>
					</tr>
					<tr>
						<th class="text-center" colspan="2">Redes de Células</th>
						<th class="text-center" colspan="2">Discipuladores</th>
						<th class="text-center" colspan="3">Movimento Discipular</th>
						<th class="text-center" colspan="7">Acompanhamento das Redes</th>
						<th class="text-center" >Discipuladores</th>
					</tr>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-center" colspan="3">Trilhas (Não concluídas)</th>
						<th class="text-center">Trilhas (Concluídas)</th>
						<th class="text-center" colspan="6">Gestão</th>
					</tr>
					<tr>
						<th class="text-center">Redes</th>
						<th class="text-center">Líder</th>
						<th class="text-center">Discipuladores</th>
						<th class="text-center">Potencial Discipulados</th>
						<th class="text-center">Discipulado</th>
						<th class="text-center">Igreja</th>
						<th class="text-center">Acompanhamento</th>
						<th></th>
						<th class="text-center">Mentoria Mensal</th>
						<th class="text-center">Batismo</th>
						<th class="text-center">Membro</th>
						<th class="text-center">Célula</th>
						<th class="text-center">Ciclo do Discípulo (tornar-se discipulador)</th>
						<th class="text-center">Pessoa sendo acompanhada</th>
						<th class="text-center">Indisponível</th>
					</tr>
				</thead>
				<tbody>
				';
			foreach ($dadosTabelaGerencial as $dado){
				//echo "<pre>";
				//print_r($dado);
				//exit;
				$totalHorasGeral = $totalHorasGeral + $dado['horas'];
				$totaltrilhafinalizada = $totaltrilhafinalizada + $dado['trilhafinalizada'];
				$totalDiscipulado = $totalDiscipulado + $dado[0]['totaldiscipuladorede'];
				$totalIgreja = $totalIgreja + $dado[0]['totaligrejarede'];
				$totalAcompanhamento = $totalAcompanhamento + $dado[0]['totalacompanhamentorede'];
				$totalTrilhas = $totalDiscipulado + $totalIgreja + $totalAcompanhamento;
				
				//$porcDiscipulado = number_format(((($totalDiscipulado / $totalTrilhas)-1) *(-100)), 2, ",", ".");
				//$porcIgreja = number_format(((($totalIgreja / $totalTrilhas)-1) *(-100)), 2, ",", ".");
				//$porcAcompanhamento = number_format(((($totalAcompanhamento / $totalTrilhas)-1) *(-100)), 2, ",", ".");
				
				$porcDiscipulado =  number_format(($totalDiscipulado / $totalTrilhas) * 100, 2, ",", ".");
				$porcIgreja = number_format(($totalIgreja / $totalTrilhas) * 100, 2, ",", ".");
				$porcAcompanhamento = number_format(($totalAcompanhamento / $totalTrilhas) * 100, 2, ",", ".");
				
				$html .= '<tr>';
				$html .= "<td>".$dado['nomerede']."</td>";
				$html .= "<td>".$dado['liderrede']."</td>";
				$html .= "<td>".$dado['totaldiscipuladoresrede']."</td>";
				$html .= "<td>".$dado['horas']."</td>";
				$html .= "<td>".$dado[0]['totaldiscipuladorede']."</td>";
				$html .= "<td>".$dado[0]['totaligrejarede']."</td>";
				$html .= "<td>".$dado[0]['totalacompanhamentorede']."</td>";
				$html .= "<td>".$dado['trilhafinalizada']."</td>";
				$html .= "<td>".$dado['acompanhamento']."</td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
				$html .= "</tr>";
				
			}
			$html .= '
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td class="tdNormal">Total de discipuladores (Um discipulador pode pertencer a mais de uma rede)</td>
						<td>'.$totalDiscipuladores.'</td>
						<td></td>
						<td>'.$totalDiscipulado.'</td>
						<td>'.$totalIgreja.'</td>
						<td>'.$totalAcompanhamento.'</td>
						<td>'.$totaltrilhafinalizada.'</td>
					</tr>
					<tr>
						<td></td>
						<td class="tdNormal">Total de discipuladores únicos</td>
						<td>'.$totalDiscipuladoresUnico[0][0]['total'].'</td>
						<td>'.$totalHorasGeral.'</td>
						<td>'.$porcDiscipulado.'%</td>
						<td>'.$porcIgreja.'%</td>
						<td>'.$porcAcompanhamento.'%</td>
						
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td colspan="3" style="text-align: center;">'.$totalTrilhas.'</td>
					</tr>
				</tfoot>
			</table>';
			
			$this->Tabelagrupogestor->updateAll(
				array(
					'html' => "'".$html."'",
					'ultima_atualizacao' => "'".$hoje."'"
				),  array(
					'tabela' => 'grupogestor'
				)
			);
			
			$texto = "FINALIZADO - Rotina finalizada, tabela atualizada na base de dados";
			CakeLog::write('dashboards', $texto);
			
		}
	}
?>