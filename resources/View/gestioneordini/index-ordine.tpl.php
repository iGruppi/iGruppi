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
            <div class="btn-group btn-group-myig">
                <button type="button" id="gest_ordine_<?php echo $this->ordine->idordine;?>" " class="btn btn-success dropdown-toggle" data-toggle="dropdown">Gestisci Ordine <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/gestione-ordini/edit/idordine/<?php echo $this->ordine->idordine;?>">Modifica ordine</a></li>
                  <li><a href="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>">Prodotti</a></li>
                  <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine;?>">Dettaglio ordine</a></li>
                  <li class="divider"></li>
                  <li><a href="javascript:void(0)" onclick="jx_OrdineInConsegna(<?php echo $this->ordine->idordine;?>, <?php echo $this->ordine->idproduttore; ?>)"><span class="glyphicon glyphicon-arrow-right"></span> In consegna</a></li>
                </ul>
            </div>            
        </div>
      </div>
