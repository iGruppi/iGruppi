<h2>Elenco Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Prodotto aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $prodotto): ?>
      
      <div class="row row-myig">
        <div class="col-md-10">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Codice: <strong><?php echo $prodotto->codice; ?></strong><br />
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo); ?> / <?php echo $prodotto->udm; ?></strong><br />
                Disponibile: <strong class="attivo_<?php echo $prodotto->attivo; ?>"><?php echo $this->yesno($prodotto->attivo); ?></strong>
            </p>
        </div>
        <div class="col-md-2">
        <?php if($this->produttore->refObj->is_Referente()): ?>
            <a class="btn btn-success" href="/prodotti/edit/idprodotto/<?php echo $prodotto->idprodotto;?>">Modifica</a>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun prodotto!</h3>
<?php endif; ?>
  </div>
<?php if($this->produttore->refObj->is_Referente()): ?>    
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo prodotto</a>
  </div>
<?php endif; ?>

</div>
