<h2>Nuovo ordine</h2>
<div class="row">
    <div class="col-md-10">

        <form id="ordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

            <fieldset class="border_top">
                <legend>Validità ordine</legend>
                <?php echo $this->form->renderField('data_inizio'); ?>
                <?php echo $this->form->renderField('data_fine'); ?>
            </fieldset>
            
            <fieldset class="border_top">
                <legend>Listini</legend>
<?php if(count($this->listini) > 0): ?>
    <?php foreach($this->listini AS $listino): ?>
                <div class="row">
                    <div class="col-md-2">&nbsp;</div>
                    <div class="col-md-1">
                    <input type="checkbox" value="<?php echo $listino->getIdListino(); ?>" name="listini[]">
                    </div>
                    <div class="col-md-7">
                        <h4><?php echo $listino->getDescrizione(); ?></h4>
                        <h5>
                            Creato da <strong><?php echo $listino->getMasterGroup()->getRefNome(); ?></strong> (<?php echo $listino->getMasterGroup()->getGroupName(); ?>)<br />
                            Ultimo aggiornamento: <strong>...</strong><br />
                        </h5>
                    </div>
                    <div class="col-md-2">&nbsp;</div>
                </div>
                <hr />
    <?php endforeach; ?>
<?php endif; ?>         
            
            </fieldset>
            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

        </form>
    </div>
    <div class="col-md-2 col-right">
        &nbsp;
    </div>    
</div>     

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