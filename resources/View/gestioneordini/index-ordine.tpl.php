      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin">Ordine del <strong><?php echo $this->date($this->ordine->data_inizio, '%d %B %Y');?></strong></h3>
            <h4 class="ordine <?php echo $this->ordine->statusObj->getStatus(); ?>"><?php echo $this->ordine->statusObj->getStatus(); ?></h4>
            <p>
                <em>Apertura</em>: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
            </p>
        </div>
        <div class="col-md-4">
            <a role="button" id="gest_ordine_<?php echo $this->ordine->idordine;?>" class="btn btn-success" href="/gestione-ordini/dashboard/idordine/<?php echo $this->ordine->idordine; ?>"><span class="glyphicon glyphicon-pencil"></span> Gestisci Ordine</a>
        </div>
      </div>
