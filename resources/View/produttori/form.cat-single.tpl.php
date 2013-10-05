    <div id="subcat_<?php echo $this->subCat["idsubcat"]; ?>">
        <select name="arSubCat[<?php echo $this->subCat["idsubcat"]; ?>][idcat]">
        <?php foreach($this->categorie AS $idcat => $descrizione): ?>
            <option value="<?php echo $idcat; ?>" <?php if($idcat == $this->subCat["idcat"]) { echo " selected"; } ?>><?php echo $descrizione; ?></option>
        <?php endforeach; ?>
        </select>
        <input type="input" name="arSubCat[<?php echo $this->subCat["idsubcat"]; ?>][descrizione]" size="40" value="<?php echo $this->subCat["descrizione"]; ?>">
        <a class="btn btn-danger btn-sm btn-inform" href="javascript:void(0)" onclick="jx_DelSubCategoria(<?php echo $this->subCat["idsubcat"]; ?>)"><span class="glyphicon glyphicon-trash"></span> Rimuovi</a>
        <br />
    </div>
