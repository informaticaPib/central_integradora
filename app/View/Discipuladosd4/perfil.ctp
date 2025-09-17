<?php
	$nomeDiscipulador = $dadosDiscipulador['Clientepib']['nome'];
	$idDiscipuladorPib = $dadosDiscipulador['Clientepib']['id'];
	//dados da pessoa
	$valorSexo = $dadosMembro['Clientepib']['sexo'];
	if($valorSexo == 0){
		$sexo = "Masculino";
	}else{
		$sexo = "Feminino";
	}
	$time = $dadosMembro['Clientepib']['time_ci'];
	$dataNascimento = $dadosMembro['Clientepib']['data_nascimento'];
	if($dataNascimento == "0000-00-00" || $dataNascimento == "1971-01-01"){
		$dataNascimentoBR = "Não informado";
		$idade = "Não informado";
	}else{
		$dataNascimentoBR = substr($dataNascimento, 8,2).'/'.substr($dataNascimento, 5,2).'/'.substr($dataNascimento, 0,4);
		$date = new DateTime($dataNascimento );
		$idade = $date->diff( new DateTime( date('Y-m-d') ) );
		$idade = $idade->format( '%Y anos' );
	}
	
	$valorEstadoCivil = $dadosMembro['Clientepib']['tiporelacionamento_id'];
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
		default:
			$estadoCivil = "Não informado";
			break;
	}
	
	$valorstatus = $dadosMembro['Clientepib']['status_id'];
	switch ($valorstatus){
		case 2:
			$status = "Frequentador";
			break;
		case 5:
			$status = "Membro";
			break;
		case 26:
			$status = "Ser Igreja";
			break;
		case 27:
			$status = "Batismo";
			break;
		case 29:
			$status = "Acompanhamento";
			break;
		default:
			$status = "D4";
			break;
	}
    
    

	$dataCasamento      =  $dadosMembro['Clientepib']['dtCasamento'];
	$dataCasamentoBR    = substr($dataCasamento, 8,2).'/'.substr($dataCasamento, 5,2).'/'.substr($dataCasamento, 0,4);
	$naturalidade       = $dadosMembro['Clientepib']['sNaturalidade'];
	$idOrigemJornada    = $dadosMembro['Clientepib']['idorigem'];
	$nome               = $dadosMembro['Clientepib']['nome'];
	$cpf                = $dadosMembro['Clientepib']['cpf'];
	$email              = $dadosMembro['Clientepib']['email'];
	$telefone_1         = $dadosMembro['Clientepib']['telefone_1'];
	$cidade             = $dadosMembro['Clientepib']['cidade'];
	if($cidade == '0'){
	    $cidade = "Não informado";
    }
	$bairro = $dadosMembro['Clientepib']['bairro'];
	if($bairro == '0'){
		$bairro = "Não informado";
	}
	$idDiscipulador = $dadosMembro['MembroVinculo']['discipulador_id'];
	$idPib = $this->request->params['pass'][0];
	$dataCadastro = $dadosMembro['Clientepib']['dtCadastro'];
	$dataCadastroBR = substr($dataCadastro, 8,2).'/'.substr($dataCadastro, 5,2).'/'.substr($dataCadastro, 0,4);
	$nomeFoto = $dadosMembro['Clientepib']['foto'];
	$statusDiscipulo = $dadosMembro['Clientepib']['status_id'];
	
	//Conta da % de liberação para entrevista, mínimo de 60%
	$totalFeitoIgreja = $totalPresencasEuvimIgreja;
	$totalAulasIgreja = 7;
	$totalIgreja = (($totalFeitoIgreja/$totalAulasIgreja) * 100);
	
	$totalFeitoDiscipulado = $totalPresencasEuvimDiscipulado;
	$totalAulasDiscipulado = 12;
	$totalDiscipulado = (($totalFeitoDiscipulado/$totalAulasDiscipulado) * 100);


