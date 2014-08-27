<h2>Modifica Listino <strong><?php echo $this->listino->getDati()->descrizione; ?></strong></h2>
<h3>Produttore: <strong><?php echo $this->listino->getDati()->getProduttoreName(); ?></strong></h3>
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Listino aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200" novalidate>

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
          <fieldset>
            <?php echo $this->form->renderField('note'); ?>      
          </fieldset>
      </div>
    </div>

    <?php echo $this->form->renderField('idproduttore'); ?>
    <?php echo $this->form->renderField('idlistino'); ?>

    <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>

<script>
    $(function() {
        
        $('#myTab a:first').tab('show');
        
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