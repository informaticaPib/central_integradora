
<div class="opt-interna">
	<button class="btn btn-default" data-toggle="modal" data-target="#addUsuario"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> Cadastrar usuário</button>
</div>
<p>teste</p>
<p>teste23</p>
<hr>
<div class="row col-md-12">
    <?php
        echo $this->Form->create('users', array(
            'url' => array('controller' => 'users', 'action' => 'index'),
            'type' => 'get',
            'class' => 'form-inline'
        ));
    ?>
        <div class="form-group">
            <label class="" for="nome">Nome da pessoa</label>
            <input type="text" name="nome" class="form-control" value="<?php echo $this->params['url']['nome'] ;?>">
        </div>
        <input type="submit" class="btn btn-info" value="Filtrar">
	</form>
</div>

<?php



$paginator = $this->Paginator;
echo "<div class='table-responsive'>";
    echo "<table class='table table-condensed table-hover'>";
        echo "<thead class='thead-dark'>";
            echo "<th>Ações</th>";
            echo "<th>" . $paginator->sort('id', 'ID')."</th>";
            echo "<th>" . $paginator->sort('nome', 'Nome')."</th>";
            echo "<th>" . $paginator->sort('username', 'Usuário')."</th>";
            echo "<th>" . $paginator->sort('contato', 'Contato')."</th>";
            echo "<th>" . $paginator->sort('email', 'Email')."</th>";
            echo "<th>" . $paginator->sort('role', 'Perfil')."</th>";
            echo "<th>" . $paginator->sort('created', 'Data de cadastro')."</th>";
            echo "<th>" . $paginator->sort('modified', 'Ultima alteração')."</th>";
        echo "</thead>";
        echo "<tbody>";
        foreach( $listaUsuarios as $lista ){
            echo '<tr>';
            echo '<td>';
                    echo
                        $this->Html->tag('i', '',
                            array(
                                'class' => 'fas fa-user-edit pointer',
                                'escape' => false,
                                'data-toggle' => 'modal',
                                'data-target' => '#editUsuario',
                                'data-name' => $lista["User"]["nome"],
                                'data-contato' => $lista["User"]["contato"],
                                'data-email' => $lista["User"]["email"],
                                'data-idmembro' => $lista["User"]["membro_id"],
                                'data-id' => $lista["User"]["id"],
                                'data-username' => $lista["User"]["username"],
                                'data-role' => $lista["User"]["role"],
                                'title' => 'Editar Usuário',
                                'data-placement' => 'bottom',
                                'data-tt' => 'tooltip'
                            )
                        );
                    echo "&nbsp;&nbsp;&nbsp;";
                    echo $this->Html->link ($this->Html->tag('i', '',
                            array(
                                    'class' => 'fas fa-cogs pointer',
                                    'title' => 'Editar Permissões',
                            )
                    ), ['controller' =>'users', 'action' =>'permissoes', $lista['User']['id']], array('escape' => false));
                    echo "&nbsp;&nbsp;&nbsp;";
                    echo $this->Html->link(
                        $this->Html->tag('i', '',
                            array(
                                'class' => 'fas fa-trash-alt pointer',
                                'title' => 'Deletar Usuário',
                            )
                        ),
                        array(
                            'controller' => 'Users',
                            'action' => 'delete',
                            $lista['User']['id']),
                        array(
                            'escape' => false,
                            'confirm' => 'Você tem certeza?'
                        )
                    );
                echo '</td>';
                echo '<td>'.$lista['User']['id'] .'</td>';
                echo '<td>'.$lista['User']['nome'] .'</td>';
			    echo '<td>'.$lista['User']['username'] .'</td>';
			    echo '<td>'.$lista['User']['contato'] .'</td>';
			    echo '<td>'.$lista['User']['email'] .'</td>';
                echo '<td>'.$lista['User']['role'] .'</td>';
                echo '<td>'.$lista['User']['created'] .'</td>';
                echo '<td>'.$lista['User']['modified'] .'</td>';
                
            echo '</tr>';
        }
        echo "<tbody>";
    echo "</table>";
echo "</div>";
	echo "<div class='paging'>";
	
	// the 'first' page button
	echo $paginator->first("Primeira página");
	
	// 'prev' page button,
	// we can check using the paginator hasPrev() method if there's a previous page
	// save with the 'next' page button
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	
	// the 'number' page buttons
	echo $paginator->numbers(array('modulus' => 5));
	
	// for the 'next' button
	if($paginator->hasNext()){
		echo "&nbsp;".$paginator->next("Próximo ");
	}
	
	// the 'last' page button
	echo $paginator->last(" Última página");
	echo "</div>"
?>

