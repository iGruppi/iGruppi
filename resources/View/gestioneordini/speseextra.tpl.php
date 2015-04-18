<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>
<h3>Modifica Spese extra</h3>
<?php if($this->ordine->canManageSpeseExtra()): ?>
<div class="row">
    <div class="col-md-8">
        <form id="form_extra_spese" action="/gestione-ordini/speseextra/idordine/<?php echo $this->ordine->getIdOrdine(); ?>" method="post" class="f1n200">

        <?php 
                $counterextra = 0;
                if($this->ordine->getSpeseExtra()->has()): ?>
            <?php foreach($this->ordine->getSpeseExtra()->get() AS $extra): 
                $counterextra++;
                ?>
            <fieldset id="fieldset_extra_<?php echo $counterextra; ?>">
                    <label for="descrizione">Descrizione:</label>
                    <input type="text" name="extra[<?php echo $counterextra; ?>][descrizione]" id="descrizione_<?php echo $counterextra; ?>" size="25" maxlength="25" required="" value="<?php echo $extra->getDescrizione(); ?>" />
                    <br />
                    <label for="descrizione">Costo (&euro;):</label>
                    <input type="text" name="extra[<?php echo $counterextra; ?>][costo]" id="costo_<?php echo $counterextra; ?>" size="10" maxlength="10" required="" value="<?php echo $extra->getCosto(); ?>" />
                    <br />
                    <label for="tipo">Tipo:</label>
                    <select name="extra[<?php echo $counterextra; ?>][tipo]" id="tipo_<?php echo $counterextra; ?>">
                        <option value="RU" <?php echo ($extra->getTipo() == "RU") ? 'selected=""' : ''; ?>>Ripartite per utente</option>
                        <option value="RI" <?php echo ($extra->getTipo() == "RI") ? 'selected=""' : ''; ?>>Ripartite per importo (TODO!)</option>
                        <option value="FU" <?php echo ($extra->getTipo() == "FU") ? 'selected=""' : ''; ?>>Fisse per ogni utente (TODO!)</option>
                    </select>
                    <hr />
            </fieldset>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no_extra_row">Nessuna Spesa extra inserita per quest'ordine.</p>
        <?php endif; ?>

            <fieldset id="extra_row">
                <label for="descrizione">Descrizione:</label>
                <input type="text" name="" id="descrizione" size="25" maxlength="100" required="" disabled="" />
                <br />
                <label for="descrizione">Costo (&euro;):</label>
                <input type="text" name="" id="costo" size="10" maxlength="10" required="" disabled="" />
                <br />
                <label for="tipo">Tipo:</label>
                <select name="" id="tipo" disabled="">
                    <option value="RU" selected="">Ripartite per utente</option>
                    <option value="RI">Ripartite per importo (TODO!)</option>
                    <option value="FU">Fisse per ogni utente (TODO!)</option>
                </select>
                <hr />
            </fieldset>
            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

        </form>
    </div>
    <div class="col-md-4 col-right">
        <a id="add_extra_row" class="btn btn-default btn-mylg" href="#"><span class="glyphicon glyphicon-plus"></span> Aggiungi Spesa Extra</a>
    </div>    
</div>
<script>
$(document).ready(function () {
    var counter = <?php echo $counterextra; ?>;
    $('#extra_row').hide();
    $('#add_extra_row').on('click',
        function ()
        {
            // remove P useless
            if(counter === 0) {
                $('#no_extra_row').remove();
            }
            // increment counter
            counter++;
            // create a new fieldset ID
            var newID = 'fieldset_extra_' + counter;
            // clone my row
            var newRow = $('#extra_row').clone().attr('id', newID);
            // append the new fieldset to the form
            newRow.prependTo( $('#form_extra_spese') ).show();
            
            // set attributes to the new element
            $('#'+newID+' #descrizione').attr('name', 'extra['+counter+'][descrizione]').attr('id', 'descrizione_'+counter).prop('disabled', false);
            $('#'+newID+' #costo').attr('name', 'extra['+counter+'][costo]').attr('id', 'costo_'+counter).prop('disabled', false);
            $('#'+newID+' #tipo').attr('name', 'extra['+counter+'][tipo]').attr('id', 'tipo_'+counter).prop('disabled', false);
        }
    );
});
</script>
<?php else: ?>
    <p>Non è possibile gestire le Spesa extra in questo stato.</p>
<?php endif; ?>
