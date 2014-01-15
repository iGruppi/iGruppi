<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Dati prodotto</legend>
        <?php echo $this->form->renderField('codice'); ?>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('idsubcat'); ?>
        <?php echo $this->form->renderField('attivo'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Dati prodotto</legend>
        <?php echo $this->form->renderField('costo'); ?>
        <?php echo $this->form->renderField('aliquota_iva'); ?>
        <?php echo $this->form->renderField('udm'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idprodotto'); ?>
        <?php echo $this->form->renderField('idproduttore'); ?>

        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>
