<?php
#coloca as células em uma unuca linha
foreach ($dadosCelulaApi as $dadosCel) {
    $arrayCel .=$dadosCel['CelulasGrupo']['name'].',';
}
if ($arrayCel) {
    $dadosCelulaApi = $arrayCel;
}
else{
    $dadosCelulaApi ="Dados de células não localizado";
}

//dados da pessoa na central
$horas_semanais = $dadosDiscipuladorCentral['Discipulador']['horas_semanais'];
$horas_usadas = $dadosDiscipuladorCentral['Discipulador']['total_vinculo'];
$qtdHrsDispo = $horas_semanais - $horas_usadas;

$status_disponibilidade = $dadosDiscipuladorCentral['Discipulador']['status_disponibilidade'];
if($status_disponibilidade == 1){
    $disponibilidade = "Disponível";
}else{
    $disponibilidade = "Não disponível";
}
$faixaEtaria = $dadosDiscipuladorCentral['Etaria']['descricao'];
$rede = $dadosDiscipuladorCentral['Rede']['nome'];
$liderRede = $nomeLiderRede['Clientepib']['nome'];
//dados da pessoa na pib
$valorSexo = $dadosDiscipuladorPib['Clientepib']['sexo'];
if($valorSexo == 0){
    $sexo = "Masculino";
}else{
    $sexo = "Feminino";
}
$telefone = $dadosDiscipuladorPib['Clientepib']['telefone_1'];

$dataNascimento = $dadosDiscipuladorPib['Clientepib']['data_nascimento'];
if($dataNascimento == "0000-00-00" || $dataNascimento == "1971-01-01"){
	$dataNascimentoBR = "Não informado";
	$idade = "Não informado";
}else{
	$dataNascimentoBR = substr($dataNascimento, 8,2).'/'.substr($dataNascimento, 5,2).'/'.substr($dataNascimento, 0,4);
	$date = new DateTime($dataNascimento );
	$idade = $date->diff( new DateTime( date('Y-m-d') ) );
	$idade = $idade->format( '%Y anos' );
}



$valorEstadoCivil = $dadosDiscipuladorPib['Clientepib']['tiporelacionamento_id'];
switch ($valorEstadoCivil){
    case 3:
        $estadoCivil = "Divorciado";
        break;
    case 2:
        $estadoCivil = "Solteiro";
        break;
    case 4:
        $estadoCivil = "Viúvo(a)";
        break;
    case 23:
        $estadoCivil = "Casado(a)";
        break;
    case 20:
        $estadoCivil = "Desquitado";
        break;
}

$dataCasamento      =  $dadosDiscipuladorPib['Clientepib']['dtCasamento'];
$dataCasamentoBR    = substr($dataCasamento, 8,2).'/'.substr($dataCasamento, 5,2).'/'.substr($dataCasamento, 0,4);
$naturalidade       = $dadosDiscipuladorPib['Clientepib']['sNaturalidade'];
$idOrigemJornada    = $dadosDiscipuladorPib['Clientepib']['idorigem'];
$nome               = $dadosDiscipuladorPib['Clientepib']['nome'];
$cpf                = $dadosDiscipuladorPib['Clientepib']['cpf'];
$email              = strtolower($dadosDiscipuladorPib['Clientepib']['email']);
$idPib              = $this->request->params['pass'][0];
$dataCadastro       = $dadosDiscipuladorPib['Clientepib']['dtCadastro'];
$dataCadastroBR     = substr($dataCadastro, 8,2).'/'.substr($dataCadastro, 5,2).'/'.substr($dataCadastro, 0,4);
$nomeFoto           = $dadosDiscipuladorPib['Clientepib']['foto'];
$idCentral          = $dadosDiscipuladorCentral['Discipulador']['id'];
$isLiberado         = $dadosDiscipuladorCentral['Discipulador']['is_liberado'];
$status             = $dadosDiscipuladorCentral['Discipulador']['status'];

if(empty($isLiberado)){
    $isLiberado = 0;
}
if(empty($status)){
	$status = 0;
}
/*echo "<pre>";
print_r($hrsDisponiveis);
exit;*/

