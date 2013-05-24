    <h2>Aggiungi Prodotto</h2>
<?php if(isset($this->added)): ?>
    <h3>Aggiunto con successo!</h3>
<?php else: ?>
    
	<?php include $this->template('prodotti/form.tpl.php'); ?>
    
<?php endif; ?>