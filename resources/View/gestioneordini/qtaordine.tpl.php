<?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>

<div class="row">
  <div class="col-md-8">
    <h3 class="big-margin-top">Quantità ordinate per utente</h3>
<?php if($this->ordCalcObj->getProdottiUtenti() > 0): ?>
    <?php foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $user): ?>
        <h3 id="user_<?php echo $iduser; ?>" class="big-margin-top"><strong><?php echo $user["nome"] . " " . $user["cognome"]; ?></strong></h3>
        <table class="table table-condensed">
            <thead>
              <tr>
                <th style="width:320px">Prodotto</th>
                <th style="width:190px">Quantità</th>
                <th class="text-right" style="width:80px">Totale</th>
              </tr>
            </thead>
            <tbody>
        <?php foreach ($user["prodotti"] AS $idprodotto => $pObj): ?>
                <tr id="trow_<?php echo $iduser; ?>_<?php echo $idprodotto;?>" <?php if(!$pObj->isDisponibile()): ?>class="danger strike"<?php endif; ?>>
                <?php include $this->template('gestioneordini/qtaordine-row.tpl.php'); ?>
                </tr>
        <?php endforeach; ?>
            </tbody>
        </table>        
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
  </div>
    <div class="col-md-4 col-right">
      <div class="bs-sidebar" data-spy="affix" data-offset-top="176" data-offset-bottom="150" role="complementary">
    <?php if(count($this->users) > 0): ?>
        <div class="totale big-margin-top">
          <button type="submit" id="submit" class="btn btn-success btn-mylg"><span class="glyphicon glyphicon-<?php echo($this->updated) ? "saved" : "save"; ?>"></span> SALVA</button>
        </div>
        <ul class="nav bs-sidenav">
        <?php foreach ($this->users as $iduser => $userVal): ?>
            <li>
              <a class="restricted" href="#user_<?php echo $iduser; ?>"><?php echo $userVal["nome"] . " " . $userVal["cognome"]; ?></a>
            </li>    
        <?php endforeach; ?>
        </ul>
      </div>
    <script>
    !function ($) {
      $(function(){
        var $window = $(window);
        var $body   = $(document.body);
        var navHeight = $('.header').outerHeight(true) + 10;
        $body.scrollspy({
          target: '.bs-sidebar',
          offset: navHeight
        });
        $window.on('load', function () {
          $body.scrollspy('refresh');
        });
        $('.moreThan30').tooltip('hide');
      })
    }(window.jQuery)
    </script>
    <?php endif; ?>
    </div>
</div>