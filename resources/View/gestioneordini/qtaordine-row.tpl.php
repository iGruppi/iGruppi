<?php if($pObj->isDisponibile()): 
        $keyrow = $iduser . "_" . $idprodotto;
    ?>
<tr>
    <td><?php echo $pObj->descrizione;?><br />
        <?php echo $pObj->getDescrizionePrezzo();?> (Codice: <?php echo $pObj->codice;?>)<br />
        <?php if($pObj->hasPezzatura()): ?>
            <small>Pezzatura/Taglio: <?php echo $pObj->getDescrizionePezzatura(); ?></small>
        <?php endif; ?>
    </td>
    <td><a id="btn_<?php echo $keyrow;?>" data-loading-text="..." onclick="jx_ReferenteModifyQta(<?php echo $iduser; ?>,<?php echo $idprodotto;?>, <?php echo $this->ordCalcObj->getIdOrdine();?>)" class="btn btn-default pull-left" href="javascript:void(0)"><span class="glyphicon glyphicon-pencil"></span></a></td>
    <td>
        Ordinata: <strong><?php echo $pObj->getQtaOrdinata();?></strong><br />
        <span id="qtareal_<?php echo $keyrow;?>">
            Effettiva: <strong><?php echo $pObj->getQtaReale();?></strong> <?php echo $pObj->getUdm(); ?>
        </span>
        <div style="display: none;" id="div_chgqta_<?php echo $keyrow;?>"></div>
    </td>
    <td class="text-right" id="td_totrow_<?php echo $keyrow;?>">
        <strong><?php echo $this->valuta($pObj->getTotale()); ?></strong>
    </td>
</tr>
<?php else: ?>
<tr>
    <td class="danger strike"><?php echo $pObj->descrizione;?><br />
        <?php echo $pObj->getDescrizionePrezzo();?> (Codice: <?php echo $pObj->codice;?>)<br />
        <?php if($pObj->hasPezzatura()): ?>
            <small>Pezzatura/Taglio: <?php echo $pObj->getDescrizionePezzatura(); ?></small>
        <?php endif; ?>
    </td>
    <td class="danger">&nbsp;</td>
    <td class="danger strike">
        Ordinata: <strong><?php echo $pObj->getQtaOrdinata();?></strong><br />
        Effettiva: <strong><?php echo $pObj->getQtaReale();?></strong> <?php echo $pObj->getUdm(); ?>
    </td>
    <td class="danger text-right">
        <strong class="no_strike">NON DISPONIBILE!</strong>
    </td>
</tr>
<?php endif; ?>