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
                <th style="width:40px">&nbsp;</th>
                <th style="width:190px">Quantità</th>
                <th class="text-right" style="width:80px">Totale</th>
              </tr>
            </thead>
            <tbody>
        <?php foreach ($user["prodotti"] AS $idprodotto => $pObj):
                include $this->template('gestioneordini/qtaordine-row.tpl.php');
              endforeach; ?>
        <?php if($this->ordCalcObj->hasCostoSpedizione() && $this->ordCalcObj->getTotaleByIduser($iduser)): ?>
                <tr class="warning">
                    <td><b>Spese di spedizione</b></td>
                    <td colspan="2">&nbsp;</td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcObj->getSpedizione()->getCostoSpedizioneRipartitoByIduser($iduser)); ?></strong></td>
                </tr>
        <?php endif; ?>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td><b>Totale:</b></td>
                    <td class="text-right" id="td_grandtotrow_<?php echo $iduser;?>"><strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConSpedizioneByIduser($iduser)); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <a href="#" class="btn btn-success" id="btn_<?php echo $iduser; ?>"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                    </td>
                </tr>
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
        <ul class="nav bs-sidenav">
            <li><a href="javascript: void(0);" onclick="$('html,body').animate({scrollTop: $('#wrap').offset().top});" class="text-right"><small>Top <span class="glyphicon glyphicon-circle-arrow-up"></span></small></a></li>
            
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
<div id="form_qta_modify" style="display: none; padding: 5px;">
    <form id="qta_ord_form" class="ordini" 
          onsubmit="" method="post">
        <input type="text" class="field_in_table is_Number" id="qta_eff"
               required data-loading-text="Loading..." size="5"
               name="qta_reale" value="" 
               onkeyup="this.formatNumber()" />
        Udm<br />
        <button style="margin: 2px;" class="btn btn-warning btn-xs" id="submit_aggiorna">Aggiorna</button>
        <input type='hidden' name="idordine" value="" /> 
        <input type='hidden' name="iduser" value="" />
        <input type='hidden' name="idprodotto" value="" />
    </form>
</div>  
<script>
    // Start these procedures always
	$(document).ready(function(){
        console.log(UserOrder.calculateTotal(1));
    });
</script>
