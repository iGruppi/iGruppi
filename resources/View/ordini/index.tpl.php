<h2>Elenco Ordini del <strong><?php echo "Gruppo"; ?></strong></h2>

<div id="right_col" class="links_order">
    <?php 
    if(count($this->produttori) > 0):
        foreach ($this->produttori as $key => $produttore): ?>
        <?php if( $produttore->idproduttore == $this->idproduttore ): ?>
        <h3><span class="menu_low_selected"><?php echo $produttore->ragsoc; ?></span></h3>
        <?php else: ?>
        <h3><a class="menu_low" href="/ordini/index/idproduttore/<?php echo $produttore->idproduttore; ?>"><span class="box_menu_text"><?php echo $produttore->ragsoc; ?></span></a></h3>
        <?php endif; ?>
    <?php 
        endforeach; 
    endif; ?>

</div>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $ordine):
            ?>
        <div class="box_row" id="box_<?php echo $ordine->idordine;?>">
        <?php if(!$ordine->statusObj->is_Pianificato()): ?>
            <div class="sub_menu" style="margin-top: 40px;">
            <?php if($ordine->statusObj->is_Aperto()): ?>
                <a class="menu" href="/ordini/ordina/idordine/<?php echo $ordine->idordine;?>">Ordina ora!</a>
            <?php endif; ?>
                <a class="menu" href="/ordini/viewdettaglio/idordine/<?php echo $ordine->idordine;?>">Visualizza dettaglio</a>
            </div>
        <?php endif; ?>

            <h3 class="dom_title">Ordine per <strong><?php echo $ordine->ragsoc;?></strong></h3>
        <?php if($ordine->statusObj->is_Pianificato()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>">
                <?php echo $ordine->statusObj->getStatus(); ?>
                per il <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Aperto()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>            
            <p>
                Chiusura prevista il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M:%S');?></strong>
            </p>
        <?php elseif($ordine->statusObj->is_Chiuso()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>">
                <?php echo $ordine->statusObj->getStatus(); ?>
                il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Archiviato()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>            
            <p>
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_inizio, '%H:%M:%S');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M:%S');?></strong>
            </p>
        <?php endif; ?>

        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
</div>
