<h1>Registrazione</h1>

<?php if( $this->added === true): ?>
<h2>La tua iscrizione è avvenuta con successo!</h2>
<h3>Presto il tuo account verrà abilitato e avrai accesso a tutte le funzionalità.<br />Grazie!</h3>
<?php else: ?>
<form id="loginForm" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n150">

    <fieldset class="border_top">
        <?php echo $this->form->renderField('nome'); ?>
        <?php echo $this->form->renderField('cognome'); ?>
        <?php echo $this->form->renderField('email'); ?>
        <?php echo $this->form->renderField('password'); ?>
        <?php echo $this->form->renderField('password2'); ?>
    </fieldset>
    <fieldset class="border_top">
        <?php echo $this->form->renderField('idgroup'); ?>
    </fieldset>

        <input type="submit" id="submit" value="LOGIN" />
</form>
<?php endif; ?>
