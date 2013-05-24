    <h2>Aggiungi Produttore</h2>
<?php if(isset($this->added)): ?>
    <h3>Aggiunto con successo!</h3>
<?php else: ?>
    
	<?php include $this->template('produttori/form.tpl.php'); ?>
    
<?php endif; ?>