<!-- Modal ADD-->
<div class="modal fade" id="addUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Cadastro de usuários</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
                echo $this->Form->create('User', array(
                    'url' => array('controller' => 'users', 'action' => 'add'),
                    'class' => 'form-horizontal'
                ));

            ?>
                <div class="form-group">
                    <label for="">ID de membro</label>
                    <?php echo $this->Form->input('membro_id', array('label' => false, 'class' => 'form-control', 'type' => 'number')); ?>
                    <small id="nameHelp" class="form-text text-muted">Não é obrigado, mas caso saiba preencher</small>
                </div>

                <div class="form-group">
                    <label for="">Nome<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('nome', array('label' => false, 'class' => 'form-control', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha o nome completo para melhor identificação</small>
                </div>
                <div class="form-group">
                    <label for="">Contato<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('contato', array('label' => false, 'class' => 'form-control', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha um número para contato</small>
                </div>
                <div class="form-group">
                    <label for="">Email<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('email', array('label' => false, 'type'=>'email', 'class' => 'form-control', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha o email</small>
                </div>
                <div class="form-group">
                    <label for="">Sigla/Time<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('sigla', array('label' => false, 'class' => 'form-control', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Exemplo: ABC</small>
                </div>
                <div class="form-group">
                    <label for="">Usuário<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Exemplo: nome.sobrenome</small>
                </div>

                <div class="form-group">
                    <label for="">Senha<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control', 'required' => 'required')); ?>
                </div>

                <div class="form-group">
                    <label for="">Nível de acesso<span class="vermelho">*</span></label>
                    <?php
                        echo $this->Form->input('role', array(
                        	'class' => 'form-control',
                        	'label' => false,
				            'options' => array('operador' => 'Operador', 'discipulador' => 'Discipulador'),
                            'required' => 'required'
				        ));
                    ?>
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


<!-- Modal EDIT-->
<div class="modal fade" id="editUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Editar usuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
                echo $this->Form->create('User', array(
                    'url' => array('controller' => 'users', 'action' => 'edit'),
                    'class' => 'form-horizontal'
                ));
                echo $this->Form->input('id', array('label' => false, 'class' => 'form-control idUsuario', 'type' => 'hidden'));
            ?>
                <div class="form-group">
                    <label for="">ID de membro</label>
                    <?php echo $this->Form->input('membro_id', array('label' => false, 'class' => 'form-control editIdMembro', 'type' => 'text')); ?>
                    <small id="nameHelp" class="form-text text-muted">Não é obrigado, mas caso saiba preencher</small>
                </div>

                <div class="form-group">
                    <label for="">Nome<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('nome', array('label' => false, 'class' => 'form-control editNome', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha o nome completo para melhor identificação</small>
                </div>
                <div class="form-group">
                    <label for="">Contato<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('contato', array('label' => false, 'class' => 'form-control editContato', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha um número para contato</small>
                </div>
                <div class="form-group">
                    <label for="">Email<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('email', array('label' => false, 'type'=>'email', 'class' => 'form-control editEmail', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha o email</small>
                </div>
                <div class="form-group">
                    <label for="">Usuário<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control editUsuario', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Exemplo: nome.sobrenome</small>
                </div>

                <div class="form-group">
                    <label for="">Senha<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control')); ?>
                </div>

                <div class="form-group">
                    <label for="">Nível de acesso<span class="vermelho">*</span></label>
                    <?php
                        echo $this->Form->input('role', array(
                            'class' => 'form-control editAcesso',
                            'label' => false,
							'options' => array('admin' => 'Administrador', 'operador' => 'Operador', 'pastor' => 'Pastor', 'discipulador' => 'Discipulador'),
                            'required' => 'required'
                        ));
                    ?>
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

<script type="text/javascript">
$(document).ready(function(){

    $(".botaoSubmit").click(function (){
        var idUser = $("#userId").val();
        var dados = idUser;
        form = $("#UserEditForm").serialize();
        $.ajax({
            type: "POST",
            url:'users/edit/'+dados,
            data: form,
            success: function(dados)
            {
                window.setTimeout(function(){
                    location.reload()
                },1000)
            }
        })
    });

    $(document).on( "click", '.fa-user-edit',function(e) {
        $('#editUsuario').modal('show');
        var name = $(this).data('name');
        var id = $(this).data('id');
        var idMembro = $(this).data('idmembro');
        var username = $(this).data('username');
        var role = $(this).data('role');
        var contato = $(this).data('contato');
        var email = $(this).data('email');
        
        $(".editIdMembro").val(idMembro);
        $(".editNome").val(name);
        $(".idUsuario").val(id);
        $(".editUsuario").val(username);
        $(".editAcesso").val(role);
        $(".editContato").val(contato);
        $(".editEmail").val(email);

    });

});

    jQuery(function($){
        $("#UserContato").mask("(99) 99999-9999");

    });
</script>