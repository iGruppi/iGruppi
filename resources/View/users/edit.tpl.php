<h2>Modifica Utente</h2>
<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Dati utente</legend>
        <?php echo $this->form->renderField('nome'); ?>
        <?php echo $this->form->renderField('cognome'); ?>
        <?php echo $this->form->renderField('email'); ?>
        <?php echo $this->form->renderField('attivo'); ?>
    </fieldset>

        <?php echo $this->form->renderField('iduser'); ?>

        <input type="submit" id="submit" value="SALVA" />

</form>