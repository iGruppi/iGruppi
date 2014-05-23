<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<div class="row">
  <div class="col-md-8">
    
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<?php if($this->statusObj->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
<?php endif; ?>
    
<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine, 'produttore' => $this->produttore)); ?>

<?php if(count($this->listaProdotti) > 0): ?>
    <?php foreach ($this->listaProdotti as $idprodotto => $pObj): ?>
      <div class="row row-myig<?php echo ($pObj->isDisponibile()) ? "" : " box_row_dis" ; ?>">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $pObj->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $pObj->categoria; ?></strong><br />
        <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $pObj)); ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
                <span class="menu_icon_empty" >&nbsp;</span>
                <span class="prod_qta<?php if(!$pObj->isDisponibile()){ echo "_dis"; } ?>"><?php echo $pObj->qta;?></span>
                <span class="menu_icon_empty" >&nbsp;</span>
            <?php if($pObj->isDisponibile()): ?>
                <div class="sub_totale"><?php echo $this->valuta($pObj->getTotale()) ?></div>
            <?php else: ?>
                <h4 class="non-disponibile">NON disponibile!</h4>
            <?php endif; ?>
            </div>
        </div>
      </div>
    <?php endforeach; ?>

<?php else: ?>
    <h3>Nessun prodotto ordinato o disponibile!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-4 col-right">
<?php if(count($this->listaProdotti) > 0): ?>
    <div class="bs-sidebar" data-spy="affix" data-offset-top="80" role="complementary">
        <div class="totale">
    <?php if($this->costo_spedizione > 0): ?>
            <h5>Totale ordine: <b id="totale"><?php echo $this->valuta($this->totale_ordine) ?></b></h5>
            <h5>Costo di spedizione: <b><?php echo $this->valuta($this->costo_spedizione); ?></b></h5>
    <?php endif; ?>            
            <h4>Totale: <strong><?php echo $this->valuta($this->totale_con_spedizione); ?></strong></h4>
        </div>
    </div>
<?php endif; ?>
  </div>
    
</div>