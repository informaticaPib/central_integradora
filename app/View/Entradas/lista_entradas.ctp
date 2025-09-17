<h3> Validação de novas entradas<span data-toggle="modal" data-target="#modalReabilitar"> . </span></h3>
<meta name="robots" content="noindex">
<?php
	$permissao = $this->Session->read('Auth.User.role');
    $id_usuario = $this->Session->read('Auth.User.id');
    
    echo "<div class='row'>";
        echo "<div class='col-md-12'>";
            echo $this->Form->create('Entrada', array(
                'url' => array('controller' => 'entradas', 'action' => 'listaEntradas'),
                'class' => 'form-horizontal',
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
                                <input name='cpf' class='form-control' id='idCpf' type='text' value='$returnCpf'>
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
                    echo "<div class='col-md-3'>";
                        echo "<div class='form-group'>".
                                $this->Form->input('dadosCompletos', array(
                                        'class'     => 'form-control',
                                        'label'     => 'Dados',
                                        'type'      => 'select',
                                        'empty'     => 'Selecione uma opção',
                                        'options'   => array(1=>'Dados Preenchidos corretamente', 2=>'Faltante de informação')
                                ))."
                            </div>";
                    echo "</div>"; 


        echo "</div>";//fecha a row

            echo "<div class='form-group'>
                    <input type='submit' class='btn btn-primary' value='Filtrar'>
                    <input type='button' class='btn btn-info' id=btnLimpar value='Limpar'>";
                    
                    if ($_GET['nome']!="") { 
                        $enviaInfo =  $_GET['nome'];
                        echo '&nbsp';
                        echo $this->Html->link('Export Excel', array('controller' => 'entradas', 'action' => 'exportExcelNome', $enviaInfo),array('class' => 'btn btn-success'));
                    }
                    else{

                    }
                    if ($_GET['cpf']!="") { 
                        $enviaInfo =  $_GET['cpf'];
                        echo '&nbsp';
                        echo $this->Html->link('Export Excel', array('controller' => 'entradas', 'action' => 'exportExcelCpf', $enviaInfo),array('class' => 'btn btn-success'));
                    }
                    else{

                    }
                    if ($_GET['tipo']!="") { 
                        $enviaInfo =  $_GET['tipo'];
                        echo '&nbsp';
                        echo $this->Html->link('Export Excel', array('controller' => 'entradas', 'action' => 'exportExcelTipo', $enviaInfo),array('class' => 'btn btn-success'));
                    }
                    if($_GET['nome']=="" && $_GET['cpf']=="" && $_GET['tipo']=="")
                    {
                        echo '&nbsp';
                        echo $this->Html->link('Export Geral', array('controller' => 'entradas', 'action' => 'exportExcelGeral'),array('class' => 'btn btn-success'));
                    }
                    echo '&nbsp';
                    echo "<button type='button' class='btn btn-primary' title='Importar Planilha' data-toggle='modal' data-target='.importar' alt='Importar Planilha'>
                            <i class='far fa-file-excel'></i> Importar Planilha
                        </button>"; 

                echo "</div>";
    echo "</div>";    
    echo "</form>";
?>

<?php
$paginator = $this->Paginator;
echo "<table class='table table-condensed table-hover'>";
echo "<thead>";
    echo "<th>Ações</th>";       
    echo "<th>" . $paginator->sort('id', 'ID')."</th>";
    echo "<th>" . $paginator->sort('nome', 'Nome')."</th>";    
    echo "<th>" . $paginator->sort('cpf', 'CPF')."</th>";
    echo "<th>" . $paginator->sort('email', 'Email')."</th>";        
    echo "<th>" . $paginator->sort('celular', 'Celular')."</th>";        
    echo "<th>" . $paginator->sort('dt_nascimento', 'Dt.    Nasci.')."</th>";        
    echo "<th>" . $paginator->sort('sexo', 'Sexo')."</th>";        
    echo "<th>" . $paginator->sort('cep', 'Cep')."</th>";        
    echo "<th>" . $paginator->sort('nome_discipulador', 'Nome    Disci.')."</th>";        
    echo "<th>" . $paginator->sort('contato_discipulador', 'Contato    Disci.')."</th>";        
    echo "<th>" . $paginator->sort('tipo', 'Tipo')."</th>";
    echo "<th>" . $paginator->sort('trilha', 'Trilha')."</th>";
    echo "<th>" . $paginator->sort('membro', 'Membro')."</th>";
    echo "<th>" . $paginator->sort('origem', 'Origem')."</th>";
    echo "<th>" . $paginator->sort('created', 'Dt.    Cadastro')."</th>";
    
echo "</thead>";
foreach( $entradas as $entrada ){
    
    if(empty($entrada['Entrada']['dt_nascimento']) || ($entrada['Entrada']['dt_nascimento']=='' || ($entrada['Entrada']['dt_nascimento']=='0000-00-00') )){
        $dataNascimento = 'não informado';
    }else{
        $dataNascimento = date("d/m/Y", strtotime(($entrada['Entrada']['dt_nascimento'])));
    }
    echo '<tr style="font-size: 13px;" >';
    #regra para mostrar o botão somente para os ids 1,8,13 / rever regra para retirar essas permissao
    if($id_usuario == 1 || $id_usuario == 7 || $id_usuario == 8 || $id_usuario == 13 || $id_usuario == 111 || $id_usuario == 162 || $id_usuario == 40 || $id_usuario == 233 || $id_usuario == 151 || $id_usuario == 297 || $id_usuario == 268){
        #mostra o botao da modal somente se o tipo for DIFERENTE de celula
        
        if($entrada['Entrada']['tipo'] !='celula'){
            //alterado 23082024 nao estava entrando o btn de celulas aqui 
            //realoquei para a parte de baixo dentro da permissão de admin
            echo "<td style='white-space: nowrap;'>
                    <button type='button' class='btn btn-primary' title='Detalhes' data-toggle='modal' data-target='.detalhes' alt='Detalhes' onclick='detalheItem(\"{$entrada['Entrada']['cpf']}\",\"{$entrada['Entrada']['nome']}\",\"{$entrada['Entrada']['id']}\",\"{$entrada['Entrada']['tipo']}\",\"{$entrada['Entrada']['nome_discipulador']}\",\"{$entrada['Entrada']['trilha']}\",\"{$entrada['Entrada']['origem']}\",\"{$entrada['Entrada']['contato_discipulador']}\" )'>
                        <i class='fas fa-address-card'></i>
                    </button>
                    <button class='btn btn-danger'  aria-hidden='true'  title='Excluir' onclick='excluirItem(".$entrada['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>
                </td>";
        }
        else{
                if($permissao == 'admin'){
                    echo "  <td>
                                <button type='button' class='btn btn-primary' title='Detalhes' data-toggle='modal' data-target='.detalhes' alt='Detalhes' onclick='detalheItem(\"{$entrada['Entrada']['cpf']}\",\"{$entrada['Entrada']['nome']}\",\"{$entrada['Entrada']['id']}\",\"{$entrada['Entrada']['tipo']}\",\"{$entrada['Entrada']['nome_discipulador']}\",\"{$entrada['Entrada']['trilha']}\",\"{$entrada['Entrada']['origem']}\",\"{$entrada['Entrada']['contato_discipulador']}\" )'>
                                    <i class='fas fa-address-card'></i>
                                </button>
                                <button class='btn btn-danger' aria-hidden='true' title='Excluir' onclick='excluirItem(".$entrada['Entrada']['id'].")' alt='deletar'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </td>";
                }
                else{
                    echo "<td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button class='btn btn-danger' aria-hidden='true' title='Excluir' onclick='excluirItem(".$entrada['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>
                        </td>";
                }
        }
    }
        echo '<td>'.$entrada['Entrada']['id'] .'</td>';
        echo '<td>'.$entrada['Entrada']['nome'] .'</td>';
        echo '<td>'.$entrada['Entrada']['cpf'] .'</td>';
        echo '<td style="font-size:11px;">'.$entrada['Entrada']['email'] .'</td>';
        echo '<td>'.$entrada['Entrada']['celular'] .'</td>';
        echo '<td style=text-align:center;>'.$dataNascimento.'</td>';
        if($entrada['Entrada']['sexo']=='F'){
            echo '<td>Feminino</td>';
        }
        else{
            echo '<td>Masculino</td>';
        }
        echo '<td>'.$entrada['Entrada']['cep'] .'</td>';
        echo '<td>'.$entrada['Entrada']['nome_discipulador'] .'</td>';
        echo '<td>'.$entrada['Entrada']['contato_discipulador'] .'</td>';
        echo '<td>'.$entrada['Entrada']['tipo'] .'</td>';                        
        
        #tratamento no campo trilha para os tipos andamento e discipulado
        if($entrada['Entrada']['tipo']=='andamento' || $entrada['Entrada']['tipo']=='discipulo'){
            if($entrada['Entrada']['trilha']==1){
                echo '<td>Ser igreja (já sou batizado)</td>';
            }        
            else if ($entrada['Entrada']['trilha']==0) {
                echo '<td>Discipulado (não sou batizado)</td>';
            }
            else{
                echo '<td>&nbsp;</td>';
            }
        }
        else{
            echo '<td>&nbsp;</td>';
        }

        if($entrada['Entrada']['membro']==0){
            if(isset($entrada['Entrada']['membro'])){
                echo '<td>Não</td>';
            }
            else{
                echo '<td>&nbsp;</td>';
            }
        }        
        else{
            echo '<td>Sim</td>';
        }
        echo '<td>'.$entrada['Entrada']['origem'] .'</td>';
        echo '<td>'.date("d/m/Y", strtotime(($entrada['Entrada']['created']))).'</td>';

        /*if($permissao == 'admin'){
			echo "<td>
                    <button class='btn btn-danger' aria-hidden='true' title='Excluir' onclick='excluirItem(".$entrada['Entrada']['id'].")' alt='deletar'><i class='fas fa-trash'></i></button>
                </td>";
		}*/

    echo '</tr>';
}
echo "</table>";
echo "<div class='paging'>";
	echo $paginator->first("Primeira página");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo "&nbsp;".$paginator->next("Próximo ");
	}
    echo $paginator->last(" Última página");

    echo $this->Paginator->counter(
        '<br/> Página {:page} de {:pages}, registro: {:start} até {:end} <br/>Total de {:count} registros'
    );


echo "</div>";

#chamada das modais
include "detalhes.ctp";
include "importacoes.ctp";
include "naoEncontrado.ctp";
include "vincular.ctp";
include "mesmaPessoa.ctp";
include "reabilitar.ctp";
?>



<script>
$(document).ready(function () {
    var retorno ="<?php echo $_GET['retornoErro'];?>";
    
    if(retorno==':ok'){
        swal("Importado", "Seu arquivo foi importado com sucesso", "success");
    }
    if(retorno==':erro'){
        swal("ATENÇÃO", "Seu arquivo possui alguns erros, verifique o modelo e tente importar novamente", "info");
    }

});

 //#----------------- parte que faz a pesquisa dentro da aba do discipulador no campo de texto
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




$("#btnLimpar").click(function() {
    window.location.href = 'https://centralintegradora.pibcuritiba.org.br/entradas/listaEntradas';
});

function excluirItem(params) {
    swal({
        title: "Atenção!",
        text: "Deseja deletar realmente esse registro?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'deletarItem'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+params,
                async: true,
                success:function(dados){
                    console.log(dados);
                },
                error: function (request, status, error) {
                    console.log('erro');
                }
            });
                swal("Registro deletado com sucesso!", {
                icon: "success",
                });            
                setTimeout(function(){ location.reload(); }, 2000);
        } else {
            //swal("Your imaginary file is safe!");
        }
    });
}


