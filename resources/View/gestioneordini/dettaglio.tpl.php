<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
    <p>
        Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
        Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
  </div>
  <div class="col-md-4  hidden-print">
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

<div class="row">
  <div class="col-md-12">
<?php if(count((array)$this->riepilogo) > 0): ?>
    
    <h3 class="big-margin-top">Riepilogo Prodotti ordinati</h3>
    <table class="table table-striped table-condensed">
        <thead>
          <tr>
            <th>Quantità</th>
            <th>Codice</th>
            <th>Costo unitario</th>
            <th>Descrizione</th>
            <th class="text-right">Totale</th>
          </tr>
        </thead>
        <tbody>
    <?php 
        $totale = 0;
        $totale_colli = 0;
        foreach ($this->riepilogo as $idprodotto => $prodotto): ?>
            <tr>
                <td><strong><?php echo $prodotto["qta_ord"];?></strong></td>
                <td><strong><?php echo $prodotto["codice"];?></strong></td>
                <td><?php echo $prodotto["costo_op"];?> &euro; / <?php echo $prodotto["udm"]; ?></td>
                <td><?php echo $prodotto["descrizione"];?></td>
                <td class="text-right"><strong><?php 
                  $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                  $totale += $subtotale;
                  $totale_colli += $prodotto["qta_ord"];
                  echo $this->valuta($subtotale);
                  ?></strong></td>
            </tr>
    <?php endforeach; ?>
        
        </tbody>
    </table>
        
    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale colli: <strong><?php echo $totale_colli; ?></strong></h3>
            <h3 class="totale">Totale ordine: <strong><?php echo $this->valuta($totale); ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
    
    
    <h3 class="big-margin-top">Dettaglio Prodotti utenti</h3>
    <?php foreach ($this->dettaglio as $iduser => $user): ?>
        <h3 class="big-margin-top"><strong><?php echo $user["cognome"] . " " . $user["nome"]; ?></strong></h3>
        <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>Quantità</th>
                <th>Codice</th>
                <th>Costo unitario</th>
                <th>Descrizione</th>
                <th class="text-right">Totale</th>
              </tr>
            </thead>
            <tbody>
        <?php 
        $totale = 0;        
        foreach ($user["prodotti"] as $idprodotto => $prodotto): ?>
                <tr>
                    <td><strong><?php echo $prodotto["qta_ord"];?></strong></td>
                    <td><strong><?php echo $prodotto["codice"];?></strong></td>
                    <td><?php echo $prodotto["costo_op"];?> &euro; / <?php echo $prodotto["udm"]; ?></td>
                    <td><?php echo $prodotto["descrizione"];?></td>
                    <td class="text-right"><strong><?php 
                        $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                        $totale += $subtotale;
                        echo $this->valuta($subtotale);
                      ?></strong></td>
                </tr>        
        <?php endforeach; ?>
            </tbody>
        </table>        
        
        <div class="sub_menu">
            <h3 class="totale">Totale utente: <strong><?php echo $this->valuta($totale) ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    