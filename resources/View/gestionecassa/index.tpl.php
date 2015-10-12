<h2>Gestione Cassa</h2>
<div class="row">
  <div class="col-md-9">
    <table class="table table-condensed">
        <thead>
          <tr>
            <th>ID</th>
            <th>Utente</th>
            <th>Data</th>
            <th class="text-right">Importo</th>
            <th>Descrizione</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($this->movimenti AS $movimento): ?>            
            <tr class="<?php echo($movimento->getImporto() > 0) ? "success" : "danger"; ?>">
                <td><i><?php echo $movimento->getId(); ?></i></td>
                <td><strong><?php echo $movimento->getUser(); ?></strong></td>
                <td><?php echo $this->date($movimento->getData(), '%d/%m/%Y %H:%M'); ?></td>
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
  </div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/add"><span class="glyphicon glyphicon-plus"></span> Aggiungi movimento</a>
      <br /><br />
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/ordertoclose"><span class="glyphicon glyphicon-list-alt"></span> Ordini da chiudere</a>
      <br /><br />
      <a class="btn btn-default btn-mylg" href="/gestione-cassa/viewsaldi"><span class="glyphicon glyphicon-euro"></span> Saldi di cassa</a>
  </div>
</div>
      