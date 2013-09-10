<h2>Elenco Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<?php if($this->produttore->refObj->is_Referente()): ?>
    <div id="right_col">
        <h3><a class="menu" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="box_menu_text">Nuovo prodotto</span></a></h3>
    </div>
<?php endif; ?>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $prodotto):
            ?>
        <div class="box_row" id="box_<?php echo $prodotto->idprodotto;?>">
        <?php if($this->produttore->refObj->is_Referente()): ?>
            <div class="sub_menu">
                <a class="menu" href="/prodotti/edit/idprodotto/<?php echo $prodotto->idprodotto;?>">Modifica</a>
            </div>
        <?php endif; ?>

            <h3 class="dom_title"><?php echo $prodotto->descrizione;?></h3>
            
            <p id="p_details_<?php echo $prodotto->idprodotto;?>">
                Codice: <strong><?php echo $prodotto->codice; ?></strong><br />
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo); ?> / <?php echo $prodotto->udm; ?></strong><br />
                Disponibile: <strong class="attivo_<?php echo $prodotto->attivo; ?>"><?php echo $this->yesno($prodotto->attivo); ?></strong>
            </p>
                
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun prodotto!</h3>
<?php endif; ?>
</div>
