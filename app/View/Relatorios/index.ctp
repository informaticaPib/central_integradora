<!-- HTML form for filter -->
<form action="<?php echo $this->Html->url(array('controller' => 'relatorios', 'action' => 'index')); ?>" method="post" class="form-inline mb-3">
    <div class="form-group mr-3">
        <?php echo $this->Form->input('nivel_discipulado', array('required', 'type' => 'select', 'options' => array(1 => 'Discipulado 1', 2 => 'Discipulado 2', 3 => 'Discipulado 3', 4 => 'Discipulado 4'), 'empty' => '-- Selecione uma opção --', 'class' => 'form-control')); ?>
    </div>
    <div class="form-group mr-3">
        <?php echo $this->Form->input('vinculado', array('required', 'type' => 'select', 'options' => array(1 => 'Sim', 0 => 'Não'), 'empty' => '-- Selecione uma opção --', 'class' => 'form-control')); ?>
    </div>
    <div class="form-group mr-3">
        <?php echo $this->Form->input('discipulo', array('type' => 'select', 'options' => $listaDiscipuladores, 'empty' => 'Todos', 'class' => 'form-control')); ?>
    </div>
    <button type="submit" class="btn btn-primary">Filtrar</button>
</form>

<!-- Check if filter applied and display results -->
<?php if (!empty($resultado)): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <th>Time</th>
                <th>Rede</th>
                <th>Discipulador</th>
                <th>Discípulo</th>
                <th>Email</th>
                <th>Contato</th>
                <th>Cidade</th>
                <th>Data de cadastro</th>
                <th>Idade</th>
                <th>Trilha</th>
                <th>Origem</th>
            </thead>
            <tbody>
                <?php 
                foreach ($resultado as $lista):
                    $dataVinculo  		= date("Y-m", strtotime($lista['Clientepib']['data_vinculo_discipulador']));
                    $dataAtual 	  		= date("Y-m");
                    $ultimoHistorico 	= date("Y-m", strtotime($lista[0]['ultimoHistorico']));
                    $mesAnterior 	  	= date("Y-m", strtotime("-1 months"));
                    
                    if($lista['Discipulador']['nome'] == ''){
                        $nomediscipulador = 'Sem vínculo';
                    }
                    else{
                        $nomediscipulador = $lista[0]['nomeDiscipulador'];
                    }
                    if($lista['Rede']['nome'] == ''){
                        $nomerede = 'Sem vínculo';
                    }
                    else{
                        $nomerede = $lista['Rede']['nome'];
                    }
                    if($lista['Clientepib']['status_id'] == 26){
                        $status = "Ser Igreja";
                    }
                    elseif($lista['Clientepib']['status_id'] == 27){
                        $status = "Batismo";
                    }
                    elseif($lista['Clientepib']['status_id'] == 30){
                        $status = "standby";
                    }
                    else{
                        $status = "Acompanhamento";
                    }
                    echo '<tr>';
                        echo "<td>".$lista['Clientepib']['time_ci']."</td>";
                        echo '<td>'.$lista['Rede']['nome'] .'</td>';
                        echo '<td>'.$nomediscipulador.'</td>';
                        echo '<td>'.$lista['Clientepib']['nome'] .'</td>';
                        echo '<td>'.$lista['Clientepib']['email'] .'</td>';
                        echo '<td>'.$lista['Clientepib']['telefone_1'] .'</td>';
                        echo '<td>'.$lista['Clientepib']['cidade'].'</td>';
                        echo '<td>'.$lista[0]['datacadastro'] .'</td>';
                        echo '<td>'.$lista[0]['idade'] .' anos</td>';
                        echo '<td>'.$status.'</td>';
                    echo '</tr>';
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>

    <!-- Export button -->
    <a href="<?php echo $this->Html->url(array('controller' => 'relatorios', 'action' => 'exportar')); ?>" class="btn btn-success">Exportar para XLSX</a>
<?php else: ?>
    <p>Nenhum resultado encontrado.</p>
<?php endif; ?>
