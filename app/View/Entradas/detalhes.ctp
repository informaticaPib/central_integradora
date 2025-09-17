<!-- ###################################### INICIO MODAL DE DETALHES ######################################-->
<div class="modal fade detalhes" tabindex="-1" role="dialog" aria-labelledby="modaDetalhes" aria-hidden="true" id="idDetalhes">
    <div class="modal-dialog modal-lg">    
        <div class="modal-content">
            <!--header modal-->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detalhes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <!--corpo modal-->
            <div class="modal-body">

                <div id='idMsgTipo' style="text-align: center;"><!--recebe o retorno das mensagens--></div>
                <br/>

                <div id='idCarregaEntradas'><!--recebe o retorno da tbl Entradas quando o usuario for == membro --></div>
                <hr>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Nome</th>
                            <th>Dt.Nasci</th>
                            <th>CPF</th>
                            <th style="text-align: center;">Ação</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;" id='returnTbl'>
                        <!-- recebe aqui o retorno da controller para exibir as informações-->
                    </tbody>
                </table>
            </div>
            
            <!--acao botoes
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>-->

        </div>
    </div>
</div>
<!-- ###################################### FINAL  MODAL DE DETALHES ######################################-->