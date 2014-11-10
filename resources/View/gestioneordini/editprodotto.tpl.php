<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>
<div class="row">
    <div class="col-md-8">
        <h3>Modifica prodotto: <strong><?php echo $this->prodotto->getDescrizioneListino(); ?></strong></h3>
        <p>La variazione di questi dati riguarda esclusivamente quest'ordine.</p>
        <form id="prodottoordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
            <fieldset>
                <?php echo $this->form->renderField('costo_ordine'); ?>
                <?php echo $this->form->renderField('disponibile_ordine'); ?>
            </fieldset>

            <fieldset class="border_top">
                <legend>Offerta/Sconto</legend>
                <?php echo $this->form->renderField('offerta_ordine'); ?>
                <?php echo $this->form->renderField('sconto_ordine'); ?>
            </fieldset>

                <?php echo $this->form->renderField('idprodotto'); ?>
                <?php echo $this->form->renderField('idlistino'); ?>
                <?php echo $this->form->renderField('idordine'); ?>

            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
        </form>

    </div>
    <div class="col-md-4 col-right">
        &nbsp;
    </div>    
</div>