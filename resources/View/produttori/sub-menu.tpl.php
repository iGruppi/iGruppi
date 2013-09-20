<div class="btn-group btn-group-myig">
    <?php if($produttore->refObj->is_Referente()): ?>
          <button type="button" class="btn btn-success">Gestisci</button>
          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/produttori/edit/idproduttore/<?php echo $produttore->idproduttore;?>">Dati produttore</a></li>
            <li><a href="/prodotti/list/idproduttore/<?php echo $produttore->idproduttore;?>">Prodotti</a></li>
            <li><a href="/gestione-ordini/index/idproduttore/<?php echo $produttore->idproduttore;?>">Ordini</a></li>
            <li class="divider"></li>
            <li><a href="/gestione-ordini/new/idproduttore/<?php echo $produttore->idproduttore;?>">Crea nuovo ordine</a></li>
          </ul>
    <?php else: ?>
          <button type="button" class="btn btn-default">Visualizza</button>
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/produttori/edit/idproduttore/<?php echo $produttore->idproduttore;?>">Dati produttore</a></li>
            <li><a href="/prodotti/list/idproduttore/<?php echo $produttore->idproduttore;?>">Prodotti</a></li>
          </ul>
    <?php endif; ?>
</div>