// -------------------------------------------------------- INICIO DE TODA FUNCTION DO DETALHAMENTO ----------------------------------------------------------------------
function detalheItem(cpf, nome, id, tipo, nmDiscipulador, trilha, origem, contatoDisci){
            //pesquisa para dados da modal parte inferior 
            
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'pesquisaCpf'), true)."'";  ?>,
                dataType: 'json',
                data:"cpf="+cpf+"&nome="+nome+"&origem="+origem,
                async: true,
                success:function(dados){
                    //console.log(dados['statusPessoa']);

                    $("#idMsgTipo").html("");
                    $("#returnTbl").html("");

                    if(dados['tipo'] =='membro'){
                            setTimeout(function(){
                                //nesse caso vai mostrar a msg somente se a pessoa realmente for membro
                                if(dados['statusPessoa']==5){
                                    $("#idMsgTipo").html("<span class='alert alert-danger' role='alert'><b>Atenção! </b>Esse usuário ja é <u>" +dados['tipo']+ "</u>.</span>");
                                }
                                $("#returnTbl").html(dados[0]);                        
                            }, 1000);
                    }else{
                        $("#returnTbl").html(dados[0]);
                    }
                    
                },
                error: function (request, status, error) {
                    console.log('erro1');
                }
            });

            
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'listaEntradasId'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+id+"&tipo="+tipo+"&cpf="+cpf+"&nmDisci="+nmDiscipulador+"&trilhas="+trilha+"&origem="+origem+"&contatoDisci="+contatoDisci+"&nome="+nome,
                async: true,
                success:function(dados){
                        var statusMembro = $("#idStatusMembro").val();//coloca o status em um campo hidden dentro de detalhes.ctp
                        $("#idCarregaEntradas").html("");
                        //so vai aparecer quando o usuario estiver vinculada a um discipulador na base de membros campo discipulador_ci
                        if(dados['idDisicpulador']>0){
                            $("#idMsgTipo").html("");
                            //a acao esta sendo realizada na function listaEntradasId
                            //$("#idMsgTipo").html("<span class='alert alert-danger' role='alert'><b>Atenção! </b>Essa pessoa ja esta vinculada a um discipulador, favor tratar no painel de operador!</span>");
                        }
                        
                        $("#idCarregaEntradas").html(dados[0]);

                },
                error: function (request, status, error) {
                    console.log('erro2');

                }
            });
} 

