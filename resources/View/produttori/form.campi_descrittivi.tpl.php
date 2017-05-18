      <fieldset>
          <h2>Abstract</h2>
          <div id="desc_abstract" class="medium_editor_editable"><?php echo $this->form->getField('desc_abstract')->getValue(); ?></div>
          <textarea name="desc_abstract" style="display: none;"><?php echo $this->form->getField('desc_abstract')->getValue(); ?></textarea>
          <hr />
          <h2>Presentazione</h2>
          <div id="desc_presentazione" class="medium_editor_editable"><?php echo $this->form->getField('desc_presentazione')->getValue(); ?></div>
          <textarea name="desc_presentazione" style="display: none;"><?php echo $this->form->getField('desc_presentazione')->getValue(); ?></textarea>
          <hr />
          <h2>Storia</h2>
          <div id="desc_storia" class="medium_editor_editable"><?php echo $this->form->getField('desc_storia')->getValue(); ?></div>
          <textarea name="desc_storia" style="display: none;"><?php echo $this->form->getField('desc_storia')->getValue(); ?></textarea>
          <hr />
          <h2>Certificazioni</h2>
          <div id="desc_certificazioni" class="medium_editor_editable"><?php echo $this->form->getField('desc_certificazioni')->getValue(); ?></div>
          <textarea name="desc_certificazioni" style="display: none;"><?php echo $this->form->getField('desc_certificazioni')->getValue(); ?></textarea>
          <hr />
          <h2>Attenzioni all'ambiente</h2>
          <div id="desc_ambiente" class="medium_editor_editable"><?php echo $this->form->getField('desc_ambiente')->getValue(); ?></div>
          <textarea name="desc_ambiente" style="display: none;"><?php echo $this->form->getField('desc_ambiente')->getValue(); ?></textarea>
          <hr />
          <h2>Servizi</h2>
          <div id="desc_servizi" class="medium_editor_editable"><?php echo $this->form->getField('desc_servizi')->getValue(); ?></div>
          <textarea name="desc_servizi" style="display: none;"><?php echo $this->form->getField('desc_servizi')->getValue(); ?></textarea>
          <hr />
          <h2>Scelto perchè...</h2>
          <div id="desc_scelto" class="medium_editor_editable"><?php echo $this->form->getField('desc_scelto')->getValue(); ?></div>
          <textarea name="desc_scelto" style="display: none;"><?php echo $this->form->getField('desc_scelto')->getValue(); ?></textarea>
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
              $('textarea[name="'+id+'"]').val($(this).html());
          });

      </script>