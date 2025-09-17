<div class="row">
    <div class="col-md-12">
        <h2>Discípulos vinculados (<?php echo $totalDiscipulosComVinculo; ?>)</h2>
        <h3>Discípulos vinculados em stand-by (<?php echo $totalDiscipulosComVinculoStandBy; ?>)</h3>
    </div>
</div>
<div class="row">
	<?php
		echo $this->Form->create('Discipulador', array(
			'url' => array('controller' => 'discipulados', 'action' => 'vinculados'),
			'type' => 'get',
			'class' => 'form-inline'
		));
	?>
	<div class="form-group margin10px">
		<label class="marginRight5px">Discípulo</label>
		<input type="text" name="nome" class="form-control" value="<?php echo $this->params['url']['nome']; ?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">E-mail</label>
		<input type="text" name="email" class="form-control" value="<?php echo $this->params['url']['email']; ?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Contato</label>
		<input type="text" name="contato" class="form-control" value="<?php echo $this->params['url']['contato']; ?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Data de cadastro</label>
		<input type="date" name="dtcadastro" class="form-control" value="<?php echo $this->params['url']['dtcadastro']; ?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Origem</label>
		<select class="form-control" name="origem">
			<option value="">Selecione uma Opção</option>
			<option value="9">Recadastro MD</option>
				<?php
					foreach($listaOrigens as $origens){
						echo "<option value=".$origens['Clientepib']['origem'].">".$origens['Clientepib']['origem']."</option>";
					}
				?>
		</select>
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Rede Pai</label>
		<?php echo $this->Form->input('id_rede_pai',
			array(
				'empty' => 'Selecione uma opção',
				'options' => $listaRedesPais,
				'class' => 'form-control',
				'default' => $this->params['url']['id_rede_pai'],
				'label' => false));
		?>
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Rede</label>
		<?php echo $this->Form->input('id_rede',
			array(
				'empty' => 'Selecione uma opção',
				'options' => $listaRedes,
				'class' => 'form-control',
				'default' => $this->params['url']['id_rede'],
				'label' => false));
		?>
	</div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Time</label>
		<?php echo $this->Form->input('time',
			array(
				'empty' => 'Selecione uma opção',
				'options' => $listaTimes,
				'default' => $this->params['url']['time'],
				'class' => 'form-control',
				'label' => false));
		?>
    </div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Discipulador</label>
		<?php echo $this->Form->input('id_discipulador',
			array(
				'empty' => 'Selecione uma opção',
				'options' => $listaDiscipuladores,
				'class' => 'form-control',
				'default' => $this->params['url']['id_discipulador'],
				'label' => false));
		?>
	</div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Cidade</label>
        <select class="form-control" name="cidade">
            <option value="">Selecione uma opção</option>
            <option value="1" <?php echo ($this->params['url']['cidade'] == 1 ? 'selected' : ''); ?>>Curitiba</option>
            <option value="2" <?php echo ($this->params['url']['cidade'] == 2 ? 'selected' : ''); ?>>Fora de Curitiba</option>
        </select>
    </div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Trilha</label>
        <select class="form-control" name="trilha">
            <option value="">Selecione uma opção</option>
            <option value="26" <?php echo ($this->params['url']['trilha'] == 26 ? 'selected' : ''); ?>>Ser Igreja</option>
            <option value="27" <?php echo ($this->params['url']['trilha'] == 27 ? 'selected' : ''); ?>>Batismo</option>
            <option value="29" <?php echo ($this->params['url']['trilha'] == 29 ? 'selected' : ''); ?>>Acompanhamento</option>
            <option value="30" <?php echo ($this->params['url']['trilha'] == 30 ? 'selected' : ''); ?>>Stand-by</option>
        </select>
    </div>
	<div class="form-group margin10px">
        <label class="marginRight5px">Sexo</label>
		<?php echo $this->Form->input('sexo',
			array(
				'empty' => 'Selecione uma opção',
				'options' => array(0=>'Masculino',1=>'Feminino'),
				'value' => $_GET['sexo'],
				'class' => 'form-control',		
				'default' => $this->params['url']['sexo'],		
				'label' => false));
		?>
    </div>
	<div class="form-group margin10px">
		<div class="input-group">
			<label class="marginRight5px">Idade inicial e final</label>
			<input type="text" class="form-control" name="idadeinicial" value="<?php echo $this->params['url']['idadeinicial']; ?>">
			<div class="input-group-prepend">
				<span class="input-group-text" id=""> - </span>
			</div>
			<input type="text" class="form-control" name="idadefinal" value="<?php echo $this->params['url']['idadefinal']; ?>">
		</div>
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Mostrar Todos os registros</label>
		<select class="form-control" name="limite">
			<option value="">Selecione uma Opção</option>
			<option value="0">Mostrar Todos</option>
			<option value="20">Mostrar 20 por página</option>
			<option value="40">Mostrar 40 por página</option>
		</select>
	</div>
	<input type="submit" class="btn btn-info" value="Filtrar">
	</form>
