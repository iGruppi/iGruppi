/* Jx Functions */
    
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
    
    function jx_AddSetReferenteUser(iduser, flag) {
        $('#no_user_ref').hide();
        var idproduttore = $('#iduser_ref').find(":selected").val();
        $.getJSON(
            '/users/addref/',
            {iduser: iduser, idproduttore: idproduttore, flag: flag},
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

    function jx_OrdineMoveStatus(idordine, flagMover) {
        $('#a_mso').button('loading');
        $.getJSON(
            '/gestione-ordini/movestatus/',
            {idordine: idordine, flagMover: flagMover},
            function(data) {
                $('#ordine_header_status').html(data.myTpl_status);
                $('#ordine_header_menu').html(data.myTpl_menu);
            });
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
    
    function importProdottoToListino(idlistino, idprodotto)
    {
        $('#li_import_' + idlistino + '_' +idprodotto + ' > .btn').button('loading');
        $.getJSON(
            '/listini/importa/',
            {idlistino: idlistino, idprodotto: idprodotto},
            function(data) {
                if(data.res)
				{
                    $('#li_import_' + idlistino + '_' +idprodotto).remove();
                }
            });
    }
    
    function jx_ListinoUpdateData(idlistino)
    {
        $('#btn_listino_user_update').button('loading');
        $.getJSON(
            '/listini/updatedata/',
            {idlistino: idlistino},
            function(data) {
                if(data.res)
                {
                    $('#listino_user_update').html(moment().format('DD/MM/YYYY'));
                    $('#btn_listino_user_update').button('reset');
                }
            });
    }