<!--<div class="alert alert-danger" role="alert">
  <h4><strong>Atenção!!!</strong></h4> O sistema está em manutenção, favor não efetuar <h3><strong>nenhuma alteração!</strong></h3><br/> Atenciosamente Ti Pib.  
</div>-->
<?php 

/*echo "<pre>";
print_r($_GET['sexo']);
echo "</pre>";*/
?>

<div class="row"> 
    <div class="col-md-12">
        <h2>Discípulos não vinculados(<?php echo $totalDiscipulosSemVinculo; ?>) para formação avançada (D4)</h2>
    </div>
</div>
<div class="row">
	<?php
		echo $this->Form->create('Discipulador', array(
			'url' => array('controller' => 'discipuladosd4', 'action' => 'naovinculados'),
			'type' => 'get',
			'class' => 'form-inline'
		));
	?>
	<div class="form-group margin10px">
		<label class="marginRight5px">Discípulo</label>
		<input type="text" name="nome" class="form-control">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">E-mail</label>
		<input type="text" name="email" class="form-control">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Contato</label>
		<input type="text" name="contato" class="form-control">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Data de cadastro</label>
		<input type="date" name="dtcadastro" class="form-control">
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
        <label class="marginRight5px">Time</label>
		<?php echo $this->Form->input('time',
			array(
				'empty' => 'Selecione uma opção',
				'options' => $listaTimes,
				'class' => 'form-control',
				'label' => false));
		?>
    </div>
	<div class="form-group margin10px">
        <label class="marginRight5px">Sexo</label>
		<?php echo $this->Form->input('sexo',
			array(
				'empty' => 'Selecione uma opção',
				'options' => array(0=>'Masculino',1=>'Feminino'),
				'value' => $_GET['sexo'],
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
	<input type="submit" class="btn btn-info" value="Filtrar">
	</form>
</div>

<hr>

<?php
	
	$paginator = $this->Paginator;
	echo "<div class='row'>";
	echo "</div>";
	echo "<table class='table table-condensed table-hover'>";
	echo "<thead class='thead-dark'>";
	echo "<th>Ações</th>";
	echo "<th>" . $paginator->sort('time_ci', 'Time')."</th>";
	echo "<th>Rede</th>";
	echo "<th>Discipulador</th>";
	echo "<th>" . $paginator->sort('nome', 'Discípulo')."</th>";
	echo "<th>" . $paginator->sort('sDescricao', 'Email')."</th>";
	echo "<th>" . $paginator->sort('sTelefone', 'Contato')."</th>";
	echo "<th>" . $paginator->sort('dtCadastro', 'Data de cadastro')."</th>";
	echo "<th>" . $paginator->sort('status_id', 'Trilha')."</th>";
	echo "<th>" . $paginator->sort('origem', 'Origem')."</th>";
	
	echo "</thead>";
	echo "<tbody>";	
	foreach( $listaNaoVinculadosD4 as $lista ){		
        $nomediscipulador = 'Sem vínculo';
        $nomerede = 'Sem vínculo';
		
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
			$status = "D3";
		}
		
		echo '<tr>';
		echo '<td>';
			//echo $this->Html->link("Perfil completo", array('controller' => 'discipulados', 'action' => 'perfil', $lista['Clientepib']['id']), array('class' => 'btn btn-info'));
			echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipuladosd4', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false));
		echo '</td>';
		echo "<td>".$lista['Clientepib']['time_ci']."</td>";
		echo '<td>'.$nomerede.'</td>';
		echo '<td>'.$nomediscipulador.'</td>';
		echo '<td>'.$lista['Clientepib']['nome'] .'</td>';
		echo '<td>'.$lista['Clientepib']['email'] .'</td>';
		echo '<td>'.$lista['Clientepib']['telefone_1'] .'</td>';
		echo '<td>'.$lista[0]['datacadastro'] .'</td>';
		echo '<td>'.$status.'</td>';
		#recadastramento md
		if(empty($lista['Clientepib']['origem']) || strlen($lista['Clientepib']['origem'])==1){
			if($lista['Clientepib']['origem'] == 9){
				echo '<td><center>Recad. MD</center></td>';	
			}
			else{
				echo '<td><center>Origem não <br/> inserida</center></td>';
			}
			
		}
		else{
			echo '<td>'.$lista['Clientepib']['origem'] .'</td>';
		}
		

		echo '</tr>';
		
		$contador ++;
		
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
