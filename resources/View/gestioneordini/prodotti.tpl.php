<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<p>
    Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M:%S');?></strong><br />
    Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M:%S');?></strong>
</p>    
<div id="content_list1" style="margin-top: 50px;">
    <?php if(count($this->list) > 0): ?>
        <?php if($this->statusObj->can_ModificaProdotti()): ?>
            <?php include $this->template('gestioneordini/prodotti.aperto.tpl.php'); ?>
        <?php else: ?>
            <?php include $this->template('gestioneordini/prodotti.chiuso.tpl.php'); ?>
        <?php endif; ?>
    <?php else: ?>
        <h3>Nessun prodotto disponibile!</h3>
    <?php endif; ?>
</div>
