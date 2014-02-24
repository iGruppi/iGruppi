<form id="ordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Validità ordine</legend>
        <?php echo $this->form->renderField('data_inizio'); ?>
        <?php echo $this->form->renderField('data_fine'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note_consegna'); ?>
    </fieldset>

    <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>
<script>
    $(function() {
        $('#data_inizio').datetimepicker({
            lang:   'it',
            i18n:   { it:{ months:mesi, dayOfWeek:giorni} },
            format: 'd/m/Y H:i',
            onShow: function( ct ){
                if($('#data_fine').val())
                {
                    this.setOptions({ 
                        maxDate: moment($('#data_fine').val(), "DD/MM/YYYY HH:mm").subtract('days', 1).format("YYYY/MM/DD")
                    })
                }
            }
        });
        
        $('#data_fine').datetimepicker({
            lang:'it',
            i18n:{ it:{ months:mesi, dayOfWeek:giorni} },
            format:'d/m/Y H:i',
            onShow:function( ct ){
                if($('#data_inizio').val())
                {
                    this.setOptions({ 
                        minDate: moment($('#data_inizio').val(), "DD/MM/YYYY HH:mm").add('days', 1).format("YYYY/MM/DD")
                    })
                }
              }
        });
    });
</script>