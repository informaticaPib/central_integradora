<ul class="nav">
	<li class="nav-item">
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#adicionarPermissaoUsuarioRede" data-id="<?php echo $id; ?>" >Adicionar permissão</button>
	</li>
</ul>

<table class="table">
	<thead class='thead-dark'>
		<tr>
			<th>Rede</th>
			<th>Permissão</th>
			<th>Ações</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$nomeRede = "";
	if (!empty($permissoesUsuario)) {
		foreach ($permissoesUsuario as $permissao) {
			echo "<tr>";
			if ($nomeRede != $permissao["Rede"]["nome"]) {
				$nomeRede = $permissao["Rede"]["nome"];
				echo "<td>";
					echo $permissao["Rede"]["nome"];
				echo "</td>";
			}else{
				echo "<td></td>";
			}
			echo "<td>";
			echo $permissao["Permissao"]["descricao"];
			echo "</td>";
			echo "<td>";
				echo $this->Html->link(
					$this->Html->tag('i', '',
						array(
							'class' => 'fas fa-trash-alt pointer',
							'title' => 'Deletar Permissão',
						)
					),
					array(
						'controller' => 'Users',
						'action' => 'deletarpermissao',
						$permissao['UsuariosPermissao']['id']),
					array(
						'escape' => false,
						'confirm' => 'Você tem certeza?'
					)
				);
			echo "</td>";
			echo "</tr>";
		}
	}
	?>
	</tbody>
</table>

<!-- Modal ADD-->
<div class="modal fade" id="adicionarPermissaoUsuarioRede" tabindex="-1" role="dialog" aria-labelledby="Permissão Usuário Rede" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Cadastro de permissão</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create('UsuariosPermissao', array(
						'url' => array('controller' => 'users', 'action' => 'permissoes'),
						'class' => 'form-horizontal'
				));
				echo $this->Form->input('id_usuario', array('type' => 'hidden', 'value' => $id));
				?>
				<div>
					<label>Rede</label>
					<?php echo $this->Form->input('id_rede', array('empty' => 'Selecione uma opção', 'options' => $listaRedes, 'class' => 'form-control', 'label' => false)); ?>
					<small id="nameHelp" class="form-text text-muted">Campo não obrigatório, preencher apenas se for uma sub-rede</small>
				</div>
				<div class="form-group">
					<label for="">Permissão</label>
					<?php echo $this->Form->input('id_permissao', array('empty' => 'Selecione uma opção','label' => false, 'options' => $listaPermissoes, 'class' => 'form-control', 'required' => 'required')); ?>
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
