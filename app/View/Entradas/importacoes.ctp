<!-- ###################################### INICIO MODAL DE DETALHES ######################################-->
<div class="modal fade importar" tabindex="-1" role="dialog" aria-labelledby="modalImportar" aria-hidden="true">
    <div class="modal-dialog modal-lg">    
        <div class="modal-content">
            <!--header modal-->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Importar Planilha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <!--corpo modal-->
            <div class="modal-body">

                <div class="row">
                    <div class="col-md">
                            Download do modelo de importação <a href="/app/webroot/files/modelo_1.csv" download><b><u><i class="fas fa-cloud-download-alt"></i> aqui! </u></b></a>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-md">
                            Orientrações para o preenchimento do excel
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md">
                        <b>1</b> - A 1° linha contem o cabeçalho dos campos, essa linha <b>NÃO</b> deve ser excluída.<br/>
                        <b>2</b> - A ordem dos campos <b>NÃO</b> pode ser alterada.<br/>
                        <b>3</b> - O campo data_nascimento deve conter o formato  dd/mm/AAAA (dia/mês/Ano)<br/>
                        <b>4</b> - O campo tipo pode conter as informações :
                        <ul>
                            <li>discipulado</li>
                            <li>ser_igreja</li>
                            <li>reconciliar</li>
                            <li>andamento</li>
                            <li>celula</li>
                            <li>conhecer_jesus</li>
                        </ul>
                        <b>5</b> - O campo trilha pode conter as informações :  
                            <ul>
                                <li>0 => 'Discipulado (não sou batizado)',</li>
                                <li>1 => 'Ser Igreja (já sou batizado)'</li>
                            </ul>
                        <b>6</b> - O campo conhecer pode conter as informações :
                            <ul>
                                <li>0 => 'Decidi crer em Jesus e quero dar os primeiros passos',</li>
                                <li>1 => 'Venho de outra igreja evangélica e quero fazer parte PIB Curitiba',</li>
                                <li>2 =>'Voltar para Jesus',</li>
                                <li>3 =>'Quero participar de uma célula',</li>
                                <li>4 =>'Quero conhecer mais sobre Jesus'</li>
                            </ul>
                        <b>7</b> - O campo SEXO :
                            <ul>
                                <li>M => 'Masculino',</li>
                                <li>F => 'Feminino',</li>
                            </ul>
                        <br/>

                    </div>                    
                </div>

                <br/>
                <?php
                    echo $this->Form->create('Entrada', array(
                        'url' => array('controller' => 'entradas', 'action' => 'importaExcel'),
                        'role' => 'form',
                        'enctype'=>'multipart/form-data',
                        'id' => 'formImportaExcel'
                    ));

                    echo "<div class='row'>";
                        echo "<div class='col-md-12'>"; 
                            echo "<div class='form-group'>
                                    <label for=''>Carregar o arquivo modelo em Excel</label>".$this->Form->input('arquivoExcel',array('type'=>'file','class'=>'form-control', 'label'=>false, 'required'=>'required'))."</div>"; 
                        echo "</div>";                        
                    echo "</div>";

                ?>
            </div>
            <!--acao botoes-->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>

        </div>
    </div>
</div>
<!-- ###################################### FINAL MODAL DE DETALHES ######################################-->