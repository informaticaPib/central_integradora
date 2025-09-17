<?php

class DashboardsController extends AppController {
	public function index() {
		$this->loadModel("Rede");
		$this->loadModel("Euvim");
		$this->loadModel("Clientepib");
		$this->loadModel("Discipulador");
		$this->loadModel("Historicoacompanhamento");
		$this->loadModel("Tabelagrupogestor");				

		$tabelagrupogestor = $this->Tabelagrupogestor->find('first', array(
			"fields" => "Tabelagrupogestor.html",
			"conditions" => "Tabela = 'grupogestor'",
			"recursive" => -1
		));
		$tabeladiscipuladoresociosos = $this->Tabelagrupogestor->find('first', array(
			"fields" => "Tabelagrupogestor.html",
			"conditions" => "Tabela = 'disicpuladoresociosos'",
			"recursive" => -1
		));
		
		$novosDiscipuladoresMes = $this->Discipulador->cadastrosMes();

		
		foreach ($novosDiscipuladoresMes as $discipuladoresMes){
			
			if(empty($discipuladoresMes[0]['mes'] == 1)){
				$janeiroDiscipulador = 0;
			}else{
				$janeiroDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 2)){
					$fevereiroDiscipulador = 0;
			}else{
				$fevereiroDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 3)){
				$marcoDiscipulador = 0;
			}else{
				$marcoDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 4)){
				$abrilDiscipulador = 0;
			}else{
				$abrilDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 5)){
				$maioDiscipulador = 0;
			}else{
				$maioDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 6)){
				$junhoDiscipulador = 0;
			}else{
				$junhoDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 7)){
				$julhoDiscipulador = 0;
			}else{
				$julhoDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 8)){
				$agostoDiscipulador = 0;
			}else{
				$agostoDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 9)){
				
				$setembroDiscipulador = 0;
			}else{
				$setembroDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 10)){
				$outubroDiscipulador = 0;
			}else{
				$outubroDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 11)){
				$novembroDiscipulador = 0;
			}else{
				$novembroDiscipulador = $discipuladoresMes[0]["total"];
			}
			
			if(empty($discipuladoresMes[0]['mes'] == 12)){
				
				$dezembroDiscipulador = 0;
			}else{
				$dezembroDiscipulador = $discipuladoresMes[0]["total"];
			}
		}
		$novosDiscipulosMes = $this->Clientepib->cadastrosMes();
		foreach ($novosDiscipulosMes as $discipulosMes){
			if(empty($discipulosMes[0]['mes'] == 1)){
				$janeiroDiscipulo = 0;
			}else{
				$janeiroDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 2)){
				$fevereiroDiscipulo = 0;
			}else{
				$fevereiroDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 3)){
				$marcoDiscipulo = 0;
			}else{
				$marcoDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 4)){
				$abrilDiscipulo = 0;
			}else{
				$abrilDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 5)){
				$maioDiscipulo = 0;
			}else{
				$maioDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 6)){
				$junhoDiscipulo = 0;
			}else{
				$junhoDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 7)){
				$julhoDiscipulo = 0;
			}else{
				$julhoDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 8)){
				$agostoDiscipulo = 0;
			}else{
				$agostoDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 9)){
				
				$setembroDiscipulo = 0;
			}else{
				$setembroDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 10)){
				$outubroDiscipulo = 0;
			}else{
				$outubroDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 11)){
				$novembroDiscipulo = 0;
			}else{
				$novembroDiscipulo = $discipulosMes[0]["total"];
			}
			
			if(empty($discipulosMes[0]['mes'] == 12)){
				
				$dezembroDiscipulo = 0;
			}else{
				$dezembroDiscipulo = $discipulosMes[0]["total"];
			}
		}
		
		$totalVinculado = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci != 0 AND Clientepib.ativo = 1 AND (Clientepib.status_id = 26 OR Clientepib.status_id = 27 OR Clientepib.status_id = 29)",
			"recursive" => -1
		));
		
		$totalSemVinculoDiscipulado = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND Clientepib.status_id = 27",
			"recursive" => -1
		));
		$totalSemVinculoIgreja = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND Clientepib.status_id = 26",
			"recursive" => -1
		));
		$totalSemVinculoAcompanhamento = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND Clientepib.status_id = 29",
			"recursive" => -1
		));
		
		$totalSemVinculoDiscipuladoLiberado = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 1 AND Clientepib.ativo = 1 AND Clientepib.status_id = 27",
			"recursive" => -1
		));
		$totalSemVinculoIgrejaLiberado = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 1 AND Clientepib.ativo = 1 AND Clientepib.status_id = 26",
			"recursive" => -1
		));
		$totalSemVinculoAcompanhamentoLiberado = $this->Clientepib->find("count", array(
			"fields" => "Clientepib.id",
			"conditions" => "Clientepib.discipulador_ci = 0 AND Clientepib.liberado_entrevista = 1 AND Clientepib.ativo = 1 AND Clientepib.status_id = 29",
			"recursive" => -1
		));
		
		$redexdiscipulador = $this->Rede->find('all', array(
			"fields" => "Rede.nome, COUNT(Discipuladorrede.id) as total",
			"joins" => array(
				array(
					"type" => "inner",
					"table" => "discipuladores_redes",
					"alias" => "Discipuladorrede",
					"conditions" => "Discipuladorrede.rede_id = Rede.id"
				)
			),
			"group" => "Rede.id"
		));
		
		$buscaAtuando = $this->Discipulador->query("SELECT COUNT(*) AS count FROM centralintegradora.discipuladores AS Discipulador WHERE Discipulador.id_membro IN (SELECT discipulador_ci FROM sistemapib.membros)");
		$discipuladoresAtuando = $buscaAtuando[0][0]['count'];
		
		
		//Aguardando, não possue nenhum vínculo
		$buscaAguardando = $this->Discipulador->query("SELECT COUNT(*) AS count FROM centralintegradora.discipuladores AS Discipulador WHERE Discipulador.id_membro NOT IN (SELECT discipulador_ci FROM sistemapib.membros)");
		$totalDiscipuladoresAguardando = $buscaAguardando[0][0]['count'];

		$discipuladoresIndisponiveis = $this->Discipulador->find("count", array(
			"conditions" => "Discipulador.status_disponibilidade = 0 AND Discipulador.status = 1"
		));
		$totalDiscipuladoresSistema = $this->Discipulador->find("count", array(
			"conditions" => "Discipulador.status = 1"
		));
		//$totalDiscipuladoresAguardando = $totalDiscipuladoresSistema - $discipuladoresAtuando;
		
		//Aguardando liberação para discipular
		$totalDiscipuladoresSistemaBloqueados = $this->Discipulador->find("count", array(
			"conditions" => "Discipulador.status = 0"
		));
		
		$listaRedePai = $this->Rede->getListRedePai();
		
		
		$this->set(compact(
			"tabeladiscipuladoresociosos",
			"tabelagrupogestor",
			"redexdiscipulador",
			"totalVinculado",
			"totalSemVinculoDiscipulado",
			"totalSemVinculoIgreja",
			"totalSemVinculoAcompanhamento",
			"totalSemVinculoDiscipuladoLiberado",
			"totalSemVinculoIgrejaLiberado",
			"totalSemVinculoAcompanhamentoLiberado",
			"discipuladoresAtuando",
			"totalDiscipuladoresSistemaBloqueado",
			"discipuladoresIndisponiveis",
			"totalDiscipuladoresAguardando",
			"listaRedePai",
			"janeiroDiscipulador",
			"fevereiroDiscipulador",
			"marcoDiscipulador",
			"abrilDiscipulador",
			"maioDiscipulador",
			"junhoDiscipulador",
			"julhoDiscipulador",
			"agostoDiscipulador",
			"setembroDiscipulador",
			"outubroDiscipulador",
			"novembroDiscipulador",
			"dezembroDiscipulador",
			"janeiroDiscipulo",
			"fevereiroDiscipulo",
			"marcoDiscipulo",
			"abrilDiscipulo",
			"maioDiscipulo",
			"junhoDiscipulo",
			"julhoDiscipulo",
			"agostoDiscipulo",
			"setembroDiscipulo",
			"outubroDiscipulo",
			"novembroDiscipulo",
			"dezembroDiscipulo"
		));
	}
	
	//Rede filtrada
	public function totaisrede(){
		$this->loadModel("Rede");
		$this->loadModel("Euvim");
		$this->loadModel("Clientepib");
		$this->loadModel("Clientepibantigo");
		$this->loadModel("Discipulador");
		$this->loadModel("Historicoacompanhamento");
		$this->loadModel("Tabelagrupogestor");
		$this->loadModel("Discipuladorrede");
		$this->loadModel("Historicoacompanhamentodiscipulo");
		
		$this->autoRender = false;#nao permite carregar paginas em html
		$this->layout = false;
		
		$html = '';
		$totaltrilhafinalizada = 0;
		$contadorAcompanhamento = 0;
		$mesAtual = date("m");
		$totalDiscipuladores = 0;
		$totalHorasRede = 0;
		$totalHorasGeral = 0;
		$totalIgrejaRede = 0;
		$totalDiscipuladoRede = 0;
		$totalAcompanhamentoRede = 0;
		$totalIgreja = 0;
		$totalDiscipulado = 0;
		$totalAcompanhamento = 0;
		$totalRedeLiberado = 0;
		
		$idrede = $this->params['url']['rede'];
		
		$redes = $this->Rede->redespaisfiltradas($idrede);
		
		
		
		foreach ($redes as $rede){
			
			$totalDiscipuladoresRede = $this->Discipuladorrede->find('all', array(
				"fields" => "COUNT(Discipuladorrede.id) as total",
				"conditions" => "Discipuladorrede.rede_id = ".$rede['Rede']['id']." AND Discipuladorrede.rede_contagem = ".$rede['Rede']['id_pai']." ",
			));
			
			//adicionando ao array
			//$dadosTabelaGerencial[$rede['Rede']['id']] = array('idrede' => $rede['Rede']['id'], 'nomerede' => $rede['Rede']['nome'], 'liderrede' => $rede['Membro']['nome'], 'totaldiscipuladoresrede' => $totalDiscipuladoresRede[0][0]['total']);
			//$totalDiscipuladores = $totalDiscipuladores + $totalDiscipuladoresRede[0][0]['total'];
			
		}
		
		
		
		foreach ($redes as $rede){
			$dados = $this->Discipuladorrede->query(
				"
				SELECT centralintegradora.redes.nome,sistemapib.membros.nome, (SELECT COUNT(discipulador_id) as total FROM centralintegradora.discipuladores_redes WHERE rede_id = ".$rede['Rede']['id'].") as total_discipuladores,
				(SELECT SUM(horas_semanais) AS total_horas FROM centralintegradora.discipuladores INNER JOIN centralintegradora.discipuladores_redes ON centralintegradora.discipuladores_redes.discipulador_id = centralintegradora.discipuladores.id_membro WHERE discipuladores_redes.rede_id = ".$rede['Rede']['id']." ) as total_horas_semanais,
				(SELECT count(rede_discipulador_filha) as total FROM sistemapib.membros
				INNER JOIN centralintegradora.redes  as r ON r.id = membros.rede_discipulador_filha
				WHERE discipulador_ci != 0 AND `ativo` = 1 AND atendimento_finalizado = 0 AND `rede_discipulador_filha` = ".$rede['Rede']['id']." AND `liberado_entrevista` = 0 AND status_id = 27) AS total_discipulado,
				(SELECT count(rede_discipulador_filha) as total FROM sistemapib.membros
				INNER JOIN centralintegradora.redes  as r ON r.id = membros.rede_discipulador_filha
				WHERE discipulador_ci != 0 AND `ativo` = 1 AND atendimento_finalizado = 0 AND `rede_discipulador_filha` = ".$rede['Rede']['id']." AND `liberado_entrevista` = 0 AND status_id = 26) AS total_igreja,
				(SELECT count(rede_discipulador_filha) as total FROM sistemapib.membros
				INNER JOIN centralintegradora.redes  as r ON r.id = membros.rede_discipulador_filha
				WHERE discipulador_ci != 0 AND `ativo` = 1 AND atendimento_finalizado = 0 AND `rede_discipulador_filha` = ".$rede['Rede']['id']." AND `liberado_entrevista` = 0 AND status_id = 29) AS total_acompanhamento,
				(SELECT count(rede_discipulador_filha) as total FROM sistemapib.membros
				INNER JOIN centralintegradora.redes  as r ON r.id = membros.rede_discipulador_filha
				WHERE discipulador_ci != 0 AND `ativo` = 1 AND `rede_discipulador_filha` = ".$rede['Rede']['id']." AND `liberado_entrevista` = 1 AND (status_id = 27 OR status_id = 26)) AS total_concluido
				FROM centralintegradora.redes
				LEFT JOIN sistemapib.membros ON sistemapib.membros.id = centralintegradora.redes.pastor_rede
				WHERE centralintegradora.redes.id = ".$rede['Rede']['id']."
				"
			);
			/*
			$acompanhamentosDiscipulador = $this->Historicoacompanhamentodiscipulo->find("count", arraY(
				"conditions" => "MONTH(Historicoacompanhamentodiscipulo.data) = '".$mesAtual."' AND Historicoacompanhamentodiscipulo.id_discipulo = ".$discipulado['Clientepib']['id']."",
				"recursive" => -1
			));
			if(!empty($acompanhamentosDiscipulador)){
				$contadorAcompanhamento = $contadorAcompanhamento + $acompanhamentosDiscipulador;
			}
			*/
			$dadosTabelaGerencial[$rede['Rede']['id']]['nomerede'] = $dados[0]['redes']['nome'];
			$dadosTabelaGerencial[$rede['Rede']['id']]['liderrede'] = $dados[0]['membros']['nome'];
			$dadosTabelaGerencial[$rede['Rede']['id']]['totaldiscipuladoresrede'] = $dados[0][0]['total_discipuladores'];
			$dadosTabelaGerencial[$rede['Rede']['id']]['trilhafinalizada'] = $dados[0][0]['total_concluido'];
			$dadosTabelaGerencial[$rede['Rede']['id']]['horas'] = $dados[0][0]['total_horas_semanais'];
			$totalDiscipuladores = $totalDiscipuladores + $dados[0][0]['total_discipuladores'];
			
			array_push($dadosTabelaGerencial[$rede['Rede']['id']], array(
				'totaligrejarede' => $dados[0][0]['total_igreja'],
				'totaldiscipuladorede' => $dados[0][0]['total_discipulado'],
				'totalacompanhamentorede' => $dados[0][0]['total_acompanhamento']
			));
		}
		
		$html .= '
		<table id="tabelaGGT" class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
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
			
			$totalHorasGeral = $totalHorasGeral + $dado['horas'];
			$totaltrilhafinalizada = $totaltrilhafinalizada + $dado['trilhafinalizada'];
			$totalDiscipulado = $totalDiscipulado + $dado[0]['totaldiscipuladorede'];
			$totalIgreja = $totalIgreja + $dado[0]['totaligrejarede'];
			$totalAcompanhamento = $totalAcompanhamento + $dado[0]['totalacompanhamentorede'];
			$totalTrilhas = $totalDiscipulado + $totalIgreja + $totalAcompanhamento;
			
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
			$html .= "<td></td>";
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
					<td></td>
					<td>'.$totalDiscipuladores.'</td>
					<td>'.$totalHorasGeral.'</td>
					<td>'.$totalDiscipulado.'</td>
					<td>'.$totalIgreja.'</td>
					<td>'.$totalAcompanhamento.'</td>
					<td>'.$totaltrilhafinalizada.'</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
		
		echo json_encode($html);
		
	}
	
	public function tabelatotaissistema(){
		$this->loadModel("Clientepib");
		$this->loadModel('Clientepibantigo');
		$this->loadModel("Discipulador");
		$this->autoRender = false;#nao permite carregar paginas em html
		$this->layout = false;
		
		$html = '';
		$ano = date("Y");
		$totalIgreja = $this->Clientepib->find("count", array(
			'fields' => 'DISTINCT(Clientepib.id)',
			'conditions' => 'Clientepib.status_id = 26 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
			'joins' => array(
				array(
					'type' => 'inner',
					'table' => 'membros_vinculos',
					'alias' => 'MembroVinculo',
					'conditions' => 'MembroVinculo.membro_id = Clientepib.id'
				)
			)
		));
		$totalDiscipulado = $this->Clientepib->find("count", array(
			'fields' => 'DISTINCT(Clientepib.id)',
			'conditions' => 'Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
			'joins' => array(
				array(
					'type' => 'inner',
					'table' => 'membros_vinculos',
					'alias' => 'MembroVinculo',
					'conditions' => 'MembroVinculo.membro_id = Clientepib.id'
				)
			)
		));		
		$totalAcompanhamento = $this->Clientepib->find("count", array(
			'fields' => 'DISTINCT(Clientepib.id)',
			'conditions' => 'Clientepib.status_id = 29 AND Clientepib.liberado_entrevista = 0 AND Clientepib.ativo = 1 AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1',
			'joins' => array(
				array(
					'type' => 'inner',
					'table' => 'membros_vinculos',
					'alias' => 'MembroVinculo',
					'conditions' => 'MembroVinculo.membro_id = Clientepib.id'
				)
			)
		));
		/*
		$totalIgrejaAguardando = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 26 AND Clientepib.liberado_entrevista = 0 AND Clientepib.discipulador_ci = 0 AND Clientepib.agendado = 0 AND Clientepib.rede_discipulador = 0 AND Clientepib.ativo = 1"
		));
		$totalDiscipuladoAguardando = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 0 AND Clientepib.discipulador_ci = 0 AND Clientepib.agendado = 0 AND Clientepib.rede_discipulador = 0 AND Clientepib.ativo = 1"
		));
		$totalAcompanhamentoAguardando = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 29 AND Clientepib.liberado_entrevista = 0 AND Clientepib.discipulador_ci = 0 AND Clientepib.agendado = 0 AND Clientepib.rede_discipulador = 0 AND Clientepib.ativo = 1"
		));
		*/
		$totalIgrejaIntegracaoAgendado = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 26 AND Clientepib.liberado_entrevista = 1 AND Clientepib.atendimento_finalizado = 0 AND Clientepib.agendado = 1 AND Clientepib.ativo = 1"
		));
		$totalDiscipuladoIntegracaoAgendado = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 1 AND Clientepib.atendimento_finalizado = 0 AND Clientepib.agendado = 1 AND Clientepib.ativo = 1"
		));
		$totalIgrejaIntegracaoAguardandoAgendamento = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 26 AND Clientepib.liberado_entrevista = 1 AND Clientepib.agendado = 0 AND Clientepib.ativo = 1"
		));
		$totalDiscipuladoIntegracaoAguardandoAgendamento = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 1 AND Clientepib.agendado = 0 AND Clientepib.ativo = 1"
		));
		$totalDiscipuladoIntegracaoAguardandoBatismo = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 1 AND Clientepib.apto = 1 AND Clientepib.atendimento_finalizado = 1 AND Clientepib.dtBatismo = '0000-00-00'  AND Clientepib.ativo = 1"
		));
		$totalIgrejaIntegracaoAprovado = $this->Clientepib->find("count", array(
			"conditions" => "
				Clientepib.status_id = 26 AND 
				Clientepib.liberado_entrevista = 1 AND 
				Clientepib.apto = 1 AND 
				Clientepib.atendimento_finalizado = 1 AND 
				(SELECT Movimentacao.id_Recebimento FROM membros_movimentacoes AS Movimentacao WHERE Movimentacao.id_recebimento = 2 OR Movimentacao.id_recebimento = 3 ORDER BY Movimentacao.id DESC LIMIT 1) AND 
				Clientepib.ativo = 1"
		));
		$totalDiscipuladoIntegracaoAprovado = $this->Clientepib->find("count", array(
			"conditions" => "Clientepib.status_id = 27 AND Clientepib.liberado_entrevista = 1 AND Clientepib.apto = 1 AND Clientepib.atendimento_finalizado = 1  AND Clientepib.dtBatismo != '0000-00-00' AND Clientepib.ativo = 1"
		));
		//echo $totalDiscipuladoIntegracaoAprovado; exit;
		$dataInicioBusca = $ano."-01-01";
		
		$totalAnoMembros = $this->Clientepib->query("
		SELECT count( m.id ) as total, mov.id_recebimento,
		CASE
		WHEN mov.id_recebimento =1
		THEN 27
		WHEN mov.id_recebimento =2
		THEN 26
		WHEN mov.id_recebimento =3
		THEN 26
		WHEN mov.id_recebimento =4
		THEN 26
		WHEN mov.id_recebimento =5
		THEN 26
		END
		FROM membros as m
		INNER JOIN membros_movimentacoes AS mov ON mov.id_membro = m.id
		WHERE m.ativo =1 AND m.status_id = 5
				AND mov.data_entrada
		BETWEEN '$dataInicioBusca'
				AND NOW( )
		GROUP BY mov.id_recebimento
		");
		
		
		$totalAnoMembroSerIgreja = 0;
		$totalAnoMembroDiscipulado = 0;
		foreach ($totalAnoMembros as $total){
			if($total['mov']['id_recebimento'] == 1){
				$totalAnoMembroDiscipulado = $total[0]['total'];
			}elseif($total['mov']['id_recebimento'] == 2 || $total['mov']['id_recebimento'] == 3 || $total['mov']['id_recebimento'] == 4 || $total['mov']['id_recebimento'] == 5){
				$totalAnoMembroSerIgreja = $totalAnoMembroSerIgreja + $total[0]['total'];
			}
		}
		
		$totalBatismoPosPandemia = 102;
		$totalBatismoFuturo = 0;
		$totalBatismoAguardandoResposta = $totalDiscipuladoIntegracaoAguardandoBatismo;
		$totalTodos = $totalDiscipulado + $totalIgreja + $totalAcompanhamento + $totalDiscipuladoIntegracaoAgendado + $totalIgrejaIntegracaoAgendado + $totalDiscipuladoIntegracaoAguardandoAgendamento + $totalIgrejaIntegracaoAguardandoAgendamento + $totalIgrejaIntegracaoAprovado + $totalDiscipuladoIntegracaoAprovado + $totalAnoMembroSerIgreja + $totalAnoMembroDiscipulado + $totalDiscipuladoIntegracaoAguardandoBatismo;
		$totalMovimentoDiscipular = $totalDiscipulado + $totalIgreja + $totalAcompanhamento;
		$totalAguardandoEntrevista = $totalDiscipuladoIntegracaoAgendado + $totalIgrejaIntegracaoAgendado + $totalDiscipuladoIntegracaoAguardandoAgendamento + $totalIgrejaIntegracaoAguardandoAgendamento;
		$totalEntrevistaAgendado = $totalDiscipuladoIntegracaoAgendado + $totalIgrejaIntegracaoAgendado;
		$totalEntrevistaNaoAgendado = $totalDiscipuladoIntegracaoAguardandoAgendamento + $totalIgrejaIntegracaoAguardandoAgendamento;
		$totalNovosMembrosFuturos = $totalDiscipuladoIntegracaoAprovado + $totalIgrejaIntegracaoAprovado;
		$totalNovosMembros = $totalAnoMembroDiscipulado + $totalAnoMembroSerIgreja;
		
		
		$html .= '
		<table id="tabelaTotaisTudo" class="table table-bordered table-striped table-sm">
            <thead class="thead-dark">
				<tr>
					<th colspan="5">Integrações - Discipulados e Igreja</th>
				</tr>
				<tr>
					<th></th>
					<th>Discipulado</th>
					<th>Igreja</th>
					<th>Acompanhamento</th>
					<th>Total</th>
				</tr>
				
            </thead>
            <tbody>
            ';
		$html .= "<tr>";
			$html .= "<td>1- MOVIMENTO DISCIPULAR - Processos de integração em andamento</td>";
			$html .= "<td>".$totalDiscipulado."</td>";
			$html .= "<td>".$totalIgreja."</td>";
			$html .= "<td>".$totalAcompanhamento."</td>";
			$html .= "<td>".$totalMovimentoDiscipular."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
			$html .= "<td>2- INTEGRAÇÃO - Aguardando entrevista com pastor</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td>".$totalAguardandoEntrevista."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
			$html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1 Entrevistas agendadas</td>";
			$html .= "<td>".$totalDiscipuladoIntegracaoAgendado."</td>";
			$html .= "<td>".$totalIgrejaIntegracaoAgendado."</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
		$html .= "</'tr>";
		$html .= "<tr>";
			$html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2 Entrevistas aguardando agendamento</td>";
			$html .= "<td>".$totalDiscipuladoIntegracaoAguardandoAgendamento."</td>";
			$html .= "<td>".$totalIgrejaIntegracaoAguardandoAgendamento."</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
		$html .= "</'tr>";
		$html .= "<tr>";
			$html .= "<td>3- INTEGRAÇÃO - Aguardando batismo</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td>".$totalDiscipuladoIntegracaoAguardandoBatismo."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
			$html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1- Batismos não agendados</td>";
			$html .= "<td>".$totalBatismoAguardandoResposta."</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
		$html .= "</'tr>";
		$html .= "<tr>";
			$html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2- Batismos agendados</td>";
			$html .= "<td>".$totalBatismoFuturo."</td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
			$html .= "<td></td>";
		$html .= "<tr>";
			$html .= "<td>4- INTEGRAÇÃO - Novos membros a serem recebidos na próxima assembleia</td>";
			$html .= "<td>".$totalDiscipuladoIntegracaoAprovado."</td>";
			$html .= "<td>".$totalIgrejaIntegracaoAprovado."</td>";
			$html .= "<td></td>";
			$html .= "<td>".$totalNovosMembrosFuturos."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
			$html .= "<td>5- INTEGRAÇÃO - Membros recebidos em assembleias já realizadas em ".$ano."</td>";
			$html .= "<td>".$totalAnoMembroDiscipulado."</td>";
			$html .= "<td>".$totalAnoMembroSerIgreja."</td>";
			$html .= "<td></td>";
			$html .= "<td>".$totalNovosMembros."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
				$html .= "<td>TOTAL</td>";
				$html .= "<td colspan='3' style='text-align: center'><b>".$totalTodos."</b></td>";
				$html .= "<td></td>";
				$html .= "<td></td>";
		$html .= "</tr>";
		
		
		$html .= '
            </tbody>
        </table>';
		
		echo json_encode($html);
		
	}
	
	function tabeladiscipulos(){
		$this->loadModel('Clientepib');
		$this->loadModel('Logalteracaostatus');
		
		$this->autoRender = false;
		$this->layout = false;
		
		$totalDiscipulosComVinculo = $this->Clientepib->totalComVinculo();
		$totalDiscipulosComVinculoStandBy = $this->Clientepib->totalComVinculoStandBy();
		$totalDiscipulosSemVinculo = $this->Clientepib->totalSemVinculo();
		$contadorTotalCancelamento = 0;
		$html = "";
		
		
		$totalCancelamento = $this->Logalteracaostatus->query("
			SELECT 
				COUNT(log_alteracaostatus.id_atualizado) as qtd, 
				log_alteracaostatus.id_atualizado, 
				log_alteracaostatus.novo_status 
			FROM 
				log_alteracaostatus 
			INNER JOIN 
				sistemapib.membros AS m 
			ON 
				m.id = log_alteracaostatus.id_atualizado 
				AND m.ativo = 1 
				AND m.liberado_entrevista = 0 
			WHERE 
				novo_status = 2 
			group by 
				log_alteracaostatus.id_atualizado 
			having 
				COUNT(log_alteracaostatus.id_atualizado) = 1"
		);
		$contadorTotalCancelamento = sizeof($totalCancelamento);
		/*
		$totalVoltando = $this->Logalteracaostatus->query("SELECT count(id_atualizado), id_atualizado, GROUP_CONCAT(novo_status ORDER BY id ASC SEPARATOR '/' ) as status FROM `log_alteracaostatus` group by id_atualizado having count(id_atualizado) >1");
		foreach ($totalVoltando as $voltando){
			$status = explode("/", $voltando[0]["status"]);
		}
		*/
		$html .= "<table class='table table-bordered table-striped table-sm'>";
			$html .= "<thead class='thead-dark'>";
				$html .= "<tr>";
					$html .= "<th colspan='4' class='text-center'>Discípulos</th>";
				$html .= "</tr>";
				$html .= "<tr>";
					$html .= "<th>Vinculados</th>";
					$html .= "<th>Não vinculados</th>";
					$html .= "<th>Stand-by</th>";
					$html .= "<th>Cancelados no meio do discipulado</th>";
				$html .= "</tr>";
			$html .= "</thead>";
			$html .= "<tbody>";
				$html .= "<tr>";
					$html .= "<td>".$totalDiscipulosComVinculo."</td>";
					$html .= "<td>".$totalDiscipulosSemVinculo."</td>";
					$html .= "<td>".$totalDiscipulosComVinculoStandBy."</td>";
					$html .= "<td><a href='#' onclick='discipulosvinculados()'>".$contadorTotalCancelamento."</a></td>";
				$html .= "</tr>";
			$html .= "</tbody>";
		$html .= "</table>";
		
		
		echo json_encode($html);
		
	}
	
	function discipuloscancelados(){
		$this->loadModel('Clientepib');
		$this->loadModel('Logalteracaostatus');
		
		$this->autoRender = false;
		$this->layout = false;
		
		$html = "";
		$totalCancelamento = $this->Logalteracaostatus->query("	SELECT COUNT(log_alteracaostatus.id_atualizado) as qtd, log_alteracaostatus.data, log_alteracaostatus.id_atualizado, m.nome, m.email, m.telefone_1, m.status_id, m.observacao, s.descricao, log_alteracaostatus.novo_status
																FROM log_alteracaostatus
																INNER JOIN sistemapib.membros AS m ON m.id = log_alteracaostatus.id_atualizado AND m.ativo = 1 AND m.liberado_entrevista = 0
																INNER JOIN sistemapib.status AS s ON s.id = m.status_id
																WHERE novo_status = 2 group by log_alteracaostatus.id_atualizado having COUNT(log_alteracaostatus.id_atualizado) = 1 ORDER BY m.nome ASC");
		$html .= "<table class='table table-sm'>";
			$html .= "<thead>";
				$html .= "	<th>Status</th>
							<th>Data</th>
							<th>Nome</th>
							<th>Email</th>
							<th>Contato</th>
							<th>Observação</th>";
			$html .= "</thead>";
		foreach ($totalCancelamento as $total){
			$html .= "<tr>";
				$html .= "<td>".$total["s"]["descricao"]."</td>";
				$html .= "<td>".$total["log_alteracaostatus"]["data"]."</td>";
				$html .= "<td>".$total["m"]["nome"]."</td>";
				$html .= "<td>".$total["m"]["email"]."</td>";
				$html .= "<td>".$total["m"]["telefone_1"]."</td>";
				$html .= "<td>".$total["m"]["observacao"]."</td>";
			$html .= "</tr>";
		}
		$html .= "</table>";
		
		echo json_encode($html);
	}
	
	function discipuladoresociosos(){
		$this->loadModel("Rede");
		$this->loadModel("Euvim");
		$this->loadModel("Clientepib");
		$this->loadModel("Discipulador");
		$this->loadModel("Historicoacompanhamento");
		$this->loadModel("Tabelagrupogestor");
		$this->autoRender = false;#nao permite carregar paginas em html
		$this->layout = false;
		
		$html = '';
		$contador = 0;
		$dadosTabelaDicipuladorLivre = array();

		$redes = $this->Rede->redespais();
		//Buscando discipuladores ociosos por rede/faixa etária
		foreach ($redes as $rede){
			$discipuladoresRede = $this->Discipulador->find('all', array(
				"fields" => "Discipulador.id_faixa, COUNT(Discipulador.id_faixa) as total",
				"conditions" => "Discipuladorrede.rede_contagem = ".$rede['Rede']['id']." AND Discipuladorrede.discipulador_id NOT IN (SELECT discipulador_ci FROM sistemapib.membros WHERE discipulador_ci = Discipuladorrede.discipulador_id)",
				"joins" => array(
					array(
						"type" => "left",
						"table" => "discipuladores_redes",
						"alias" => "Discipuladorrede",
						"conditions" => "Discipuladorrede.discipulador_id = Discipulador.id_membro"
					)
				),
				"group" => "Discipulador.id_faixa"
			));
			$dadosTabelaDicipuladorLivre[$contador] = array('idrede' => $rede['Rede']['id'], 'nomerede' => $rede['Rede']['nome']);
			foreach($discipuladoresRede as $dr){
				array_push($dadosTabelaDicipuladorLivre[$contador], array("faixaetaria" => $dr['Discipulador']['id_faixa'], 'totalfaixa' => $dr[0]['total']));
			}
			
			$contador ++;
		}
		$html .= '
		<table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
            <tr>
                <th colspan="7">Discipuladores aguardando</th>
            </tr>
            <tr>
                <th>Rede</th>
                <th>Até 20 anos</th>
                <th>Até 30 anos</th>
                <th>Até 40 anos</th>
                <th>Até 50 anos</th>
                <th>Até 60 anos</th>
                <th>Maiores que 60 anos</th>
            </tr>
            </thead>
            <tbody>
            ';
                foreach ($dadosTabelaDicipuladorLivre as $dl){
                    $html .= "<tr>";
					$html .= "<td>".$dl['nomerede']."</td>";
					$html .= "<td>".$dl[0]['totalfaixa']."</td>";
					$html .= "<td>".$dl[0]['totalfaixa']."</td>";
					$html .= "<td>".$dl[1]['totalfaixa']."</td>";
					$html .= "<td>".$dl[2]['totalfaixa']."</td>";
					$html .= "<td>".$dl[3]['totalfaixa']."</td>";
					$html .= "<td>".$dl[4]['totalfaixa']."</td>";
					$html .= "</tr>";
                }
		$html .= '
            </tbody>
        </table>';
		
		$this->Tabelagrupogestor->updateAll(
			array(
				'html' => "'".$html."'"
			),  array(
				'tabela' => 'disicpuladoresociosos'
			)
		);
  
		//echo json_encode($html);
	}
	
	public function teste(){
		
		$this->loadModel('Rede');
		$this->loadModel('Euvim');
		$this->loadModel('Clientepib');
		$this->loadModel('MembroVinculo');
		$this->loadModel('Clientepibantigo');
		$this->loadModel('Discipulador');
		$this->loadModel('Historicoacompanhamento');
		$this->loadModel('Tabelagrupogestor');
		$this->loadModel('Discipuladorrede');
		$this->loadModel('Historicoacompanhamentodiscipulo');
		
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
			
			$redes = $this->Rede->redespais();
			$totalDiscipuladoresUnico = $this->Discipuladorrede->query('SELECT COUNT(DISTINCT(discipulador_id)) as total FROM discipuladores_redes');
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
					
					$discipulados = $this->Clientepib->find("all", array(
						"fields" => "Clientepib.id, Clientepib.status_id",
						"conditions" => "
							Clientepib.liberado_entrevista = 0 
							AND Clientepib.discipulador_ci != 0 
							AND  Clientepib.discipulador_ci = ".$discipulador['Discipuladorrede']['discipulador_id']." 
							AND Clientepib.rede_discipulador = ".$rede['Rede']['id']." 
							AND (
								Clientepib.status_id = 26 
								OR Clientepib.status_id = 27
								OR Clientepib.status_id = 29
							) 
							AND Clientepib.ativo = 1"
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
			echo '<pre>'; print_r($dadosTabelaGerencial); exit;
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
	public function buscasubredes($id = null){
		$this->loadModel('Rede');
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['rede'];
			$dadosSubRede = $this->Rede->dadosSubRede($idRede);
			if(!empty($dadosSubRede)){
				foreach($dadosSubRede as $subclasse){					
					$opcoesSelect .= "<option value='".$subclasse['Rede']['id']."'>".$subclasse['Rede']['nome']."</option>";					
				}
			}else{
				$opcoesSelect ='<option value="">Não há sub-redes</option>';
			}
			echo json_encode($opcoesSelect);
		}
	}
	public function tabelaniveisdiscipulado(){
		$this->autoRender = false;#nao permite carregar paginas em html
		$this->layout = false;
		$this->loadModel('Rede');
		$this->loadModel('MembroVinculo');

		$html = '';

		$redes = $this->Rede->redespais();

		$html .= '<table class="table table-bordered">';
			$html .= '<thead class="thead-dark">';
				$html .= '<th>#</th>';
				$html .= '<th>Rede</th>';
				$html .= '<th>Nível 1</th>';
				$html .= '<th>Nível 2</th>';
				$html .= '<th>Nível 3</th>';
				$html .= '<th>Nível 4</th>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach($redes as $rede){
				$nivel1Rede = $this->MembroVinculo->find('count', array(
					'conditions' => 'MembroVinculo.rede_discipulador = '.$rede['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1'
				));
				$nivel2Rede = $this->MembroVinculo->find('count', array(
					'conditions' => 'MembroVinculo.rede_discipulador = '.$rede['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 2'
				));
				$nivel3Rede = $this->MembroVinculo->find('count', array(
					'conditions' => 'MembroVinculo.rede_discipulador = '.$rede['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 3'
				));
				$nivel4Rede = $this->MembroVinculo->find('count', array(
					'conditions' => 'MembroVinculo.rede_discipulador = '.$rede['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 4'
				));
				$html .= '<tr data-toggle="collapse" data-target="#subRede'.$rede['Rede']['id'].'"  class="accordion-toggle" >';
					$html .= '<td><i id="iconeSubRede-'.$rede["Rede"]["id"].'" class="fas fa-plus-circle" onclick="buscaSubRede('.$rede["Rede"]["id"].')" aria-hidden="true"></i></td>';
					$html .= '<td>'.$rede['Rede']['nome'].'</td>';
					$html .= '<td>'.$nivel1Rede.'</td>';
					$html .= '<td>'.$nivel2Rede.'</td>';
					$html .= '<td>'.$nivel3Rede.'</td>';
					$html .= '<td>'.$nivel4Rede.'</td>';					
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td colspan="12" class="hiddenRow">';
						$html .=  '<div class="accordian-body collapse" id="subRede'.$rede['Rede']['id'].'"></div>';						
					$html .= '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
		$html .= '</table>';

		echo json_encode($html);
	}
	public function tabelasubredes($id = null){
		$this->loadModel('Rede');
		$this->loadModel('MembroVinculo');
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['id'];
			$dadosSubRede = $this->Rede->dadosSubRede($idRede);
			if(!empty($dadosSubRede)){
				$html .= '<table class="table table-bordered">';
					$html .= '<thead class="thead-dark">';
						$html .= '<th>#</th>';
						$html .= '<th>Rede</th>';
						$html .= '<th>Nível 1</th>';
						$html .= '<th>Nível 2</th>';
						$html .= '<th>Nível 3</th>';
						$html .= '<th>Nível 4</th>';
					$html .= '</thead>';
					$html .= '<tbody>';
						foreach ($dadosSubRede as $subclasse){
							$nivel1Rede = $this->MembroVinculo->find('count', array(
								'conditions' => 'MembroVinculo.rede_discipulador_filha = '.$subclasse['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 1'
							));
							$nivel2Rede = $this->MembroVinculo->find('count', array(
								'conditions' => 'MembroVinculo.rede_discipulador_filha = '.$subclasse['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 2'
							));
							$nivel3Rede = $this->MembroVinculo->find('count', array(
								'conditions' => 'MembroVinculo.rede_discipulador_filha = '.$subclasse['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 3'
							));
							$nivel4Rede = $this->MembroVinculo->find('count', array(
								'conditions' => 'MembroVinculo.rede_discipulador_filha = '.$subclasse['Rede']['id'].' AND MembroVinculo.ativo = 1 AND MembroVinculo.nivel_discipulo = 4'
							));
							$html .= '
							<tr data-toggle="collapse" data-target="#pessoasSubRede'.$subclasse['Rede']['id'].'"  class="accordion-toggle">
								<td><i id="iconePessoasSubRede-'.$subclasse["Rede"]["id"].'" class="fas fa-plus-circle" onclick="buscaPessoasSubRede('.$subclasse["Rede"]["id"].')" aria-hidden="true"></i></td>
								<td>'.$subclasse['Rede']['nome'].'</td>
								<td>'.$nivel1Rede.'</td>
								<td>'.$nivel2Rede.'</td>
								<td>'.$nivel3Rede.'</td>
								<td>'.$nivel4Rede.'</td>
								
							</tr>
							';
							$html .= '<tr>';
								$html .= '<td colspan="12" class="hiddenRow">';
									$html .=  '<div class="accordian-body collapse" id="pessoasSubRede'.$subclasse['Rede']['id'].'"></div>';						
								$html .= '</td>';
							$html .= '</tr>';
						}
					$html .= '</tbody>';
				$html .= '</table>';
			}else{
				$html ='<p>Não há sub-redes</p>';
			}
			echo json_encode($html);
		}
		
	}
	public function pessoassubrede(){
		$this->loadModel('Rede');
		$this->loadModel('MembroVinculo');
		$this->layout = false;
		$this->autoRender = false;
		$html = '';
		if($this->request->is('ajax')){
			$idRede = $this->request->data['id'];			
			if(!empty($idRede)){
				$html .= '<table class="table table-bordered">';
					$html .= '<thead class="thead-dark">';
						$html .= '<th>#</th>';
						$html .= '<th>Nível</th>';
						$html .= '<th>Nome</th>';
						$html .= '<th>Discipulador</th>';
					$html .= '</thead>';
					$html .= '<tbody>';
						$pessoasSubRede = $this->MembroVinculo->find('all', array(
							'fields' => 'MembroVinculo.nivel_discipulo, Membro.id, Membro.nome, Discipulador.nome',
							'conditions' => 'MembroVinculo.rede_discipulador_filha = '.$idRede.' AND MembroVinculo.ativo = 1',
							'joins' => array(
								array(
									'type' => 'left',
									'table' => 'membros',
									'alias' => 'Membro',
									'conditions' => 'Membro.id = MembroVinculo.membro_id'
								),
								array(
									'type' => 'left',
									'table' => 'membros',
									'alias' => 'Discipulador',
									'conditions' => 'Discipulador.id = MembroVinculo.discipulador_id'
								)
							),
							'order' => 'MembroVinculo.nivel_discipulo, Membro.nome, Discipulador.nome'
						));
						foreach ($pessoasSubRede as $pessoas){
							if($pessoas['MembroVinculo']['nivel_discipulo'] == 1){
								$caminhoPerfil = 'discipulados/perfil/'.$pessoas['Membro']['id'];
							}else if($pessoas['MembroVinculo']['nivel_discipulo'] == 2){
								$caminhoPerfil = 'discipuladosd2/perfil/'.$pessoas['Membro']['id'];
							}else if($pessoas['MembroVinculo']['nivel_discipulo'] == 3){
								$caminhoPerfil = 'discipuladosd3/perfil/'.$pessoas['Membro']['id'];
							}else{
								$caminhoPerfil = 'discipuladosd4/perfil/'.$pessoas['Membro']['id'];
							}
							$html .= '
							<tr>
								<td><a href="'.$caminhoPerfil.'" target="_blank"><i class="fas fa-eye"></i></a></td>
								<td>'.$pessoas['MembroVinculo']['nivel_discipulo'].'</td>
								<td>'.$pessoas['Membro']['nome'].'</td>
								<td>'.$pessoas['Discipulador']['nome'].'</td>						
							</tr>
							';
						}
					$html .= '</tbody>';
				$html .= '</table>';
			}else{
				$html ='<p>Não há pessoas</p>';
			}
			echo json_encode($html);
		}
	}
}