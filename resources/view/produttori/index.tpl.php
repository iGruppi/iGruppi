<h2>Elenco Produttori</h2>

<div id="right_col">
    <h3><a class="menu" href="/produttori/add"><span class="box_menu_text">Nuovo produttore</span></a></h3>
</div>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $produttore):
            ?>
        <div class="box_row" id="box_<?php echo $produttore->idproduttore;?>">
            <div class="sub_menu">
                <a class="menu" href="/prodotti/list/idproduttore/<?php echo $produttore->idproduttore;?>">Prodotti</a>
        <?php if($produttore->refObj->is_Referente()): ?>
                <a class="menu" href="/gestione-ordini/index/idproduttore/<?php echo $produttore->idproduttore;?>">Elenco ordini</a>
                <a class="menu" href="/gestione-ordini/new/idproduttore/<?php echo $produttore->idproduttore;?>">Nuovo ordine</a>
        <?php endif; ?>
            </div>

        <?php if($produttore->refObj->is_Referente()): ?>
            <h3 class="dom_title"><a href="/produttori/edit/idproduttore/<?php echo $produttore->idproduttore;?>"><?php echo $produttore->ragsoc;?></a></h3>
        <?php else: ?>
            <h3 class="dom_title"><?php echo $produttore->ragsoc;?></h3>
        <?php endif; ?>
            
            <p id="p_details_<?php echo $produttore->idproduttore;?>">
                Referente: <strong><?php echo $produttore->nome . " " . $produttore->cognome; ?></strong>
            </p>
            <div class="my_clear" style="clear:both;">&nbsp;</div>
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun ordine disponibile!</h3>
<?php endif; ?>
</div>
