<div style="padding: 5px;">
    <form id="qta_ord_form_<?php echo $this->iduser; ?>_<?php echo $this->idprodotto;?>" class="ordini" 
          onsubmit="jx_RefModQta_Save(<?php echo $this->iduser; ?>,<?php echo $this->idprodotto;?>,<?php echo $this->idordine;?>); return false;" method="post">
        <input type="text" class="field_in_table is_Number" id="qta_eff_<?php echo $this->iduser; ?>_<?php echo $this->idprodotto;?>"
               name="qta_reale" value="<?php echo $this->pObj->getQtaReale_ByIduser($this->iduser);?>" required size="5"
               onkeyup="this.formatNumber()" />
        <?php echo $this->pObj->getUdm(); ?><br />
        <button style="margin: 2px;" class="btn btn-warning btn-xs" id="submit_<?php echo $this->iduser; ?>_<?php echo $this->idprodotto;?>">Aggiorna</button>
        <input type='hidden' name="idordine" value="<?php echo $this->idordine;?>" /> 
        <input type='hidden' name="iduser" value="<?php echo $this->iduser;?>" />
        <input type='hidden' name="idprodotto" value="<?php echo $this->idprodotto;?>" />
    </form>
</div>    