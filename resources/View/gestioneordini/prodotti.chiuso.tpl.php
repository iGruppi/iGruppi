<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<p>
    Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
    Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
</p>    

<div class="row">
  <div class="col-md-8">
      
    <h3>Prodotti:</h3>
    <p><strong>La lista prodotti per quest'ordine non è modificabile</strong>.</p>
    
    <?php if(count($this->listProdotti) > 0): ?>
    <?php 
        foreach ($this->listProdotti as $idcat => $cat): ?>
        <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
    <?php foreach ($cat as $idsubcat => $prodotti): ?>
        <?php include $this->template('prodotti/subcat-title.tpl.php'); ?>
    <?php   foreach ($prodotti as $idprodotto): 
                $pObj = $this->lpObjs[$idprodotto];
            ?>

            <div class="row row-myig<?php echo ($pObj->isDisponibile()) ? "" : " box_row_dis" ; ?>">
                <div class="col-md-8">
                    <h3 class="no-margin"><?php echo $pObj->descrizione;?></h3>
                    <p>
                        Codice: <strong><?php echo $pObj->codice; ?></strong><br />
                        Prezzo: <strong><?php echo $this->valuta($pObj->getPrezzo());?></strong> / <strong><?php echo $pObj->udm; ?></strong>
                    </p>
                </div>
            </div>

          <?php endforeach; ?>
        <?php endforeach; ?>
      <?php endforeach; ?>
    <?php else: ?>
        <h3>Nessun prodotto disponibile!</h3>
    <?php endif; ?>
  </div>
  <div class="col-md-4 col-right">
    <div class="bs-sidebar" data-spy="affix" role="complementary">
      <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
    </div>
  </div>    
</div>