function detalhesListaEntradas(id) {
    
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'infoEntradaId'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+id,
                async: true,
                success:function(dados){
                    console.log(dados);
                    //var statusMembro = $("#idStatusMembro").val();
                    $("#resultDetalhes").html("");                    
                    $("#resultDetalhes").html(dados[0]);

                    //dados do form
                    $("#cadId").val(dados['result']['Entrada']['id']);
                    $("#cadEntradaNome").val(dados['result']['Entrada']['nome']);
                    $("#cadEntradaCpf").val(dados['result']['Entrada']['cpf']);
                    $("#cadEntradaDtNasci").val(dados['result']['Entrada']['dt_nascimento']);
                    $("#cadEntradaCelular").val(dados['result']['Entrada']['celular']);
                    $("#cadEntradaEmail").val(dados['result']['Entrada']['email']);
                    $("#cadEntradaCep").val(dados['result']['Entrada']['cep']);
                    $("#cadEntradaOrigem").val(dados['result']['Entrada']['origem']);
                    //$("#cadEntradaOrigem").val('222');

                    if(dados['result']['Entrada']['sexo']=='F'){
                        $("#cadEntradaSexo").val("1");
                    }
                    else{
                        $("#cadEntradaSexo").val("0");
                    }
                    $("#cadEntradaTipo").val(dados['result']['Entrada']['tipo']);


                },
                error: function (request, status, error) {
                    console.log('erro');
                }
            });
    
}


