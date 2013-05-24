<h1>Login</h1>

<?php if( $this->errorLogin !== false): ?>
<h2 class="alert"><?php echo $this->errorLogin; ?></h2>
<?php endif; ?>

<form id="loginForm" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n150">

    <fieldset class="border_top">
        <?php echo $this->form->renderField('email'); ?>
        <?php echo $this->form->renderField('password'); ?>
    </fieldset>

        <input type="submit" id="submit" value="LOGIN" />

</form>
