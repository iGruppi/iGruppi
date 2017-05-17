<h2>Aggiungi Produttore</h2>
<div class="row">
  <div class="col-md-8 col-sm-8 col-xs-8">
    <form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
        <fieldset>
            <?php echo $this->form->renderField('ragsoc'); ?>
            <?php echo $this->form->renderField('p_iva'); ?>
        </fieldset>
      <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
    </form>
  </div>
  <div class="col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
  <div class="col-md-3 col-sm-3 col-xs-3">
      &nbsp;
  </div>
</div>