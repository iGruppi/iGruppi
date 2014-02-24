<?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>

<?php if($this->statusObj->can_ModificaProdotti()): ?>
    <?php include $this->template('gestioneordini/prodotti.aperto.tpl.php'); ?>
<?php else: ?>
    <?php include $this->template('gestioneordini/prodotti.chiuso.tpl.php'); ?>
<?php endif; ?>
