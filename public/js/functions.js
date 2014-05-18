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