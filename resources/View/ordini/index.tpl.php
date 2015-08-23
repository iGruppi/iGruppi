<h2>Elenco Ordini</h2>

<div class="row">
  <div class="col-md-8 col-sm-8">
      
<?php if(count($this->ordini) > 0): ?>
    <?php foreach ($this->ordini as $key => $ordine): 
        $status = $ordine->getStateName();
        ?>
      <div class="row row-myig">
        <div class="col-md-12 col-sm-12">
            <h3 class="no-margin text-dark"><?php 
                $categorie = $ordine->getListaDescrizioniCategorie();
                echo $this->arrayToString($categorie); 
            ?></h3>
        </div>
        <div class="col-md-8 col-sm-8">
        <?php if( count($categorie) > 0 ): ?>
            <h5><span class="text-muted">Produttori: </span> <?php echo $this->arrayToString( $ordine->getProduttoriList() ); ?></h5>
        <?php endif; ?>
        
        <?php if($ordine->is_Pianificato()): ?>
            <h4 class="ordine">
                Ordine <span class="<?php echo $ordine->getStatusCSSClass(); ?>"><?php echo $ordine->getStateName(); ?></span>
                per il <strong><?php echo $this->date($ordine->getDataInizio(), '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->is_Aperto()): ?>
            <h4 class="ordine">Ordine <strong class="<?php echo $ordine->getStatusCSSClass(); ?>"><?php echo $ordine->getStateName(); ?></strong></h4>            
            <p class="no-margin">Chiusura prevista il <strong><?php echo $this->date($ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->getDataFine(), '%H:%M');?></p>
        <?php elseif($ordine->is_Chiuso()): ?>
            <h4 class="ordine">
                Ordine <span class="<?php echo $ordine->getStatusCSSClass(); ?>"><?php echo $ordine->getStateName(); ?></span>
                il <strong><?php echo $this->date($ordine->getDataFine(), '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->is_Archiviato()): ?>
            <h4 class="ordine">Ordine <span class="<?php echo $ordine->getStatusCSSClass(); ?>"><?php echo $ordine->getStateName(); ?></span></h4>            
            <p class="no-margin">
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->getDataInizio(), '%H:%M');?><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->getDataFine(), '%H:%M');?>
            </p>
        <?php endif; ?>
        </div>
        <div class="col-md-4  col-sm-4">
        <?php if($ordine->canUser_OrderProducts()): ?>
            <a href="/ordini/ordina/idordine/<?php echo $ordine->getIdOrdine();?>" class="btn btn-success" style="margin-bottom: 5px;"><span class="glyphicon glyphicon-shopping-cart"></span> Ordina ora!</a>
        <?php endif; ?>
        <?php if($ordine->canUser_ViewDettaglio()): ?>
            <a href="/ordini/viewdettaglio/idordine/<?php echo $ordine->getIdOrdine();?>" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Visualizza dettaglio</a>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine disponibile con questi filtri di ricerca.</h3>
<?php endif; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-md-offset-1 col-sm-offset-1">
      <?php include $this->template('ordini/left-menu.tpl.php'); ?>      
  </div>    
</div>