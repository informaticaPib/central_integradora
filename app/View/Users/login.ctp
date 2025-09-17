<div class="wrapper fadeInDown">
    <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
        <div class="fadeIn first">
            <?php echo $this->Html->image('central-integradora.png', array('id' => 'icon', 'alt' => 'Ícone central integradora')); ?>
        </div>

        <!-- Login Form -->
        <?php echo $this->Form->create('User', array('role' => 'login'));?>
            <?php echo $this->Form->input('username', array('label' => false,'placeholder'=> 'login', 'class' => 'fadeIn second campo-login', 'required', 'required')); ?>
            <?php echo $this->Form->input('password', array('label' => false,'placeholder'=> 'Senha', 'class' => 'fadeIn third campo-login', 'required', 'required')); ?>
            <input type="submit" class="fadeIn fourth" value="Acessar sistema">
        <?php echo $this->Form->end();?>

        <!-- Remind Passowrd
        <div id="formFooter">
            <a class="underlineHover" href="#">Esqueceu sua senha?</a>
        </div>
        -->
    </div>
</div>