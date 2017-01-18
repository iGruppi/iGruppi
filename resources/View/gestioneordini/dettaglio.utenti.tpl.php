<div class="row">
  <div class="col-md-12">
<?php if($this->ordCalcObj->getProdottiUtenti() > 0): ?>
    <?php foreach ($this->ordCalcObj->getProdottiUtenti() AS $iduser => $dataUser): 
              $user = $dataUser["user"];
        ?>
        <h4 class="big-margin-top"><strong><?php echo $user->nome . " " . $user->cognome; ?></strong></h4>
        <p>
            <?php echo $user->indirizzo; ?> - <?php echo $user->localita; ?> (<?php echo $user->provincia; ?>)<br />
            Tel: <?php echo $user->tel; ?><br />
            Email: <a href="mailto: <?php echo $user->email; ?>"><?php echo $user->email; ?></a>
        </p>
        <table class="table table-condensed">
            <thead>
              <tr>
                <th>Codice</th>
                <th>Descrizione</th>
                <th class="text-right">Quantità ordinata</th>
                <th class="text-right">Quantità reale</th>
                <th class="text-right">Prezzo unitario</th>
                <th class="text-right">Totale</th>
              </tr>
            </thead>
            <tbody>
        <?php foreach ($dataUser["prodotti"] AS $idprodotto => $pObj): ?>
            <?php if( $pObj->isDisponibile()): ?>
                <tr>
            <?php else: ?>
                <tr class="danger strike">
            <?php endif; ?>
                    <td><strong><?php echo $pObj->getCodice();?></strong></td>
                    <td><?php echo $pObj->getDescrizioneAnagrafica();?></td>
                    <td class="text-right"><?php echo $pObj->getQta_ByIduser($iduser) . " x " . $pObj->getDescrizioneUdmQtaOrdinata();?></td>
                    <td class="text-right"><?php echo $this->formatQta($pObj->getQtaReale_ByIduser($iduser)) . " " . $pObj->getUdm(); ?></td>
                    <td class="text-right"><?php echo $this->valuta($pObj->getCostoOrdine()) . "/" . $pObj->getUdm();?></td>
                    <td class="text-right"><strong><?php echo $this->valuta($pObj->getTotale_ByIduser($iduser)); ?></strong></td>
                </tr>        
        <?php endforeach; ?>
        <?php $extraArray = $this->ordCalcObj->getSpeseExtra_Utente($iduser);
            if(count($extraArray) > 0): ?>
            <?php foreach ($extraArray AS $extra): ?>
                <tr class="warning">
                    <td>&nbsp;</td>
                    <td colspan="3"><?php echo $extra["descrizione"]; ?> (<em><?php echo $extra["descrizioneTipo"]; ?></em>)</td>
                    <td class="text-right"><strong><?php echo $this->valuta($extra["parziale_utente"]); ?></strong></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>            
            </tbody>
        </table>        
        
        <div class="sub_menu">
            <h3 class="totale">Totale utente: <strong><?php echo $this->valuta($this->ordCalcObj->getTotaleConExtraByIduser($iduser)) ?></strong></h3>
        </div>                    
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    