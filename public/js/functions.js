/*
 * Javascript functions
 */
    var mesi = new Array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
    var giorni = new Array('Lun','Mar','Mer','Gio','Ven','Sab','Dom');
    
    Number.prototype.formatNumber = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d === undefined ? "," : d, t = t === undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
    
    // Prototype HTMLInputElement to improve localization
    HTMLInputElement.prototype.formatNumber = function() {
        this.value = this.value.replace(".", ",");
    };
    HTMLInputElement.prototype.setNumber = function() {
        this.value = this.value.replace(",", ".");
    },
    HTMLInputElement.prototype.getNumber = function() {
        return parseFloat( this.value.replace(",", ".") );
    };
    
    
    // Start these procedures always
	$(document).ready(function(){
        
        // SET some specific functions for _number_ input field (defined by "is_Number" class)
        $(this).find('.is_Number').each(function(){
            $(this)[0].formatNumber(); // $(this)[0] return the HTMLInputElement
        });
        $('.is_Number').on('change keyup', function(event) {
            $(this)[0].formatNumber();
        });
        
        // Convert all number fields in real number format
        $(this).find('form').submit(function( event ) {
            $(this).find('.is_Number').each(function(){
                $(this)[0].setNumber();
            });                 
        });

    });


/**
 * General functions for Handsontable
 */
    // Helper function to check if it is sorted
    function isSorted(hotInstance){
      return hotInstance.sortingEnabled && typeof hotInstance.sortColumn != 'undefined';
    }    
    
    
/**
 * Advanced functions for Ordina/Prodotti
 */

    // Advanced Search for Prodotti
    function searchProducts(text)
    {
        myText = String(text);
        found = 0;
        if(myText.length >= 1) {
            // HIDE Categories
            $(document).find('.categorie_hidden').each(function(){
                $(this).hide();
            });
            // HIDE Sub-Categories
            $(document).find('.subcat-title').each(function(){
                $(this).hide();
            });
            $(document).find('.product_descrizione').each(function(){
                // search text case insensitive (toLowerCase)
                if($(this).text().toLowerCase().search(myText.toLowerCase()) === -1 ) {
                    $(this).parent().parent().hide();
                } else {
                    $(this).parent().parent().show();
                    found++;
                }
            });
            
            // show NO RESULT
            if(found === 0) {
                $('#search_no_result').show();
                $('#search_num_result').hide();
            } else {
                $('#search_no_result').hide();
                $('#search_num_result').show();
                $('#search_num_result > h3 > strong').html(found);
            }
            
        } else {
            // SHOW Categories
            $(document).find('.categorie_hidden').each(function(){
                $(this).show();
            });
            // SHOW Sub-Categories
            $(document).find('.subcat-title').each(function(){
                $(this).show();
            });
            // SHOW ALL Products
            $(document).find('.product_descrizione').each(function(){
                $(this).parent().parent().show();
            });
            // HIDE no result alert
            $('#search_no_result').hide();
            $('#search_num_result').hide();
        }
    }
    
/*
 *  Trolley class
 *  Gestisce le operazioni di inserimento e cancellazione di Items nel carrello (Ordine)
 */
    var Trolley = {
        // idordine
        idordine: 0,
        // list of products
        products: {},
                
        initByParams: function(idproduct, idlistino, prezzo, multip, qta) {
            this.products[idproduct] = {
                idlistino: parseFloat(idlistino), 
                prezzo:    parseFloat(prezzo), 
                multip:    parseFloat(multip), 
                qta   :    parseInt(qta)
            };
//            console.log(idproduct + " - " + prezzo +" x "+qta);
        },

        add: function(idproduct) {
            if(idproduct in this.products) {
                return ++this.products[idproduct].qta;
            }
        },

        sub: function(idproduct) {
            if(idproduct in this.products) {
                if(this.products[idproduct].qta > 0) {
                    return --this.products[idproduct].qta;
                } else {
                    this.products[idproduct].qta = 0;
                    return 0;
                }
            }
        },

        getQta: function(idproduct) {
            if(idproduct in this.products) {
                return this.products[idproduct].qta;
            }
        },

        calculatePartial: function(idproduct) {
            if(idproduct in this.products) {
                return this.products[idproduct].prezzo * this.products[idproduct].multip * this.products[idproduct].qta;
            } else {
                return 0;
            }
        },
                
        calculateTotal: function() {
            var totale = 0;
            if( Object.keys(this.products).length > 0 ) {
                for(ppi in this.products) 
                {
                    totale += this.calculatePartial(ppi);
//                    console.log("ppi: " + ppi);
                }
            }
            return totale;
        }
    };
    
    function Trolley_setQtaProdotto(idprodotto, op) 
    {
        // check for ADD or SUB
        if( op === "+" ) {
            Trolley.add(idprodotto);
        } else {
            Trolley.sub(idprodotto);
        }
        // Update Qta via ajax
        jx_updateQtaProdotto(idprodotto);
    }
    
    function Trolley_rebuildPartial(idprodotto)
    {
        var newQta = Trolley.getQta(idprodotto);
        $('#prod_qta_'+idprodotto).val(newQta);
        var subtotale = Trolley.calculatePartial(idprodotto);
        $('#subtotale_'+idprodotto).html(subtotale.formatNumber(2, ',', '') + "&nbsp;&euro;");
    }
    
    function Trolley_rebuildTotal()
    {
        var totale = Trolley.calculateTotal();
        $('#totale').html(totale.formatNumber(2, ',', '') + "&nbsp;&euro;");
    }
    
    function jx_updateQtaProdotto(idprodotto)
    {
        var idlistino = Trolley.products[idprodotto].idlistino;
        var qta = Trolley.products[idprodotto].qta;
        $.getJSON(
			'/ordini/updateorder/',
            {idordine: Trolley.idordine, idprodotto: idprodotto, idlistino: idlistino, qta: qta},
			function(data) {
                if(data)
                {
                    Trolley_rebuildPartial(idprodotto);
                    Trolley_rebuildTotal();
                }
			});
    }