    <h2>Aggiungi Produttore</h2>
    
    <form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
      <?php include $this->template('produttori/form.dati.tpl.php'); ?>
        
      <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
    </form>
    