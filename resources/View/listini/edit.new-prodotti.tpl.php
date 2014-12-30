<?php if($this->listino->countProdotti() > 0): ?>
    <fieldset>
    <p class="hint_50">
        Nuovi prodotti disponibili:
    </p>
<?php foreach ($this->listino->getProdottiWithCategoryArray() AS $cat):
        foreach ($cat->getSubcat() AS $subcat): ?>
<?php     // get Prodotti List in this Idsubcat
          foreach ($subcat->getProdotti() AS $prodotto): 
              $pObj = $prodotto->getProdotto(); 
              // Check if prodotto is in LISTINO
              if(!$pObj->isInListino()):
?>
        <p class="hint_50 text-danger">
            <button type="button" class="btn btn-danger btn-xs">&nbsp;Importa</button> 
            <?php echo $pObj->getDescrizioneAnagrafica(); ?>
        </p>
<?php         endif; ?>
<?php     endforeach; ?>
<?php   endforeach; ?>
<?php endforeach; ?>
    </fieldset>
<?php else: ?>
    <p>Nessun prodotto disponibile in questo listino.</p>
<?php endif; ?>
