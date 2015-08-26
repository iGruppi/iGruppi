<h2>Modifica Produttore <strong><?php echo $this->produttore->ragsoc; ?></strong></h2>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Produttore aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>
    
    
    <form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n150">

        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#dati" data-toggle="tab">Anagrafica</a></li>
          <li><a href="#categorie" data-toggle="tab">Categorie prodotti</a></li>
          <li><a href="#note" data-toggle="tab">Note</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="dati">
              <?php include $this->template('produttori/form.dati.tpl.php'); ?>
          </div>
          <div class="tab-pane" id="categorie">
              <?php include $this->template('produttori/form.cat.tpl.php'); ?>
          </div>
          <div class="tab-pane" id="note">
              <fieldset>
                <?php echo $this->form->renderField('note'); ?>      
              </fieldset>
          </div>
        </div>

        <?php echo $this->form->renderField('idproduttore'); ?>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
    </form>
  <div class="col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
  <div class="col-md-3 col-sm-3 col-xs-3">
      &nbsp;
  </div>
</div>
<script>
  $(function () {
    $('#myTab a:first').tab('show')
  })
</script> 