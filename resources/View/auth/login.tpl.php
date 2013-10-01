<h1>Login</h1>
<?php if( $this->errorLogin !== false): ?>
<h2 class="alert"><?php echo $this->errorLogin; ?></h2>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <form id="loginForm" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n150">
            <fieldset>
                <?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('password'); ?>
            </fieldset>
            <button type="submit" id="submit" class="btn btn-success btn-mylg">LOGIN</button>
            <a class="btn btn-default btn-mylg" href="/auth/password">Password dimenticata?</a>
        </form>
    </div>
</div>