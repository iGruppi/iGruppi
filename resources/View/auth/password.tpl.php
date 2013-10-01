<h1>Recupero password</h1>
<?php if( $this->sent !== false): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <b>Nuova password inviata con successo!</b>
    </div>
<?php else: ?>
    <?php if( $this->errorPwd !== false): ?>
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <b><?php echo $this->errorPwd; ?></b>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <form id="loginForm" action="/auth/password" method="post" class="f1n150">
                <fieldset>
                    <label for="email">Email:</label><input type="input" name="email" id="email" size="40" maxlength="100"><br>
                </fieldset>
                <button type="submit" id="submit" class="btn btn-success btn-mylg">Invia NUOVA password</button>
            </form>
        </div>
    </div>
<?php endif; ?>