<div class="row">
    <div class="col-md-12">
        <h2>Discipuladores</h2>
    </div>
</div>
<div class="opt-interna">
    <button class="btn btn-default" data-toggle="modal" data-target="#addDiscipulador"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> Cadastrar discipulador</button>
</div>

<div class="row">
	<?php
	echo $this->Form->create(false, array(
			'url' => array('controller' => 'discipuladores', 'action' => 'index'),
			'type' => 'get',
			'class' => 'form-inline'
	));
	?>
	<div class="form-group margin10px">
		<label class="marginRight5px">Nome</label>
		<input type="text" name="nome" class="form-control" value="<?php echo $this->params['url']['nome'] ;?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">E-mail</label>
		<input type="text" name="email" class="form-control" value="<?php echo $this->params['url']['email'] ;?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Contato</label>
		<input type="text" name="contato" class="form-control" value="<?php echo $this->params['url']['contato'] ;?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Data de cadastro</label>
		<input type="date" name="dtcadastro" class="form-control" value="<?php echo $this->params['url']['dtcadastro'] ;?>">
	</div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Rede</label>
		<?php echo $this->Form->input('id_rede', array('value'=>$this->params['url']['id_rede'], 'empty' => 'Selecione uma opção', 'options' => $listaRedes, 'class' => 'form-control', 'label' => false)); ?>
	</div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Status</label>
		<?php echo $this->Form->input('status_disponibilidade', array('value'=>$this->params['url']['status_disponibilidade'], 'empty' => 'Selecione uma opção', 'options' => array("0" => "Não disponível", "1" => "Disponível", "2" => "Em treinamento"), 'class' => 'form-control', 'label' => false)); ?>
    </div>
	<div class="form-group margin10px">
		<label class="marginRight5px">Discipulador</label>
		<?php echo $this->Form->input('discipulador', array('value'=>$this->params['url']['discipulador'], 'empty' => 'Selecione uma opção', 'options' => $listaDiscipuladoresFiltro, 'class' => 'form-control', 'label' => false)); ?>
	</div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Estado civil</label>
		<?php echo $this->Form->input('estadocivil', array('value'=>$this->params['url']['estadocivil'], 'empty' => 'Selecione uma opção', 'options' => $listaEstadoCivil   , 'class' => 'form-control', 'label' => false)); ?>
    </div>
    <div class="form-group margin10px">
        <label class="marginRight5px">Disponível casal</label>
		<?php echo $this->Form->input('dispcasal', array('value'=>$this->params['url']['dispcasal'], 'empty' => 'Selecione uma opção', 'options' => array("0" => "Não", "1" => "Sim"), 'class' => 'form-control', 'label' => false)); ?>
    </div>
    <div class="form-group margin10px">        
        <label class="marginRight5px">Sexo</label>
		<?php echo $this->Form->input('sexo',
			array(
				'empty' => 'Selecione uma opção',
				'options' => array(0=>'Masculino',1=>'Feminino'),
				'value' => $_GET['sexo'],
				'class' => 'form-control',				
				'label' => false));
		?>
    </div>
	<div class="form-group margin10px">
        <label class="marginRight5px">Horas Usadas</label>
		<?php echo $this->Form->input('total_vinculo', array('value'=>$this->params['url']['total_vinculo'], 'empty' => 'Selecione uma opção', 'options' => array("0" => "0 Horas", "1" => "1 Hora ou mais"), 'class' => 'form-control', 'label' => false)); ?>
    </div>
    <div class="form-group margin10px">
		<div class="input-group">
			<label class="marginRight5px">Idade inicial e final</label>
			<input type="text" class="form-control" name="idadeinicial" value="<?php echo $this->params['url']['idadeinicial']; ?>">
			<div class="input-group-prepend">
				<span class="input-group-text" id=""> - </span>
			</div>
			<input type="text" class="form-control" name="idadefinal" value="<?php echo $this->params['url']['idadefinal']; ?>">
		</div>
	</div>
	<input type="submit" class="btn btn-info" value="Filtrar">
	
	</form>
