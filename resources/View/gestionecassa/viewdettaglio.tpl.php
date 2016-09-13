<?php echo $this->partial('gestioneordini/header-title.tpl.php', array('ordine' => $this->ordine) ); ?>

<h3 class="big-margin-top">Riepilogo per utente</h3>
<?php if($this->ordCalcoli->getProdottiUtenti() > 0): ?>
<div class="row">
    <div class="col-md-8">
        <table class="table table-condensed">
            <thead>
              <tr>
                <th>Utente</th>
                <th class="text-right">Totale</th>
              </tr>
            </thead>
            <tbody>
    <?php foreach ($this->ordCalcoli->getProdottiUtenti() AS $iduser => $dataUser):
                $user = $dataUser["user"];
        ?>
                <tr>
                    <td><b><?php echo $user->nome . " " . $user->cognome; ?></b></td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcoli->getTotaleConExtraByIduser($iduser)) ?></strong></td>
                </tr>
    <?php endforeach; ?>
                <tr class="success">
                    <td>Totale: </td>
                    <td class="text-right"><strong><?php echo $this->valuta($this->ordCalcoli->getTotaleConExtra()); ?></strong></td>
                </tr>
            </tbody>
        </table>        
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    </div>
<?php else: ?>
    <div class="col-md-8">
        <div class="lead">Nessun prodotto ordinato!</div>
    </div>
<?php endif; ?>
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-3">
<?php if($this->ordCalcoli->canArchiviaOrdine()): ?>      
        <a class="btn btn-success" role="button" href="/gestione-cassa/archivia/idordine/<?php echo $this->ordCalcoli->getIdOrdine();?>"><span class="glyphicon glyphicon-ok"></span> Archivia</a>
<?php endif; ?>    
    </div>  
</div>
        
