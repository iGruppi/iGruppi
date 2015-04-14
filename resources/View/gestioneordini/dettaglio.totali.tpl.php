<div class="row">
  <div class="col-md-12">
    <h3 class="big-margin-top">Riepilogo Totale Prodotti ordinati</h3>
  </div>
</div>    
<div class="row">
    <div class="col-md-12">
<?php 
    $arProductsGrid = array();
    if(count($this->ordCalcObj->getProdotti()) > 0):
        foreach ($this->ordCalcObj->getProdotti() AS $pObj)
        {
            $arProductsGrid[] = array(
                'disponibile_ordine'    => $pObj->isDisponibile(),
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
            <h3 class="totale">Totale colli: <strong><?php echo $this->ordCalcObj->getTotaleColli(); ?></strong></h3>
            <h3 class="totale">Totale ordine (senza IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleSenzaIva()); ?></strong></h3>
            <h3 class="totale">Totale ordine (con IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotale()); ?></strong></h3>
    <?php if($this->ordCalcObj->getSpeseExtra()->has()): ?>
        <?php foreach ($this->ordCalcObj->getSpeseExtra()->get() AS $extra): ?>
            <h4 class="totale"><?php echo $extra->getDescrizione(); ?> (extra): <strong><?php echo $this->valuta($extra->getTotaleGruppo()); ?></strong></h4>
        <?php endforeach; ?>
    <?php endif; ?>            
            <h3 class="totale">Totale ordine: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConExtra()); ?></strong></h3>
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
      colHeaders: ['Disp.', 'Codice', 'Descrizione', 'Prezzo', 'Udm', 'Qta Ord.', 'Qta Reale', 'Categoria'],
      colWidths: [50, 80, 380, 70, 120, 70, 70, 120],
      columnSorting: true,
      currentRowClassName: 'currentRow',
      columns: [
        {
          data: 'disponibile_ordine',
          readOnly: true,
          type: 'checkbox'
        },
        {
          data: 'codice',
          readOnly: true
        },
        {
          data: 'descrizione',
          readOnly: true
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
          data: 'subcat',
          readOnly: true
        }
      ]
    });
});
</script>