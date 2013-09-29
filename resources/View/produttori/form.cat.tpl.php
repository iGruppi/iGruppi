      <fieldset>
        <label for="idcat">Aggiungi Categoria:</label>
        <select name="idcat" id="idcat" onchange="$('#btn_add_subCat').show()">
            <option value="0" selected="">Seleziona...</option>
        <?php foreach($this->categorie AS $idcat => $descrizione): ?>
            <option value="<?php echo $idcat; ?>"><?php echo $descrizione; ?></option>
        <?php endforeach; ?>
        </select>
        <a id="btn_add_subCat" class="menu_icon_btn" style="display: none;" href="javascript:void(0)" onclick="jx_AddSubCategoria(<?php echo $this->produttore->idproduttore; ?>)">Aggiungi</a>
      </fieldset>
      <fieldset class="border_top">
        <div id="d_subCat">
        <?php if(count($this->arSubCat) > 0): ?>
            <?php foreach($this->arSubCat AS $subCat): ?>
                <div id="subcat_<?php echo $subCat["idsubcat"]; ?>">
                    <label><?php echo $subCat["cat_descrizione"]; ?>:</label>
                    <input type="input" name="arSubCat[<?php echo $subCat["idsubcat"]; ?>]" size="40" value="<?php echo $subCat["descrizione"]; ?>">
                    <a class="menu_icon_btn" href="javascript:void(0)" onclick="jx_DelSubCategoria(<?php echo $subCat["idsubcat"]; ?>)">Rimuovi</a>
                    <br />
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no_subCat">Nessuna categoria selezionata!</p>
        <?php endif; ?>
        </div>
      </fieldset>