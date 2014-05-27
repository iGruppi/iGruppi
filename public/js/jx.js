/* Jx Functions */

	function Loading(id)
	{
		$(id).show().html('<img src="/images/loading.gif" />');
	}
    
    function StopLoading(id) {
		$(id).hide().html('');
    }


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
    
    function jx_ReferenteModifyQta(iduser, idprodotto)
    {
        if($('#qta_eff_'+iduser+'_'+idprodotto).val() != $('#qta_eff_old_'+iduser+'_'+idprodotto).val())
        {
            $('#btn_'+iduser+'_'+idprodotto).show();
        } else {
            $('#btn_'+iduser+'_'+idprodotto).hide();
        }
    }
    
    function jx_RefModQta_Save(iduser, idprodotto, idordine)
    {
        var btn = $('#btn_'+iduser+'_'+idprodotto);
        btn.button('loading');
		$.post(
			'/gestione-ordini/changeqta/idordine/'+idordine,
			$('#qta_ord_form_'+iduser+'_'+idprodotto).serialize(),
			function(data){
//                console.log(data);
                if(data.res)
				{
                    var tot = parseFloat(data.newTotale);
                    $('#tdrow_'+iduser+'_'+idprodotto).html("<strong>"+tot.formatNumber(2, ',', '')+"&nbsp;&euro;</strong>");
                    btn.button('reset').hide();
                    $('#qta_eff_'+iduser+'_'+idprodotto)[0].formatNumber();
                }
			},
			"json");
    }