$("#idBtnCadastar").click(function(){

    swal({
        title: "Atenção!",
        text: "Deseja realmente inserir esse registro na base de pessoas?",
        icon: "info",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {            
            var dadosForm = $("#formNaoEncontrados").serialize();            
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'cadastroNaoMembro'), true)."'";  ?>,
                dataType: 'json',
                data:dadosForm,
                async: true,
                success:function(dados){
                    
                    if(dados==0){
                        swal("Não inserido!, Cpf ja consta na base de membros, favor verificar!", {
                            icon: "error",
                        });
                        setTimeout(function(){ location.reload(); }, 4000);
                    }
                    else{
                        console.log(dados);
                        swal("Registro inserido com sucesso!", {
                            icon: "success",
                        });
                        setTimeout(function(){ location.reload(); }, 2000);
                    }
                    if(dados==3){
                        swal("Atenção!, o cpf é inválido, favor verificar novamente", {
                            icon: "error",
                        });
                        setTimeout(function(){ location.reload(); }, 4000);
                    }
                },
                error: function (request, status, error) {
                        swal("Não inserido!", {
                            icon: "error",
                        });
                }
            });
            
        } else {
            //console.log('ocorreu um erro ');
        }
    });
});

function vincularDetalhes(id, trilha, nmDiscipulador) {
    
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'vincularTopoEntradasId'), true)."'";  ?>,
                dataType: 'json',
                data:"id="+id,
                async: true,
                success:function(dados){
                    
                        $("#retornoDetalherVincular").html("");
                        $("#retornoDetalherVincular").html(dados[0]);

                        $("#idVincularId").val(dados['result']['Entrada']['id']);
                        $("#idVincularNome").val(dados['result']['Entrada']['nome']);
                        $("#idVincularCpf").val(dados['result']['Entrada']['cpf']);
                        $("#idVincularDtNasci").val(dados['result']['Entrada']['dt_nascimento']);
                        $("#idVincularCelular").val(dados['result']['Entrada']['celular']);
                        $("#idVincularEmail").val(dados['result']['Entrada']['email']);
                        $("#idVincularCep").val(dados['result']['Entrada']['cep']);
                        $("#idVincularTipo").val(dados['result']['Entrada']['tipo']);
                        $("#idTrilha").val(trilha);
                        $("#idVincularSexo").val(dados['result']['Entrada']['sexo']);

                        $("#idOrigem").val(dados['result']['Entrada']['origem']);
                },
                error: function (request, status, error) {
                    console.log('erro');
                }
            });
            carregaDiscipulador(nmDiscipulador);
}

