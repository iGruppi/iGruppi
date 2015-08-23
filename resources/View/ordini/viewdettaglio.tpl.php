
<div class="row">
  <div class="col-md-8">
<?php echo $this->partial('ordini/header-title.tpl.php', array('ordine' => $this->ordine) ); ?>      
    <p>
<?php if($this->ordine->is_Aperto()): ?>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
<?php else: ?>
        Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong>
<?php endif; ?>
    </p>
    
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
    <h3>Nessun prodotto ordinato.</h3>
<?php endif; ?>
  </div>
  <div class="col-md-3 col-md-offset-1">
    <div class="bs-sidebar" data-spy="affix" data-offset-top="80" role="complementary">
        <div class="totale">
<?php if($this->ordine->is_Aperto()): ?>
            <h5>Totale ordine: <b id="totale"><?php echo $this->valuta($this->ordCalcObj->getTotaleByIduser($this->iduser)) ?></b></h5>
            <a role="button" class="btn btn-success" href="/ordini/ordina/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-arrow-left"></span> Continua ad ordinare</a>
<?php else: ?>
            <h4>Totale: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConExtraByIduser($this->iduser)) ?></strong></h4>
            <h5>Totale ordine: <b id="totale"><?php echo $this->valuta($this->ordCalcObj->getTotaleByIduser($this->iduser)) ?></b></h5>
        <?php $extraArray = $this->ordCalcObj->getSpeseExtra_Utente($this->iduser);
            if(count($extraArray) > 0): ?>
            <h5><b>Spese Extra</b></h5>
            <?php foreach ($extraArray AS $extra): ?>
                <h5><?php echo $extra["descrizione"]; ?> (<em><?php echo $extra["descrizioneTipo"]; ?></em>): <strong><?php echo $this->valuta($extra["parziale_utente"]); ?></strong></h5>
            <?php endforeach; ?>
        <?php endif; ?>            
            
<?php endif; ?>
            
        </div>                    
    </div>
  </div>
</div>