<h2>Elenco Ordini per <strong><?php echo $this->produttore->ragsoc; ?></strong></h2>

<div id="right_col">
    <h3><a class="menu" href="/gestione-ordini/new/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="box_menu_text">Nuovo ordine</span></a></h3>
</div>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $ordine):
            ?>
        <div class="box_row" id="box_<?php echo $ordine->idordine;?>">
            <div class="sub_menu" style="margin-top: 40px;">
                <a class="menu" href="/gestione-ordini/edit/idordine/<?php echo $ordine->idordine;?>">Modifica ordine</a>
                <a class="menu" href="/gestione-ordini/prodotti/idordine/<?php echo $ordine->idordine;?>">Prodotti</a>
                <a class="menu" href="/gestione-ordini/dettaglio/idordine/<?php echo $ordine->idordine;?>">Dettaglio ordine</a>
            </div>

            <h3 class="dom_title">Ordine del <strong><?php echo $this->date($ordine->data_inizio, '%d %B %Y');?></strong></h3>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>
            <p>
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_inizio, '%H:%M:%S');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M:%S');?></strong>
            </p>
            <div class="my_clear" style="clear:both;">&nbsp;</div>
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
</div>
