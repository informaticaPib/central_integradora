<ul class="nav">
	<li class="nav-item">
		<button type="button" class="btn btn-info" data-target="#adicionarRede" data-toggle="modal">Adicionar nova rede</button>
	</li>
</ul>

<table class="table table-condensed table-hover">
	<thead class='thead-dark'>
		<th></th>
		<th>Ações</th>
		<th>Rede</th>
		<th>Criado em</th>
		<th>Última edição</th>
		
	</thead>
	<tbody>
	<?php
	foreach($todasRedes as $rede){
		echo "<tr data-toggle='collapse' data-target='#subRede".$rede['Rede']['id']."' class='accordion-toggle'>";
			echo "<td>";
			?>
				<span class="fas fa-chevron-down pointer" id="iconeSubRede-<?php echo $rede["Rede"]["id"]; ?>" onclick="buscaSubRede(<?php echo $rede["Rede"]["id"]; ?>)" aria-hidden="true"></span>
			<?php
			echo "</td>";
			echo "<td>";
				echo $rede['Rede']['nome'];
			echo "</td>";
			echo "<td>";
				echo $rede['Rede']['created'];
			echo "</td>";
			echo "<td>";
				echo $rede['Rede']['modified'];
			echo "</td>";
		echo "</tr>";
		echo "<tr>
				<td colspan='12' class='hiddenRow'>
					<div class='accordian-body collapse' id='subRede".$rede["Rede"]['id']."'>
								<!--parte que recebe o retorno do ajax com as subredes-->
					</div>
				</td>
			</tr>";
	}
	?>
	</tbody>
</table>

<!-- Modal ADD-->
<div class="modal fade" id="adicionarRede" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Cadastro de rede</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('Rede', array(
					'url' => array('controller' => 'redes', 'action' => 'add'),
					'class' => 'form-horizontal'
				));

				?>
				<div class="form-group">
					<label for="">Nome</label>
					<?php echo $this->Form->input('nome', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'required' => 'required')); ?>
					<small id="nameHelp" class="form-text text-muted">Nome completo da rede</small>
				</div>
				<div>
					<label>Rede pai</label>
					<?php echo $this->Form->input('id_pai', array('empty' => 'Selecione uma opção', 'options' => $listaRedesPais, 'class' => 'form-control', 'label' => false)); ?>
					<small id="nameHelp" class="form-text text-muted">Campo não obrigatório, preencher apenas se for uma sub-rede</small>
				</div>
                <div>
                    <label>ID de membro do pastor da rede</label>
					<?php echo $this->Form->input('pastor_rede', array('class' => 'form-control', 'label' => false, 'type' => 'text', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Campo obigatório, caso não saiba a ID de membro solicitar ao mesmo ou pesquisar no sistema vida</small>
                </div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-success">Salvar</button>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script>

function buscaSubRede(id){
	if( $("#iconeSubRede-"+id).hasClass("fa-chevron-down") ){
		$("#iconeSubRede-"+id).removeClass("fa-chevron-down");
		$("#iconeSubRede-"+id).addClass("fa-chevron-up");
	}else{
		$("#iconeSubRede-"+id).removeClass("fa-chevron-up");
		$("#iconeSubRede-"+id).addClass("fa-chevron-down");
	}

	$.ajax({
		type:"POST",
		url:<?php echo "'".$this->Html->url(array('controller' => 'redes', 'action' => 'buscasubredes'), true)."'";  ?>,
		dataType: 'json',
		data:'id='+id,
		async: true,
		success:function(dados){
			$("#subRede"+id).empty();
			$("#subRede"+id).append(dados);
		},
		error: function (request, status, error) {
			alert(request.responseText);
		}
	});
}
</script>
