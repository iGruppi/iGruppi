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
                        'idlistino'      => $pObj->getIdListino(),
                        'attivo_listino' => ($pObj->getAttivoListino() == "S" ? true : false),
                        'codice'        => $pObj->getCodice(),
                        'subcat'        => $subcat->getDescrizione(),
                        'costo_listino'  => $pObj->getCosto(),
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
      colHeaders: ['Disponibile', 'Codice', 'Descrizione', 'Prezzo', 'Udm', 'Categoria'],
      colWidths: [50, 80, 380, 70, 120, 280],
      columnSorting: true,
      currentRowClassName: 'currentRow',
      columns: [
        {
          data: 'attivo_listino',
          readOnly: <?php echo (!$this->listino->canUpdatePrezzi() ? "true" : "false"); ?>,
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
          data: 'costo_listino',
          readOnly: <?php echo (!$this->listino->canUpdatePrezzi() ? "true" : "false"); ?>,
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
      <?php if($this->listino->canUpdatePrezzi()): ?>
      ,afterChange: function (changes, source) {
          if (source === 'edit') {
            for(var i = changes.length - 1; i >= 0; i--)
            {
                // NOT SORTED (as Default value), logicalIndex = physicalIndex
                var physicalIndex = changes[i][0];
                if(isSorted(this)) {
                    // SORTED, convert logicalIndex to physicalIndex to get the right SourceData
                    physicalIndex = this.sortIndex[changes[i][0]][0];
                }
                // get SourceData by physicalIndex
                var rowSourceData = this.getSourceDataAtRow(physicalIndex);
                // get value changed fields
                var field = changes[i][1];
                var old_value = changes[i][2];
                var new_value = changes[i][3];
//                console.log(idprodotto);  
                // check if it is really changed
                if(old_value !== new_value)
                {   
                    $.getJSON(
                        '/listini/updatedata/',
                        {idprodotto: rowSourceData.idprodotto, idlistino: rowSourceData.idlistino, field: field, value: new_value},
                        function(data) {
                            if(!data.res)
                            {
                                console.log('ERROR!'); // TODO
                            }
                        });
                }
            }
          }
      }
      <?php endif; ?>
    });
});
    
</script>