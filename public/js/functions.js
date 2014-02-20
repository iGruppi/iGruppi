/*
 * Javascript functions
 */
    var mesi = new Array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
    var giorni = new Array('Lun','Mar','Mer','Gio','Ven','Sab','Dom');
    
    Number.prototype.formatNumber = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d === undefined ? "," : d, t = t === undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
    
    // Start these procedures always
    $(function() {
        
        // CHECK Validity for Number fields
        $('.is_Number').on('change keyup', function(event) {
            if ( event.target.validity.valid ) {
                $(this).removeClass("is_Number_invalid");
            } else {
                $(this).addClass("is_Number_invalid");
            }
        });

        // CHECK Validity FORM on submit for Number fields
        $(this).find('form').submit(function( event ) {
            var myForm = $(this)[0];
            if(!myForm.checkValidity())
            {
                $(this).find('.is_Number').each(function(){
                    var myField = $(this)[0];
                    if ( !myField.checkValidity() ) 
                    {
                        $(this).focus();
                        return false;
                    }
                });                 
                return false;
            } else {
                return true;
            }
        });
    });

