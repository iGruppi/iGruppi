<div class="row">
  <div class="col-md-12">
<?php if($this->ordCalcObj->getNum() > 0): ?>
    <h3 class="big-margin-top">Dettaglio Parziali per utente</h3>
    <?php foreach ($this->ordCalcObj->getElenco() AS $iduser => $user): ?>
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
        <?php foreach ($user["prodotti"] AS $idprodotto => $pObj): ?>
                <tr>
                    <td><strong><?php echo $pObj->getQta();?></strong></td>
                    <td><strong><?php echo $pObj->getCodice();?></strong></td>
                    <td><?php echo $pObj->getPrezzo();?> &euro; / <?php echo $pObj->getUdm(); ?></td>
                    <td><?php echo $pObj->getDescrizione();?></td>
                    <td class="text-right"><strong><?php echo $this->valuta($pObj->getTotale()); ?></strong></td>
                </tr>        
        <?php endforeach; ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td><b>Spese di spedizione</b></td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcObj->getCostoSpedizioneRipartito($iduser)); ?></strong></td>
                </tr>
            </tbody>
        </table>        
        
        <div class="sub_menu">
            <h3 class="totale">Totale utente: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleByIduser($iduser)) ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    