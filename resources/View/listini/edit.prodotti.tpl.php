<?php if($this->listino->getProdotti()->count() > 0): ?>
    
    <div class="hint_50" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="alert alert-info" role="alert"><strong id="chk_num_Y">0</strong> su <strong id="chk_num_ALL">0</strong> prodotti selezionati per questo Listino<br />
            <input type="checkbox" id="check_all" onchange="setAllProdotti();"/> Seleziona tutti
        </div>
    </div>
<?php foreach ($this->listino->getAllCategorie()->getChildren() AS $cat):
        foreach ($cat->getChildren() AS $subcat): ?>
    <fieldset class='border_top'>
        <legend><?php echo $cat->getDescrizione() ." : ". $subcat->getDescrizione(); ?></legend>
<?php     // get Prodotti List in this Idsubcat
          foreach ($subcat->getChildren() AS $prodotto): 
              $pObj = $this->listino->getProdotti()->getProdottoById($prodotto->getId());
              // Check if prodotto is in LISTINO
              if($pObj->isInListino()):
?>
        <p id="row_prod_<?php echo $pObj->getIdProdotto(); ?>" class="hint_50">
            <button type="button" class="btn btn-success btn-xs">Modifica</button>
            <input type="checkbox" class="checkbox_prodotto" onchange="checkMe(this);" name="prodotti[]" value="<?php echo $pObj->getIdProdotto(); ?>" <?php echo $pObj->getAttivoListino() ? "checked=''" : ""; ?> /> 
            <?php echo $pObj->getDescrizioneListino(); ?>
        </p>
<?php         else: ?>
        <p class="hint_50 text-danger">
            <button type="button" class="btn btn-danger btn-xs">&nbsp;Importa</button> 
            <input type="checkbox" disabled="" /> <?php echo $pObj->getDescrizione(); ?>
        </p>
<?php         endif; ?>
<?php     endforeach; ?>
    </fieldset>
<?php   endforeach; ?>
<?php endforeach; ?>

<?php else: ?>
    <p>Nessun prodotto disponibile in questo listino.</p>
<?php endif; ?>

<script>

    // set default numbers values
    var num_checked_Y = 0;
    var num_checked_ALL = <?php echo $this->listino->getProdotti()->count(); ?>;
    
    $(function() {
        // call checkMe for every checkbox
        $('.checkbox_prodotto').each(function() {
            checkMe_ChangeClass(this);
        });
        // set Numbers
        resetCheckNumbers();
    });
    
    
    function setAllProdotti()
    {
        $('.checkbox_prodotto').each(function() {
           this.checked = $('#check_all').is(':checked');
           checkMe_ChangeClass(this);
        });
        resetCheckNumbers();
    }
    
    function checkMe_ChangeClass(chk)
    {
        $('#row_prod_' + $(chk).val()).toggleClass('text-muted', !$(chk).is(':checked'));
    }
    
    function checkMe(chk)
    {
        checkMe_ChangeClass(chk);
        if($(chk).is(':checked')) {
            num_checked_Y++;
        } else {
            num_checked_Y--;
        }
        setCheckNumbers();
    }
    
    function resetCheckNumbers()
    {
        num_checked_Y = 0;
        $('.checkbox_prodotto').each(function() {
            if(this.checked) {
                num_checked_Y++;
            }
        });
        setCheckNumbers();
    }
    
    function setCheckNumbers()
    {
        $('#chk_num_ALL').html(num_checked_ALL);
        $('#chk_num_Y').html(num_checked_Y);
    }
        
    
</script>