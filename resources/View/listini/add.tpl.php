<h2>Aggiungi Listino</h2>
<div class="row">
  <div class="col-md-8">
        <form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
            <fieldset>
                <?php echo $this->form->renderField('descrizione'); ?>
                <?php echo $this->form->renderField('idproduttore'); ?>
            </fieldset>        

            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
            </form>
      </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <!-- RIGHT SIDEBAR -->
  </div>
</div>
