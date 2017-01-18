<div class="row">
    <div class="col-md-12">
<?php 
    $arProductsGrid = array();
    if(count($this->ordCalcObj->getProdottiUtenti()) > 0):
        foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $prodotti)
        {
            $userDati = $prodotti["user"];
            foreach($prodotti["prodotti"] AS $pObj)
            {
                $arProductsGrid[] = array(
                    'idprodotto'            => $pObj->getIdProdotto(),
                    'idlistino'             => $pObj->getIdListino(),
                    'iduser'                => $iduser,
                    'user_nome'             => $userDati->cognome . " " . $userDati->nome,
                    'gas_nome'              => $userDati->nome_gas,
                    'disponibile_ordine'    => $pObj->isDisponibile(),
                    'produttore'            => $pObj->getProduttore(),
                    'codice'                => $pObj->getCodice(),
                    'descrizione'           => $pObj->getDescrizioneAnagrafica(),
                    'costo_ordine'          => $pObj->getCostoOrdine(),
                    'udm'                   => $pObj->getUdm(),
                    'udm2'                  => "&euro;/" . $pObj->getUdm(),
                    'qta'                   => $pObj->getQta_ByIduser($iduser),
                    'qta_pezzatura'         => " x " . $pObj->getDescrizioneUdmQtaOrdinata(),
                    'qta_reale'             => $pObj->getQtaReale_ByIduser($iduser),
                    'subcat'                => $pObj->getSubCategoria()
                );
            }
        }
?>
        <div id="grid-prodotti" class="handsontable myhandsontable"></div>
  </div>
</div>

    <div class="totale_line">
        <div class="sub_menu">
            <h3 class="totale">Totale ordine (con IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotale()); ?></strong></h3>
            <h3 class="totale">Totale ordine (senza IVA): <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleSenzaIva()); ?></strong></h3>
        </div>
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
<script>    
$(document).ready(function () {
    // store my idordine
    var idordine = <?php echo $this->ordine->getIdOrdine(); ?>;

  // init Container for Handsontable
  $('#grid-prodotti').handsontable({
        data: <?php echo json_encode($arProductsGrid); ?>,
        manualColumnMove: true,
        manualColumnResize: true,
        colHeaders: ['Disp.', 'Utente', 'Gruppo', <?php if($this->ordCalcObj->isMultiProduttore()) { echo "'Produttore', "; } ?> 'Codice', 'Qta Ord.', '', 'Descrizione', 'Qta Reale', '', 'Prezzo', '', 'Categoria'],
        colWidths: [50, 100, 100, <?php if($this->ordCalcObj->isMultiProduttore()) { echo "150, "; } ?> 80, 70, 110, 380, 70, 120, 70, 120, 270],
        columnSorting: true,
        currentRowClassName: 'currentRow',
        columns: [
          {
            data: 'disponibile_ordine',
            type: 'checkbox',
            readOnly: true
          },
          {
            data: 'user_nome',
            readOnly: true
          },
          {
            data: 'gas_nome',
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
            data: 'qta',
            readOnly: true,
            type: 'numeric'
          },
          {
            data: 'qta_pezzatura',
            readOnly: true
          },
          {
            data: 'descrizione',
            readOnly: true
          },
            {
              data: 'qta_reale',
              readOnly: true,
              type: 'numeric',
              format: '0,0.000',
              language: 'it',
              readOnly: <?php echo (!$this->ordine->canModificaQtaOrdinate()) ? "true" : "false"; ?>            
            },
            {
              data: 'udm',
              renderer: "html",
              readOnly: true
            },
            {
              data: 'costo_ordine',
              readOnly: true,
              type: 'numeric',
              format: '0,0.00',
              language: 'it'
            },
            {
              data: 'udm2',
              renderer: "html",
              readOnly: true
            },
        {
            data: 'subcat',
            readOnly: true
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
                    var old_value = changes[i][2];
                    var new_value = changes[i][3];
                    // check if it is really changed
                    if(old_value !== new_value)
                    {
                        $.getJSON(
                            '/gestione-ordini/changeqta/',
                            {iduser: rowSourceData.iduser, idordine: idordine, idprodotto: rowSourceData.idprodotto, idlistino: rowSourceData.idlistino, value: new_value, whois: 'Supervisore'},
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