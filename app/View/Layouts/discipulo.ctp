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
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
	
	<?php
		echo $this->Html->meta('icon','icon.png');
		
		echo $this->Html->css('sistema');
		echo $this->Html->css('geral');
		echo $this->Html->css('bootstrap-datepicker3.css');
		echo $this->Html->css('bootstrap-datepicker3.min.css');
		
		echo $this->Html->css('select2.min.css');
		
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		echo $this->Html->script('regular');
		echo $this->Html->script('googlecharts');		

		
	?>
	
	<!-- Font Awesome JS -->
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
	
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
	
	<!-- jQuery CDN - Slim version (=without AJAX) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- Popper.JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
	<!-- Bootstrap JS -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
	
	
	<!--datatables-->
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	
	<!-- Sweetalert-->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
	<!-- Bootstrap Toggle -->
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<?php

		echo $this->Html->script('bootstrap-datepicker.min');
		echo $this->Html->script('bootstrap-datepicker');
		echo $this->Html->script('bootstrap-datepicker.pt-BR.min');
		
		echo $this->Html->script('jquery.autocomplete');
		echo $this->Html->script('select2.full.min');
	?>

</head>
<body>
<div class="wrapper">
	<!-- Page Content -->
	<div class="container-fluid" id="content">
		<?php echo $this->Flash->render(); ?>
		
		<?php echo $this->fetch('content'); ?>
		
		<?php echo $this->element('sql_dump'); ?>
	</div>

</div>

</body>
</html>
