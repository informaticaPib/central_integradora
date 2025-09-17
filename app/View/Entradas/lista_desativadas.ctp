<h3>Entradas Desativadas</h3>
<hr>
<meta name="robots" content="noindex">
<?php
	$permissao  = $this->Session->read('Auth.User.role');
    $id_usuario = $this->Session->read('Auth.User.id');

    echo "<div class='row'>";
        echo "<div class='col-md-12'>";
            echo $this->Form->create('Entrada', array(
                'url' => array('controller' => 'entradas', 'action' => 'listaDesativadas'),
                'type' => 'GET'
            ));
            echo "<div class='row'>";
                    if ($_GET['nome']!="") { $returnNome = $_GET['nome'];} else{$returnNome ="";}        
                    echo "<div class='col-md-3'>";        
                        echo "<div class='form-group'>
                                <label for=''>Nome</label>
                                <input name='nome' type='text' id='EntradaNome' class='form-control' value='$returnNome'>";
                        echo "</div>";
                    echo "</div>";

                    if ($_GET['cpf']!="") { $returnCpf = $_GET['cpf'];} else{$returnCpf ="";}
                    echo "<div class='col-md-3'>";        
                        echo "<div class='form-group'>
                                <label for=''>CPF</label>
                                <input name='cpf' class='form-control' id='idCpf' type='text' value='$returnCpf' placeholder='somente números'>
                                </div>
                        </div>";

                    if ($_GET['tipo']!="") { $returnTipo = $_GET['tipo'];} else{$returnTipo ="";}

                    echo "<div class='col-md-3'>";
                        echo "<div class='form-group'>".
                                $this->Form->input('tipo', array(
                                        'class'     => 'form-control',
                                        'label'     => 'Tipo',
                                        'type'      => 'select',
                                        'empty'     => 'Selecione uma opção',
                                        'options'   => array('andamento'=>'Andamento','celula'=>'Célula','conhecer_jesus'=>'Conhecer Jesus','discipulado'=>'Discipulado',   'reconciliar'=>'Reconciliar','ser_igreja'=>'Ser igreja' ),
                                        'selected'  => $returnTipo
                                ))."
                            </div>";
                    echo "</div>";    
                    if ($_GET['origem']!="") { $returnOrigem = $_GET['origem'];} else{$returnOrigem ="";}
                    echo "<div class='col-md-3'>";
                        echo "<div class='form-group'>".
                                $this->Form->input('origem', array(
                                        'class'     => 'form-control',
                                        'label'     => 'Origem',
                                        'type'      => 'select',
                                        'empty'     => 'Selecione uma opção',
                                        'options'   => array('atemporal_discipulado'=>'Atemporal Discipulado','atemporal_reconciliacao'=>'Atemporal Reconciliacao','atemporal_serigreja'=>'Atemporal Ser Igreja','campanha-jesus-e-a-resposta'=>'Jesus é a resposta',   'discipulados_em_andamento'=>'Discipulados em andamento','jesus'=>'Jesus' ,'jornada'=>'Jornada'),
                                        'selected'  => $returnOrigem
                                ))."
                            </div>";
                    echo "</div>"; 


                    echo "<div class='col-md-3'>
                            <div class='form-group'>
                                <label for=''>Data Inicial:</label>
                                <input name='dtIni' class='form-control' id='idDtIni' type='date' value=".$_GET['dtIni'].">
                            </div>
                        </div>";
                        
                        echo "<div class='col-md-3'>
                            <div class='form-group'>
                                <label for=''>Data Final:</label>
                                <input name='dtFim' class='form-control' id='idDtFim' type='date' value=".$_GET['dtFim'].">
                            </div>
                        </div>";



            echo "</div>";//fecha a md
            echo "<div class='form-group'>
                        <input type='submit' class='btn btn-primary' value='Filtrar'>
                        <input type='button' class='btn btn-warning' id=btnLimpar value='Limpar'>";

                        #carrego as infos depois de serem resgatadas no botão filtrar
                        #então eu filtro primeiro e depois se precisar eu envio elas.
                        $infosGerais = array(
                            'nome'   => isset($this->request->query['nome']) ? $this->request->query['nome'] : '',
                            'cpf'    => isset($this->request->query['cpf']) ? $this->request->query['cpf'] : '',
                            'tipo'   => isset($this->request->query['tipo']) ? $this->request->query['tipo'] : '',
                            'origem' => isset($this->request->query['origem']) ? $this->request->query['origem'] : '',
                            'dtIni'  => isset($this->request->query['dtIni']) ? $this->request->query['dtIni'] : '',
                            'dtFim'  => isset($this->request->query['dtFim']) ? $this->request->query['dtFim'] : ''
                        );
                            
                            echo '&nbsp';

                            //btn de envio das infos
                            echo $this->Html->link(
                                                    'Export Geral',
                                                    array(
                                                        'controller' => 'entradas',
                                                        'action' => 'exportExcelGeralDesativada',
                                                        '?' => $infosGerais
                                                    ),
                                                    array('class' => 'btn btn-success')
                                                );
                        
            echo "</div>";
         echo "</div>";    
    echo "</form>";
    echo "</div>";//fecha a row

?>

    <table class="table">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>celular</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Origem</th>
        <th>Dt.Entrada</th>
        <th>Ação</th>
    </tr>
    <?php foreach ($infosReabilitar as $dados): ?>
    <tr>
        <td><?php echo h($dados['Entrada']['id']); ?></td>
        <td><?php echo h($dados['Entrada']['nome']); ?></td>
        <td><?php echo h($dados['Entrada']['cpf']); ?></td>
        <td><?php echo h($dados['Entrada']['celular']); ?></td>
        <td><?php echo h($dados['Entrada']['email']); ?></td>
        <td><?php echo h($dados['Entrada']['tipo']); ?></td>
        <td><?php echo h($dados['Entrada']['origem']); ?></td>
        <td><?php echo h(date("d/m/Y H:i:s", strtotime(($dados['Entrada']['created']))) ); ?></td>
        <td>
          <?php echo"
                    <button type='button' class='btn btn-danger' onclick='reabilitarUser(".$dados['Entrada']['id'].");'>
                      <i class='fas fa-sync-alt' alt='reabilitar' title='reabilitar' ></i> Reativar
                    </button>
          ";?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="paginator">
    <ul class="pagination">
        <?php
            echo $this->Paginator->prev('« Anterior', null, null, array('class' => 'disabled'));
            echo $this->Paginator->numbers(array('separator' => ''));
            echo $this->Paginator->next('Próximo »', null, null, array('class' => 'disabled'));
        ?>
    </ul>
</div>
<script>
  function reabilitarUser(id) {
    $.ajax({
        type:"POST",
        url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'reabilitarUsuario'), true)."'";  ?>,
        dataType: 'json',
        data:"id="+id,
        async: true,
        success:function(dados){
            
            swal("Registro Atualizado com sucesso!", {
                icon: "success",
            });            

            //setTimeout(function(){ location.reload(); }, 2000);

        },
        error: function (request, status, error) {
            console.log('erro');
        }
    });
  }

  $("#btnLimpar").click(function() {
      window.location.href = 'https://centralintegradora.pibcuritiba.org.br/entradas/listaDesativadas';
  });


</script>