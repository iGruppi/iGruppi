<?php if($this->listino->countProdotti() > 0): ?>
    
    <div class="hint_50" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="alert alert-info" role="alert"><strong id="chk_num_Y">0</strong> su <strong id="chk_num_ALL">0</strong> prodotti selezionati per questo Listino<br />
            <input type="checkbox" id="check_all" onchange="setAllProdotti();"/> Seleziona tutti
        </div>
    </div>
    <div id="grid-prodotti" class="handsontable"></div>
        
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
                        'idproduct'     => $pObj->getIdProdotto(),
                        'subcat'        => $subcat->getDescrizione(),
                        'prezzo'        => $pObj->getCosto(),
                        'udm'           => $pObj->getUdm() .($pObj->hasPezzatura() ? "Taglio/Pezzatura:" . $pObj->getDescrizionePezzatura() : ""),
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
  
  var container1 = document.getElementById('grid-prodotti'),
    hot1;
  
  hot1 = new Handsontable(container1, {
      data: <?php echo json_encode($arProductsGrid); ?>,
      manualColumnMove: true,
      manualColumnResize: true,
      colHeaders: ['ID', 'Categoria', 'Prezzo', 'Udm', 'Descrizione'],
      colWidths: [40, 300, 70, 110, 400],
      columnSorting: true,
      columns: [
        {
          data: 'idproduct',
          readOnly: true
        },
        {
          data: 'subcat',
          readOnly: true
        },
        {
          data: 'prezzo',
          type: 'numeric',
          format: '0,0.00 $',
          language: 'de'
        },
        {
          data: 'udm',
          readOnly: true
        },
        {
          data: 'descrizione',
          readOnly: true
        }
      ]
    });

});
    
</script>