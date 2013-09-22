<form id="ordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Validità ordine</legend>
        <?php echo $this->form->renderField('data_inizio'); ?>
        <?php echo $this->form->renderField('data_fine'); ?>
        <?php echo $this->form->renderField('archiviato'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note_consegna'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idgroup'); ?>
        <?php echo $this->form->renderField('idproduttore'); ?>

        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>
<script>
    $(function() {
        $( "#data_inizio" ).datepicker({ dateFormat: "dd/mm/yy", 
            onClose: function( selectedDate ) {
                $( "#data_fine" ).datepicker( "option", "minDate", selectedDate );
            } 
        });
        $( "#data_fine" ).datepicker({ dateFormat: "dd/mm/yy", 
            onClose: function( selectedDate ) {
                $( "#data_inizio" ).datepicker( "option", "maxDate", selectedDate );
            } 
        });
    });
</script>