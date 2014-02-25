<td><?php echo $pObj->descrizione;?><br /><?php echo $pObj->getPrezzo();?> &euro; / <?php echo $pObj->udm; ?> (Codice: <?php echo $pObj->codice;?>)</td>
<td><strong><?php echo $pObj->getQta();?></strong></td>
<td>
<?php if($pObj->isDisponibile()): ?>
    <form id="qta_ord_form_<?php echo $iduser; ?>_<?php echo $idprodotto;?>" class="ordini" onsubmit="jx_RefModQta_Save(<?php echo $iduser; ?>,<?php echo $idprodotto;?>,<?php echo $this->ordine->idordine;?>); return false;" method="post">
        <input type="number" class="field_in_table" id="qta_eff_<?php echo $iduser; ?>_<?php echo $idprodotto;?>"
               pattern="[0-9]+([\.|,][0-9]+)?" step="0.001" required data-loading-text="Loading..."
               name="qta_reale" value="<?php echo $pObj->getQtaReale();?>" 
               title="Deve essere un numero, per i decimali utilizzare o il punto (.) o la virgola (,)" 
               onchange="jx_ReferenteModifyQta(<?php echo $iduser; ?>,<?php echo $idprodotto;?>)" />
        <input type='hidden' id="qta_eff_old_<?php echo $iduser; ?>_<?php echo $idprodotto;?>" value="<?php echo $pObj->getQtaReale();?>" />
        <input type='hidden' name="idordine" value="<?php echo $this->ordine->idordine;?>" /> 
        <input type='hidden' name="iduser" value="<?php echo $iduser;?>" />
        <input type='hidden' name="idprodotto" value="<?php echo $idprodotto;?>" />
        <button style="display: none;" class="btn btn-success btn-xs" id="btn_<?php echo $iduser; ?>_<?php echo $idprodotto;?>">Aggiorna</button>
    </form>
<?php endif; ?>
</td>
<td class="text-right" id="tdrow_<?php echo $iduser; ?>_<?php echo $idprodotto;?>"><strong><?php echo $this->valuta($pObj->getTotaleReale()); ?></strong></td>
