      <fieldset>
        <label for="idcat">Aggiungi Categoria:</label>
        <select name="idcat" id="idcat" onchange="$('#btn_add_subCat').show()">
            <option value="0" selected="">Seleziona...</option>
        <?php foreach($this->categorie AS $idcat => $descrizione): ?>
            <option value="<?php echo $idcat; ?>"><?php echo $descrizione; ?></option>
        <?php endforeach; ?>
        </select>
        <a id="btn_add_subCat" class="btn btn-info btn-sm btn-inform" style="display: none;" href="javascript:void(0)" onclick="jx_AddSubCategoria(<?php echo $this->produttore->idproduttore; ?>)"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
      </fieldset>
      <fieldset class="border_top">
        <div id="d_subCat">
        <?php if(count($this->arSubCat) > 0): ?>
            <?php foreach($this->arSubCat AS $subCat): ?>
                <?php echo $this->partial('produttori/form.cat-single.tpl.php', array('subCat' => $subCat, 'categorie' => $this->categorie) ); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no_subCat">Nessuna categoria selezionata!</p>
        <?php endif; ?>
        </div>
      </fieldset>