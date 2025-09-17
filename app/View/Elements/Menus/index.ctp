<?php
$permissao = $this->Session->read('Auth.User.role');
?>
    <ul class="list-unstyled components">
        <li>
            <?php echo $this->Html->link("Área gerencial", array('controller' => 'dashboards', 'action' => 'index')); ?>
        </li>
        <li>
            <a href="#OperadorSubMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Discipulado 1 - Conexão</a>
            <ul class="collapse list-unstyled" id="OperadorSubMenu">
                <li>
					<?php echo $this->Html->link("Discípulos não vinculados", array('controller' => 'discipulados', 'action' => 'naovinculados')); ?>
                </li>
                <li>
					<?php echo $this->Html->link("Discípulos vinculados", array('controller' => 'discipulados', 'action' => 'vinculados')); ?>
                </li>
            </ul>
        </li>
        <li>
            <a href="#OperadorD2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Discipulado 2 - Formação de Discipuladores</a>
            <ul class="collapse list-unstyled" id="OperadorD2">
                <li>
                    <?php echo $this->Html->link("Discípulos não vinculados", array('controller' => 'discipuladosd2', 'action' => 'naovinculados')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link("Discípulos vinculados", array('controller' => 'discipuladosd2', 'action' => 'vinculados')); ?>
                </li>
            </ul>
        </li>
        <li>
            <a href="#OperadorD3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Discipulado 3 - Formação de Líderes</a>
            <ul class="collapse list-unstyled" id="OperadorD3">
                <li>
                    <?php echo $this->Html->link("Discípulos não vinculados", array('controller' => 'discipuladosd3', 'action' => 'naovinculados')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link("Discípulos vinculados", array('controller' => 'discipuladosd3', 'action' => 'vinculados')); ?>
                </li>
            </ul>
        </li>
        <li>
            <a href="#OperadorD4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Discipulado 4 - Avançado</a>
            <ul class="collapse list-unstyled" id="OperadorD4">
                <li>
                    <?php echo $this->Html->link("Discípulos não vinculados", array('controller' => 'discipuladosd4', 'action' => 'naovinculados')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link("Discípulos vinculados", array('controller' => 'discipuladosd4', 'action' => 'vinculados')); ?>
                </li>
            </ul>
        <li>
            <a href="#DiscipuloSubMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Acompanhamento</a>
            <ul class="collapse list-unstyled" id="DiscipuloSubMenu">
                <li>
					<?php echo $this->Html->link("Discipuladores aprovados", array('controller' => 'discipuladores', 'action' => 'index')); ?>
                </li>
                <li>
					<?php echo $this->Html->link("Discipuladores aguardando aprovação", array('controller' => 'discipuladores', 'action' => 'discipuladoresaguardando')); ?>
                </li>
            </ul>
        </li>
        <li>
            <a href="#Relatoriosubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Relatórios</a>
            <ul class="collapse list-unstyled" id="Relatoriosubmenu">
                <li>
					<?php echo $this->Html->link("Discipulados", array('controller' => 'relatorios', 'action' => 'index')); ?>
                </li>
                <li>
					<?php echo $this->Html->link("Previsão de finalização", array('controller' => 'relatorios', 'action' => 'previsaofinalizacao')); ?>
                </li>
            </ul>
        </li>
        <li>
			<?php echo $this->Html->link("Validação Entradas", array('controller' => 'entradas', 'action' => 'listaEntradas')); ?>
        </li>
        <li>
			<?php echo $this->Html->link("Desativadas Entradas", array('controller' => 'entradas', 'action' => 'listaDesativadas')); ?>
        </li>
        
        <?php if($permissao == 'admin'){ ?>
        <li>
            <a href="#Configsubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Configurações</a>
            <ul class="collapse list-unstyled" id="Configsubmenu">
                <li>
                    <?php echo $this->Html->link("Usuários", array('controller' => 'users', 'action' => 'index')); ?>
                </li>
                <!-- <li>
                    <?php echo $this->Html->link("Redes", array('controller' => 'redes', 'action' => 'index')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link("Permissões", array('controller' => 'permissoes', 'action' => 'index')); ?>
                </li> -->
            </ul>
        </li>
        
        <?php } ?>
    </ul>
