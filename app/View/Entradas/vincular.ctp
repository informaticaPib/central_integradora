<!-- Modal VINCULAR DISCIPULADOR-->
<div class="modal fade" id="vincularDiscipulador" tabindex="-1" role="dialog" aria-labelledby="vincularDiscipulador" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
		<?php
			

            echo $this->Form->create('Entrada', array(
                'url' => array('controller' => 'entradas', 'action' => 'vincularDiscipuladores'),
                'class' => 'form-horizontal',
                'id' => 'formVincularDiscipulador',
                'type' => 'POST'
            ));

            echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'idVincularId'));
            echo $this->Form->input('nome', array('type'=>'hidden', 'id'=>'idVincularNome'));
            echo $this->Form->input('cpf', array('type'=>'hidden', 'id'=>'idVincularCpf'));
            echo $this->Form->input('dt_nascimento', array('type'=>'hidden', 'id'=>'idVincularDtNasci'));
            echo $this->Form->input('celular', array('type'=>'hidden', 'id'=>'idVincularCelular'));
            echo $this->Form->input('email', array('type'=>'hidden', 'id'=>'idVincularEmail'));
            echo $this->Form->input('cep', array('type'=>'hidden', 'id'=>'idVincularCep'));
            echo $this->Form->input('tipo', array('type'=>'hidden', 'id'=>'idVincularTipo'));
            echo $this->Form->input('trilha', array('type'=>'hidden', 'id'=>'idTrilha'));
            echo $this->Form->input('sexo', array('type'=>'hidden', 'id'=>'idVincularSexo'));
            echo $this->Form->input('idDiscipuladorSelecionado', array('type'=>'hidden', 'id'=>'idDiscipuladorSelecionado2'));
            echo $this->Form->input('origem', array('type'=>'hidden', 'id'=>'idOrigem'));
		?>

        <?php //$idPib = 24429; ?>
        <input type='hidden' value='<?php echo $idPib; ?>' name='idDiscipulado'><!--ver a usabilidade desse id-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Vincular discipulador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id='retornoDetalherVincular'><!--local onde recebe a parte superior da lista de entradas--></div>
                </div>
                <hr>
                <h3>Lista de discipuladores</h3>
                <div class='row'>
                    <div class="col-sm-2"><i class="fas fa-circle vermelho"></i> Sobrecarregado</div>
                    <div class="col-sm-2"><i class="fas fa-circle verde"></i> Disponível</div>
                    <div class="col-sm-2"><i class="fas fa-circle laranja"></i> No limite</div>
                    <div class="col-sm-2"><div class="legendaTreinamento">Em treinamento</div></div>
                    <div class="col-sm-2"><div class="legendaIndisponivel">Indisponível</div></div>
                </div>
                <div class="row">
                    <div class="col-md-12 divTabelaVincularDiscipulador">
                        <div class="form-group pull-right divPesquisarDiscipulador">
                            <input type="text" class="pesquisarDiscipulador form-control" placeholder="Pesquisar discipulador" onkeypress="return anularEnter(event)">
                        </div>
                        <span class="counter pull-right"></span>
                        <table class="table tabelaDiscipuladores" id="tabelaDiscipuladores">
                            <thead>
                            <tr>
                                <th>Selecionar</th>
                                <th>Status</th>
                                <th>Rede</th>
                                <th>Disp.</th>
                                <th>Usad.</th>
                                <th>Tot.</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Contato</th>
                                <th>Sexo</th>
                                <th>Idade</th>
                                <th>Disp.<br/>Casal</th>
                                <!--<th>Horas disponíveis</th>-->
                            </tr>
                            <tr class="warning no-result">
                                <th colspan="4"><i class="fa fa-warning"></i> Nenhum encontrado</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php

								foreach ($listaTodosDadosDiscipuladores as $lista) {
									$qtdHrsDispo = $lista['Discipulador']['horas_semanais'] - $lista['Discipulador']['total_vinculo'];
									if($lista['Membro']['sexo'] == 1){
										$sexoDiscipulador = "Feminino";
									}else{
										$sexoDiscipulador = "Masculino";
									}
									$date = new DateTime($lista['Membro']['data_nascimento'] );
									$idade = $date->diff( new DateTime( date('Y-m-d') ) );
									$idade = $idade->format( '%Y anos' );
									
									if($lista['Discipulador']['status_disponibilidade'] == 0){
										echo '<tr class="linhaTabelaVermalha">';
									}else{
										if($lista['Discipulador']['is_liberado'] == 0){
											echo '<tr class="linhaTabelaAmarelo">';
										}else{
											echo '<tr>';
										}
									}
									
                                    echo "<td>
                                            <input type='radio' value='".$lista['Discipulador']['id_membro']."' name='data[Discipulador][id_membro]' id='DiscipuladorIdDiscipuladorCentral' onclick='eventoR(".$lista['Discipulador']['id_membro'].")';>
                                            <input type='hidden' value='".$lista['Rede']['id']."' name='data[Discipulador][id_rede]' id='idRedeDiscipulador'>
                                        </td>";
                                    
									if($qtdHrsDispo < 0){
										echo '<td><i class="fas fa-circle vermelho"></i></td>';
									}else{
										if($qtdHrsDispo == 0 ){
											echo '<td><i class="fas fa-circle laranja"></i></td>';
										}else{
											echo '<td><i class="fas fa-circle verde"></i></td>';
										}
									}
									#--------------------------- calculo de horas/ qtd discipulos ---------------------------
									
									$qtdHorasDisponivies = $lista['Discipulador']['horas_semanais'] - $lista['Discipulador']['total_vinculo'] ;
									echo "<td>".$lista['Rede']['nome']."</td>";
									echo "<td>".$qtdHorasDisponivies."H</td>";
									echo "<td>".$lista['Discipulador']['total_vinculo']."H</td>";
									echo "<td>".$lista['Discipulador']['horas_semanais']."H</td>";
									
									echo "<td>".$lista['Membro']['nome']."</td>";
									echo "<td>".$lista['Discipulador']['cpf']."</td>";
									echo "<td>".$lista['Membro']['email']."</td>";
									echo "<td>".$lista['Membro']['telefone_1']."</td>";
									echo "<td>".$sexoDiscipulador."</td>";
                                    echo "<td>".$idade."</td>";
                                    if($lista['Discipulador']['disponib_casal']==1){
                                        echo "<td>Disponível</td>";
                                    }else{
                                        echo "<td>Não <br/> Disponível</td>";
                                    }
									echo "</tr>";
								}
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id='idBtnVincularDiscipulador'>Salvar</button>

            </div>
        </div>
		<?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
function eventoR(id) {
    $("#idDiscipuladorSelecionado2").val(id);
}
</script>