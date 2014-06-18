/* Jx Functions */

    function jx_SelProdottoOrdine(idprodotto) {
        
        var idSelected = $('#prod_sel_'+idprodotto);
        if(idSelected.val() == "S") {
            $('#img_sel_'+idprodotto).addClass('ok').removeClass('delete');
            $('#box_'+idprodotto).addClass('box_row_dis');
            idSelected.val("N");
            
        } else {
            $('#img_sel_'+idprodotto).addClass('delete').removeClass('ok');
            $('#box_'+idprodotto).removeClass('box_row_dis');
            idSelected.val("S");
        }
        
    }
    
    function jx_AddSubCategoria(idproduttore) {
        $('#no_subCat').hide();
        var idcat = $('#idcat').val();
        var catName = $('#idcat').find(":selected").text();
        $.getJSON(
			'/produttori/addcat/',
            {idcat: idcat, catName: catName, idproduttore: idproduttore},
			function(data) {
                if(data.res) {
                    $('#d_subCat').prepend(data.myTpl);
                }
			});
    }
    
    function jx_DelSubCategoria(idsubcat) {
        $.getJSON(
			'/produttori/delcat/',
            {idsubcat: idsubcat},
			function(data) {
                if(data.res) {
                    $('#subcat_'+idsubcat).remove();
                } else {
                    $('#subcat_'+idsubcat+' > .alert').show();
                    $('#subcat_'+idsubcat+' > .alert > .alert_red').html(data.prodotti.length);
                    for (var i in data.prodotti) {
                        $('#subcat_'+idsubcat+' > .alert > ul').append('<li><a href="/prodotti/edit/idprodotto/'+data.prodotti[i].idprodotto+'">'+data.prodotti[i].descrizione+'</a></li>');
                    }
                }
			});
    }
    
    function jx_AddReferenteUser(iduser) {
        $('#no_user_ref').hide();
        var idproduttore = $('#iduser_ref').find(":selected").val();
        $.getJSON(
			'/users/addref/',
            {iduser: iduser, idproduttore: idproduttore},
			function(data) {
                if(data) {
                    $('#list_user_ref').append('<h4>'+$('#iduser_ref').find(":selected").text()+'</h4>');
                }
			});
    }

    function jx_EnableUser(iduser) {
        $('#disabled_button_'+iduser).button('loading');
        $.getJSON(
			'/users/enable/',
            {iduser: iduser},
			function(data) {
                if(data) {
                    $('#disabled_'+iduser).remove();
                    $('#disabled_button_'+iduser).remove();
                }
			});
    }

    function jx_OrdineInConsegna(idordine, idproduttore) {
        $('#gest_ordine_'+idordine).button('loading');
        $.getJSON(
			'/gestione-ordini/inconsegna/',
            {idordine: idordine, idproduttore: idproduttore},
			function(data) {
                $('#ordine_'+idordine).html(data.myTpl);
			});
    }
    
    function jx_ReferenteModifyQta(iduser, idprodotto, idordine)
    {
        var keyRow = iduser+'_'+idprodotto;
        $('#btn_'+keyRow).button('loading');
        $.getJSON(
			'/gestione-ordini/getformqta/',
            {iduser: iduser, idprodotto: idprodotto, idordine: idordine},
			function(data) {
                if(data.res)
				{
                    $('#qtareal_'+keyRow).hide();
                    $('#div_chgqta_'+keyRow).html(data.myTpl).show();
                }
			});
    }
    
    function jx_RefModQta_Save(iduser, idprodotto, idordine)
    {
        var keyRow = iduser+'_'+idprodotto;
        $('#submit_'+keyRow).button('loading');
        // SET Number field value
        $('#qta_eff_'+keyRow)[0].setNumber();
        // store the new value
        var newValue = parseFloat($('#qta_eff_'+keyRow).val());
        $('#qtareal_'+keyRow+' > strong').html(newValue);
		$.post(
			'/gestione-ordini/changeqta/idordine/'+idordine,
			$('#qta_ord_form_'+keyRow).serialize(),
			function(data){
                if(data.res)
				{
                    var tot = parseFloat(data.newTotale);
                    var grandTot = parseFloat(data.grandTotal);
                    $('#td_totrow_'+keyRow+' > strong').html(tot.formatNumber(2, ',', '')+"&nbsp;&euro;");
                    $('#td_grandtotrow_'+iduser+' > strong').html(grandTot.formatNumber(2, ',', '')+"&nbsp;&euro;");
                    $('#qtareal_'+keyRow).show();
                    $('#div_chgqta_'+keyRow).html(' ').hide();
                    $('#btn_'+keyRow).button('reset');
                }
			},
			"json");
    }
    
    function jx_ReferenteAddNewProd(iduser, idordine)
    {
        $('#td_add_'+iduser+' > a').button('loading');
        $.getJSON(
			'/gestione-ordini/newprodform/',
            {iduser: iduser, idordine: idordine},
			function(data) {
                if(data.res)
				{
                    $('#td_add_'+iduser+' > a').button('reset').hide();
                    $('#div_add_'+iduser).html(data.myTpl).show();
                }
			});
    }

    function jx_ReferenteAddNewProd_Save(iduser, idordine)
    {
        $('#submit_'+iduser).button('loading');
        var idprodotto = $('#addprod_form_'+iduser+' > #idprodotto').val();
        if(idprodotto > 0) {
            $.post(
			'/gestione-ordini/newprodsave/idordine/'+idordine,
			$('#addprod_form_'+iduser).serialize(),
			function(data){
                if(data.res)
				{
                    // update grand Total
                    var grandTot = parseFloat(data.grandTotal);
                    $('#td_grandtotrow_'+iduser+' > strong').html(grandTot.formatNumber(2, ',', '')+"&nbsp;&euro;");
                    // add product row
                    $('#tr_last_'+iduser).before(data.myTpl);
                }
                $('#td_add_'+iduser+' > a').button('reset').show();
                $('#div_add_'+iduser).html('-');
			},
			"json");
        } else {
            alert('Nessun prodotto selezionato!');
            $('#submit_'+iduser).button('reset');
        }
    }