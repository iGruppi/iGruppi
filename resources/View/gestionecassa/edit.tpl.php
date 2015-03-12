<h2>Aggiungi Movimento di cassa</h2>
<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
    <fieldset class="border_top">
        <legend>Movimento per utente</legend>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('data'); ?>
        <?php echo $this->form->renderField('importo'); ?>
        <?php echo $this->form->renderField('iduser'); ?>
    </fieldset>
        <?php echo $this->form->renderField('idmovimento'); ?>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
</form>

<script>
    $(function() {
        
        $('#data').datetimepicker({
            lang:   'it',
            i18n:   { it:{ months:mesi, dayOfWeek:giorni} },
            format: 'd/m/Y H:i'
        });
    });
</script>