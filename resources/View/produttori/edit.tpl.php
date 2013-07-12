    <h2>Modifica Produttore</h2>
<?php if(isset($this->updated) && $this->updated): ?>
    <h3>Aggiornato con successo!</h3>
<?php else: ?>
    
	<?php include $this->template('produttori/form.tpl.php'); ?>
    
<?php endif; ?>