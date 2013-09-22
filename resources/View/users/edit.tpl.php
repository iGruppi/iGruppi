<h2>Modifica Utente</h2>
<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Dati utente</legend>
        <?php echo $this->form->renderField('nome'); ?>
        <?php echo $this->form->renderField('cognome'); ?>
        <?php echo $this->form->renderField('email'); ?>
        <?php echo $this->form->renderField('num_members'); ?>
    </fieldset>
    <fieldset class="border_top">
        <legend>Impostazioni utente</legend>
        <?php echo $this->form->renderField('fondatore'); ?>
        <?php echo $this->form->renderField('attivo'); ?>
        <?php echo $this->form->renderField('contabile'); ?>
    </fieldset>
        <?php echo $this->form->renderField('iduser'); ?>

    <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>