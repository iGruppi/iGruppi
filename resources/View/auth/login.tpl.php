<h1>Login</h1>
<?php if( $this->errorLogin !== false): ?>
<div class="row">
  <div class="col-md-8">
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->errorLogin; ?>       
        </div>
  </div>
</div>
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