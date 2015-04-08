<?php if($this->listino->countProdotti() > 0): ?>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Prodotti inseriti in questo listino:</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="grid-prodotti" class="handsontable myhandsontable"></div>
        </div>
    </div>
        
<?php 
    $availableProducts = 0;
    $arProductsGrid = array();
    foreach ($this->listino->getProdottiWithCategoryArray() AS $cat)
    {
        foreach ($cat->getSubcat() AS $subcat)
        {
            // get Prodotti List in this Idsubcat
            foreach ($subcat->getProdotti() AS $prodotto)
            {
                $pObj = $prodotto->getProdotto(); 
                // Check if prodotto is in LISTINO
                if($pObj->isInListino())
                {
                    $availableProducts++;
                    $arProductsGrid[] = array(
                        'idprodotto'     => $pObj->getIdProdotto(),
                        'codice'        => $pObj->getCodice(),
                        'subcat'        => $subcat->getDescrizione(),
                        'prezzo'        => $pObj->getCosto(),
                        'udm'           => $pObj->getUdm() .($pObj->hasPezzatura() ? "<br /><small>(Minimo " . $pObj->getDescrizionePezzatura() . ")</small>" : ""),
                        'descrizione'   => $pObj->getDescrizioneListino()
                    );
                }
            }
        }
    }
?>
<?php else: ?>
    <p>Nessun prodotto disponibile in questo listino.</p>
<?php endif; ?>

<script>

$(document).ready(function () {
  
  $('#grid-prodotti').handsontable({
      data: <?php echo json_encode($arProductsGrid); ?>,
      manualColumnMove: true,
      manualColumnResize: true,
      colHeaders: ['Codice', 'Descrizione', 'Prezzo', 'Udm', 'Categoria'],
      colWidths: [80, 380, 70, 120, 280],
      columnSorting: true,
      currentRowClassName: 'currentRow',
      columns: [
        {
          data: 'codice',
          readOnly: true
        },
        {
          data: 'descrizione',
          readOnly: true
        },
        {
          data: 'prezzo',
          <?php echo (!$this->listino->canUpdatePrezzi() ? "readOnly: true," : ""); ?>
          type: 'numeric',
          format: '0,0.00 $',
          language: 'de' // TODO: usare IT
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
      <?php if($this->listino->canUpdatePrezzi()): ?>
      ,afterChange: function (changes, source) {
          if (source === 'edit') {
            for(var i = changes.length - 1; i >= 0; i--)
            {
                // convert logicalIndex to physicalIndex to get the right SourceData
                var physicalIndex = this.sortIndex[changes[i][0]][0];
                var rowSourceData = this.getSourceDataAtRow(physicalIndex);
                var row = changes[i][0];
                var old_value = changes[i][2];
                var new_value = changes[i][3];
                var codice = this.getSourceDataAtRow(row);
                var idprodotto = rowSourceData.idprodotto;
                console.log(idprodotto);  
            }
          }
      }
      <?php endif; ?>
    });
});
    
</script>