</div>

<hr>

<?php
$paginator = $this->Paginator;
echo "<div class='row'>";
    echo '<div class="col-sm-1"><i class="fas fa-circle vermelho"></i> Sobrecarregado</div>';
	echo '<div class="col-sm-1"><i class="fas fa-circle verde"></i> Disponível</div>';
	echo '<div class="col-sm-1"><i class="fas fa-circle laranja"></i> No limite</div>';
	echo '<div class="col-sm-1"><div class="legendaTreinamento">Em treinamento</div></div>';
	echo '<div class="col-sm-1"><div class="legendaIndisponivel">Indisponível</div></div>';
	$parametros = explode("?",$_SERVER["REQUEST_URI"]);
	$enviaInfo = (string)$parametros[1];
	//$id_user = $this->Session->read('Auth.User.id');
	//if($id_user == 8){
		echo $this->Html->link('Export Excel', array('controller' => 'discipuladores', 'action' => 'exportExcel', $enviaInfo),array('class' => 'btn btn-success'));		
	//}
echo "</div>";
echo "<div class='tabelaSemQuebra'>";
    echo "<table class='table table-condensed'>";
        echo "<thead class='thead-dark'>";
        echo "<th>Ações</th>";
        echo "<th>Disponib.</th>";                
        echo "<th>" . $paginator->sort('', 'Disp.')."</th>";
        echo "<th>" . $paginator->sort('', 'Usad.')."</th>";
        echo "<th>" . $paginator->sort('', 'Tot.')."</th>";
        echo "<th>" . $paginator->sort('nome', 'Nome')."</th>";
        echo "<th>" . $paginator->sort('email', 'Email')."</th>";
        echo "<th>" . $paginator->sort('telefone_1', 'Contato')."</th>";        
        echo "<th>" . $paginator->sort('data_nascimento', 'Idade')."</th>";
        echo "<th>Redes</th>";
        echo "<th>" . $paginator->sort('Discipulador.disponib_casal', 'Disp. Casal')."</th>";        
        echo "<th>" . $paginator->sort('TipoRelacionamento.descricao', 'Estado Civil')."</th>";

        
        echo "</thead>";
        echo "<tbody>";
        foreach( $listaDiscipuladores as $lista ){
			
			#-------------------------------- inicio calculo hrs disponiveis / discípulos --------------------------------
			$qtdHrsDispo = $lista['Discipulador']['horas_semanais'] - $lista['Discipulador']['total_vinculo'];
			if($lista['Discipulador']['status_disponibilidade'] == 0){
				echo '<tr class="linhaTabelaVermalha">';
            }else{
			    if($lista['Discipulador']['is_liberado'] == 0){
					echo '<tr class="linhaTabelaAmarelo">';
                }else{
					echo '<tr>';
                }
            }
             echo '<td>';
				//echo $this->Html->link("Perfil completo", array('controller' => 'discipuladores', 'action' => 'perfil', $lista['Membro']['id']), array('class' => 'btn btn-info'));
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipuladores', 'action' => 'perfil',$lista['Membro']['id']), array('escape' => false));

				echo "&nbsp;&nbsp;&nbsp;";
				echo
				$this->Html->tag('i', '',
					array(
						'class' => 'fas fa-user-edit pointer',
						'escape' => false,
						'data-toggle' => 'modal',
						'data-target' => '#editUsuario',
						'data-id' => $lista['Discipulador']['id'],
						'data-idmembro' => $lista['Discipulador']['id_membro'],
						'data-rede' => $lista[0]['idredesdiscipuladores'],
						'data-rede_contagem' => $lista[0]['idredespais'],
						'data-horas' => $lista["Discipulador"]["horas_semanais"],
						'data-disponibilidade' => $lista["Discipulador"]["status_disponibilidade"],
						'data-liberado' => $lista["Discipulador"]["is_liberado"],
						'data-faixa' => $lista["Discipulador"]["id_faixa"],
						'title' => 'Editar Discipulador',
						'data-placement' => 'bottom',
						'data-tt' => 'tooltip'
					)
				);
				echo "&nbsp;&nbsp;&nbsp;";
				echo $this->Html->tag('i', '',
					array(
						'class' => 'fas fa-trash pointer deletarDiscipulador',
						'escape' => false,
						'title' => 'Excluir Discipulador',
						'data-placement' => 'bottom',
						'data-tt' => 'tooltip',
						'data-id' => $lista['Discipulador']['id_membro']
					)
				);
				//echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-trash')), array('controller' => 'discipuladores', 'action' => 'delete', $lista["Membro"]["id"]), array('escape' => false, "class" => "btn btn-danger" ));
				
				
				echo '</td>';
                if($qtdHrsDispo < 0){
						echo '<td><i class="fas fa-circle vermelho"></i></td>';
                }else{
                    if($qtdHrsDispo == 0 ){
                        
						echo '<td><i class="fas fa-circle laranja"></i></td>';
                    }else{
                        
						echo '<td><i class="fas fa-circle verde"></i></td>';
					}
                }


               
                #aqui
                echo '<td>'.$qtdHrsDispo.'H</td>';
                echo '<td>'.$lista['Discipulador']['total_vinculo'].'H</td>';
                echo '<td>'.$lista['Discipulador']['horas_semanais'].'H</td>';

                echo '<td>'.$lista['Membro']['nome'] .'</td>';
                echo '<td>'.$lista['Membro']['email'] .'</td>';
                echo '<td>'.$lista['Membro']['telefone_1'] .'</td>';
                echo '<td>'.$lista[0]['idade'] .' anos</td>';
			    echo '<td>'.$lista[0]['redesdiscipulador'] .'</td>';
                if($lista['Discipulador']['disponib_casal']== 1){
                    echo '<td> Disponível </td>';
                }else{
                    echo '<td> Não Disponível</td>';
                }

                echo '<td>'.$lista['TipoRelacionamento']['descricao'].'</td>';

               
            echo '</tr>';
        }
        echo "</tbody>";
    echo "</table>";
