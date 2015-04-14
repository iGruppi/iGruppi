<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>
<div class="row">
  <div class="col-md-12">
    <h3 class="big-margin-top">Quantità ordinate per utente</h3>
  </div>
</div>  
<div class="row">
    <div class="col-md-12">
<?php if($this->ordine->canRef_ModificaQtaOrdinate()): ?>
<?php 
        $arProductsGrid = array();
        if($this->ordCalcObj->getProdottiUtenti() > 0): 
            foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $user): ?>
<?php        
                foreach ($user["prodotti"] AS $idprodotto => $pObj):
                    $arProductsGrid[] = array(
                        'disponibile_ordine'    => $pObj->isDisponibile(),
                        'user'                  => $user["cognome"] . " " . $user["nome"],
                        'qta'                   => $pObj->getQta_ByIduser($iduser),
                        'qta_reale'             => $pObj->getQtaReale_ByIduser($iduser),
                        'codice'                => $pObj->getCodice(),
                        'descrizione'           => $pObj->getDescrizioneListino(),
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
<?php else: ?>
        <p>Non è possibile modificare le quantità ordinate perchè l'ordine è in stato: <span class="<?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></span></p>
<?php endif; ?>
  </div>
</div>    


<script>    
$(document).ready(function () { 

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
            language: 'it'
          }
        ],
        cells: function (row, col, prop) {
          var cellProperties = {};
          if (this.instance.getData()[row]["disponibile_ordine"] === false) {
            cellProperties.readOnly = true;
          }
          return cellProperties;
        }
    });
    
    
});
</script>