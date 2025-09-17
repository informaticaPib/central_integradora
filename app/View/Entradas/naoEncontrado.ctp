<!-- ###################################### INICIO MODAL DE naoEncontrado ######################################-->
<div class="modal fade naoEncontrado" tabindex="-1" role="dialog" aria-labelledby="naoEncontrado" aria-hidden="true">
    <div class="modal-dialog">    
        <div class="modal-content">
            <!--header modal-->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastrar pessoa na base?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <!--corpo modal-->
            <div class="modal-body">
                    <div id='resultDetalhes'>&nbsp;</div>
                    <?php
                        echo $this->Form->create('Entrada', array(
                            'url' => array('controller' => 'entradas', 'action' => 'cadastroNaoMembro'),
                            'class' => 'form-horizontal',
                            'id' => 'formNaoEncontrados',
                            'type' => 'POST'
                        ));

                        echo $this->Form->input('id', array(            'type'=>'hidden', 'id'=>'cadId'));
                        echo $this->Form->input('nome', array(          'type'=>'hidden', 'id'=>'cadEntradaNome'));
                        echo $this->Form->input('cpf', array(           'type'=>'hidden', 'id'=>'cadEntradaCpf'));
                        echo $this->Form->input('dt_nacimento', array(  'type'=>'hidden', 'id'=>'cadEntradaDtNasci'));
                        echo $this->Form->input('celular', array(       'type'=>'hidden', 'id'=>'cadEntradaCelular'));
                        echo $this->Form->input('email', array(         'type'=>'hidden', 'id'=>'cadEntradaEmail'));
                        echo $this->Form->input('sexo', array(          'type'=>'hidden', 'id'=>'cadEntradaSexo'));
                        echo $this->Form->input('cep', array(           'type'=>'hidden', 'id'=>'cadEntradaCep'));
                        echo $this->Form->input('tipo', array(          'type'=>'hidden', 'id'=>'cadEntradaTipo'));
                        echo $this->Form->input('origem', array(        'type'=>'hidden', 'id'=>'cadEntradaOrigem'));
                        
                    ?>
                    
            </div>
            
            <!--acao botoes-->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id='idBtnCadastar'>Cadastrar Pessoa?</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- ###################################### FINAL  MODAL DE naoEncontrado ######################################-->