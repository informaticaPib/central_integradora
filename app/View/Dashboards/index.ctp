<div class="row">
    <div class="col-md-7">
        <div id="tabelaTotaisIntegracao"></div>
    </div>
    
</div>
<hr>
<div class="row">
    <div class="col-md-12">
		<?php echo $this->Form->input('rede', array('value'=>$this->params['url']['rede'], 'empty' => 'Todas as redes', 'options' => $listaRedePai, 'class' => 'form-control', 'label' => 'Filtrar por rede', 'onchange' => 'filtrorede(this.value)')); ?>
    </div>
</div>
<hr>
<div class="row">
	<?php //echo "<pre>"; print_r($dadosTabelaGerencial); echo "</pre>"; ?>
    <div class="col-md-12">
        <div id='wait' style="display:none;">
            <div class="alert alert-info" role="alert">
                Está consulta pode demorar um pouco, por favor, aguarde.
            </div>
			<?php echo $this->Html->image('ajax-loader_big.gif'); ?>
        </div>
        <div id="tabelaGrupoGestor">
			<?php echo $tabelagrupogestor["Tabelagrupogestor"]["html"]; ?>
        </div>
        <div id="tabelaGrupoGestorFiltrado" style="display:none;">
        
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div id="tabelaNiveisDiscipulado"></div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <div id="tabelaDiscipulos"></div>
    </div>
    <div class="col-md-6">
        <div id="tabelaDiscipuladoresOciosos">
			<?php echo $tabeladiscipuladoresociosos["Tabelagrupogestor"]["html"]; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="divTotaisVinculo"></div>
        <div id="divTotaisVinculoLiberado"></div>

        <div id="divDiscipuladoresAtivos"></div>
        <div id="divNovosMes"></div>
    </div>
</div>

<div id="divPagina" class="row">
    <div class="d-flex justify-content-center flex-wrap">
        <div id="divDiscipuladoresRede"></div>
    </div>
</div>

<div id="modalDiscipulosCancelados" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Discipulados cancelados no meio do caminho</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>A lista abaixo são de pessoa que tiveram seus status alterado para frequentador e não foi mais alterado seu status em nenhum momento. Contém pessoas que tiveram seus discipulos retirados também. </p>
                <div id="divDiscipulosCancelados"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>

    </div>
</div>