</div>
<hr>
<button id="gerarPlanilha" class="btn btn-success">Salvar lista em planilha</button>
<hr>

<?php
    
	$paginator = $this->Paginator;
	echo "<div class='row'>";
		echo '<div class="col-sm-1"><i class="fas fa-circle vermelho"></i> Mais de 1 mês atrasado</div>';
		echo '<div class="col-sm-1"><i class="fas fa-circle verde"></i> Acompanhamento em dia </div>';
		echo '<div class="col-sm-1"><i class="fas fa-circle laranja"></i>  1 mês atrasado </div>';
	    echo '<div class="col-sm-1"><div class="legendaTreinamento">Discípulo de fora de Curitiba</div></div>';
	    echo '<div class="col-sm-1"><div class="legendaIndisponivel">Stand-by (parado)</div></div>';
	echo "</div>";
	echo "<table class='table table-condensed table-hover' id='tabelaResultado'>";
        echo "<thead class='thead-dark'>";
			echo "<th>Ações</th>";
            echo "<th>Acompanhamento</th>";
            echo "<th>" . $paginator->sort('time_ci', 'Time')."</th>";
            echo "<th>Rede</th>";
            echo "<th>Discipulador</th>";
            echo "<th>" . $paginator->sort('nome', 'Discípulo')."</th>";
            echo "<th>" . $paginator->sort('sDescricao', 'Email')."</th>";
            echo "<th>" . $paginator->sort('sTelefone', 'Contato')."</th>";
	        echo "<th>" . $paginator->sort('cidade', 'Cidade')."</th>";
            echo "<th>" . $paginator->sort('dtCadastro', 'Data de cadastro')."</th>";
			echo "<th>" . $paginator->sort('data_nascimento', 'Idade')."</th>";
            echo "<th>" . $paginator->sort('status_id', 'Trilha')."</th>";
            echo "<th>" . $paginator->sort('origem', 'Origem')."</th>";			
            echo "<th hidden>" . $paginator->sort('cpf', 'CPF')."</th>";			
        echo "</thead>";
        echo "<tbody>";
        foreach( $discipulosVinculados as $lista ){

            $dataVinculo  		= date("Y-m", strtotime($lista['Clientepib']['data_vinculo_discipulador']));
            $dataAtual 	  		= date("Y-m");
            $ultimoHistorico 	= date("Y-m", strtotime($lista[0]['ultimoHistorico']));
            $mesAnterior 	  	= date("Y-m", strtotime("-1 months"));
            
            if($lista['Discipulador']['nome'] == ''){
                $nomediscipulador = 'Sem vínculo';
            }
            else{
                $nomediscipulador = $lista[0]['nomeDiscipulador'];
            }
            if($lista['Rede']['nome'] == ''){
                $nomerede = 'Sem vínculo';
            }
            else{
                $nomerede = $lista['Rede']['nome'];
            }
            if($lista['Clientepib']['status_id'] == 26){
                $status = "Ser Igreja";
            }
            elseif($lista['Clientepib']['status_id'] == 27){
                $status = "Batismo";
            }
            elseif($lista['Clientepib']['status_id'] == 30){
                $status = "standby";
            }
            else{
                $status = "Acompanhamento";
            }

			if($lista['Clientepib']['cidade'] != 'Curitiba' && $lista['Clientepib']['cidade'] != '0'){
				echo '<tr class="linhaTabelaAmarelo">';
			}
			else{
				if($lista['Clientepib']['status_id'] == 30){
					echo '<tr class="linhaTabelaVermalha">';
				}
				else{
					echo '<tr>';
				}
			}

			#farol que verifica se o discipulado esta atrasado ou nao
			if($ultimoHistorico >= $dataAtual ){
				echo '<td>';						
					echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipulados', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false));
					echo "<i class='fas fa-comments fa-2x' style='color:green' title='Acompanhamento' alt='Acompanhamento' data-toggle='modal' data-target='#acompanhamentodiscipulo' id='idBtnAcompanhamento' onclick='acompanhamentos(\"{$lista['MembroVinculo']['discipulador_id']}\",\"{$lista[0]['nomeDiscipulador']}\",\"{$lista['Clientepib']['id']}\",\"{$lista['Clientepib']['nome']}\")';> </i>";
				echo '</td>';
				echo '<td><i class="fas fa-circle verde"></i></td>';
			}

			elseif($ultimoHistorico == $mesAnterior ){
				echo '<td>';
					echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipulados', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false));
					echo "<i class='fas fa-comments fa-2x' style='color:green' title='Acompanhamento' alt='Acompanhamento' data-toggle='modal' data-target='#acompanhamentodiscipulo' id='idBtnAcompanhamento' onclick='acompanhamentos(\"{$lista['MembroVinculo']['discipulador_id']}\",\"{$lista[0]['nomeDiscipulador']}\",\"{$lista['Clientepib']['id']}\",\"{$lista['Clientepib']['nome']}\")';> </i>";
				echo '</td>';
				echo '<td><i class="fas fa-circle laranja"></i></td>';
			}
			else{
				echo '<td>';						
					echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipulados', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false));												
					echo "<i class='fas fa-comments fa-2x' style='color:green' title='Acompanhamento' alt='Acompanhamento' data-toggle='modal' data-target='#acompanhamentodiscipulo' id='idBtnAcompanhamento' onclick='acompanhamentos(\"{$lista['MembroVinculo']['discipulador_id']}\",\"{$lista[0]['nomeDiscipulador']}\",\"{$lista['Clientepib']['id']}\",\"{$lista['Clientepib']['nome']}\")';> </i>";
				echo '</td>';
				echo '<td><i class="fas fa-circle vermelho"></i></td>';
			}
				
                echo "<td>".$lista['Clientepib']['time_ci']."</td>";
				echo '<td>'.$lista['Rede']['nome'] .'</td>';
                echo '<td>'.$nomediscipulador.'</td>';
                echo '<td>'.$lista['Clientepib']['nome'] .'</td>';
                echo '<td>'.$lista['Clientepib']['email'] .'</td>';
                echo '<td>'.$lista['Clientepib']['telefone_1'] .'</td>';
                echo '<td>'.$lista['Clientepib']['cidade'].'</td>';
                echo '<td>'.$lista[0]['datacadastro'] .'</td>';
				echo '<td>'.$lista[0]['idade'] .' anos</td>';
                echo '<td>'.$status.'</td>';
				
				if(empty($lista['MembroVinculo']['origem']) || strlen($lista['MembroVinculo']['origem'])==1){
					if($lista['MembroVinculo']['origem'] == 9){
						echo '<td><center>Recad. MD</center></td>';	
					}
					else{
						if($lista['Clientepib']['origem']){
							echo '<td><center>'.$lista['Clientepib']['origem'].'</center></td>';
						}
						else{
							echo '<td><center>Origem não inserida</center></td>';
						}
					}
				}
				else{
					echo '<td>'.$lista['MembroVinculo']['origem'] .'</td>';
				}
				echo '<td hidden>'.$lista['Clientepib']['cpf'] .'</td>';
            echo '</tr>';
        }
        echo "</tbody>";
	echo "</table>";
	
	
	echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	echo $this->Paginator->counter(
		'Página {:page} de {:pages}, mostrando de {:start} até {:end} de {:count} registros'
	);
	echo "</div>";
	echo "</div>";
	
	// pagination section
	echo "<div class='paging'>";
	
	// the 'first' page button
	echo $paginator->first("Primeira página ");
	
	// 'prev' page button,
	// we can check using the paginator hasPrev() method if there's a previous page
	// save with the 'next' page button
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior ");
	}
	// the 'number' page buttons
	echo $paginator->numbers(array('modulus' => 5));
	
	// for the 'next' button
	if($paginator->hasNext()){
		echo "&nbsp;".$paginator->next("Próximo ");
	}
	// the 'last' page button
	echo $paginator->last(" Última página ");
	
	echo "</div>";
