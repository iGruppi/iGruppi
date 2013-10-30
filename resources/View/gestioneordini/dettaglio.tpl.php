<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
    <p>
        Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
        Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
    
<?php if(count((array)$this->riepilogo) > 0): ?>
    
    <h3 class="big-margin-top">Riepilogo Prodotti ordinati</h3>
    <?php 
        $totale = 0;
        foreach ($this->riepilogo as $idprodotto => $prodotto): ?>
        
    <div class="row row-myig-thin">
      <div class="col-md-9">
          <h4 class="no-margin"><strong><?php echo $prodotto["qta_ord"];?></strong> x <?php echo $prodotto["descrizione"];?></h4>
          <p>Costo: <strong><?php echo $prodotto["costo_op"];?></strong> &euro; / <strong><?php echo $prodotto["udm"]; ?></strong></p>
      </div>
      <div class="col-md-3">
          <div class="sub_menu">
              <h4><?php 
                  $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                  $totale += $subtotale;
                  echo $this->valuta($subtotale);
                  ?>
              </h4>
          </div>
      </div>
    </div>
        
    <?php endforeach; ?>
        
    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale ordine: <strong><?php echo $this->valuta($totale) ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
    
    
    <h3 class="big-margin-top">Dettaglio Prodotti utenti</h3>
    <?php foreach ($this->dettaglio as $iduser => $user): ?>
        <h3 class="big-margin-top"><strong><?php echo $user["cognome"] . " " . $user["nome"]; ?></strong></h3>
        <?php 
        $totale = 0;        
        foreach ($user["prodotti"] as $idprodotto => $prodotto): ?>
        
    <div class="row row-myig-thin">
      <div class="col-md-9">
          <h4 class="no-margin"><strong><?php echo $prodotto["qta_ord"];?></strong> x <?php echo $prodotto["descrizione"];?></h4>
          <p>Costo: <strong><?php echo $prodotto["costo_op"];?></strong> &euro; / <strong><?php echo $prodotto["udm"]; ?></strong></p>
      </div>
      <div class="col-md-3">
          <div class="sub_menu">
              <h4><?php 
                  $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                  $totale += $subtotale;
                  echo $this->valuta($subtotale);
                  ?>
              </h4>
          </div>
      </div>
    </div>
        
        <?php endforeach; ?>
        <div class="sub_menu">
            <h3 class="totale">Totale utente: <strong><?php echo $this->valuta($totale) ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
  <div class="col-md-4">
<?php if( $this->statusObj->is_Chiuso() && count((array)$this->riepilogo) > 0): ?>
    <div class="bs-callout bs-callout-info">
      <h4>Invio ordine</h4>
      <form id="forminvioordine" action="/gestione-ordini/invia/idordine/<?php echo $this->ordine->idordine; ?>" method="post">
        <p><input type="checkbox" name="invia_dettaglio" value="S" /> Invia <b>Dettaglio Prodotti utenti</b></p>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">INVIA!</button>
      </form>
    </div>
<?php endif; ?>
  </div>
    
</div>