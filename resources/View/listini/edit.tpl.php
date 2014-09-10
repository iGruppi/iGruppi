<h2>Modifica Listino <strong><?php echo $this->listino->getDati()->getDescrizione(); ?></strong></h2>
<h3>Produttore: <strong><?php echo $this->listino->getDati()->getProduttoreName(); ?></strong></h3>

<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n120">
<div class="row">
  <div class="col-md-8">

<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Listino aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>


        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#dati" data-toggle="tab">Dati listino</a></li>
          <li><a href="#sharing" data-toggle="tab">Condivisione</a></li>
          <li><a href="#prodotti" data-toggle="tab">Prodotti</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="dati">
              <?php include $this->template('listini/edit.dati.tpl.php'); ?>
          </div>
          <div class="tab-pane" id="sharing">
              <?php include $this->template('listini/edit.condivisione.tpl.php'); ?>
          </div>
          <div class="tab-pane" id="prodotti">
              <?php echo $this->partial('listini/edit.prodotti.tpl.php', array('listino' => $this->listino)); ?>
          </div>
        </div>

        <?php echo $this->form->renderField('idproduttore'); ?>
        <?php echo $this->form->renderField('idlistino'); ?>
  </div>
  <div class="col-md-3 col-md-offset-1">
    <div class="row row-margin-bottom">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <button type="submit" class="btn btn-success btn-mylg">SALVA</button>
        </div>
    </div>      
    <?php if($this->listino->getProdotti()->countOutOfListino() > 0): ?>
    <div class="row row-margin-bottom">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-info alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              Attenzione!<br />
              <a href="#prodotti" data-toggle="tab"><?php echo $this->listino->getProdotti()->countOutOfListino(); ?> nuovi Prodotti</a><br /> 
              <small>non presenti in Listino</small>
            </div>
        </div>
    </div>      
    <?php endif; ?>
  </div>
</div>
</form>
<script>
    $(function() {
        // get Hash from URL
        hash = window.location.hash;
        // set TAB by hash (if it exists)
        $('a[href="' + hash + '"]').tab('show');
        
        // Run always to init
        setCondivisione();
        $('#condivisione').change(setCondivisione);
        setValidita();
        $('#validita').change(setValidita);
        
        $('#valido_dal').datetimepicker({
            lang:   'it',
            i18n:   { it:{ months:mesi, dayOfWeek:giorni} },
            format: 'd/m/Y',
            timepicker:false,
            onShow: function( ct ){
                if($('#valido_al').val())
                {
                    this.setOptions({ 
                        maxDate: moment($('#valido_al').val(), "DD/MM/YYYY").subtract('days', 1).format("YYYY/MM/DD")
                    })
                }
            }
        });
        
        $('#valido_al').datetimepicker({
            lang:'it',
            i18n:{ it:{ months:mesi, dayOfWeek:giorni} },
            format:'d/m/Y',
            timepicker:false,
            onShow:function( ct ){
                if($('#valido_dal').val())
                {
                    this.setOptions({ 
                        minDate: moment($('#valido_dal').val(), "DD/MM/YYYY").add('days', 1).format("YYYY/MM/DD")
                    })
                }
              }
        });
    });
    
    function setCondivisione()
    {
        if($('#condivisione').val() === "SHA")
        {
            $('#d_sharing').show();
        } else {
            $('#d_sharing').hide();
        }        
    }
    
    function setValidita()
    {
        if($('#validita').val() === "S")
        {
            $('#d_validita').show();
        } else {
            $('#d_validita').hide();
        }        
    }
    
</script>