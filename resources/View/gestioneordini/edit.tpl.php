<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>
<div class="row">
    <div class="col-md-10">
        <h3>Modifica dati ordine</h3>
        <form id="ordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

            <fieldset class="border_top">
                <legend>Validità ordine</legend>
                <?php echo $this->form->renderField('visibile'); ?>
            <?php if($this->ordine->canManageDate()): ?>
                <?php echo $this->form->renderField('data_inizio'); ?>
                <?php echo $this->form->renderField('data_fine'); ?>
            <?php endif; ?>
            </fieldset>

            <fieldset class="border_top">
                <legend>Gestione permessi</legend>
            <?php if($this->ordine->canManageCondivisione()): ?>
                <?php echo $this->form->renderField('condivisione'); ?>
                <div id="d_sharing" class="hint" style="display: block;">
                <?php foreach ($this->groups as $group):
                        if($this->ordine->getMasterGroup()->getIdGroup() != $group->idgroup): ?>
                    <p><input type="checkbox" name="groups[]" value="<?php echo $group->idgroup; ?>" 
                       <?php if($this->ordine->issetGroup($group->idgroup)) { echo "checked='checked'"; } ?> /> <b><?php echo $group->nome; ?></b></p>
                <?php   endif; 
                      endforeach; ?>
                </div>
            <?php else: ?>
                <p class="hint">Condiviso dal gruppo <strong><?php echo $this->ordine->getMasterGroup()->getGroupName(); ?></strong></p>
            <?php endif; ?>
            <?php if($this->ordine->canManageReferente()): ?>
                <?php echo $this->form->renderField('iduser_ref'); ?>
            <?php endif; ?>
            </fieldset>

            <fieldset class="border_top">
                <legend>Note</legend>
                <?php echo $this->form->renderField('note_consegna'); ?>
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
        
        // Run always to init
        setCondivisione();
        $('#condivisione').change(setCondivisione);
        
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
        
        function setCondivisione()
        {
            if($('#condivisione').val() === "SHA")
            {
                $('#d_sharing').show();
            } else {
                $('#d_sharing').hide();
            }        
        }
        
    });
</script>