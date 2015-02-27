<h2>Elenco Produttori</h2>

<div class="row">
  <div class="col-md-8 col-sm-8 col-xs-8">
      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $produttore): ?>
      
      <div class="row row-myig">
        <div class="col-md-12">
            <h3 class="no-margin <?php echo ($this->userSessionVal->refObject->canManageProduttore($produttore->idproduttore)) ? "green" : "text-dark"; ?>"><?php echo $produttore->ragsoc;?></h3>
        </div>
        <div class="col-md-8">
            <p>
                Referente <i>generale</i><strong class="text-danger">[?]</strong>: <a href="mailto: <?php echo $produttore->ref_ext_email; ?>"><?php echo $produttore->ref_ext_nome . " " . $produttore->ref_ext_cognome; ?></a><br />
                Referente interno: <a href="mailto: <?php echo $produttore->ref_int_email; ?>"><?php echo $produttore->ref_int_nome . " " . $produttore->ref_int_cognome; ?></a>
            </p>
        <?php if( isset($this->arCat[$produttore->idproduttore]) ): ?>
            <h4><span class="text-muted">Prodotti:</span> <?php echo implode(", ", $this->arCat[$produttore->idproduttore]); ?></h4>
        <?php endif; ?>
        </div>
        <div class="col-md-4">
            <?php include $this->template('produttori/sub-menu.tpl.php'); ?>
        </div>
      </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun produttore disponibile!</h3>
<?php endif; ?>
      
  </div>
  <div class="col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
  <div class="col-md-3 col-sm-3 col-xs-3">
      <!-- <a class="btn btn-default btn-mylg" href="/produttori/add"><span class="glyphicon glyphicon-plus"></span> Nuovo produttore</a> -->
      &nbsp;
  </div>
</div>

