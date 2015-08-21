      <div class="row-myig">
          <div class="row">
            <div class="col-md-12">
                <h3 class="title-list">Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong>  <?php echo $this->createLabelCondivisione($this->ordine->getCondivisione()); ?></h3>
                <p><strong><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></p>
                <h5><span class="text-muted">Prodotti:</span> <?php 
                    $categorie = $this->ordine->getListaDescrizioniCategorie();
                    echo $this->arrayToString($categorie); 
                    ?></h5>
                <h5> 
                    <?php if(!$this->ordine->getMyGroup()->isSetUserRef()): ?>
                        <b class="text-danger">Nessun Incaricato ordine ordine assegnato!</b>
                    <?php else: ?>
                        <span class="text-muted">Incaricato ordine:</span> <?php echo $this->ordine->getMyGroup()->getRefNome(); ?>
                    <?php endif; ?>
                            
                </h5>

                <h4 class="ordine <?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?> </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
                <p>
                    <em>Apertura</em>: <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></strong><br />
                    <em>Chiusura</em>: <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
                </p>
            </div>
            <div class="col-md-4 text-right">
      <?php if($this->ordine->canManageOrdine()): ?>
                <a role="button" id="gest_ordine_<?php echo $this->ordine->getIdOrdine();?>" class="btn btn-success" href="/gestione-ordini/dashboard/idordine/<?php echo $this->ordine->getIdOrdine(); ?>"><span class="glyphicon glyphicon-pencil"></span> Gestisci Ordine</a>
      <?php endif; ?>
            </div>
          </div>
      </div>
