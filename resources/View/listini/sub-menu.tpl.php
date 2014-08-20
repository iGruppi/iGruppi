<?php if($lObj->canManageListino()): ?>
    <div class="btn-group btn-group-myig">
          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Gestisci Listino <span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/listini/edit/idlistino/<?php echo $lObj->getIdListino();?>">Dati Listino</a></li>
            <li><a href="/listini/prodotti/idlistino/<?php echo $lObj->getIdListino();?>">Prodotti</a></li>
          </ul>
    </div>
<?php else: ?>
      <a role="button" class="btn btn-default" href="/listini/view/idlistino/<?php echo $lObj->getIdListino(); ?>">Visualizza Listino</a>
<?php endif; ?>
