<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Dati prodotto</legend>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('idcat'); ?>
        <?php echo $this->form->renderField('udm'); ?>
        <?php echo $this->form->renderField('costo'); ?>
        <?php echo $this->form->renderField('attivo'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idprodotto'); ?>
        <?php echo $this->form->renderField('idproduttore'); ?>

        <input type="submit" id="submit" value="SALVA" />

</form>
