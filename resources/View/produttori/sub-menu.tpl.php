<div class="btn-group btn-group-myig">
    <?php if(Zend_Registry::get("permsProduttori")->canManageProduttore($produttore->idproduttore)): ?>
          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Gestisci Produttore <span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/produttori/edit/idproduttore/<?php echo $produttore->idproduttore;?>">Dati produttore</a></li>
            <li><a href="/prodotti/list/idproduttore/<?php echo $produttore->idproduttore;?>">Anagrafica Prodotti</a></li>
          </ul>
    <?php else: ?>
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Visualizza <span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/produttori/view/idproduttore/<?php echo $produttore->idproduttore;?>">Dati produttore</a></li>
            <li><a href="/prodotti/list/idproduttore/<?php echo $produttore->idproduttore;?>">Anagrafica Prodotti</a></li>
          </ul>
    <?php endif; ?>
</div>