?>

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#Discipulos" aria-expanded="false" aria-controls="collapseDiscipulos">
            Discipulos
        </button>
        <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#HistoricoAcompanhamentos" aria-expanded="false" aria-controls="collapseHistoricoAcompanhamentos">
            Histórico de mentorias
        </button>
        <div class="collapse" id="HistoricoAcompanhamentos">
            <div class="card card-body">
                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalAcompanhamento">Registrar mentoria</button>
                <h3>Histórico de mentorias</h3>
                <table class="table table-bordered">
                    <thead>
                        <th>Registrante</th>
                        <th>Data</th>
                        <th>Observação</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach($historicoAcompanhamento as $historico){
                        echo "<tr>";
                            echo "<td>".$historico['Usuario']['nome']."</td>";
						    echo "<td>".$historico[0]['datacadastro']."</td>";
						    echo "<td>".$historico['Historicoacompanhamento']['observacao']."</td>";
                        echo "</tr>";
                    }
                    
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        
        <div class="collapse" id="Discipulos">
            <div class="card card-body">

                <table class="table tabelaDiscipulosPerfilDisscipulador">
                    <thead>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>Email</th>
                        <th>Data de início</th>
                        <th>Dia/semana</th>
                        <th>Horário</th>
                        <th>Ações</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($discipulos as $discipulo){
                        $nivelDiscipulo = $discipulo['MembroVinculo']['nivel_discipulo']; 
                        echo "<tr>";
                            echo "<td>".$discipulo['Clientepib']['nome']."</td>";
                            echo "<td>".$discipulo['Clientepib']['telefone_1']."</td>";
                            echo "<td>".$discipulo['Clientepib']['email']."</td>";
                            echo "<td>30/06/2020</td>
                                    <td>Sábado</td>
                                    <td>17:00</td>
                                ";
                            echo "<td>";
                                if($nivelDiscipulo == 1){
                                    echo $this->Html->link("Acessar discipulo", array('controller' => 'discipulados', 'action' => 'perfil', $discipulo['Clientepib']['id']), array('class' => 'btn btn-info'));
                                }else if($nivelDiscipulo == 2){
                                    echo $this->Html->link("Acessar discipulo", array('controller' => 'discipuladosd2', 'action' => 'perfil', $discipulo['Clientepib']['id']), array('class' => 'btn btn-info'));
                                }else if($nivelDiscipulo == 3){
                                    echo $this->Html->link("Acessar discipulo", array('controller' => 'discipuladosd3', 'action' => 'perfil', $discipulo['Clientepib']['id']), array('class' => 'btn btn-info'));
                                }                                
                                echo '<button class="btn btn-danger" onclick="cancelarVinculo('.$discipulo['Clientepib']['id'].')">Cancelar vínculo</button>';
                            echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                    <!-- parte da info que mostra os discipulos ja liberados para entrevista --->
                <hr>
                <h3>Liberados para entrevista</h3>
                <table class="table tabelaDiscipulosPerfilDisscipulador">
                    <thead style="background-color: #e0e2e0;">
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>Email</th>
                        <th>Data de início</th>
                        <th>Dia/semana</th>
                        <th>Horário</th>
                        <th>Liberado</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($discipulosLiberados as $liberados){
                        echo "<tr>";
                            echo "<td>".$liberados['Clientepib']['nome']."</td>";
                            echo "<td>".$liberados['Clientepib']['telefone_1']."</td>";
                            echo "<td>".$liberados['Clientepib']['email']."</td>";
                            echo "<td>30/06/2020</td>
                                    <td>Sábado</td>
                                    <td>17:00</td>";                        
                        echo "<td>SIM</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>

                
            </div>
        </div>
    </div>

</div>



<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <?php
                    if($nomeFoto == ''){
                        echo '<i class="fas fa-user imagemUsuarioPerfil"></i>';
                    }else{
                        echo '<img src="https://sistemapib.pibcuritiba.org.br/sistemapib/img/membros/'.$nomeFoto.'" class="card-img" alt="...">';
                    }
                    ?>

                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $nome ; ?></h5>
                        <div class="form-group">
                            <input type="checkbox"id="isTreinamento" data-onstyle="success" data-offstyle="warning" <?php if($isLiberado == 1) echo "checked='checked'"; ?>>
                        </div>
                        <div class="form-group">
                            <input type="checkbox"id="isLiberado" data-onstyle="success" data-offstyle="danger" <?php if($status == 1) echo "checked='checked'"; ?>>
                        </div>
                        <table class="table naoQuebrarLinha">
                            <tr>
                                <td>Email: <?php echo $email; ?></td><td>Sexo: <?php echo $sexo; ?></td><td></td>
                            </tr>
                            <tr>
                                <td>Data de Nascimento: <?php echo $dataNascimentoBR; ?></td><td>Idade: <?php echo $idade; ?></td>
                            </tr>
                            <tr>
                                <td>Estado civil: <?php echo $estadoCivil; ?></td><td>Data de casamento: <?php echo $dataCasamentoBR; ?></td>
                            </tr>
                            <tr>
                                <td>Naturalidade: <?php echo $naturalidade; ?></td>
                                <td>Telefone: <?php echo $telefone; ?>
                                    <br/>
                                    Bairro: <?php echo $dadosDiscipuladorPib['Clientepib']['bairro'];; ?>
                                </td>
                            </tr>
                        </table>
                        <table class="table naoQuebrarLinha">
                            <tr>
                                <td>Rede: <?php echo $rede; ?></td><td>Líder: <?php echo $liderRede; ?></td><td></td>
                            </tr>
                            <tr>
                                <td>Horas semanais disponibilizadas: <?php echo $horas_semanais; ?> Hrs</td><td> Horas semanais utilizadas: <?php echo $horas_usadas; ?></td>
                            </tr>
                            <tr>                                
                                <td>Horas disponíveis: <?php echo $qtdHrsDispo;?> Hrs</td>
                                <td>Célula: <?php echo $dadosCelulaApi;?> </td>
                            </tr>
                            <tr> 
                                <?php
                                if($qtdHrsDispo == 0){
                                    echo $result = "<td><p class='paragrafoNoLimite'>Situação: No limite</p></td>";
                                }
                                elseif($qtdHrsDispo > 0){
                                    echo  $result = "<td><p class='paragrafoDisponivel'>Situação: Disponível</p></td>";
                                }
                                else{
                                    echo  $result = "<td><p class='paragrafoSobrecarregado'>Situação: Sobrecarregado</p></td>";
                                }
                                ?>
                            </tr>
                        </table>
                        <p class="card-text"><small class="text-muted">Cadastrado em <?php echo $dataCadastroBR; ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Registrar acompanhamento-->
<div class="modal fade" id="modalAcompanhamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
		<?php
			echo $this->Form->create('Historicoacompanhamento', array(
				'url' => array('controller' => 'discipuladores', 'action' => 'registraracompanhamento'),
				'class' => 'form-horizontal'
			));
			echo $this->Form->input("id_registrante", array("type" => "hidden", "value"=>$this->Session->read("Auth.User.id")));
			echo $this->Form->input("id_discipulador", array("type" => "hidden", "value"=>$idCentral));
		?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Registrar Acompanhamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Data de cadastro</label>
                            <input type="date" class="form-control" name="data[Historicoacompanhamento][data]" id="data" value="<?php echo date("Y-m-d"); ?>" readonly>
                            <small id="dataHelp" class="form-text text-muted">Campo preenchido automaticamente</small>
                        </div>
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control" name="data[Historicoacompanhamento][observacao]" id="observacao"></textarea>
                            <small id="dataHelp" class="form-text text-muted">Campo não é obrigatório</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success">Salvar</button>

            </div>
        </div>
		<?php echo $this->Form->end(); ?>
    </div>
</div>

<script>
    function cancelarVinculo(idPib){
        $.ajax({
            type:"POST",
            url:"<?php echo $this->Html->url(array("controller" => "discipulados","action"=> "cancelarvinculo"), true); ?>",
            data: 'idpib='+idPib,
            dataType: 'json',
            async: true,
            success:function(data){
                if(data == 1){
                    location.reload();
                }
            }
        });
    }

    $(function() {
        $('#isTreinamento').bootstrapToggle({
            on: 'Treinamento finalizado',
            off: 'Em treinamento',
            width: 200
        });
        $('#isLiberado').bootstrapToggle({
            on: 'Acesso liberado para o discipulador',
            off: 'Acesso bloqueado',
            width: 350
        });
        $('#isTreinamento').change(function() {
            var isdisponivel = 0;
            if($(this).prop('checked')){
                isdisponivel = 1;
            }else{
                isdisponivel = 0;
            }
            $.ajax({
                type:"POST",
                url:"<?php echo $this->Html->url(array("controller" => "discipuladores","action"=> "atualizarstatusdiscipulador"), true); ?>",
                data: "liberado="+isdisponivel+"&id="+<?php echo $idCentral; ?>,
                dataType: 'json',
                async: true,
                success:function(retorno){
                    if(retorno == 1){
                        console.log("sucesso");
                    }else{
                        console.log("erro");
                    }
                },error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        })
        $('#isLiberado').change(function() {
            var isliberado = 0;
            if($(this).prop('checked')){
                isliberado = 1;
            }else{
                isliberado = 0;
            }
            $.ajax({
                type:"POST",
                url:"<?php echo $this->Html->url(array("controller" => "discipuladores","action"=> "liberaracesso"), true); ?>",
                data: "liberado="+isliberado+"&id="+<?php echo $idCentral; ?>,
                dataType: 'json',
                async: true,
                success:function(retorno){
                    if(retorno == 1){
                        console.log("sucesso");
                    }else{
                        console.log("erro");
                    }
                },error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        })
    })
    
    
</script>