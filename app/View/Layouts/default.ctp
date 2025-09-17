<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title> PIB - Central integradora</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="noindex">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
	<?php
		echo $this->Html->meta('icon','icon.png');

        echo $this->Html->css('sistema');
        echo $this->Html->css('geral');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Html->script('regular');
		echo $this->Html->script('googlecharts');
		echo $this->Html->script('jquery.maskedinput');
        echo $this->Html->script('sweetalert.min');
        echo $this->Html->script('jquery.table2excel');
	?>

    <!-- Font Awesome JS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    
    
    <!--datatables-->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <!-- Sweetalert-->
    
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    
    <!-- Bootstrap Toggle -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <?php
                    if($user['User']['image'] == null){
                        echo '<i class="fas fa-user imagemUsuarioMenuIcone"></i>';
                    }else{
                        echo "imagem do usuário";
                    }
                        echo "<span class='nomeUsuarioMenu'>".$user['User']['nome']." <i class='fas fa-cog'></i></span>";
                ?>
            </div>
            <?php echo $this->element('Menus/index'); ?>
        </nav>
        <!-- Page Content -->
        <div class="container-fluid" id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-default">
                        <i class="fas fa-bars menu-hamburguer"></i>
                        <span id=""></span>
                    </button>
                    <?php
                        echo $this->Form->create(false, array(
                            'url' => array('controller' => 'buscas', 'action' => 'index'),
                            'type' => 'get',
                            'class' => 'form-inline'
                        ));
                    ?>
                        <div class="form-group">
                            <input type="text" name='nome' class="form-control" placeholder="Buscar discípulo" value="<?php echo $this->params['url']['nome']; ?>">
                        </div>
                        <button type="submit" class="btn btn-default">Pesquisar</button>
                    </form>
                    <?php
                        echo $this->Html->link( 
                            $this->Html->tag('i', '',
                                array(
                                    'class' => 'fas fa-sign-out-alt icone-sair',
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'bottom',
                                    'title' => 'Sair do sistema?'
                                )
                            ).
                            " Sair",
                            array(
                                'controller' => 'Users',
                                'action' => 'logout'
                            ),
                            array(
                                'escape' => false
                            )
                        );
                    ?>

                </div>
            </nav>
            <?php echo $this->Flash->render(); ?>

            <?php echo $this->fetch('content'); ?>

            <?php echo $this->element('sql_dump'); ?>
        </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            /*$('#sidebarCollapse span').text('Fechar menu');*/
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                if($("#sidebar").hasClass("active")){
                    $('#sidebarCollapse span').text('');
                }else{
                    $('#sidebarCollapse span').text('');
                }
            });

         


            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })


        });
    </script>

</body>
</html>
