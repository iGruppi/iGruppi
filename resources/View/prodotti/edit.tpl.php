<h2>Modifica Prodotto di <strong><?php echo $this->produttore->ragsoc; ?></strong></h2>
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
        <?php echo $this->form->renderField('costo'); ?>
        <?php echo $this->form->renderField('udm'); ?>
        <div id="d_moltiplicatore" style="display: block;">
            <?php echo $this->form->renderField('moltiplicatore'); ?>
        </div>
        <?php echo $this->form->renderField('aliquota_iva'); ?>
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
    
    var arValWithMultip = <?php echo $this->arValWithMultip; ?>;
    
    function setCostoLabel()
    {
        if($('#aliquota_iva').val() > 0)
        {
            $('label[for=costo]').text('Prezzo (IVA compresa):');
        } else {
            $('label[for=costo]').text('Prezzo finale utente:');
        }
    }
    
    function setMoltiplicatore()
    {
        if( $('#udm').val() in arValWithMultip)
        {
            var myMultip = arValWithMultip[$('#udm').val()];
            $('#d_moltiplicatore').show();
            $('#np_moltiplicatore').html(myMultip.label);
            formatMoltiplicatore();
        } else {
            $('#d_moltiplicatore').hide();
            $('#np_moltiplicatore').html('-');
        }
    }
    
    /*
     * DA VERIFICARE
     *  Sembra non funzionare su Chrome Versione 33.0.1750.152
     *  -> Non imposta step correttamente quando step=1
     */
    
    function formatMoltiplicatore()
    {
        var myMultip = arValWithMultip[$('#udm').val()];
        var mObj = $('#moltiplicatore');
        mObj.attr('pattern', myMultip.pattern).attr('step', myMultip.step);
        // Format number for integer step
//        console.log("Step: "+ myMultip.step);
        if( myMultip.step === "1" )
        {
//            console.log(mObj.val());
            mObj.val( Math.round( mObj.val() ) );
//            console.log(mObj.val());
        }
    }
    
    $(function() {
        setCostoLabel();
        setMoltiplicatore();
        
        $('#moltiplicatore').change(formatMoltiplicatore);
        $('#udm').change(setMoltiplicatore);
        
    });    
</script>