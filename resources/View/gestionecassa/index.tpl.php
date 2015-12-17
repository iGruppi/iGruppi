<h2>Gestione Cassa</h2>
<div class="row">
  <div class="col-md-9">
    <table class="table table-condensed">
        <thead>
          <tr>
            <th>ID</th>
            <th>Utente/Data</th>
            <th class="text-right">Importo</th>
            <th>Descrizione</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($this->movimenti AS $movimento): ?>            
            <tr class="<?php echo($movimento->getImporto() > 0) ? "success" : "danger"; ?>">
                <td><i><?php echo $movimento->getId(); ?></i></td>
                <td><strong><?php echo $movimento->getUser(); ?></strong><br />
                    <small><?php echo $this->date($movimento->getData(), '%d/%m/%Y %H:%M'); ?></small>
                </td>
                <td class="text-right"><?php echo $this->valuta($movimento->getImporto()); ?></td>
                <td>
                    <?php if($movimento->isRelatedToOrdine()): ?>
                    <a href="#"><?php echo $movimento->getDescrizione(); ?></a><br />
                    Data Ordine: <?php echo $this->date($movimento->getDataOrdine(), '%d/%m/%Y'); ?>
                    <?php else: ?>
                        <?php echo $movimento->getDescrizione(); ?>
                    <?php endif; ?>
                </td>
                <td><?php if(!$movimento->isRelatedToOrdine()): ?><a class="btn btn-default btn-xs" href="/gestione-cassa/edit/idmovimento/<?php echo $movimento->getId(); ?>" role="button">Modifica</a><?php endif; ?></td>
            </tr>        
<?php endforeach; ?>
        </tbody>
    </table>
        <nav>
          <ul class="pagination">
            <li class="<?php echo ($this->sPager->hasPrev()) ? "" : "disabled"; ?>">
                <?php if($this->sPager->hasPrev()): ?>
                    <a href="<?php echo ($this->sPager->hasPrev()) ? $this->sPager->getURL_Prev() : ""; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                <?php else: ?>
                    <span><span aria-hidden="true">&laquo;</span></span>                
                <?php endif; ?>
            </li>
            <li class="active">
                <a href="#"><?php echo $this->sPager->getPageNumber(); ?> <span class="sr-only">(current)</span></a>
            </li>
            <li class="<?php echo ($this->sPager->hasNext()) ? "" : "disabled"; ?>">
                <?php if($this->sPager->hasNext()): ?>
                <a href="<?php echo ($this->sPager->hasNext()) ? $this->sPager->getURL_Next() : ""; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                <?php else: ?>
                    <span><span aria-hidden="true">&raquo;</span></span>                
                <?php endif; ?>
            </li>
          </ul>
        </nav>        
      
  </div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/add"><span class="glyphicon glyphicon-plus"></span> Aggiungi movimento</a>
      <br /><br />
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/ordertoclose"><span class="glyphicon glyphicon-list-alt"></span> Ordini da chiudere</a>
      <br /><br />
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/viewsaldi"><span class="glyphicon glyphicon-euro"></span> Saldi di cassa</a>
  </div>
</div>
      