?>
<div class="row">
    <div class="col-md-12">
		<?php
            echo '<button class="btn btn-warning" data-toggle="modal" data-target="#enviarintegracaoliberado" id="idBtnVoltar"><i class="fas fa-arrow-alt-circle-left"></i>  <b>VOLTAR PARA A LISTA</b></button>';
		?>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h2>Aulas realizadas</h2>
    </div>

    <div class="col-md-12">
        <ul class="nav nav-tabs nav-pills">
            <li class="nav-item">
                <a class="nav-link" id="jornada-tab" data-toggle="tab" href="#jornada" role="tab" aria-controls="jornada" aria-selected="false">Jornada</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="euvim-tab" data-toggle="tab" href="#euvim" role="tab" aria-controls="euvim" aria-selected="false">Eu vim - TOTAL (<span><?php echo $totalPresencasEuvim; ?></span>)</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="jornada" role="tabpanel" aria-labelledby="jornada-tab">
                <ul class="nav nav-tabs nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" id="jornadaresult-tab" data-toggle="tab" href="#jornadaresult" role="tab" aria-controls="jornadadiscipulado" aria-selected="false" onclick="buscarJornada(7)">Discipulado - total(<span id="totalDiscipulado"></span>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="jornadaresult-tab" data-toggle="tab" href="#jornadaresult" role="tab" aria-controls="jornadaresult" aria-selected="false" onclick="buscarJornada(13)">Ser igreja - total(<span id="totalIgreja"></span>)</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade" id="jornadaresult" role="tabpanel" aria-labelledby="jornadaresult-tab">
                        <div id='wait'>
                            <div class="alert alert-info" role="alert">
                                Está consulta pode demorar um pouco, por favor, aguarde.
                            </div>
							<?php echo $this->Html->image('ajax-loader_big.gif'); ?>
                        </div>
                        <div id='dvRespostas'></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="euvim" role="tabpanel" aria-labelledby="euvim-tab">
                <ul class="nav nav-tabs nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" id="euvim-tab-discipulado" data-toggle="tab" href="#euvimresult" role="tab" aria-selected="false" onclick="buscarEuVim(6)">Liderando uma célula saudável (<span id="totalEuVimLiderando"></span>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="euvim-tab-serigreja" data-toggle="tab" href="#euvimresult" role="tab" aria-selected="false" onclick="buscarEuVim(24)">Nós cremos (<span id="totalEuVimNosCremos"></span>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="euvim-tab-serigreja" data-toggle="tab" href="#euvimresult" role="tab" aria-selected="false" onclick="buscarEuVim(8)">Autoridade e submissão espiritual (<span id="totalEuVimAutoridade"></span>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="euvim-tab-serigreja" data-toggle="tab" href="#euvimresult" role="tab" aria-selected="false" onclick="buscarEuVim()">Cosmovisão cristã (<span id="totalEuVimEuCosmo"></span>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="euvim-tab-serigreja" data-toggle="tab" href="#euvimresult" role="tab" aria-selected="false" onclick="buscarEuVim()">Vida de oração (<span id="totalEuVimVidaOracao"></span>)</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade" id="euvimresult" role="tabpanel">
                        <div id='dvRespostasEuvim'></div>                        
                    </div>
                </div>                             
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-3">
        <h3 style="text-align:center;">Ações</h3>
        <?php
        if($idDiscipulador == 0){
            echo '<button class="btn btn-default btnAcaoPerfilDiscipulo" data-target="#vincularDiscipulador" data-toggle="modal">VINCULAR A UM DISCIPULADOR</button>';
        }
        if($idDiscipulador != 0){
            // echo '<button class="btn btn-info btnAcaoPerfilDiscipulo" data-toggle="modal" data-target="#registrarAula">REGISTRAR NOVA FREQUÊNCIA</button>';
            echo '<button class="btn btn-danger btnAcaoPerfilDiscipulo" onclick="cancelarVinculo('.$idPib.')">CANCELAR VÍNCULO</button>';
        }        
        echo '<button class="btn btn-warning btnAcaoPerfilDiscipulo" data-toggle="modal" data-target="#definirtime">DEFINIR TIME</button>';
        echo '<button class="btn btn-info btnAcaoPerfilDiscipulo" data-toggle="modal" data-target="#acompanhamentodiscipulo">REGISTRAR ACOMPANHAMENTO</button>';
        // echo '<button class="btn btn-default btnAcaoPerfilDiscipulo" data-toggle="modal" data-target="#modalFinalizarDiscipulado">FINALIZAR DISCIPULADO DIRETO</button>';
        ?>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="row no-gutters">                
                <div class="card-body">
                    <h5 class="card-title"><?php echo $nome; ?></h5>
                    <?php
                        if($nomeDiscipulador != ''){
                            echo "<hr><h3>Discipulador: ".$nomeDiscipulador."</h3>";
                            echo $this->Html->link(
                                'Acessar perfil do discipulador',
                                array('controller' => 'discipuladores', 'action' => 'perfil', $idDiscipuladorPib),
                                array('class' => 'btn btn-default')
                            );
                        }
                    ?>
                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#Historicoacompanhamentos" aria-expanded="false" aria-controls="collapseHistoricoacompanhamentos">
                        Histórico de acompanhamentos
                    </button>
                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#statusdiscipulado" aria-expanded="false" aria-controls="collapsestatusdiscipulado">
                        Status Discipulado
                    </button>
                    <div class="collapse" id="Historicoacompanhamentos">
                        <div class="card card-body">
                            <table class="table table-bordered">
                                <thead>
                                <th>Registrante</th>
                                <th>Data</th>
                                <th>Observação</th>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($historicoAcompanhamentoDiscipulo as $historico){
                                        echo "<tr>";
                                        echo "<td>".$historico['Usuario']['nome']."</td>";
                                        echo "<td>".$historico[0]['datacadastro']."</td>";
                                        echo "<td>".$historico['Historicoacompanhamentodiscipulo']['observacao']."</td>";
                                        echo "</tr>";
                                    }
                            
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="collapse" id="statusdiscipulado">
                        <br><br>
                        <iframe src="https://sistemapib.pibcuritiba.org.br/dados/vidas/?id='<?php echo $idPib; ?>'" title="Status Discipulado" width="100%" height="500" frameBorder="0"></iframe>
                    </div>
                    <table class="table naoQuebrarLinha">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <b>Status: <?php echo $status; ?></b>
                            </td>
                        </tr>
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
                            <td>Naturalidade: <?php echo $naturalidade; ?></td><td>Telefone: <?php echo $telefone_1; ?></td>
                        </tr>
                        <tr>
                            <td>Cidade: <?php echo $cidade; ?></td><td>Bairro: <?php echo $bairro; ?></td>
                        </tr>
                        <tr>
                            <td>Célula: <?php echo $dadosCelulaApi; ?></td>
                        </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td>
                                <p class="card-text"><small class="text-muted">Cadastrado em <?php echo $dataCadastroBR; ?></small></p>
                            </td>
                            <td>
                                <p class="card-text"><small class="text-muted">CEP:<?php echo $dadosMembro['Clientepib']['cep']; ?></small></p>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    

                </div>
                
            </div>
        </div>
    </div>
</div>



<!-- Modal ADD-->
<div class="modal fade" id="registrarAula" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Registrar aula</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><b>Este registro será feito dentro do sistema Eu Vim</b></p>
				<?php
					echo $this->Form->create('Euvim', array(
						'url' => array('controller' => 'discipuladosd4', 'action' => 'addfrequencia'),
						'class' => 'form-horizontal'
					));
					echo $this->Form->input("id_membro", array("type" => "hidden", "value"=>$idPib));
					echo $this->Form->input("nome_membro", array("type" => "hidden", "value"=>$nome));
					echo $this->Form->input("cpf_membro", array("type" => "hidden", "value"=>$cpf));
					echo $this->Form->input("email_membro", array("type" => "hidden", "value"=>$email));
				?>
                <div class="form-group">
                    <label for="">Data realizada*</label>
                    <input type="date" class="form-control" name="data[Euvim][data_frequencia]" required="required">
                    <small id="dataHelp" class="form-text text-muted">Data que foi realizada a aula</small>
                </div>
                <div class="form-group">
                    <label for="">Módulo*</label>
                    <select class="form-control" required="required" name="data[Euvim][id_turma]" onchange="buscarAulas(this.value)">
                        <option value="">Selecione uma opção</option>
                        <?php 
                        if($totalLiderandoCelula < 8){
                            echo '<option value="6">LIDERANDO UMA CÉLULA SAUDÁVEL</option>';
                        }
                        ?>
                        <option value="24">NÓS CREMOS</option>
                        <option value="8">AUTORIDADE ESPIRITUAL E SUBMISSÃO</option>
                        <option value="">COSMOVISÃO CRISTÃ</option>
                        <option value="">VIDA DE ORAÇÃO</option>
                    </select>
                    <small id="moduloHelp" class="form-text text-muted">qual módulo pertence essa chamada</small>
                </div>
                <div class="form-group">
                    <label for="">Aula*</label>
                    <select class="form-control" required="required" name="data[Euvim][id_aula]" id="aulas">
                        <option value="">Selecione uma opção</option>
                    </select>
                    <small id="moduloHelp" class="form-text text-muted">qual aula pertence essa chamada</small>
                </div>
                <div>
                    <label>Observações</label>
					<?php echo $this->Form->input('observacao', array('label' => false, 'class' => 'form-control', 'type' => 'textarea')); ?>
                    <small id="observacaoHelp" class="form-text text-muted">Campo não obrigatório, preencher apenas se for necessário</small>
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

<!-- Modal DEFINIR TIME -->
<div class="modal fade" id="definirtime" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">DEFINIR TIME</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><b>Aqui você vai definir um time para o discípulo</b></p>
				<?php
					echo $this->Form->create('discipulado', array(
						'url' => array('controller' => 'discipuladosd4', 'action' => 'definirtime'),
						'class' => 'form-horizontal'
					));
					echo $this->Form->input("id_membro", array("type" => "hidden", "value"=>$idPib));
					echo $this->Form->input("time_ci", array("empty" => "Selecione uma opção", "options" => $listaTimes,  "default"=>$time, 'class' => 'form-control', 'label' => 'Time'));
				?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
				<?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal REGISTRAR ACOMPANHAMENTO DISCIPULO -->
<div class="modal fade" id="acompanhamentodiscipulo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
		<?php
			echo $this->Form->create('Historicoacompanhamentodiscipulo', array(
				'url' => array('controller' => 'discipuladosd4', 'action' => 'registraracompanhamento'),
				'class' => 'form-horizontal'
			));
			echo $this->Form->input("id_registrante", array("type" => "hidden", "value"=>$this->Session->read("Auth.User.id")));
			echo $this->Form->input("id_discipulo", array("type" => "hidden", "value"=>$idPib));
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
                            <input type="date" class="form-control" name="data[Historicoacompanhamentodiscipulo][data]" id="data" value="<?php echo date("Y-m-d"); ?>" readonly>
                            <small id="dataHelp" class="form-text text-muted">Campo preenchido automaticamente</small>
                        </div>
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control" name="data[Historicoacompanhamentodiscipulo][observacao]" id="observacao"></textarea>
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

<!-- Modal FINALIZAR DISCIPULADO -->
<div class="modal fade" id="modalFinalizarDiscipulado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Finalizar Discipulado IV</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">            
				<?php
					echo $this->Form->create('discipulado', array(
						'url' => array('controller' => 'discipuladosd4', 'action' => 'enviard4'),
						'class' => 'form-horizontal'
					));
					echo $this->Form->input("id_membro", array("type" => "hidden", "value"=>$idPib));
					echo $this->Form->input("nome_membro", array("type" => "hidden", "value"=>$nome));
					echo $this->Form->input("cpf_membro", array("type" => "hidden", "value"=>$cpf));
					echo $this->Form->input("email_membro", array("type" => "hidden", "value"=>$email));
					
				?>
                <div>
                    <label>Observações</label>
					<?php echo $this->Form->input('observacao', array('label' => false, 'class' => 'form-control', 'type' => 'textarea', 'required' => 'required')); ?>
                    <small id="observacaoHelp" class="form-text text-muted">Caso deseje deixar alguma informação, digite neste campo.</small>
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
 
<!-- Modal VINCULAR DISCIPULADOR-->
<div class="modal fade" id="vincularDiscipulador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
		<?php
			echo $this->Form->create('Discipulador', array(
				'url' => array('controller' => 'discipuladosd4', 'action' => 'vinculardiscipulador'),
				'class' => 'form-horizontal'
			));
		?>
        <input type='hidden' value='<?php echo $idPib; ?>' name='idDiscipulado'>
        <input type='hidden' value='<?php echo $email; ?>' name='emailDiscipulo'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Vincular discipulador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Sexo</th>
                            <th>Data de nascimento</th>
                            <th>Idade</th>
                            <th>Estado civil</th>
                            <th>Data de casamento</th>
                            <th>Naturalidade</th>

                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo $nome; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $sexo; ?></td>
                                <td><?php echo $dataNascimentoBR; ?></td>
                                <td><?php echo $idade; ?></td>
                                <td><?php echo $estadoCivil; ?></td>
                                <td><?php echo $dataCasamentoBR; ?></td>
                                <td><?php echo $naturalidade; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
                                <th>Disponível</th>
                                <th>Horas usadas</th>
                                <th>Tot.</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Contato</th>
                                <th>Sexo</th>
                                <th>Idade</th>
                                <th>Disp. Casal</th>
                                <th>Estado civil</th>
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
									
									echo "<td><input type='radio' value='".$lista['Membro']['id']."-".$lista['Discipuladorrede']['rede_contagem']."-".$lista['Discipuladorrede']['rede_id']."' name='data[Discipulador][id_membro]' id='DiscipuladorIdDiscipuladorCentral'> </td>";
									if($qtdHrsDispo < 0){
										echo '<td><i class="fas fa-circle vermelho"></i></td>';
									}else{
										if($qtdHrsDispo == 0 ){
											echo '<td><i class="fas fa-circle laranja"></i></td>';
										}else{
											echo '<td><i class="fas fa-circle verde"></i></td>';
										}
									}
									echo '<td>'.$lista['Rede']['nome'] .'</td>';
									#--------------------------- calculo de horas/ qtd discipulos ---------------------------
									$qtdHorasDisponivies = $lista['Discipulador']['horas_semanais'] - $lista['Discipulador']['total_vinculo'] ;
									echo "<td>".$qtdHorasDisponivies."H</td>";
									echo "<td>".$lista['Discipulador']['total_vinculo']."H</td>";
									echo "<td>".$lista['Discipulador']['horas_semanais']."H</td>";
									
									
									
									echo "<td>".$lista['Membro']['nome']."</td>";
									echo "<td>".$lista['Membro']['email']."</td>";
									echo "<td>".$lista['Membro']['telefone_1']."</td>";
									echo "<td>".$sexoDiscipulador."</td>";
									echo "<td>".$idade."</td>";
									if($lista['Discipulador']['disponib_casal']== 1){
										echo '<td> Disponível </td>';
									}else{
										echo '<td> Não Disponível</td>';
									}
									echo '<td>'.$lista['TipoRelacionamento']['descricao'].'</td>';
									//echo "<td>".$lista['Discipulador']['horas_semanais']." horas</td>";
									
									
									
									
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
                <button type="submit" class="btn btn-success">Salvar</button>

            </div>
        </div>
		<?php echo $this->Form->end(); ?>
    </div>
</div>


<script type="text/javascript">

    function anularEnter(e) {
        //See notes about 'which' and 'key'
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    }

    $(document).ready( function () {
        var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
        var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
        var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
        if(isSafari){
            $('#tabelaDiscipuladores').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
                }
            });

            $('.no-result').remove();
            $('.divPesquisarDiscipulador').hide();
            $('.counter').hide();
        }else{
            $(".pesquisarDiscipulador").keyup(function () {
                var searchTerm = $(".pesquisarDiscipulador").val();
                var listItem = $('.tabelaDiscipuladores tbody').children('tr');
                var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

                $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                    }
                });

                $(".tabelaDiscipuladores tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
                    $(this).attr('visible','false');
                });

                $(".tabelaDiscipuladores tbody tr:containsi('" + searchSplit + "')").each(function(e){
                    $(this).attr('visible','true');
                });

                var jobCount = $('.tabelaDiscipuladores tbody tr[visible="true"]').length;
                $('.counter').text(jobCount + ' item');

                if(jobCount == '0') {$('.no-result').show();}
                else {$('.no-result').hide();}
            });
        }
    });
    function buscarJornada(idModulo){
        $.ajax({
            type:"get",
            url:"https://ajornada.com.br/novoadm/RespExercicios/respostas_usuario_modulo/<?php echo $idOrigemJornada; ?>/"+idModulo,
            dataType: 'json',
            async: true,
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            success:function(data){
                $('#dvRespostas').html(data);
                //var totalRespondido = $("#totalrespondido").val();
                var totalRespondido = document.getElementById("totalrespondido").value;
                if(idModulo == 7){
                    $("#totalDiscipulado").html(totalRespondido);
                }else{
                    $("#totalIgreja").html(totalRespondido);
                }

            }
        });
    }

    function cancelarVinculo(idPib){
        swal({
            title: "Você tem certeza?",
            text: "Cancelando o vínculo ele estará disponível para outro discipulador.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type:"POST",
                    url:"<?php echo $this->Html->url(array("controller" => "discipuladosd4","action"=> "cancelarvinculo"), true); ?>",
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
        });
        
        
        
    }

    function buscarAulas(modulo){
        $.ajax({
            type:"POST",
            url:"<?php echo $this->Html->url(array("controller" => "discipuladosd4","action"=> "buscaaulas"), true); ?>",
            data: 'modulo='+modulo,
            dataType: 'json',
            async: true,
            success:function(data){
                $("#aulas").empty();
                $("#aulas").append('<option value="">Selecione uma opção</option>')
                $.each(data, function(key, aula){
                    //Use the Option() constructor to create a new HTMLOptionElement.
                    var option = new Option(aula, key);
                    //Convert the HTMLOptionElement into a JQuery object that can be used with the append method.
                    $(option).html(aula);
                    //Append the option to our Select element.
                    $("#aulas").append(option);
                });
            }
        });
    }

    $('#discipuladoStatusId').on('change', function() {
        var status = this.value;
        if(status == 2){
            $("#discipuladoObservacao").show();
            $("#labelObservacaoStatus").show();
            $("#discipuladoObservacao").prop('required',true);
        }else{
            $("#discipuladoObservacao").hide();
            $("#labelObservacaoStatus").hide();
            $("#discipuladoObservacao").prop('required',false);
        }
    });
    

    $("#idBtnVoltar").click(function() {
        history.go(-1);

    });

    function enviarDadosUsuario(id) {
        
        var retorno = "";
        /*$.ajax({
            method: 'POST',
            url: 'https://sistemapib.pibcuritiba.org.br/sistemapib/vidas/edit_reenviarcadastrocentralmembro',
            data: 'id='+id+'&externo=1&tipo=1',
            dataType: 'json',
            async: true,
            success: function(retorno){
                $("#enviarintegracaoliberado2").hide();
                location.reload();
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });*/
        //-------------- INI parte do cod responsavel por enviar o email de acesso a central de membro para o usuario    
        //var id = 35313;
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'reenvioAcessoPessoas'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+id,
                async: true,
                success:function(dados){
                    if(dados ==3){
                        //esconde os botoes para impedir a pessoa de clicar para enviar sem que esteja valido
                        $("#idDivModalEnviarEntrevista").hide();
                        console.log('enviou o email');
                    }
                    else{
                        console.log('nao enviou o email');
                    }
                },
                error: function (request, status, error) {
                    console.log(error);
                }
            });
    
    //-------------- FIM do cod responsavel por enviar o email de acesso a central de membro para o usuario
        swal("Email enviado!", "Aguarde o retorno do discípulo", "success");
        setTimeout(function() {
            location.reload();
        }, 1000);

    }


  /* $("#idEnviaLiberacao").click(function () {
        var id  = $("#discipuladoIdMembro").val();
        //var id  = 35313;
		//alert(id);
        var obs = $("#discipuladoObservacao").val(); 
        var idDiscipulador = '0';
        var dataAgendamento = '0000-00-00';
        var horaAgendamento = '00:00';
        var retorno = "";
        $.ajax({
            method: 'POST',
            url: 'https://integracao.pibcuritiba.org.br/atendimentos/agendar',
            data: 'id='+id+'&externo=1&tipo=0&obs='+obs+'&idDiscipulador='+idDiscipulador+'&dataAgendamento='+dataAgendamento+'&hora='+horaAgendamento,
            dataType: 'json',
            async: true,
            success: function(retorno){
                console.log(retorno);//alert(id);
            },
            error: function (request, status, error) {
                console.log(request.responseText);//alert(request.responseText);
            }
        });
       /*  swal("Email enviado!", "Aguarde o retorno do discípulo", "success");
        setTimeout(function() {
            location.reload();
        }, 3000);
    });*/
    function buscarEuVim(idModulo){
        $.ajax({
            type:"POST",            
            url:"<?php echo $this->Html->url(array("controller" => "discipuladosd4","action"=> "euvim"), true); ?>",
            data: 'idModulo='+idModulo+'&idPessoa='+<?php echo $idPib;?>,
            dataType: 'json',
            async: true,
            success:function(data){
                $('#dvRespostasEuvim').html(data);                
                //var totalRespondido = $("#totalrespondido").val();
                var totalRespondido = document.getElementById("totalPresencaEuVim").value;
                if(idModulo == 6){
                    $("#totalEuVimLiderando").html(totalRespondido);
                }else if(idModulo == 24){
                    $("#totalEuVimNosCremos").html(totalRespondido);
                }else if(idModulo == 8){
                    $("#totalEuVimAutoridade").html(totalRespondido);
                }else if(idModulo == 0){
                    $("#totalEuVimEuCosmo").html(totalRespondido);
                }else{
                    $("#totalEuVimVidaOracao").html(totalRespondido);
                }
            }
        });
    }
</script>
