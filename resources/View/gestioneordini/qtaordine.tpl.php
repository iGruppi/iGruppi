<div id="ordine_header">
    <?php include $this->template('gestioneordini/header.tpl.php'); ?>
    <div class="row">
      <div class="col-md-12">
        <h3 class="big-margin-top">Modifica Quantità ordinate</h3>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
<?php if(!$this->ordine->canModificaQtaOrdinate()): ?>
        <p class="text-danger">Non puoi modificare le quantità ordinate.</p>
<?php endif; ?>
<?php 
        $arProductsGrid = array();
        if($this->ordCalcObj->getProdottiUtenti() > 0): 
            foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $prodotti): 
                $userDati = $prodotti["user"];
                foreach ($prodotti["prodotti"] AS $idprodotto => $pObj):
                    
                    $arProductsGrid[] = array(
                        'idprodotto'            => $pObj->getIdProdotto(),
                        'idlistino'             => $pObj->getIdListino(),
                        'iduser'                => $iduser,
                        'disponibile_ordine'    => $pObj->isDisponibile(),
                        'user'                  => $userDati->cognome . " " . $userDati->nome,
                        'qta'                   => $pObj->getQta_ByIduser($iduser),
                        'qta_reale'             => $pObj->getQtaReale_ByIduser($iduser),
                        'codice'                => $pObj->getCodice(),
                        'descrizione'           => $pObj->getDescrizioneAnagrafica(),
                        'costo_ordine'          => $pObj->getCostoOrdine(),
                        'udm'                   => $pObj->getUdm() .($pObj->hasPezzatura() ? "<br /><small>(Minimo " . $pObj->getDescrizionePezzatura() . ")</small>" : ""),
                        'subcat'                => $pObj->getSubCategoria()
                    );
    ?>
<?php           endforeach; ?>
<?php       endforeach; ?>
        <div id="grid-prodotti" class="handsontable myhandsontable"></div>

<?php   else: ?>
        <div class="lead">Nessun prodotto ordinato!</div>
<?php   endif; ?>
  </div>
</div>    

<script>    
$(document).ready(function () { 
    // store my idordine
    var idordine = <?php echo $this->ordine->getIdOrdine(); ?>;

    $('#grid-prodotti').handsontable({
        data: <?php echo json_encode($arProductsGrid); ?>,
        manualColumnMove: true,
        manualColumnResize: true,
        colHeaders: ['Disp.', 'Codice', 'Descrizione', 'Prezzo', 'Udm', 'Utente', 'Qta Ord.', 'Qta Reale'],
        colWidths: [50, 80, 350, 80, 100, 200, 70, 70],
        columnSorting: true,
        currentRowClassName: 'currentRow',
        columns: [
          {
            data: 'disponibile_ordine',
            type: 'checkbox',
            readOnly: true
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
            data: 'user',
            readOnly: true
          },
          {
            data: 'qta',
            readOnly: true,
            type: 'numeric'
          },
          {
            data: 'qta_reale',
            type: 'numeric',
            format: '0,0.00',
            language: 'it',
            readOnly: <?php echo (!$this->ordine->canModificaQtaOrdinate()) ? "true" : "false"; ?>
          }
        ],
        cells: function (row, col, prop) {
          var cellProperties = {};
          if (this.instance.getData()[row]["disponibile_ordine"] === false) {
            cellProperties.readOnly = true;
          }
          return cellProperties;
        },
        afterChange: function (changes, source) {
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
                  // check if it is really changed
                  console.log(field + ": " + new_value + " - " + old_value );
                  console.log(rowSourceData);
                  if(old_value !== new_value)
                  {   
                      $.getJSON(
                          '/gestione-ordini/changeqta/',
                          {iduser: rowSourceData.iduser, idordine: idordine, idprodotto: rowSourceData.idprodotto, idlistino: rowSourceData.idlistino, field: field, value: new_value},
                          function(data) {
                              if(!data.res)
                              {
                                  console.log('ERROR!');
                              }
                          });
                  }
              }
            }
        }
        
    });
    
    
});
</script>