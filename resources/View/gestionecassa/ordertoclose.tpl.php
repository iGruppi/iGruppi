<h2>Ordini in attesa di chiusura</h2>

<div class="row" id="listwithsidebar">
  <div class="col-md-9">
      
<?php if(count($this->ordini) > 0): ?>
    <?php foreach ($this->ordini as $key => $ordine): ?>
      <div id="ordine_<?php echo $ordine->getIdOrdine();?>">
        <div class="row-myig">
            <div class="row">
              <div class="col-md-12">
                <?php echo $this->partial('gestioneordini/header-title.tpl.php', array('ordine' => $ordine) ); ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                  <p>
                      <em>Apertura</em>: <strong><?php echo $this->date($ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->getDataInizio(), '%H:%M');?></strong><br />
                      <em>Chiusura</em>: <strong><?php echo $this->date($ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->getDataFine(), '%H:%M');?></strong>
                  </p>
              </div>
              <div class="col-md-4">
        <?php if($ordine->canArchiviaOrdine()): ?>
                  <a id="a_mso" class="btn btn-success" role="button" href="/gestione-cassa/viewdettaglio/idordine/<?php echo $ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-ok"></span> Visualizza e Archivia</a>
        <?php else: ?>
                  <a id="a_mso" class="btn btn-default" role="button" href="/gestione-cassa/viewdettaglio/idordine/<?php echo $ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-th-list"></span> Visualizza dettaglio</a>
        <?php endif; ?>
              </div>
            </div>
        </div>
      </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine da archiviare.</h3>
<?php endif; ?>
  </div>
  <div class="col-md-2 col-md-offset-1 leftbar">
      &nbsp;
  </div>
  
</div>