?>


<!-- Modal REGISTRAR ACOMPANHAMENTO DISCIPULO -->
<div class="modal fade" id="acompanhamentodiscipulo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
		<?php
			echo $this->Form->create('Historicoacompanhamentodiscipulo', array(
				'url' => array('controller' => 'discipulados', 'action' => 'registraracompanhamento'),
				'class' => 'form-horizontal'
			));

			$url =  $_SERVER["REQUEST_URI"];
			echo $this->Form->input("id_registrante", array("type" => "hidden", "value"=>$this->Session->read("Auth.User.id")));
			echo $this->Form->input("id_discipulo", array("type" => "hidden"));
			echo $this->Form->input("tipo_vinculo", array("type" => "hidden", "value"=>1));
			echo $this->Form->input("dados_url", array("type" => "hidden", "value"=> $url));
		?>
        <div class="modal-content">
            <div class="modal-header">
			<div class="container">
				<div class="row">
					<h4><u>Acompanhamento</u></h4>
				</div>			
				<div class="row">
					<h4 class="modal-title"  id='idTopoModal'></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			</div>
                
                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Data de cadastro</label>
                            <input type="date" class="form-control" name="data[Historicoacompanhamentodiscipulo][data]" id="data" value="<?php echo date("Y-m-d"); ?>" readonly>
                            <small id="dataHelp" class="form-text text-muted">Campo preenchido automaticamente</small>
                        </div>
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control" name="data[Historicoacompanhamentodiscipulo][observacao]" id="observacao"></textarea>
                            <small id="dataHelp" class="form-text text-muted">Campo não é obrigatório</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success">Salvar</button>

            </div>
        </div>
		<?php echo $this->Form->end(); ?>
    </div>
</div>

<script>
function acompanhamentos(idDiscipulador,nomeDiscipulador,idDiscipulo, nomeDiscipulo) {
	//console.log(idDiscipulador + ' - '+nomeDiscipulador+ ' - '+idDiscipulo+ ' - '+ nomeDiscipulo);
	$("#HistoricoacompanhamentodiscipuloIdDiscipulo").val("");
	$("#HistoricoacompanhamentodiscipuloIdDiscipulo").val(idDiscipulo);
	$("#idTopoModal").text(nomeDiscipulador + ' / ' + nomeDiscipulo);

}
$(document).ready(function() {
    $("#gerarPlanilha").click(function(){
		$("#tabelaResultado").table2excel({
	    // exclude CSS class
			exclude: ".noExl",
			name: "resultado",
	    	filename: "discipulado_nivel1_vinculado" //do not include extension
		}); 
	});
});
</script>