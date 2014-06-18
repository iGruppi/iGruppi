<form id="addprod_form_<?php echo $this->iduser; ?>" class="ordini" 
      onsubmit="jx_ReferenteAddNewProd_Save(<?php echo $this->iduser; ?>,<?php echo $this->idordine;?>); return false;" method="post">
    <label>Descrizione:</label>
    <input type="text" id="txt_prod_<?php echo $this->iduser; ?>" name="txt_prod" value="" size="50" />
    <button style="margin: 2px;" class="btn btn-success" id="submit_<?php echo $this->iduser; ?>">Aggiungi</button>
    <input type='hidden' name="idordine" value="<?php echo $this->idordine;?>" /> 
    <input type='hidden' name="iduser" value="<?php echo $this->iduser;?>" />
    <input type='hidden' id="idprodotto" name="idprodotto" value="" />
</form>
<style>
  .ui-autocomplete-category {
    font-weight: bold;
    padding: .2em .4em;
    margin: .8em 0 .2em;
    line-height: 1.5;
  }
</style>
<script>
  $(function() {
    var data = <?php echo $this->arRes ?>;

    $.widget( "custom.catcomplete", $.ui.autocomplete, {
      _renderMenu: function( ul, items ) {
        var that = this,
          currentCategory = "";
        $.each( items, function( index, item ) {
          if ( item.category != currentCategory ) {
            ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
            currentCategory = item.category;
          }
          that._renderItemData( ul, item );
        });
      }
    });


    $( "#txt_prod_"+<?php echo $this->iduser; ?> ).catcomplete({
      delay: 0,
      source: data,
      select: function (event, ui) {
          $('#idprodotto').val(ui.item.id);
      }
    });
  });
</script>