/* Jx Functions */

    Number.prototype.formatMoney = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
     };

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
    
    function jx_SelQtaProdotto(idprodotto, cst, op) {

        var idSelected = $('#prod_qta_'+idprodotto);
        var idSubtotale = $('#subtotale_'+idprodotto);
        var qta = parseInt(idSelected.val());
        var costo = parseFloat(cst);
        var newQta;
        var totale = parseFloat($('#f_totale').val());
        if( op == "+" ) {
            newQta = qta + 1;
            totale += costo;
        } else {
            if(idSelected.val() > 0) {
                newQta = qta - 1;
                totale -= costo;
            } else {
                newQta = 0;
            }
        }
        idSelected.val(newQta);
        var subtotale = newQta * costo;
        idSubtotale.html(subtotale.formatMoney(2, ',', '') + "&nbsp;&euro;");
        $('#totale').html(totale.formatMoney(2, ',', '') + "&nbsp;&euro;");
        $('#f_totale').val(totale);
    }