<h2>Aggiungi Prodotto per <strong><?php echo $this->produttore->ragsoc; ?></strong></h2>
<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
    <fieldset class="border_top">
        <legend>Classificazione prodotto</legend>
        <?php echo $this->form->renderField('codice'); ?>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('idsubcat'); ?>
    </fieldset>
        <?php echo $this->form->renderField('idproduttore'); ?>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
</form>