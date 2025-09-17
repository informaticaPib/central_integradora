<div class="row">
    <div class="col-md-12">
        <?php
        
        $paginator = $this->Paginator;
        echo "<table class='table table-condensed table-hover'>";
            echo "<thead class='thead-dark'>";
                echo "<th>Ações</th>";
                echo "<th>" . $paginator->sort('status_discipulado', 'Nível Discipulado')."</th>";
                echo "<th>" . $paginator->sort('time_ci', 'Time')."</th>";
                echo "<th>Rede</th>";
                echo "<th>Discipulador</th>";
                echo "<th>" . $paginator->sort('nome', 'Discípulo')."</th>";
                echo '<th>Idade</th>';
                echo "<th>" . $paginator->sort('sDescricao', 'Email')."</th>";
                echo "<th>" . $paginator->sort('sTelefone', 'Contato')."</th>";
                echo "<th>" . $paginator->sort('cidade', 'Cidade')."</th>";
                echo "<th>" . $paginator->sort('dtCadastro', 'Data de cadastro')."</th>";
                echo "<th>" . $paginator->sort('status_id', 'Trilha')."</th>";
                echo "<th>" . $paginator->sort('origem', 'Origem')."</th>";
            echo "</thead>";
            echo "<tbody>";
            foreach( $retornoBusca as $lista ){
                $isLiberadoEntrevista = $lista['Clientepib']['liberado_entrevista'];
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
                elseif($lista['Clientepib']['status_id'] == 5){
                    $status = "Membro";
                }
                elseif($lista['Clientepib']['status_id'] == 1){
                    $status = "Frequentador";
                }
                else{
                    $status = "Acompanhamento";
                }
        
                echo '<tr>';           
                    if($lista['Clientepib']['status_discipulado'] == 1 && $isLiberadoEntrevista == 1){
                        echo '<td>Processo de entrevista</td>';
                    }else{
                        if($lista['Clientepib']['status_discipulado'] == 1){
                            echo '<td>'.$this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipulados', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false)).'</td>';
                        }elseif($lista['Clientepib']['status_discipulado'] == 2){
                            echo '<td>'.$this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipuladosd2', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false)).'</td>';
                        }else{
                            echo '<td>'.$this->Html->link($this->Html->tag('i', '', array('class' => 'fas fa-user fa-2x', 'title'=>'Perfil completo' ,'alt'=>'Perfil completo', 'style'=>'color:black')).'',array('controller' => 'discipuladosd3', 'action' => 'perfil', $lista['Clientepib']['id']), array('escape' => false)).'</td>';
                        }                      
                    }                    
                    echo "<td>".$lista['Clientepib']['status_discipulado']."</td>";
                    echo "<td>".$lista['Clientepib']['time_ci']."</td>";
                    echo '<td>'.$lista['Rede']['nome'] .'</td>';
                    echo '<td>'.$nomediscipulador.'</td>';
                    echo '<td>'.$lista['Clientepib']['nome'].'</td>';
                    echo '<td>'.$lista[0]['idade'].'</td>';
                    echo '<td>'.$lista['Clientepib']['email'] .'</td>';
                    echo '<td>'.$lista['Clientepib']['telefone_1'] .'</td>';
                    echo '<td>'.$lista['Clientepib']['cidade'].'</td>';
                    echo '<td>'.$lista[0]['datacadastro'] .'</td>';
                    echo '<td>'.$status.'</td>';
                    
                    if(empty($lista['MembroVinculo']['origem']) || strlen($lista['MembroVinculo']['origem'])==1){
                        if($lista['MembroVinculo']['origem'] == 9){
                            echo '<td><center>Recad. MD</center></td>';	
                        }
                        else{
                            echo '<td><center>Origem não <br/> inserida</center></td>';
                        }
                    }
                    else{
                        echo '<td>'.$lista['MembroVinculo']['origem'] .'</td>';
                    }
                    
                    
                echo '</tr>';
            }
            echo "</tbody>";
        echo "</table>";
        ?>
    </div>
</div>
<?php

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	echo $this->Paginator->counter(
		'Página {:page} de {:pages}, mostrando de {:start} até {:end} de {:count} registros'
	);
	echo "</div>";
	echo "</div>";
	
	// pagination section
	echo "<div class='paging'>";
	
	// the 'first' page button
	echo $paginator->first("Primeira página ");
	
	// 'prev' page button,
	// we can check using the paginator hasPrev() method if there's a previous page
	// save with the 'next' page button
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior ");
	}
	// the 'number' page buttons
	echo $paginator->numbers(array('modulus' => 5));
	
	// for the 'next' button
	if($paginator->hasNext()){
		echo "&nbsp;".$paginator->next("Próximo ");
	}
	// the 'last' page button
	echo $paginator->last(" Última página ");
	
	echo "</div>";
?>