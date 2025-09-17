<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>


<div class="modal fade" id="modalReabilitar" tabindex="-1" role="dialog" aria-labelledby="modalReabilitar" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reabilitar entradas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            
            <div class="modal-body" id='retornoReabilitar'>

                <?php
                    echo "<table id='example' class='display' style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nome</th>
                                        <th>Cpf</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>";
                    foreach ($infosReabilitar as $dados) {
                                    echo "<tr>
                                                <td>".$dados['Entrada']['id']."</td>
                                                <td>".$dados['Entrada']['nome']."</td>
                                                <td>".$dados['Entrada']['cpf']."</td>
                                                <td>".$dados['Entrada']['celular']."</td>
                                                <td>".$dados['Entrada']['email']."</td>
                                                <td><i class='fas fa-sync-alt' alt='reabilitar' title='reabilitar' onclick='reabilitarUser(".$dados['Entrada']['id'].");'></i></td>
                                            </tr>";
                    }
                    echo "</tbody>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nome</th>
                                        <th>Cpf</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>Ação</th>
                                    </tr>
                                </tfoot>
                            </table>";
                    ?>

            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>

    </div>
  </div>
</div>


<script>
$(document).ready(function() {
    $('#example').DataTable();

} );

$('#example').DataTable( {
    responsive: true
    
} );





function reabilitarUser(id) {

            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'reabilitarUsuario'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+id,
                async: true,
                success:function(dados){
                    console.log('teste');
                    //console.log(dados['result']['Entrada']['nome']);

                    swal("Registro Atualizado com sucesso!", {
                        icon: "success",
                    });            

                    setTimeout(function(){ location.reload(); }, 2000);

                },
                error: function (request, status, error) {
                    console.log('erro');
                }
            });
 
}

</script>