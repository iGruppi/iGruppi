<div class="row">
  <div class="col-md-12">
    <h3 class="big-margin-top">Riepilogo Totale Prodotti ordinati</h3>
<?php if(count($this->ordCalcObj->getProdotti()) > 0): ?>    
    <table class="table table-condensed">
        <thead>
          <tr>
            <th>Qta/QtaReale</th>
            <th>Codice</th>
            <th>Prezzo unitario</th>
            <th>Descrizione</th>
            <th class="text-right">Totale senza IVA</th>
            <th class="text-right">Totale</th>
          </tr>
        </thead>
        <tbody>
    <?php foreach ($this->ordCalcObj->getProdotti() as $idprodotto => $pObj): ?>
       <?php if($pObj->getQtaReale() > 0): ?>
            <?php if($pObj->isDisponibile()): ?>
                <tr>
            <?php else: ?>
                <tr class="danger strike">
            <?php endif; ?>
                <td><strong><?php echo $pObj->getQtaOrdinata();?> / <?php echo $pObj->getQtaReale();?></strong></td>
                <td><strong><?php echo $pObj->codice;?></strong></td>
                <td><?php echo $pObj->getDescrizionePrezzo();?></td>
                <td><?php echo $pObj->descrizione;?></td>
                <td class="text-right">
                    <?php if( $pObj->hasAliquotaIva()): ?>
                    <strong><?php echo $this->valuta($pObj->getTotaleSenzaIva()); ?></strong> (<?php echo $pObj->getAliquotaIva(); ?>%)
                    <?php else: ?>
                    <span class="glyphicon glyphicon-info-sign iva_tooltip" data-toggle="tooltip" data-placement="left" title="Il campo IVA non è gestito per questo prodotto!"></span>
                    <?php endif; ?>
                </td>
                <td class="text-right"><strong><?php echo $this->valuta($pObj->getTotale()); ?></strong></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
        
        </tbody>
    </table>

    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale colli: <strong><?php echo $this->ordCalcObj->getTotaleColli(); ?></strong></h3>
            <h3 class="totale">Totale ordine (senza IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleSenzaIva()); ?></strong></h3>
    <?php if($this->ordCalcObj->hasCostoSpedizione()): ?>
            <h3 class="totale">Spese di spedizione: <strong><?php echo $this->valuta($this->ordCalcObj->getCostoSpedizione()); ?></strong></h3>
    <?php endif; ?>            
            <h3 class="totale">Totale ordine: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConSpedizione()); ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
<script>    
    $('.iva_tooltip').tooltip('hide');
</script>