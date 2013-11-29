<div class="row">
  <div class="col-md-12">
<?php if($this->ordCalcObj->getNum() > 0): ?>
    
    <h3 class="big-margin-top">Riepilogo Totale Prodotti ordinati</h3>
    <table class="table table-striped table-condensed">
        <thead>
          <tr>
            <th>Quantità</th>
            <th>Codice</th>
            <th>Costo unitario</th>
            <th>Descrizione</th>
            <th class="text-right">Totale senza IVA</th>
            <th class="text-right">Totale</th>
          </tr>
        </thead>
        <tbody>
    <?php foreach ($this->ordCalcObj->getElenco() as $idprodotto => $pObj): ?>
            <tr>
                <td><strong><?php echo $pObj->getQta();?></strong></td>
                <td><strong><?php echo $pObj->getCodice();?></strong></td>
                <td><?php echo $pObj->getPrezzo();?> &euro; / <?php echo $pObj->getUdm(); ?></td>
                <td><?php echo $pObj->getDescrizione();?></td>
                <td class="text-right"><strong><?php echo $this->valuta($pObj->getTotaleSenzaIva()); ?></strong></td>
                <td class="text-right"><strong><?php echo $this->valuta($pObj->getTotale()); ?></strong></td>
            </tr>
    <?php endforeach; ?>
        
        </tbody>
    </table>
        
    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale colli: <strong><?php echo $this->ordCalcObj->getTotaleColli(); ?></strong></h3>
            <h3 class="totale">Totale ordine (senza IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleSenzaIva()); ?></strong></h3>
    <?php if($this->ordCalcObj->hasCostoSpedizione()): ?>
            <h3 class="totale">Costo di spedizione: <strong><?php echo $this->valuta($this->ordCalcObj->getCostoSpedizione()); ?></strong></h3>
    <?php endif; ?>            
            <h3 class="totale">Totale ordine: <strong><?php echo $this->valuta($this->ordCalcObj->getTotale()); ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    