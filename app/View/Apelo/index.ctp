<div class="" id="integracao">
	<header class="integracao_header">
	</header>
	
	<!-- <div class=" title-area area-titulo-jesus">
	    <h1 class="text-center" style="color: #fff;">Seja bem-vindo!</h1>
	        <p class="text-center" style="color: #fff;"><strong>Nós queremos te ajudar a conhecer mais sobre Jesus!</strong></p>
			<p class="text-center" style="color: #fff;">Preencha o formulário e em breve entraremos em contato com você!</p>
	</div> -->

	
    <div class="">
        <div class="form-wrap-jesus" style="border-radius: 0px!important;">			
			<section class="d-flex justify-content-center align-items-start flex-column" >			

			
			<!-- formulario -->
			
			<div id='formTipo' style="display:block" class="form-wrap-100" style="color: #fff;" >
                <?php
					echo $this->Form->create('Entrada', array(
						'url' => array('controller' => 'entradas', 'action' => 'index'),
						'role' => 'form',
						'enctype'=>'multipart/form-data',
						'id' => 'formEntradaEdit'
                    ));
                    
                    echo "<div class='form-group'>
                            ".$this->Form->input('nome',
							array('type'=>'text','class'=>'form-control', 'label'=>'Nome*', 'required' => 'required'))."
                        </div>";
                    
                    echo '<div class="form-group">';
                        echo $this->Form->input('celular', array('type'=>'tel','class'=>'form-control', 'label'=>'Whatsapp', 'required' => 'required'));
                        echo '<small id="celularHelp" class="form-text text-muted" style="color: white!important;">Formato: (99) 99999-9999</small>';
                    echo '</div>';

                    echo '
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="noWhatsappOption">
                            <label class="form-check-label" for="noWhatsappOption">
                            Não tenho Whatsapp
                            </label>
                        </div>
                    </div>
                    ';

                    echo '<div class="form-group" id="emailFormGroup" style="display: none;">';
                        echo '<label for="emailInput">Email:</label>';
                        echo '<input type="text" class="form-control" id="emailInput" placeholder="Digite seu email">';
                        echo '<div class="invalid-feedback">';
                            echo 'Por favor, insira um email válido.';
                        echo '</div>';
                    echo '</div>';
					
                    // echo "<div class='form-group'>
                    //         ".$this->Form->input('cpf',
					// 		array('type'=>'text','class'=>'form-control', 'label'=>'CPF*', 'onblur' => 'verificaCpfExiste(this.value)', 'required' => 'required'))."
                    //     </div>";

					// echo "<div class='form-group'>
                    //         ".$this->Form->input('email',
					// 		array('type'=>'text','class'=>'form-control', 'label'=>'Email*', 'required' => 'required'))."
                    //     </div>";

                    

                    // echo "<div class='form-group'>
                    //     <label id='lbDtNacimento'>Data de Nascimento*</label>
                    //     <input type='date' name='data[Entrada][dt_nascimento]' id='data' class='form-control' required>
                    // </div>";

                    // echo "<div class='form-group'>
                    //         ".$this->Form->input('cep',
                    //         array('type'=>'text','class'=>'form-control', 'label'=>'CEP*'))."
                    //     </div>";

                    // echo "<div class='form-group'>".						                            
                    //         $this->Form->input('sexo', array(
                    //             'separator' => '<br/>',
                    //             'options' => array('M' => 'Maculino', 'F' => 'Feminino'),
                    //             'type' => 'radio'
                    //         ))
                    //     ."</div>";

                    // $options2 = array(0 => 'Decidi crer em Jesus e quero dar os primeiros passos', 1 => 'Venho de outra igreja evangélica e quero fazer parte PIB Curitiba', 2 =>'Voltar para Jesus', 3 =>'Quero participar de uma célula', 4 =>'Quero conhecer mais sobre Jesus');
                    // echo "<div class='form-group'>".
                    //         $this->Form->input('conhecer', array(
                    //             'separator' => '<br/>',
                    //             'options' => array(0 => 'Decidi crer em Jesus e quero dar os primeiros passos', 1 => 'Venho de outra igreja evangélica e quero fazer parte PIB Curitiba', 2 =>'Voltar para Jesus', 3 =>'Quero participar de uma célula', 4 =>'Quero conhecer mais sobre Jesus'),
                    //             'type' => 'radio'
                    //         ))
                    //     ."</div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('pais',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Pais*'))."
                    // </div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('estado',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Estado*'))."
                    // </div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('cidade',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Cidade*'))."
                    // </div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('bairro',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Bairro*'))."
                    // </div>";
                    
                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('rua',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Rua*'))."
                    // </div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('numero',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Número*'))."
                    // </div>";

                    // echo "<div class='form-group'>
                    //     ".$this->Form->input('complemento',
                    //     array('type'=>'hidden','class'=>'form-control', 'label'=>'Complemento*'))."
                    // </div>";


                    echo "<div id='formDiscipulador' style='display:none'>";

                            /*echo "<div class='form-group'>".
                                $this->Form->input('trilha', array(
                                'label' =>'Você está participando de qual trilha?*',
                                'class' => 'form-control',
                                'type'=>'select',
                                'required' => 'required',
                                'empty' => 'Selecione uma opção',
                                'options' => array( 0 => 'Discipulado (não sou batizado)', 1 => 'Ser Igreja (já sou batizado)')
                                ))
                            ."</div>";

                            echo "<div class='form-group'>
                                ".$this->Form->input('nome_discipulador',
                                array('type'=>'text','class'=>'form-control', 'label'=>'Qual é o nome do seu DISCIPULADOR?*'))."
                            </div>";

                            echo "<div class='form-group'>
                                ".$this->Form->input('contato_discipulador',
                                array('type'=>'text','class'=>'form-control', 'label'=>'Qual telefone do seu DISCIPULADOR?*'))."
                            </div>";*/

                    echo "</div>";

                    echo "<div id='formCelula' style='display:none'>";                            

                        /*echo "<div class='form-group'>".
                                $this->Form->input('membro', array(
                                    'label' =>'Você já membro da PIB Curitiba?*',
                                    'class' => 'form-control',
                                    'type'=>'select',
                                    'empty' => 'Selecione uma opção',
                                    'options' => array(  0 => 'Não', 1 => 'Sim')
                                ))
                            ."</div>";*/
                    
                    echo "</div>";


                    echo "<div id='formConhecer' style='display:none'>";                            
                        /*echo "<div class='form-group'>".
                            $this->Form->input('conhecer', array(
                                'label' =>'Como podemos te ajudar?*',
                                'class' => 'form-control',
                                'type'=>'select',
                                'empty' => 'Selecione uma opção',
                                'options' => array(  0 => 'Decidi crer em Jesus e quero dar os primeiros passos', 1 => 'Venho de outra igreja evangélica e quero fazer parte PIB Curitiba', 2 =>'Voltar para Jesus', 3 =>'Quero participar de uma célula', 4 =>'Quero conhecer mais sobre Jesus')
                            ))
                        ."</div>";*/
                echo "</div>";


                // echo "<div class='form-group'>
                //     ".$this->Form->input('tipo',
                //     array('type'=>'hidden','class'=>'form-control', 'label'=>'tipo*', 'id'=>'idTipo', 'value'=>'conhecer_jesus'))."
                // </div>";                    
                    
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id='btnLimpar'>Limpar</button>
                    <button type="button" class="btn btn-primary" id='btnSalvar'>Salvar</button>
                </div>
                </form>
            </div>
			<section>			
        </div>
        <div class="col-2">
            &nbsp;
        </div>
    </div>    
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
    const noWhatsappOption = document.getElementById('noWhatsappOption');
    const emailFormGroup = document.getElementById('emailFormGroup');
    const emailInput = document.getElementById('emailInput');
    const celularInput = document.getElementById('EntradaCelular');

    noWhatsappOption.addEventListener('change', function() {
      if (noWhatsappOption.checked) {
        emailFormGroup.style.display = 'block';
        celularInput.removeAttribute('required');
        emailInput.setAttribute('required', 'required');
        emailInput.setAttribute('pattern', '[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$');
      } else {
        emailFormGroup.style.display = 'none';
        emailInput.removeAttribute('required');
        emailInput.removeAttribute('pattern');
        emailInput.value = '';
      }
    });

//---------------------- mascaras de campos ----------------------
$(document).ready(function () {    
    $("#EntradaCelular").mask("(99) 99999-9999");
    $("#EntradaContatoDiscipulador").mask("(99) 99999-9999");
    $("#EntradaCpf").mask("999.999.999-99");  
    $("#EntradaCep").mask("99999-999");  

//----------------- consulta a api dos correios para realizar a busca pelo cep --------------------
    //Quando o campo cep perde o foco.
    $("#EntradaCep").blur(function() {
        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                $("#EntradaRua").val("...");
                $("#EntradaBairro").val("...");
                $("#EntradaCidade").val("...");
                $("#EntradaEstado").val("...");
                $("#EntradaPais").val("...");
                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //console.log(dados);
                        //Atualiza os campos com os valores da consulta.
                        $("#EntradaRua").val(dados.logradouro);
                        $("#EntradaBairro").val(dados.bairro);
                        $("#EntradaCidade").val(dados.localidade);
                        $("#EntradaEstado").val(dados.uf);
                        $("#EntradaPais").val("Brasil");
                        $("#EntradaNumero").focus();

                        $('#EntradaRua').attr('readonly', true);
                        $('#EntradaBairro').attr('readonly', true);
                        $('#EntradaCidade').attr('readonly', true);
                        $('#EntradaEstado').attr('readonly', true);
                        $('#EntradaPais').attr('readonly', true);

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();                        
                        swal({
                                title: "Atenção!",
                                text: "CEP não encontrado, favor verificar novamente!",
                                icon: "info",
                                button: "Fechar",
                            });
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                    swal({
                        title: "Atenção!",
                        text: "Formato do cpf inválido!",
                        icon: "info",
                        button: "Fechar",
                    });
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });
});

function limpa_formulário_cep() {
    $("#EntradaBairro").val("");
    $("#EntradaCidade").val("");
    $("#EntradaEstado").val("");
    $("#EntradaPais").val("");
    $("#EntradaRua").val("");
    
}

//---------------------- validação de email ----------------------
$("#EntradaEmail").focusout(function() {
    var email = $("#EntradaEmail").val();
    if(email){
        if (validaEmail(email)) {   
        //email valido
        } 
        else{    
            swal({
                title: "Atenção!",
                text: "O email informado não é valido",
                icon: "error",
                button: "Fechar",
            });
            $("#EntradaEmail").val("");    
        }
        function validaEmail(email) {
            var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return regex.test(email);
        }
    }
    
});

//---------------------- ações dos botoes  ----------------------
    $("#btnDiscipulado").click(function() {
        $("#idTipo").val("discipulado");
        $("#formTipo").css('display', 'block');
		
		//display texto lateral
		$("#texto-discipulado").css('display', 'block');
		$("#texto-ser-igreja").css('display', 'none');
		$("#texto-reconciliacao").css('display', 'none');
		$("#texto-disc-andamento").css('display', 'none');
		$("#texto-quero-celula").css('display', 'none');
		$("#texto-jesus").css('display', 'none');

		
		$("#texto-discipulado").css('display', 'block');
        $("#formDiscipulador").css('display', 'none');
        $("#formCelula").css('display', 'none');
        $("#data").show();
        $("#lbDtNacimento").show();	
		
		//scroll to form
		$('html,body').animate({
        scrollTop: $("#form-section").offset().top},
        'slow');
		
    });
    $("#btnIgreja").click(function() {
        $("#idTipo").val("ser_igreja");
        $("#formTipo").css('display', 'block');
		
		//display texto lateral
		$("#texto-discipulado").css('display', 'none');
		$("#texto-ser-igreja").css('display', 'block');
		$("#texto-reconciliacao").css('display', 'none');
		$("#texto-disc-andamento").css('display', 'none');
        $("#texto-quero-celula").css('display', 'none');
        $("#formConhecer").css('display', 'none');
		$("#texto-jesus").css('display', 'none');

	
		
        $("#formDiscipulador").css('display', 'none');
        $("#formCelula").css('display', 'none');
        $("#data").show();
        $("#lbDtNacimento").show();
		
		//scroll to form
		$('html,body').animate({
        scrollTop: $("#form-section").offset().top},
        'slow');
    });
    $("#btnReconciliar").click(function() {
        $("#idTipo").val("reconciliar");
        $("#formTipo").css('display', 'block');
		
		//display texto lateral
		$("#texto-discipulado").css('display', 'none');
		$("#texto-ser-igreja").css('display', 'none');
		$("#texto-reconciliacao").css('display', 'block');
		$("#texto-disc-andamento").css('display', 'none');
        $("#texto-quero-celula").css('display', 'none');
        $("#formConhecer").css('display', 'none');
		$("#texto-jesus").css('display', 'none');

		
        $("#formDiscipulador").css('display', 'none');
        $("#formCelula").css('display', 'none');
        $("#data").show();
        $("#lbDtNacimento").show();
		
		//scroll to form
		$('html,body').animate({
        scrollTop: $("#form-section").offset().top},
        'slow');
    });
    $("#btnAndamento").click(function() {
        $("#idTipo").val("andamento");
        $("#formTipo").css('display', 'block');
		
		//display texto lateral
		$("#texto-discipulado").css('display', 'none');
		$("#texto-ser-igreja").css('display', 'none');
		$("#texto-reconciliacao").css('display', 'none');
		$("#texto-disc-andamento").css('display', 'block');
        $("#texto-quero-celula").css('display', 'none');
        $("#formConhecer").css('display', 'none');
		$("#texto-jesus").css('display', 'none');

		
		
        $("#formDiscipulador").css('display', 'block');
        $("#formCelula").css('display', 'none');

        $("#data").show();
        $("#lbDtNacimento").show();
        $("#EntradaSexo").show();        
        $("#lbSexo").show();
		
		//scroll to form
		$('html,body').animate({
        scrollTop: $("#form-section").offset().top},
        'slow');

        
    });
    $("#btnCelula").click(function() {
        $("#idTipo").val("celula");
        $("#formTipo").css('display', 'block');
		
		//display texto lateral
		$("#texto-discipulado").css('display', 'none');
		$("#texto-ser-igreja").css('display', 'none');
		$("#texto-reconciliacao").css('display', 'none');
        $("#texto-disc-andamento").css('display', 'none');
        $("#formConhecer").css('display', 'none');
		$("#texto-quero-celula").css('display', 'block');
		$("#texto-jesus").css('display', 'none');
		
		
        $("#formDiscipulador").css('display', 'none');
        $("#formCelula").css('display', 'block');
        $("#data").hide();
        $("#lbDtNacimento").hide();
        $("#EntradaSexo").hide();        
        $("#lbSexo").hide();
		
		//scroll to form
		$('html,body').animate({
        scrollTop: $("#form-section").offset().top},
        'slow');
        
        
    });	
	//botão novo Jesus
	$("#btnJesus").click(function(){
        $("#idTipo").val("conhecer_jesus");
        $("#formTipo").css('display', 'block');
        $("#formConhecer").css('display', 'block');
        
		//display texto lateral
		$("#texto-discipulado").css('display', 'none');
		$("#texto-ser-igreja").css('display', 'none');
		$("#texto-reconciliacao").css('display', 'none');
		$("#texto-disc-andamento").css('display', 'none');
		$("#texto-quero-celula").css('display', 'none');
		$("#texto-jesus").css('display', 'block');

	})


//---------------------- ação do formulario ----------------------
    $("#btnSalvar").click(function() {
        var dadosForm = $("#formEntradaEdit").serialize();        

        var nome    = $("#EntradaNome").val();
        var celular = $("#EntradaCelular").val();


        //se o vampo nome for igual a vazio
        if(nome.length<1){
            swal({
                title: "Atenção!",
                text: "O campo nome é obrigatório!",
                icon: "error",
                button: "Fechar",
            });
            $("#EntradaNome").attr("placeholder", "Informe o seu nome!");
            $("#EntradaNome").focus();
        }      
        else{
            //campos devidamente preenchidos pode registrar
            $.ajax({
                type:"POST",
                url:<?php echo "'".$this->Html->url(array('controller' => 'entradas', 'action' => 'index'), true)."'";  ?>,
                dataType: 'json',
                data:dadosForm,
                async: true,
                success:function(dados){
                    swal("Sucesso", "Seus dados foram registrados com sucesso, logo estaremos entrando em contato com você!", "success");
                    $('#EntradaNome').val("");     
                    $('#EntradaCelular').val("");                    
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                    //alert(request.responseText);
                }
            });
        }
    });


    //acao para limpar o formulario
    $('#btnLimpar').click(function () {
        
        $('#EntradaNome').val("");
        $('#EntradaCpf').val("");
        $('#EntradaEmail').val("");
        $('#EntradaCelular').val("");
        $('#data').val("");
        $('#EntradaSexo').val("");
        $('#EntradaCep').val("");
        $('#EntradaPais').val("");
        $('#EntradaEstado').val("");
        $('#EntradaCidade').val("");
        $('#EntradaBairro').val("");
        $('#EntradaRua').val("");
        $('#EntradaNumero').val("");
        $('#EntradaComplemento').val("");
        $('#EntradaNomeDiscipulador').val("");
        $('#EntradaContatoDiscipulador').val("");
        $('#EntradaMembro').val("");
        $('#EntradaConhecer').val("");
    });

// -------------------------------------------- validacao do date picker via safari --------------------------------------------
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
        var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
        var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
        if(isSafari){
            $('#data').prop('type', 'text');
            $('#data').datepicker({
                format: "yyyy-mm-dd",
                language: "pt-BR",
                autoclose: true,
                todayHighlight: true
            });
            $("#date").attr("readonly", true);
            $("#divInfoData").show();
        }
        if(isOpera){
            $('#data').prop('type', 'text');
            $('#data').datepicker({
                format: "yyyy-mm-dd",
                language: "pt-BR",
                autoclose: true,
                todayHighlight: true
            });
            $("#data").attr("readonly", true);
            $("#divInfoData").show();
        }
        //Retirar ou comentar o IF debaixo após testes
        /*if(isChrome){
            $('#data').prop('type', 'text');
            $('#data').datepicker({
                format: "yyyy-mm-dd",
                language: "pt-BR",
                autoclose: true,
                todayHighlight: true
            });
            $("#data").attr("readonly", true);
            $("#divInfoData").show();
        }*/




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
                    swal({
                        title: "Atenção!",
                        text: "O email informado não é valido",
                        icon: "error",
                        button: "Fechar",
                    });
                    return false;
                }
                numeros = cpfVerifica.substring(0,10);
                soma = 0;
                for (i = 11; i > 1; i--)
                    soma += numeros.charAt(11 - i) * i;
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(1)) {
                    swal({
                        title: "Atenção!",
                        text: "O email informado não é valido",
                        icon: "error",
                        button: "Fechar",
                    });
                    return false;
                }
                //passou por todas as verificações de CPF                
                return true;
            }
            else {
                swal({
                        title: "Atenção!",
                        text: "O email informado não é valido",
                        icon: "error",
                        button: "Fechar",
                    });
                return false;
            }
        }
    }
</script>