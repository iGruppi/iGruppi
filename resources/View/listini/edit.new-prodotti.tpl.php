<?php if($this->listino->countProdotti() > 0): ?>
    <fieldset>
    <div class="hint_50">
        <h4>Nuovi prodotti disponibili in Anagrafica:</h4>
        <ul class="list-unstyled">        
<?php foreach ($this->listino->getProdottiWithCategoryArray() AS $cat):
        foreach ($cat->getSubcat() AS $subcat): ?>
<?php     // get Prodotti List in this Idsubcat
          foreach ($subcat->getProdotti() AS $prodotto): 
              $pObj = $prodotto->getProdotto(); 
              // Check if prodotto is in LISTINO
              if(!$pObj->isInListino()):
?>
        <li id="li_import_<?php echo $this->listino->getIdListino(); ?>_<?php echo $pObj->getIdprodotto(); ?>">
            <button type="button" class="btn btn-danger btn-xs" 
                    data-loading-text="Importazione..."
                    onclick="importProdottoToListino(<?php echo $this->listino->getIdListino(); ?>, <?php echo $pObj->getIdprodotto(); ?>)">
                Importa</button> 
            <?php echo $pObj->getDescrizioneAnagrafica(); ?>
        </li>
<?php         endif; ?>
<?php     endforeach; ?>
<?php   endforeach; ?>
<?php endforeach; ?>
        </ul>
    </div>
    </fieldset>
<?php else: ?>
    <p>Nessun prodotto disponibile in questo listino.</p>
<?php endif; ?>
