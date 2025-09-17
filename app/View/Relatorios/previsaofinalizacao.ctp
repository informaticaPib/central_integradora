<div class="row">
	<div class="col-md-12">
		<?php
			echo $this->Form->create(false, array(
				'url' => array('controller' => 'relatorios', 'action' => 'previsaofinalizacao'),
				'type' => 'get',
				'class' => 'form-inline'
			));
		?>
		
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
        <h3>Total de discípulos e seus registros de frequência no DISCIPULADO e SER IGREJA</h3>
        <h4>Mais detalhes clicar no número de cada coluna</h4>
        <p>No <b>discipulado</b>, as pessoas que estão com 12 registros, consequentemente já finalizaram seu processo e podem ser enviadas para entrevista</p>
        <p>No <b>ser igreja</b>, as pessoas que estão com 7 registros, consequentemente já finalizaram seu processo e podem ser enviadas para entrevista</p>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<th>GERAL (<?php echo $totalDiscipulos; ?> discípulos no sistema)</th>
				<th>1 registro</th>
				<th>2 registros</th>
				<th>3 registros</th>
				<th>4 registros</th>
				<th>5 registros</th>
				<th>6 registros</th>
				<th>7 registros</th>
				<th>8 registros</th>
				<th>9 registros</th>
				<th>10 registros</th>
				<th>11 registros</th>
				<th>12 registros</th>
			</thead>
			<tbody>
				<tr>
					<td>Discipulado</td>
                    <td><a onclick='carregardiscipulos(4,1)' class="linkcarregardiscippulo"><?php echo $discipulado1dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,2)' class="linkcarregardiscippulo"><?php echo $discipulado2dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,3)' class="linkcarregardiscippulo"><?php echo $discipulado3dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,4)' class="linkcarregardiscippulo"><?php echo $discipulado4dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,5)' class="linkcarregardiscippulo"><?php echo $discipulado5dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,6)' class="linkcarregardiscippulo"><?php echo $discipulado6dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,7)' class="linkcarregardiscippulo"><?php echo $discipulado7dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,8)' class="linkcarregardiscippulo"><?php echo $discipulado8dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,9)' class="linkcarregardiscippulo"><?php echo $discipulado9dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,10)' class="linkcarregardiscippulo"><?php echo $discipulado10dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,11)' class="linkcarregardiscippulo"><?php echo $discipulado11dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(4,12)' class="linkcarregardiscippulo"><?php echo $discipulado12dia; ?></a></td>
				</tr>
				<tr>
					<td>Ser Igreja</td>
                    <td><a onclick='carregardiscipulos(5,1)' class="linkcarregardiscippulo"><?php echo $igreja1dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,2)' class="linkcarregardiscippulo"><?php echo $igreja2dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,3)' class="linkcarregardiscippulo"><?php echo $igreja3dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,4)' class="linkcarregardiscippulo"><?php echo $igreja4dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,5)' class="linkcarregardiscippulo"><?php echo $igreja5dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,6)' class="linkcarregardiscippulo"><?php echo $igreja6dia; ?></a></td>
                    <td><a onclick='carregardiscipulos(5,7)' class="linkcarregardiscippulo"><?php echo $igreja7dia; ?></a></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- Modal ADD FREQUENCIA-->
<div class="modal fade" id="listagempessoas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Discipulos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="conteudomodal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function carregardiscipulos(turma,dia){
        $.ajax({
            method: 'POST',
            url: <?php echo "'".$this->Html->url(array('controller' => 'relatorios', 'action' => 'listagempessoasfinalizacao'), true)."'";  ?>,
            data: 'turma='+turma+'&dias='+dia,
            dataType: 'json',
            async: true,
            success: function(dados){
                $("#conteudomodal").html(dados);
                $('#listagempessoas').modal();
            }
        });
    }
    $('#listagempessoas').on('hidden.bs.modal', function (e) {
        $("#conteudomodal").empty();
    })
</script>