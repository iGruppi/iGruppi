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
        <legend>Categorie prodotti</legend>
        <label for="idcat">Aggiungi Categoria:</label>
        <select name="idcat" id="idcat" onchange="$('#btn_add_subCat').show()">
            <option value="0" selected="">Seleziona...</option>
        <?php foreach($this->categorie AS $idcat => $descrizione): ?>
            <option value="<?php echo $idcat; ?>"><?php echo $descrizione; ?></option>
        <?php endforeach; ?>
        </select>
        <a id="btn_add_subCat" class="menu_icon_btn" style="display: none;" href="javascript:void(0)" onclick="jx_AddSubCategoria()">Aggiungi</a>
        <div id="d_subCat" class="hint">
        <?php if(count($this->arSubCat) > 0): ?>
            <?php foreach($this->arSubCat AS $idcat => $arCat): ?>
                <?php foreach($arCat AS $idsubcat => $subCat): ?>
                <div>
                    <input type="input" name="arSubCat[<?php echo $idcat; ?>][<?php echo $idsubcat; ?>]" size="40" value="<?php echo $subCat; ?>"> 
                    <a class="menu_icon_btn" href="javascript:void(0)" onclick="$(this).parent().remove()">Rimuovi</a>
                </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no_subCat">Nessuna categoria selezionata!</p>
        <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idproduttore'); ?>

        <input type="submit" id="submit" value="SALVA" />

</form>