echo "</div>";
	
	echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	echo $this->Paginator->counter(
		'Página {:page} de {:pages}, mostrando de {:start} até {:end} de {:count} registros}'
	);
	echo "</div>";
	echo "</div>";

    // pagination section
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
<div class="modal fade" id="addDiscipulador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Cadastro de discipulador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
				<?php
					echo $this->Form->create('Discipulador', array(
						'url' => array('controller' => 'discipuladores', 'action' => 'add'),
						'class' => 'form-horizontal'
					));
				
				?>
                <div class="form-group">
                    <label for="">CPF<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('cpf', array('label' => false, 'class' => 'form-control', 'required' => 'required','onkeyup' => 'FormataCpf(this,event)', 'onblur' => 'verificaCpfExiste(this.value)')); ?>
                    <small id="nameHelp" class="form-text text-muted">Por favor, preencha o cpf</small>
                </div>
                <div class="form-group">
                    <label for="">ID de membro<span class="vermelho">*</span></label>
                    <?php echo $this->Form->input('id_membro', array('readonly' => 'readonly', 'label' => false, 'class' => 'form-control', 'type' => 'text', 'required' => 'required')); ?>
                    <small id="nameHelp" class="form-text text-muted">Campo obrigatório</small>
                </div>
                
                <table class="tabelaRedesDiscipulador">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="">Rede de contagem<span class="vermelho">*</span></label>
								<?php echo $this->Form->input('rede_contagem.', array('label' => false, 'empty' => 'selecione uma opção', 'options' => $listaRedesPais, 'class' => 'form-control', 'required' => 'required', 'onchange' => 'carregarredesfilhas(this.value, 1)')); ?>
                            </div>
                            <div id="divredefilha">
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <input type="button" id="addNovaRede" class="btn btn-default" value="Adicionar mais uma rede  ao discipulador">
                </div>
                <div class="form-group">
                    <label for="">Horas semanais<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('horas_semanais', array('label' => false, 'type'=>'text', 'class' => 'form-control', 'required' => 'required')); ?>
                </div>
                <div class="form-group">
                    <label for="">Disponibilidade<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('status_disponibilidade', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control', 'required' => 'required')); ?>
                </div>

                <div class="form-group">
                    <label for="">Casal Disponível<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('disponib_casal', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control', 'required' => 'required')); ?>
                </div>

                <div class="form-group">
                    <label for="">Faixa etária<span class="vermelho">*</span></label>
					<?php
						echo $this->Form->input('id_faixa', array(
							'class' => 'form-control',
							'label' => false,
							'empty' => "selecione uma opção",
							'options' => $listaEtarias,
							'required' => 'required'
						));
					?>
                </div>

                <div class="form-group">
                    <label for="">Liberado sem supervisão<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('is_liberado', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control', 'required' => 'required')); ?>
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

<!-- Modal EDIT DISCIPULADOR-->
<div class="modal fade" id="editDiscipulador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Editar discipulador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
				<?php
					echo $this->Form->create('Discipulador', array(
						'url' => array('controller' => 'discipuladores', 'action' => 'edit'),
						'class' => 'form-horizontal'
					));
					echo $this->Form->input('id', array('type' => 'hidden', 'class' => 'EditDiscipuladorId'));
					echo $this->Form->input('id_membro', array('type' => 'hidden', 'class' => 'EditDiscipuladorIdMembro'));
				?>
                <table class="tabelaRedesDiscipuladorEdit">
                    <tr>
                        <td>
                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <input type="button" id="addNovaRedeEditar" class="btn btn-default" value="Adicionar mais uma rede  ao discipulador">
                </div>
                <div class="form-group">
                    <label for="">Horas semanais<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('horas_semanais', array('label' => false, 'type'=>'text', 'class' => 'form-control EditDiscipuladorHorasSemanais', 'required' => 'required')); ?>
                </div>
                <div class="form-group">
                    <label for="">Disponibilidade<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('status_disponibilidade', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control EditDiscipuladorStatusDisponibilidade', 'required' => 'required')); ?>
                </div>

                <div class="form-group">
                    <label for="">Casal Disponível<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('disponib_casal', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control EditDiscipuladorDisponibCasal', 'required' => 'required')); ?>
                </div>

                <div class="form-group">
                    <label for="">Faixa etária<span class="vermelho">*</span></label>
					<?php
						echo $this->Form->input('id_faixa', array(
							'class' => 'form-control EditDiscipuladorIdFaixa',
							'label' => false,
							'empty' => "selecione uma opção",
							'options' => $listaEtarias,
							'required' => 'required'
						));
					?>
                </div>

                <div class="form-group">
                    <label for="">Liberado sem supervisão<span class="vermelho">*</span></label>
					<?php echo $this->Form->input('is_liberado', array('label' => false, 'empty' => 'selecione uma opção', 'options' => array('1' => 'Sim', '0' => 'Não'), 'class' => 'form-control EditDiscipuladorIsLiberado', 'required' => 'required')); ?>
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
<script>
$(document).ready(function () {
    var counter = 2;

    $("#addNovaRede").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td>'
            cols += '<div class="form-group"><label for="">Rede de contagem '+counter+'<span class="vermelho">*</span></label>';
                cols += '<select name="data[Discipulador][rede_contagem][]" id="DiscipuladorRedeContagem'+counter+'" class="form-control" onchange="carregarredesfilhas(this.value, '+counter+')">';
                    cols += '<option value="">Selecione uma opção</option>';
                    <?php
                        foreach ($listaRedesPaisSecundaria as $lista){
                            $id = '"'.$lista['Rede']['id'].'"';
                            echo "cols += '<option value=".$id.">".$lista['Rede']['nome']."</option>'; ";
                        }
                    ?>
                cols += '</select>';
            cols += '</div>';
            cols += '<div id="divredefilha'+counter+'"></div>';
        cols += '</td>';
        newRow.append(cols);
        $("table.tabelaRedesDiscipulador").append(newRow);

        counter ++;
    });

    $("table.tabelaRedesDiscipulador").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
    });
    $("table.tabelaRedesDiscipuladorEdit").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
    });
    
});
    function carregarredesfilhas(id, contador){
        //data[Discipulador][id_rede]
        //passou por todas as verificações de CPF
        $.ajax({
            method: 'POST',
            url: <?php echo "'".$this->Html->url(array('controller' => 'redes', 'action' => 'redesfilhas'), true)."'";  ?>,
            data:'id='+id+'&contador='+contador,
            dataType: "json",
            async: true,
            success: function(retorno){
                if(contador > 1){
                    $("#divredefilha"+contador+"").empty();
                    $("#divredefilha"+contador+"").append(retorno);
                }else{
                    $("#divredefilha").empty();
                    $("#divredefilha").append(retorno);
                }
            },error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }
function carregarredesfilhasedit(id, contador, redepai){
    //data[Discipulador][id_rede]
    //passou por todas as verificações de CPF
    $.ajax({
        method: 'POST',
        url: <?php echo "'".$this->Html->url(array('controller' => 'redes', 'action' => 'redesfilhasedit'), true)."'";  ?>,
        data:'id='+id+'&contador='+contador+'&redepai='+redepai,
        dataType: "json",
        async: true,
        success: function(retorno){
            $("#divredefilhaedit"+contador+"").empty();
            $("#divredefilhaedit"+contador+"").append(retorno);
            
        },error: function (request, status, error) {
            console.log(request.responseText);
        }
    });
}
    
    function verificaCpfExiste(cpf){
        if(cpf != ""){
            //validação CPF
            var numeros, digitos, soma, i, resultado, digitos_iguais;
            digitos_iguais = 1;
            cpfVerifica = cpf.replace(/[^0-9]/g, '');
            if (cpfVerifica.length < 11){
                return false;
            }
            for (i = 0; i < cpfVerifica.length - 1; i++)
                if (cpfVerifica.charAt(i) != cpfVerifica.charAt(i + 1)){
                    digitos_iguais = 0;
                    break;
                }
            if (!digitos_iguais) {
                numeros = cpfVerifica.substring(0,9);
                digitos = cpfVerifica.substring(9);
                soma = 0;
                for (i = 10; i > 1; i--)
                    soma += numeros.charAt(10 - i) * i;
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(0)){
                    alert("CPF inválido");
                    return false;
                }
                numeros = cpfVerifica.substring(0,10);
                soma = 0;
                for (i = 11; i > 1; i--)
                    soma += numeros.charAt(11 - i) * i;
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(1)) {
                    alert("CPF inválido");
                    return false;
                }

                //passou por todas as verificações de CPF
                $.ajax({
                    method: 'POST',
                    url: <?php echo "'".$this->Html->url(array('controller' => 'discipuladores', 'action' => 'verificacpfexiste'), true)."'";  ?>,
                    data:'cpf='+cpf,
                    dataType: "json",
                    async: true,
                    success: function(retorno){
                        //Já é um discipulador
                        if(retorno == 1){
                            swal({
                                icon: "info",
                                title: "JÁ EXISTE",
                                text: "Está pessoa já é um discipulador no sistema, obrigado!",
                                timer: 3000
                            });
                            $("#DiscipuladorCpf").val("");
                            $("#DiscipuladorCpf").focus();
                        }
                        //Encontrado
                        else if(retorno == 2){
                            swal({
                                icon: "info",
                                title: "NÃO ENCONTRADO",
                                text: "Não foi encontrado nenhuma pessoa com esse cpf, por favor verificar com seu líder se o mesmo está cadastrado em nossa base de dados!",
                                timer: 5000
                            });
                            $("#DiscipuladorCpf").val("");
                            $("#DiscipuladorIdMembro").val("");
                            $("#DiscipuladorCpf").focus();
                        } else{
                            //inserindo os valores nos inputs
                            $("#DiscipuladorIdMembro").val(retorno);
                            $("#DiscipuladorIdRede").focus();
                            
                        }
                    },error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
                return true;
            }
            else {
                alert("CPF inválido");
                return false;
            }
        }
    }
    function FormataCpf(campo, teclapres)
    {
        var tecla = teclapres.keyCode;
        var vr = new String(campo.value);
        vr = vr.replace(".", "");
        vr = vr.replace("/", "");
        vr = vr.replace("-", "");
        tam = vr.length + 1;
        if (tecla != 14)
        {
            if (tam == 4)
                campo.value = vr.substr(0, 3) + '.';
            if (tam == 7)
                campo.value = vr.substr(0, 3) + '.' + vr.substr(3, 6) + '.';
            if (tam == 11)
                campo.value = vr.substr(0, 3) + '.' + vr.substr(3, 3) + '.' + vr.substr(7, 3) + '-' + vr.substr(11, 2);
        }
    }
    $(document).on( "click", '.deletarDiscipulador',function(e) {
        var id = $(this).data('id');
        swal({
            title: "Você tem certeza?",
            text: "Está ação é irreversível, todos os vínculos desse discipulador serão excluídos e seu registro também!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: 'POST',
                    url: <?php echo "'".$this->Html->url(array('controller' => 'discipuladores', 'action' => 'delete'), true)."'";  ?>,
                    data:'id='+id,
                    dataType: "json",
                    async: true,
                    success: function(retorno){
                        if(retorno == 1){
                            location.reload(); 
                        }else{
                            swal("ERRO!", "Discipulador não foi deletado corretamente!", "error");
                        }
                        
                    },error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            } else {
                swal("Discipulador não foi deletado.");
            }
        });
    });

    $(document).on( "click", '.fa-user-edit',function(e) {
        var counter = 1;
        $('#editDiscipulador').modal('show');
        var id = $(this).data('id');

        var idmembro = $(this).data('idmembro');
        var rede = $(this).data('rede');
        if(typeof rede != 'undefined'){
            //alert("aqui");
            var redeString = rede.toString();
            var verificaSplit = redeString.split("/");
            var rede_contagem = $(this).data('rede_contagem');
        }else{
            verificaSplit = 0;
        }
        
        
        var horas = $(this).data('horas');
        var disponibilidade = $(this).data('disponibilidade');
        if(typeof disponibilidade == 'undefined'){
            disponibilidade = 0;
        }
        var casal = $(this).data('casal');
        if(typeof casal == 'undefined'){
            casal = 0;
        }
        var faixa = $(this).data('faixa');
        var liberado = $(this).data('liberado');
        if(typeof liberado == 'undefined'){
            liberado = 0;
        }
        var contadorNovaRede = 1;
        if(verificaSplit.length == 1){
            
            var i = 0;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td>'
            cols += '<div class="form-group"><label for="">Rede de contagem '+i+'<span class="vermelho">*</span></label>';
            cols += '<select name="data[Discipulador][rede_contagem][]" id="DiscipuladorRedeContagem'+i+'" class="form-control DiscipuladorRedeContagem'+i+'" onchange="carregarredesfilhasedit(this.value, '+i+')">';
            cols += '<option value="">Selecione uma opção</option>';
			<?php
			foreach ($listaRedesPaisSecundaria as $lista){
				$id = '"'.$lista['Rede']['id'].'"';
				echo "cols += '<option value=".$id.">".$lista['Rede']['nome']."</option>'; ";
			}
			?>
            cols += '</select>';
            cols += '</div>';
            cols += '<div id="divredefilhaedit'+i+'"></div>';
            cols += '</td>';
            newRow.append(cols);
            $("table.tabelaRedesDiscipuladorEdit").append(newRow);
            $(".DiscipuladorRedeContagem"+i+"").val(rede_contagem);
            carregarredesfilhasedit(rede_contagem, i, rede);
        }else if(verificaSplit.length > 1){
            console.log("oi");
            var redeCortada = rede.split("/");
            var redecontagemCortada = rede_contagem.split("/");

            for (i = 0; i < redeCortada.length; i++) {
                for (i = 0; i < redecontagemCortada.length; i++) {
                    var newRow = $("<tr>");
                    var cols = "";
                    cols += '<td>'
                    cols += '<div class="form-group"><label for="">Rede de contagem '+i+'<span class="vermelho">*</span></label>';
                    cols += '<select name="data[Discipulador][rede_contagem][]" id="DiscipuladorRedeContagem'+i+'" class="form-control DiscipuladorRedeContagem'+i+'" onchange="carregarredesfilhasedit(this.value, '+i+')">';
                    cols += '<option value="">Selecione uma opção</option>';
					<?php
					foreach ($listaRedesPaisSecundaria as $lista){
						$id = '"'.$lista['Rede']['id'].'"';
						echo "cols += '<option value=".$id.">".$lista['Rede']['nome']."</option>'; ";
					}
					?>
                    cols += '</select>';
                    cols += '</div>';
                    cols += '<div id="divredefilhaedit'+i+'"></div>';
                    cols += '</td>';
                    cols += '<td><i class="ibtnDel far fa-trash-alt"></i></td>';
                    newRow.append(cols);
                    $("table.tabelaRedesDiscipuladorEdit").append(newRow);
                    $(".DiscipuladorRedeContagem"+i+"").val(redecontagemCortada[i]);
                    carregarredesfilhasedit(redecontagemCortada[i], i, redeCortada[i]);
                    //$(".EditDiscipuladorIdRede"+i+"").val(redeCortada[i]);
                }
                contadorNovaRede ++;
            }
            contadorNovaRede ++;
        }
        if(verificaSplit.length == 0){
            $("#addNovaRedeEditar").val("Adicionar primeira rede");
        }
        $("#addNovaRedeEditar").on("click", function () {
            $("#addNovaRedeEditar").val("Adicionar mais uma rede ao discipulador");
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td>'
            cols += '<div class="form-group"><label for="">Rede de contagem '+contadorNovaRede+'<span class="vermelho">*</span></label>';
            cols += '<select name="data[Discipulador][rede_contagem][]" id="DiscipuladorRedeContagem'+contadorNovaRede+'" class="form-control" onchange="carregarredesfilhasedit(this.value, '+contadorNovaRede+')">';
            cols += '<option value="">Selecione uma opção</option>';
			<?php
			foreach ($listaRedesPaisSecundaria as $lista){
				$id = '"'.$lista['Rede']['id'].'"';
				echo "cols += '<option value=".$id.">".$lista['Rede']['nome']."</option>'; ";
			}
			?>
            cols += '</select>';
            cols += '</div>';
            cols += '<div id="divredefilhaedit'+contadorNovaRede+'"></div>';
            cols += '</td>';
            cols += '<td><i class="ibtnDel far fa-trash-alt"></i></td>';
            newRow.append(cols);
            $("table.tabelaRedesDiscipuladorEdit").append(newRow);

            contadorNovaRede ++;
        });
        
        
        
        $(".EditDiscipuladorId").val(id);
        $(".EditDiscipuladorIdMembro").val(idmembro);
        $(".EditDiscipuladorHorasSemanais").val(horas);
        $(".EditDiscipuladorStatusDisponibilidade").val(disponibilidade);
        $(".EditDiscipuladorDisponibCasal").val(casal);
        $(".EditDiscipuladorIdFaixa").val(faixa);
        $(".EditDiscipuladorIsLiberado").val(liberado);

    });
    $('#editDiscipulador').on('hidden.bs.modal', function (e) {
        $("table.tabelaRedesDiscipuladorEdit").empty();
    })
</script>