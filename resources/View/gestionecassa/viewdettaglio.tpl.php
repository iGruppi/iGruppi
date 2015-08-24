<h2>Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong></h2>
<h3>Produttori <strong><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></h3>
<div class="row">
  <div class="col-md-12">
    <h4 class="ordine <?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></h3>
    <p>
        <em>Apertura</em>: <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></strong><br />
        <em>Chiusura</em>: <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong><br />
    </p>
  </div>
</div>

<h3 class="big-margin-top">Riepilogo per utente</h3>
<?php if($this->ordCalcoli->getProdottiUtenti() > 0): ?>
<div class="row">
  <div class="col-md-8">
        <table class="table table-condensed">
            <thead>
              <tr>
                <th>Utente</th>
                <th class="text-right">Totale</th>
              </tr>
            </thead>
            <tbody>
    <?php foreach ($this->ordCalcoli->getProdottiUtenti() AS $iduser => $user): ?>
                <tr>
                    <td><b><?php echo $user["nome"] . " " . $user["cognome"]; ?></b></td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcoli->getTotaleConExtraByIduser($iduser)) ?></strong></td>
                </tr>
    <?php endforeach; ?>
                <tr class="success">
                    <td>Totale: </td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcoli->getTotaleConExtra()); ?></strong></td>
                </tr>
            </tbody>
        </table>        
        <div class="my_clear" style="clear:both;">&nbsp;</div>
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
<?php if($this->ordCalcoli->canArchiviaOrdine()): ?>      
    <a class="btn btn-success" role="button" href="/gestione-cassa/archivia/idordine/<?php echo $this->ordCalcoli->getIdOrdine();?>"><span class="glyphicon glyphicon-ok"></span> Archivia</a>
<?php endif; ?>    
  </div>
</div>
        
<?php else: ?>
    <div class="col-md-12">
        <div class="lead">Nessun prodotto ordinato!</div>
    </div>
<?php endif; ?>