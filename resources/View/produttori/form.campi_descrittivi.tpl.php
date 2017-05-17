      <fieldset>
          <h2>Abstract</h2>
          <div id="desc_abstract" class="medium_editor_editable"><?php echo $this->form->getField('desc_abstract')->getValue(); ?></div>
          <input type="hidden" name="desc_abstract" value="<?php echo $this->form->getField('desc_abstract')->getValue(); ?>">
          <hr />
          <h2>Presentazione</h2>
          <div id="desc_presentazione" class="medium_editor_editable"><?php echo $this->form->getField('desc_presentazione')->getValue(); ?></div>
          <input type="hidden" name="desc_presentazione" value="<?php echo $this->form->getField('desc_presentazione')->getValue(); ?>">
          <hr />
          <h2>Storia</h2>
          <div id="desc_storia" class="medium_editor_editable"></div>
          <input type="hidden" name="desc_storia" value="">
          <hr />
          <h2>Certificazioni</h2>
          <div id="desc_certificazioni" class="medium_editor_editable"></div>
          <input type="hidden" name="desc_certificazioni" value="">
          <hr />
          <h2>Attenzioni all'ambiente</h2>
          <div id="desc_ambiente" class="medium_editor_editable"></div>
          <input type="hidden" name="desc_ambiente" value="">
          <hr />
          <h2>Servizi</h2>
          <div id="desc_servizi" class="medium_editor_editable"></div>
          <input type="hidden" name="desc_servizi" value="">
          <hr />
          <h2>Scelto perchè...</h2>
          <div id="desc_scelto" class="medium_editor_editable"></div>
          <input type="hidden" name="desc_scelto" value="">
          <hr />
      </fieldset>
      <script>

          var editor = new MediumEditor('.medium_editor_editable', {
              toolbar: {
                  buttons: ['bold', 'italic', 'underline', 'strikethrough', 'anchor', 'h3', 'h4', 'quote', 'orderedlist', 'unorderedlist']
              },
              placeholder: {
                  text: 'Clicca qui ed inizia a scrivere...',
                  hideOnClick: true
              }
          });
          $('.medium_editor_editable').focusout(function() {
              var id = $(this).attr('id');
              $('input[name="'+id+'"]').val($(this).html());
          });

      </script>