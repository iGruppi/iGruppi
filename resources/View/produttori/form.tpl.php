<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Dati produttore</legend>
        <?php echo $this->form->renderField('ragsoc'); ?>
        <?php echo $this->form->renderField('indirizzo'); ?>
        <?php echo $this->form->renderField('comune'); ?>
        <?php echo $this->form->renderField('provincia'); ?>
        <?php echo $this->form->renderField('telefono'); ?>
        <?php echo $this->form->renderField('email'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idproduttore'); ?>

        <input type="submit" id="submit" value="SALVA" />

</form>
