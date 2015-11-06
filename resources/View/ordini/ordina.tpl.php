<div class="row">
<div class="col-md-8 col-sm-8">
    <?php echo $this->partial('ordini/header-title.tpl.php', array('ordine' => $this->ordine) ); ?>

<?php if($this->ordine->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
    </p>
<?php endif; ?>

<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine)); ?>
    
<div class="row row-myig" id="search_no_result" style="display: none;">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">Nessun prodotto trovato.</div>            
    </div>
</div>

<div id="search_num_result" style="display:none;">
    <h3>Trovati <strong>_</strong> prodotti</h3>
</div>

<?php 
     $categorie = $this->ordine->getProdottiWithCategoryArray();
     $arTrolley = array();
     if(count($categorie) > 0): 
        foreach ($categorie AS $cat): ?>
    <span class="categorie_hidden" id="cat_<?php echo $cat->getId(); ?>" style="visibility: hidden;"><?php echo $cat->getDescrizione(); ?></span>
<?php       foreach ($cat->getSubcat() AS $subcat): 
            echo $this->partial('prodotti/subcat-title.tpl.php', array('cat' => $cat, 'subcat' => $subcat));
                foreach ($subcat->getProdotti() AS $prodObj):
                    $prodotto = $prodObj->getProdotto(); 
                    $idprodotto = $prodotto->getIdProdotto();
                 //Zend_Debug::dump($prodotto);die;
    ?>
        
      <div class="row row-myig<?php if(!$prodotto->isDisponibile()) { echo " box_row_dis"; } ; ?>">
        <div class="col-md-9">
        <?php if($prodotto->getOffertaOrdine()): ?>
                <small><span class="label label-danger">Offerta</span></small>
        <?php endif;?>
            
            <h3 class="no-margin product_descrizione"><?php echo $prodotto->getDescrizioneAnagrafica();?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->getSubCategoria(); ?></strong><br />
        <?php if($this->ordine->isMultiproduttore()): ?>
                Produttore: <strong><?php echo $prodotto->getProduttore(); ?></strong><br />
        <?php endif; ?>                
                <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $prodotto)); ?>
        <?php if($prodotto->getNoteListino() != ""): ?>
                <a href="javascript:void(0)" class="note" data-toggle="popover" title="" data-content="<?php echo $prodotto->getNoteListino(); ?>">Visualizza note</a>
        <?php endif; ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
            <?php if($prodotto->isDisponibile()):
                    $qta_ordinata = $prodotto->getQta_ByIduser($this->iduser);
                    // Create array for Trolley
                    $arTrolley[$idprodotto] = array(
                        'idListino'     => $prodotto->getIdListino(),
                        'costoOrdine'   => $prodotto->getCostoOrdine(),
                        'moltiplicatore'=> $prodotto->getMoltiplicatore(),
                        'qtaOrdinata'   => $qta_ordinata
                    );
                ?>
                <a class="menu_icon" href="javascript:void(0)" onclick="Trolley_setQtaProdotto(<?php echo $idprodotto;?>, '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $idprodotto;?>" value="<?php echo $qta_ordinata;?>" />
                <a class="menu_icon" href="javascript:void(0)" onclick="Trolley_setQtaProdotto(<?php echo $idprodotto;?>, '-')">-</a>
                <div class="sub_totale" id="subtotale_<?php echo $idprodotto;?>">...</div>
            <?php else: ?>
                <h4 class="non-disponibile">NON disponibile!</h4>
            <?php endif; ?>
            </div>
        </div>
      </div>
        
    <?php endforeach; ?>
  <?php endforeach; ?>
<?php endforeach; ?>
      <div class="row bs-footer">
          <div class="col-md-12">&nbsp;</div>
      </div>
<?php else: ?>
    <h3>Nessun prodotto disponibile!</h3>
<?php endif; ?>
    
  </div>
  <div class="col-md-3 col-md-offset-1 col-sm-4">
    <div class="bs-sidebar" data-spy="affix" data-offset-top="80" role="complementary">
        <div class="totale">
            <h4>Totale: <strong id="totale">Loading...</strong></h4>
            <a role="button" class="btn btn-success" href="/ordini/viewdettaglio/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-list"></span> Visualizza ordine</a><br />
            <small><span id="autosave_alert" style="display: none;"></span>&nbsp;</small>
        </div>
        <input type="text" name="search_products" id="search_products" placeholder="Cerca prodotto..." oninput="searchProducts(this.value);" />
        <br />
<?php if(count($categorie) > 0): ?> 
        <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $categorie)); ?>
<?php endif; ?>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {

        // SET idordine
        Trolley.idordine = <?php echo $this->ordine->getIdOrdine();?>;
        // SET Products ordered
    <?php foreach($arTrolley AS $idprodotto => $prodotto): ?>
        Trolley.initByParams(<?php echo $idprodotto;?>, <?php echo $prodotto["idListino"]; ?>, <?php echo $prodotto["costoOrdine"];?>, <?php echo $prodotto["moltiplicatore"]; ?>, <?php echo $prodotto["qtaOrdinata"];?>);
        Trolley_rebuildPartial(<?php echo $idprodotto;?>);
    <?php endforeach; ?> 
        
        // Rebuils Totale after loading page
        Trolley_rebuildTotal();
        
        //enable POPUP
        $('.note').popover();
        
    });
</script>