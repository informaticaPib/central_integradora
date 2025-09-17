<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar usuário</h4>
    </div>
    <div class="modal-body">
        <?php
        echo $this->Form->create('User', array(
            'url' => array('controller' => 'users', 'action' => 'edit'),
            'class' => 'form-horizontal'
        ));

        ?>
        <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Usuário</label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control')); ?>
            </div>
        </div><!--form-group-->

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Senha</label>
            <div class="col-sm-4">
                <?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control')); ?>
            </div>
        </div><!--form-group-->

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nível de acesso</label>
            <div class="col-sm-4">
                <?php
                    echo $this->Form->input('role', array(
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $listaSetores
                    ));
                ?>
            </div>
        </div><!--form-group-->
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success botaoSubmit">Salvar</button>
        
    </div>
    </div> 
    
    <?php echo $this->Form->end(); ?>
</div>


