<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
    <p>
        Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
        Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-list"></span> Visualizza dettaglio <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/totali">Totale ordinato</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/utenti">Parziali per utente</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/prodotti">Parziali per prodotto</a></li>
        </ul>
    </div>
  </div>
  <div class="col-md-4  hidden-print">
    <?php if( $this->statusObj->can_RefInviaOrdine() && $this->ordCalcObj->isThereSomeProductsOrdered()): ?>
      <a class="btn btn-default btn-mylg" href="/gestione-ordini/invia/idordine/<?php echo $this->ordine->idordine;?>"><span class="glyphicon glyphicon-plus"></span> Invia al Produttore</a>
    <?php endif; ?>
      
  </div>   
</div>

<?php 
    switch ($this->tipo) {
        case "totali":
            echo $this->partial('gestioneordini/dettaglio.totali.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;

        case "utenti":
            echo $this->partial('gestioneordini/dettaglio.utenti.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;
        
        case "prodotti":
            echo $this->partial('gestioneordini/dettaglio.prodotti.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;
        
        default:
        break;
    } 
?>    