<td><?php echo $pObj->descrizione;?><br />
    <?php echo $pObj->getDescrizionePrezzo();?> (Codice: <?php echo $pObj->codice;?>)<br />
    <?php if($pObj->hasPezzatura()): ?>
        <small>Pezzatura/Taglio: <?php echo $pObj->getDescrizionePezzatura(); ?></small>
    <?php endif; ?>
</td>
<td>Ordinata: <strong><?php echo $pObj->getQtaOrdinata();?></strong><br />
<?php  if($pObj->isDisponibile()): ?>
    <form id="qta_ord_form_<?php echo $iduser; ?>_<?php echo $idprodotto;?>" class="ordini" onsubmit="jx_RefModQta_Save(<?php echo $iduser; ?>,<?php echo $idprodotto;?>,<?php echo $this->ordine->idordine;?>); return false;" method="post">
       Effettiva: <input type="text" class="field_in_table is_Number" id="qta_eff_<?php echo $iduser; ?>_<?php echo $idprodotto;?>"
               required data-loading-text="Loading..." size="5"
               name="qta_reale" value="<?php echo $pObj->getQtaReale();?>" 
               onkeyup="jx_ReferenteModifyQta(<?php echo $iduser; ?>,<?php echo $idprodotto;?>)" /> <strong><?php echo $pObj->getUdm(); ?><br />
        <input type='hidden' id="qta_eff_old_<?php echo $iduser; ?>_<?php echo $idprodotto;?>" value="<?php echo $pObj->getQtaReale();?>" />
        <input type='hidden' name="idordine" value="<?php echo $this->ordine->idordine;?>" /> 
        <input type='hidden' name="iduser" value="<?php echo $iduser;?>" />
        <input type='hidden' name="idprodotto" value="<?php echo $idprodotto;?>" />
        <button style="display: none;" class="btn btn-success btn-xs" id="btn_<?php echo $iduser; ?>_<?php echo $idprodotto;?>">Aggiorna</button>
    </form>
<?php endif; ?>
</td>
<td class="text-right" id="tdrow_<?php echo $iduser; ?>_<?php echo $idprodotto;?>"><strong><?php echo $this->valuta($pObj->getTotale()); ?></strong></td>