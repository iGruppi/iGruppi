<?php if($this->listino->countProdotti() > 0): ?>
    
    <div class="hint_50" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="alert alert-info" role="alert"><strong id="chk_num_Y">0</strong> su <strong id="chk_num_ALL">0</strong> prodotti selezionati per questo Listino<br />
            <input type="checkbox" id="check_all" onchange="setAllProdotti();"/> Seleziona tutti
        </div>
    </div>
<?php 
    $availableProducts = 0;
    foreach ($this->listino->getProdottiWithCategoryArray() AS $cat):
        foreach ($cat->getSubcat() AS $subcat): ?>
    <fieldset class='border_top'>
        <legend><?php echo $cat->getDescrizione() ." : ". $subcat->getDescrizione(); ?></legend>
        <dl class="hint_50">
<?php     // get Prodotti List in this Idsubcat
          foreach ($subcat->getProdotti() AS $prodotto): 
              $pObj = $prodotto->getProdotto(); 
              // Check if prodotto is in LISTINO
              if($pObj->isInListino()):
                  $availableProducts++;
?>
            <dt><input type="checkbox" id="chk_prod_<?php echo $pObj->getIdProdotto(); ?>" class="checkbox_prodotto" onchange="checkMe(<?php echo $pObj->getIdProdotto(); ?>);" name="prodotti[<?php echo $pObj->getIdProdotto(); ?>][attivo]" value="S" <?php echo $this->yesnoToBool($pObj->getAttivoListino()) ? "checked=''" : ""; ?> /> <?php echo $pObj->getDescrizioneListino(); ?></dt>
            <dd>
                <input type="input" class="is_Number" required size="5" name="prodotti[<?php echo $pObj->getIdProdotto(); ?>][costo]" value="<?php echo $pObj->getCosto(); ?>" onkeyup="this.formatNumber()" /> <?php echo $pObj->getUdmDescrizione();?>
            <?php if($pObj->hasPezzatura()): ?>
                <small>Taglio/Pezzatura: <strong><?php echo $pObj->getDescrizionePezzatura(); ?></strong></small>
            <?php endif; ?>
                </td>
            </dd>                    
<?php         endif; ?>
<?php     endforeach; ?>
        </dl>        
    </fieldset>
<?php   endforeach; ?>
<?php endforeach; ?>

<?php else: ?>
    <p>Nessun prodotto disponibile in questo listino.</p>
<?php endif; ?>

<script>

    // set default numbers values
    var num_checked_Y = 0;
    var num_checked_ALL = <?php echo $availableProducts; ?>;
    
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
    
    function checkMe_ChangeClass(idprodotto)
    {
        $('#row_prod_' + idprodotto).toggleClass('danger', !$('#chk_prod_' + idprodotto).is(':checked'));
    }
    
    function checkMe(idprodotto)
    {
        var chk = '#chk_prod_' + idprodotto;
        checkMe_ChangeClass(idprodotto);
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