<h3>Sei iscritto al gruppo: <strong><?php echo $this->group->nome; ?></strong></h3>
<p><?php echo $this->group->descrizione; ?></p>

<p class="lead <?php echo($this->saldo > 0) ? "text-success" : "text-danger"; ?>">Saldo: <b><?php echo $this->valuta($this->saldo); ?></b></p>

<h3>Ultimi movimenti</h3>
<div class="row">
    <div class="col-md-9">
    <table class="table table-condensed">
        <thead>
          <tr>
            <th>ID</th>
            <th>Data</th>
            <th class="text-right">Importo</th>
            <th>Descrizione</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($this->movimenti AS $movimento): ?>            
            <tr class="<?php echo($movimento->getImporto() > 0) ? "success" : "danger"; ?>">
                <td><i><?php echo $movimento->getId(); ?></i></td>
                <td><?php echo $this->date($movimento->getData(), '%d/%m/%Y %H:%M'); ?></td>
                <td class="text-right"><strong><?php echo $this->valuta($movimento->getImporto()); ?></strong></td>
                <td>
                    <?php if($movimento->isRelatedToOrdine()): ?>
                    <a href="/ordini/viewdettaglio/idordine/<?php echo $movimento->getIdOrdine(); ?>"><?php echo $movimento->getDescrizione(); ?></a><br />
                    Data Ordine: <?php echo $this->date($movimento->getDataOrdine(), '%d/%m/%Y'); ?>
                    <?php else: ?>
                        <?php echo $movimento->getDescrizione(); ?>
                    <?php endif; ?>
                </td>
            </tr>        
<?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="col-md-3 dashboard-right">
        
    </div>
</div>

