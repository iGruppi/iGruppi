<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset class="border_top">
        <legend>Classificazione prodotto</legend>
        <?php echo $this->form->renderField('codice'); ?>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('idsubcat'); ?>
        <?php echo $this->form->renderField('attivo'); ?>
    </fieldset>

    <fieldset class="border_top">
        <legend>Prezzo</legend>
        <label for="costo" id="label_costo">Prezzo:</label>
        <input type="text" class="is_Number" name="costo" id="costo" size="10" required value="<?php echo $this->prodotto->costo; ?>">&nbsp;&euro;
        <br />
        <label for="aliquota_iva">IVA:</label>
        <select name="aliquota_iva" id="aliquota_iva" required onchange="setCostoLabel();">
    <?php foreach ($this->ivaObj->getArIVA() as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php if($key == $this->prodotto->aliquota_iva){ echo " selected"; } ?>><?php echo $value; ?></option>
    <?php endforeach; ?>
        </select>
        <br />    
        <label for="udm">Prezzo riferito a:</label>
        <select name="udm" id="udm" required>
    <?php foreach ($this->udmObj->getArUdm() as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php if($key == $this->prodotto->udm){ echo " selected"; } ?>><?php echo $value; ?></option>
    <?php endforeach; ?>
        </select>
        <br />    
    </fieldset>
    
    <fieldset class="border_top">
        <legend>Note</legend>
        <?php echo $this->form->renderField('note'); ?>
    </fieldset>

        <?php echo $this->form->renderField('idprodotto'); ?>
        <?php echo $this->form->renderField('idproduttore'); ?>

        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>

</form>
<script>
    function setCostoLabel()
    {
        if($('#aliquota_iva').val() > 0)
        {
            $('#label_costo').html('Prezzo (IVA compresa):');
        } else {
            $('#label_costo').html('Prezzo finale utente:');
        }
    }
    
    $(function() {
        setCostoLabel();
    });    
</script>