    
<h3>Prodotti:</h3>
<p>Segue l'elenco di <b>tutti</b> i <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti di <?php echo $this->produttore->ragsoc;?></a>.<br />
    La lista non è modificabile perchè l'ordine è <strong><?php echo $this->statusObj->getStatus(); ?></strong>.</p>

<?php foreach ($this->list as $key => $prodotto): ?>

    <div class="row row-myig<?php echo ($prodotto->selected) ? "" : " box_row_dis" ; ?>">
        <div class="col-md-8">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo);?></strong> / <strong><?php echo $prodotto->udm; ?></strong>
            </p>
        </div>
    </div>

<?php endforeach; ?>
