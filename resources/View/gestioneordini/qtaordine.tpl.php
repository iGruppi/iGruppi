<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>

<div class="row">
<?php if($this->ordine->canRef_ModificaQtaOrdinate()): ?>
    <div class="col-md-8">
    <h3 class="big-margin-top">Quantità ordinate per utente</h3>
    <?php if($this->ordCalcObj->getProdottiUtenti() > 0): 
            $users = array();
            foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $user): 
                // create array of Users
                if(!in_array($iduser, $users))
                {
                    $users[] = $iduser;
                }
            ?>
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
                    echo $this->partial('gestioneordini/qtaordine-row.tpl.php', array('pObj' => $pObj, 'idordine' => $this->ordine->getIdOrdine(), 'iduser' => $iduser, 'idprodotto' => $idprodotto));
                  endforeach; ?>
                    <tr id="tr_last_<?php echo $iduser; ?>" style="display: none;"><td colspan="4"></td></tr>
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
                        <td colspan="4" id="td_add_<?php echo $iduser; ?>">
                            <a href="javascript:void(0);" onclick="jx_ReferenteAddNewProd(<?php echo $iduser; ?>,<?php echo $this->ordine->getIdOrdine(); ?>)" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Aggiungi</a>
                            <div id="div_add_<?php echo $iduser; ?>" style="padding: 5px; display: none;"></div>
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
    <?php if(count($users) > 0): ?>
        <ul class="nav bs-sidenav">
            <li><a href="javascript: void(0);" onclick="$('html,body').animate({scrollTop: $('#wrap').offset().top});" class="text-right"><small>Top <span class="glyphicon glyphicon-circle-arrow-up"></span></small></a></li>

        <?php foreach ($users as $iduser): 
                $userVal = $this->ordCalcObj->getDatiUtenteById($iduser);
            ?>
            <li>
              <a class="restricted" href="#user_<?php echo $iduser; ?>"><?php echo $userVal->nome . " " . $userVal->cognome; ?></a>
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
    
<?php else: ?>
    <div class="col-md-8">
    <h3 class="big-margin-top">Quantità ordinate per utente</h3>
        <p>Non è possibile modificare le quantità ordinate perchè l'ordine è in stato: <span class="<?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></span></p>
    </div>
<?php endif; ?>
</div>

