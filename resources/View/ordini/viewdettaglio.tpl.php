    <h3 class="text-dark"><?php 
        $categorie = $this->ordine->getListaDescrizioniCategorie();
        echo $this->arrayToString($categorie); 
    ?></h3>
<div class="row">
  <div class="col-md-8">
<?php if( count($categorie) > 0 ): ?>
    <h5><span class="text-muted">Produttori: </span> <?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></h5>
<?php endif; ?>
    <h5>Ordine <strong class="<?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></strong></h5>
    
<?php if($this->ordine->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
    </p>
<?php endif; ?>
    
<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine)); ?>

<?php 
        $prodottiUser = $this->ordCalcObj->getProdottiByIduser($this->iduser);
        if( count($prodottiUser) > 0): ?>
    <?php foreach ($prodottiUser as $idprodotto => $pObj): ?>
      <div class="row row-myig<?php echo ($pObj->isDisponibile()) ? "" : " box_row_dis" ; ?>">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $pObj->getDescrizioneListino();?></h3>
            <p>
                <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $pObj)); ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
                <span class="menu_icon_empty" >&nbsp;</span>
                <span class="prod_qta<?php if(!$pObj->isDisponibile()){ echo "_dis"; } ?>"><?php echo $pObj->getQta_ByIduser($this->iduser);?></span>
                <span class="menu_icon_empty" >&nbsp;</span>
            <?php if($pObj->isDisponibile()): ?>
                <div class="sub_totale"><?php echo $this->valuta($pObj->getTotale_ByIduser($this->iduser)) ?></div>
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
    <div class="bs-sidebar" data-spy="affix" data-offset-top="80" role="complementary">
        <div class="totale">
        <?php if($this->ordCalcObj->getSpeseExtra()->has() && $this->ordCalcObj->getTotaleByIduser($this->iduser)): ?>
            <?php foreach ($this->ordCalcObj->getSpeseExtra()->get() AS $extra): ?>
            <h5><?php echo $extra->getDescrizione(); ?>: <b><?php echo $this->valuta($extra->getParzialeByIduser($this->ordCalcObj, $this->iduser)); ?></b></h5>
            <?php endforeach; ?>
            <h5>Totale ordine: <b id="totale"><?php echo $this->valuta($this->ordCalcObj->getTotaleByIduser($this->iduser)) ?></b></h5>
        <?php endif; ?>
            
            <h4>Totale: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConExtraByIduser($this->iduser)) ?></strong></h4>
        </div>                    
    </div>
  </div>
</div>