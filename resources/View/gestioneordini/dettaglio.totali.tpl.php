<div class="row">
    <div class="col-md-12">
<?php 
    $arProductsGrid = array();
    $arProdottiOrdinati = $this->ordCalcObj->getProdottiOrdinati();
    if(count($arProdottiOrdinati) > 0):
        foreach ($arProdottiOrdinati AS $pObj)
        {
            $arProductsGrid[] = array(
                'disponibile_ordine'    => ($pObj->isDisponibile() ? "SI" : "NO"),
                'produttore'            => $pObj->getProduttore(),
                'codice'                => $pObj->getCodice(),
                'descrizione'           => $pObj->getDescrizioneListino(),
                'costo_ordine'          => $pObj->getCostoOrdine(),
                'udm'                   => $pObj->getUdm() .($pObj->hasPezzatura() ? "<br /><small>(Minimo " . $pObj->getDescrizionePezzatura() . ")</small>" : ""),
                'qta'                   => $pObj->getQta(),
                'qta_reale'             => $pObj->getQtaReale(),
                'subcat'                => $pObj->getSubCategoria()
            );
        }
?>
        <div id="grid-prodotti" class="handsontable myhandsontable"></div>
  </div>
</div>

    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConExtra()); ?></strong></h3>
    <?php if(1): ?>
            <h3 class="totale">Totale ordine (con IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotale()); ?></strong></h3>
            <h3 class="totale">Totale ordine (senza IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleSenzaIva()); ?></strong></h3>
    <?php endif; ?>
    <?php $extraArray = $this->ordCalcObj->getSpeseExtra_Totale();
        if(count($extraArray) > 0): ?>
            <h4>Totali Spese Extra</h4>
            <p>
        <?php foreach ($extraArray AS $extra): ?>
            <?php echo $extra["descrizione"]; ?> (<em><?php echo $extra["descrizioneTipo"]; ?></em>): <strong><?php echo $this->valuta($extra["totale"]); ?></strong><br />
        <?php endforeach; ?>
            </p>
    <?php endif; ?>            
            <h4>Colli</h4>
            <p>
                Totale colli: <strong><?php echo $this->ordCalcObj->getTotaleColli(); ?></strong><br />
            </p>
        </div>
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
<script>    
$(document).ready(function () {
    
  // init Container for Handsontable
  $('#grid-prodotti').handsontable({
      data: <?php echo json_encode($arProductsGrid); ?>,
      manualColumnMove: true,
      manualColumnResize: true,
      colHeaders: ['Disp.', <?php if($this->ordCalcObj->isMultiProduttore()) { echo "'Produttore', "; } ?> 'Codice', 'Descrizione', 'Qta Ord.', 'Qta Reale', 'Prezzo', 'Udm', 'Categoria'],
      colWidths: [50, <?php if($this->ordCalcObj->isMultiProduttore()) { echo "150, "; } ?> 80, 380, 70, 70, 70, 120, 270],
      columnSorting: true,
      currentRowClassName: 'currentRow',
      columns: [
        {
          data: 'disponibile_ordine',
          readOnly: true
        },
    <?php if($this->ordCalcObj->isMultiProduttore()): ?>
        {
          data: 'produttore',
          readOnly: true
        },
    <?php endif; ?>            
        {
          data: 'codice',
          readOnly: true
        },
        {
          data: 'descrizione',
          readOnly: true
        },
        {
          data: 'qta',
          readOnly: true,
          type: 'numeric'
        },
        {
          data: 'qta_reale',
          readOnly: true,
          type: 'numeric',
          format: '0,0.00',
          language: 'it'
        },
        {
          data: 'costo_ordine',
          readOnly: true,
          type: 'numeric',
          format: '0,0.00 $',
          language: 'it'
        },
        {
          data: 'udm',
          renderer: "html",
          readOnly: true
        },
        {
          data: 'subcat',
          readOnly: true
        }
      ]
    });
});
</script>