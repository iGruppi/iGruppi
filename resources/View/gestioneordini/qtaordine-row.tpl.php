<tr>
<?php if($this->pObj->isDisponibile()): 
        $keyrow = $this->iduser . "_" . $this->idprodotto;
    ?>
    <td><?php echo $this->pObj->descrizione;?><br />
        <?php echo $this->pObj->getDescrizioneCosto();?> (Codice: <?php echo $this->pObj->codice;?>)<br />
        <?php if($this->pObj->hasPezzatura()): ?>
            <small>Pezzatura/Taglio: <?php echo $this->pObj->getDescrizionePezzatura(); ?></small>
        <?php endif; ?>
    </td>
    <td><a id="btn_<?php echo $keyrow;?>" data-loading-text="..." onclick="jx_ReferenteModifyQta(<?php echo $this->iduser; ?>,<?php echo $this->idprodotto;?>, <?php echo $this->idordine;?>)" class="btn btn-default pull-left" href="javascript:void(0)"><span class="glyphicon glyphicon-pencil"></span></a></td>
    <td>
        Ordinata: <strong><?php echo $this->pObj->getQta();?></strong><br />
        <span id="qtareal_<?php echo $keyrow;?>">
            Effettiva: <strong><?php echo $this->pObj->getQtaReale();?></strong> <?php echo $this->pObj->getUdm(); ?>
        </span>
        <div style="display: none;" id="div_chgqta_<?php echo $keyrow;?>"></div>
    </td>
    <td class="text-right" id="td_totrow_<?php echo $keyrow;?>">
        <strong><?php echo $this->valuta($this->pObj->getTotale()); ?></strong>
    </td>
<?php else: ?>
    <td class="danger strike"><?php echo $this->pObj->descrizione;?><br />
        <?php echo $this->pObj->getDescrizioneCosto();?> (Codice: <?php echo $this->pObj->codice;?>)<br />
        <?php if($this->pObj->hasPezzatura()): ?>
            <small>Pezzatura/Taglio: <?php echo $this->pObj->getDescrizionePezzatura(); ?></small>
        <?php endif; ?>
    </td>
    <td class="danger">&nbsp;</td>
    <td class="danger strike">
        Ordinata: <strong><?php echo $this->pObj->getQta();?></strong><br />
        Effettiva: <strong><?php echo $this->pObj->getQtaReale();?></strong> <?php echo $this->pObj->getUdm(); ?>
    </td>
    <td class="danger text-right">
        <strong class="no_strike">NON DISPONIBILE!</strong>
    </td>
<?php endif; ?>
</tr>