function carregaDiscipulador(nmDiscipulador) {
                
                //var searchTerm = $(".pesquisarDiscipulador").val();
                var searchTerm = nmDiscipulador;
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

                $(".pesquisarDiscipulador").val(nmDiscipulador);
}




$("#idBtnVincularDiscipulador").click(function() {

    swal({
        title: "Atenção!",
        text: "Deseja realmente vincular esse discipulador a essa pessoa?",
        icon: "info",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {

            var dadosForm = $("#formVincularDiscipulador").serialize();

            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'vincularDiscipuladores'), true)."'";  ?>,
                dataType: 'json',
                data:dadosForm,
                async: true,
                success:function(dados){

                    if(dados=="0"){
                        swal("Atenção, o discipulador não possui rede cadastrada!", {
                            icon: "error",
                        });            
                    }
                    if(dados=="2"){
                        swal("Atenção, o discipulador não possui rede cadastrada!", {
                            icon: "error",
                        });            
                    }
                    if(dados=="4"){
                        swal("Atenção, o discipulo ja esta cadastrado na base de membros, favor verificar!", {
                            icon: "error",
                        });            
                    }
                    if(dados==3){
                        swal("Atenção!, o cpf é inválido, favor verificar novamente", {
                            icon: "error",
                        });
                        setTimeout(function(){ location.reload(); }, 4000);
                    }
                    if(dados=="ok"){
                        //-------------- FIM do cod responsavel por enviar o email de acesso a central de membro para o usuario
                        swal("Registro inserido com sucesso!", {
                            icon: "success",
                        });
                    }
                },
                error: function (request, status, error) {
                    swal("Registro inserido com sucesso!", {
                            icon: "success",
                        });
                }
            });
            //setTimeout(function(){ location.reload(); }, 4000);
        } else {
            //swal("Your imaginary file is safe!");
        }
    });
});

    function comparaUsuarios(idUsuarioMembro) {
        var idEntrada = $("#idUsuarioEntrada").val();
        
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'comparaTabela'), true)."'";  ?>,
                dataType: 'json',
                data:"idMembro="+idUsuarioMembro+"&idEntrada="+idEntrada,
                async: true,
                success:function(dados){

                    $("#resultComparacao").html("");
                    $("#resultComparacao").html(dados[0]);
                    $("#resultDadosEntrada").val("");                    
                    $("#resultDadosEntrada").val(dados['dadosEntrada'][0]['Entrada']['bairro']+';'+dados['dadosEntrada'][0]['Entrada']['celular']+';'+dados['dadosEntrada'][0]['Entrada']['cep']+';'+dados['dadosEntrada'][0]['Entrada']['cidade']+';'+dados['dadosEntrada'][0]['Entrada']['complemento']+';'+dados['dadosEntrada'][0]['Entrada']['conhecer']+';'+dados['dadosEntrada'][0]['Entrada']['contato_discipulador']+';'+dados['dadosEntrada'][0]['Entrada']['cpf']+';'+dados['dadosEntrada'][0]['Entrada']['created']+';'+dados['dadosEntrada'][0]['Entrada']['dt_nascimento']+';'+dados['dadosEntrada'][0]['Entrada']['email']+';'+dados['dadosEntrada'][0]['Entrada']['estado']+';'+dados['dadosEntrada'][0]['Entrada']['id']+';'+dados['dadosEntrada'][0]['Entrada']['membro']+';'+dados['dadosEntrada'][0]['Entrada']['nome']+';'+dados['dadosEntrada'][0]['Entrada']['nome_discipulador']+';'+dados['dadosEntrada'][0]['Entrada']['numero']+';'+dados['dadosEntrada'][0]['Entrada']['pais']+';'+dados['dadosEntrada'][0]['Entrada']['rua']+';'+dados['dadosEntrada'][0]['Entrada']['sexo']+';'+dados['dadosEntrada'][0]['Entrada']['tipo']+';'+dados['dadosEntrada'][0]['Entrada']['trilha']+';'+idUsuarioMembro+';'+dados['dadosEntrada'][0]['Entrada']['origem']);
                },
                error: function (request, status, error) {
                    console.log('erro');
                }
            });
    }
    //botao de confirmação da mesma pessoa
    $("#btnConfirmaPessoa").click(function() {
        //parte que envia email esta dentro do success
        swal({
            title: "Atenção!",
            text: "Tem certeza que deseja atualizar as informações?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                var arrayDados = $("#resultDadosEntrada").val();            
                $.ajax({
                    type:"POST",
                    url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'confirmaMesmaPessoaMembro'), true)."'";  ?>,
                    dataType: 'json',
                    data:"dados="+arrayDados,
                    async: true,
                    success:function(dados){
                            swal("Registro atualizado com sucesso!", {
                                icon: "success",
                            }); 

                        setTimeout(function(){ location.reload(); }, 2000);
                    },
                    error: function (request, status, error) {
                        swal("Registro atualizado com sucesso!", {
                                icon: "success",
                            });
                            setTimeout(function(){ location.reload(); }, 2000);
                            // function () {
                            // location.reload(true);
                            // };            
                    }
                });
            } else {
                //swal("Your imaginary file is safe!");
            }
        });
    });

function fecharModal() {
    $('#idDetalhes').modal('hide');
}


function anularEnter(e) {
        //See notes about 'which' and 'key'
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    }





</script>