<script>

    var width = $('#divPagina').width();
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Discipuladores');
        data.addRows([
			<?php
			foreach ($redexdiscipulador as $rd){
				echo "['".$rd['Rede']['nome']."',".$rd[0]['total']."],";
			}
			?>
        ]);

        // Set chart options
        var options = {
            'width':width,
            'height':300,
            hAxis: {
                title: 'Rede x Discipuladores',
                viewWindow: {
                    min: [7, 30, 0],
                    max: [17, 30, 0]
                },
                textStyle: {
                    fontSize: 14,
                    color: '#053061',
                    bold: true,
                    italic: false
                },
                titleTextStyle: {
                    fontSize: 18,
                    color: '#053061',
                    bold: true,
                    italic: false
                }
            },
        };

        //Configuração pessoas com vínculo ou não
        var dataTotaisVinculo = google.visualization.arrayToDataTable([
            ['Vínculos', 'Total'],
            ['Vinculado com discipulador (<?php echo $totalVinculado; ?>)', <?php echo $totalVinculado; ?>],
            ['Sem vínculo DISCIPULADO (<?php echo $totalSemVinculoDiscipulado; ?>)', <?php echo $totalSemVinculoDiscipulado; ?>],
            ['Sem vínculo SER IGREJA (<?php echo $totalSemVinculoIgreja; ?>)', <?php echo $totalSemVinculoIgreja; ?>],
            ['Sem vínculo ACOMPANHAMENTO (<?php echo $totalSemVinculoAcompanhamento; ?>)', <?php echo $totalSemVinculoAcompanhamento; ?>]
        ]);

        var optionsDataTotaisVinculo = {
            'width':550,
            'height': 300,
            title: 'Pessoas vinculadas ou não com discipuladores'
        };

        //Configuração pessoas liberadas
        var dataTotaisVinculoLiberados = google.visualization.arrayToDataTable([
            ['Vínculos', 'Total'],
            ['Liberado DISCIPULADO (<?php echo $totalSemVinculoDiscipuladoLiberado; ?>)', <?php echo $totalSemVinculoDiscipuladoLiberado; ?>],
            ['Liberado SER IGREJA (<?php echo $totalSemVinculoIgrejaLiberado; ?>)', <?php echo $totalSemVinculoIgrejaLiberado; ?>],
            ['Liberado ACOMPANHAMENTO (<?php echo $totalSemVinculoAcompanhamentoLiberado; ?>)', <?php echo $totalSemVinculoAcompanhamentoLiberado; ?>]
        ]);
        var optionsDataTotaisVinculoLiberados = {
            'width':550,
            'height': 300,
            title: 'Pessoas liberadas para entrevista'
        };

        //Configuração de discipuladores ativos ou não
        var dataDiscipuladoresAtivo = new google.visualization.arrayToDataTable([
            ['Discipuladores', 'Quantidade', { role: 'style' }],
            ['Atuando', <?php echo $discipuladoresAtuando; ?>, 'silver'],
            ['Aguardando', <?php echo $totalDiscipuladoresAguardando; ?>, 'gold'],
            ['Indisponível (Pode ter vínculos)', <?php echo $discipuladoresIndisponiveis; ?>, 'color: #e5e4e2' ],
            ['Aguardando liberação', <?php echo $totalDiscipuladoresSistemaBloqueados; ?>, 'color: blue' ]
        ]);
        var optionsDiscipuladoresAtivo = {
            'width':850,
            'height':350,
            hAxis: {
                title: 'DISCIPULADORES',
                viewWindow: {
                    min: [7, 30, 0],
                    max: [17, 30, 0]
                },
                textStyle: {
                    fontSize: 14,
                    color: '#053061',
                    bold: true,
                    italic: false
                },
                titleTextStyle: {
                    fontSize: 18,
                    color: '#053061',
                    bold: true,
                    italic: false
                }
            },
        };

        var novosMes = new google.visualization.DataTable();
        novosMes.addColumn('string', 'Mês');
        novosMes.addColumn('number', 'Discipuladores');
        novosMes.addColumn('number', 'Discípulos');

        novosMes.addRows([
            ["Janeiro",     <?php echo $janeiroDiscipulador; ?>     , <?php echo $janeiroDiscipulo;     ?>],
            ["fevereiro",   <?php echo $fevereiroDiscipulador; ?>   , <?php echo $fevereiroDiscipulo;   ?>],
            ["março",       <?php echo $marcoDiscipulador; ?>       , <?php echo $marcoDiscipulo;       ?>],
            ["abril",       <?php echo $abrilDiscipulador; ?>       , <?php echo $abrilDiscipulo;       ?>],
            ["maio",        <?php echo $maioDiscipulador; ?>        , <?php echo $maioDiscipulo;        ?>],
            ["junho",       <?php echo $junhoDiscipulador; ?>       , <?php echo $junhoDiscipulo;       ?>],
            ["julho",       <?php echo $julhoDiscipulador; ?>       , <?php echo $julhoDiscipulo;       ?>],
            ["agosto",      <?php echo $agostoDiscipulador; ?>      , <?php echo $agostoDiscipulo;      ?>],
            ["setembro",    <?php echo $setembroDiscipulador; ?>    , <?php echo $setembroDiscipulo;    ?>],
            ["outubro",     <?php echo $outubroDiscipulador; ?>     , <?php echo $outubroDiscipulo;     ?>],
            ["novembro",    <?php echo $novembroDiscipulador; ?>    , <?php echo $novembroDiscipulo;    ?>],
            ["dezembro",    <?php echo $dezembroDiscipulador; ?>    , <?php echo $dezembroDiscipulo;    ?>]
        ]);
        var optionsNovosMes = {
            hAxis: {
                title: 'Mês'
            },
            vAxis: {
                title: 'Entradas por mês'
            }
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('divDiscipuladoresRede'));
        chart.draw(data, options);

        var chartTotaisVinculo = new google.visualization.PieChart(document.getElementById('divTotaisVinculo'));
        chartTotaisVinculo.draw(dataTotaisVinculo, optionsDataTotaisVinculo);

        var chartTotaisVinculoLiberado = new google.visualization.PieChart(document.getElementById('divTotaisVinculoLiberado'));
        chartTotaisVinculoLiberado.draw(dataTotaisVinculoLiberados, optionsDataTotaisVinculoLiberados);

        var chartDiscipuladoresAtivo = new google.visualization.ColumnChart(document.getElementById('divDiscipuladoresAtivos'));
        chartDiscipuladoresAtivo.draw(dataDiscipuladoresAtivo, optionsDiscipuladoresAtivo);

        var chartNovosMes = new google.visualization.LineChart(document.getElementById('divNovosMes'));
        chartNovosMes.draw(novosMes, optionsNovosMes);

    }
    $(document).ready(function() {
        tabelatotaissistema();
        tabelaDiscipulos();
        tabelaNiveisDiscipulado();
    });

    function filtrorede(rede){
        $("#tabelaGrupoGestor").hide();
        $("#tabelaGrupoGestorFiltrado").empty();
        $("#wait").show();
        
        if(rede == ''){
            $("#wait").hide();
            $("#tabelaGrupoGestor").show();
        }else{
            $.ajax({
                url: <?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'totaisrede'), true)."'";  ?>,
                type: 'GET',
                data: 'rede='+rede,
                dataType: 'json',
                success: function(data) {
                    $("#wait").hide();
                    $("#tabelaGrupoGestorFiltrado").show();
                    $("#tabelaGrupoGestorFiltrado").html(data);
                },
                error: function() {
                    console.log("erro");
                }
            });

        }
    }
    function tabelatotaissistema(){
        $.ajax({
            url: <?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'tabelatotaissistema'), true)."'";  ?>,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#tabelaTotaisIntegracao").html(data);
            },
            error: function() {
                console.log("erro");
            }
        });
    }

    function tabelaNiveisDiscipulado(){
        $.ajax({
            url: <?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'tabelaniveisdiscipulado'), true)."'";  ?>,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#tabelaNiveisDiscipulado").html(data);
            },
            error: function() {
                console.log("erro");
            }
        });
    }

    function tabelaDiscipulos(){
        $.ajax({
            url: <?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'tabeladiscipulos'), true)."'";  ?>,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#tabelaDiscipulos").html(data);
            },
            error: function() {
                console.log("erro");
            }
        });
    }
    
    function discipulosvinculados(){
        $.ajax({
            url: <?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'discipuloscancelados'), true)."'";  ?>,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                
                $('#modalDiscipulosCancelados').modal('show');
                $("#divDiscipulosCancelados").html(data);
            },
            error: function() {
                console.log("erro");
            }
        });
    }
    function buscaSubRede(id){
        if( $("#iconeSubRede-"+id).hasClass("fa-minus-circle") ){
            $("#iconeSubRede-"+id).removeClass("fa-minus-circle");
            $("#iconeSubRede-"+id).addClass("fa-plus-circle");
        }else{
            $("#iconeSubRede-"+id).removeClass("fa-plus-circle");
            $("#iconeSubRede-"+id).addClass("fa-minus-circle");
        }

        $.ajax({
            type:"POST",
            url:<?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'tabelasubredes'), true)."'";  ?>,
            dataType: 'json',
            data:'id='+id,
            async: true,
            success:function(dados){
                $("#subRede"+id).empty();
                $("#subRede"+id).append(dados);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
    function buscaPessoasSubRede(id){
        if( $("#iconePessoasSubRede-"+id).hasClass("fa-minus-circle") ){
            $("#iconePessoasSubRede-"+id).removeClass("fa-minus-circle");
            $("#iconePessoasSubRede-"+id).addClass("fa-plus-circle");
        }else{
            $("#iconePessoasSubRede-"+id).removeClass("fa-plus-circle");
            $("#iconePessoasSubRede-"+id).addClass("fa-minus-circle");
        }

        $.ajax({
            type:"POST",
            url:<?php echo "'".$this->Html->url(array('controller' => 'dashboards', 'action' => 'pessoassubrede'), true)."'";  ?>,
            dataType: 'json',
            data:'id='+id,
            async: true,
            success:function(dados){
                $("#pessoasSubRede"+id).empty();
                $("#pessoasSubRede"+id).append(dados);
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
    
</script>