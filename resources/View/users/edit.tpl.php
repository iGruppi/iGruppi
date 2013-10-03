<h2>Modifica Utente <strong><?php echo $this->user->nome . " " . $this->user->cognome; ?></strong></h2>

<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Produttore aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

<form id="prodform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#dati" data-toggle="tab">Dati utente</a></li>
          <li><a href="#settings" data-toggle="tab">Impostazioni</a></li>
          <li><a href="#referente" data-toggle="tab">Referente/Produttori</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="dati">
            <fieldset>
                <?php echo $this->form->renderField('nome'); ?>
                <?php echo $this->form->renderField('cognome'); ?>
                <?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('num_members'); ?>
            </fieldset>
          </div>
          <div class="tab-pane" id="settings">
              <fieldset>      
                <?php echo $this->form->renderField('fondatore'); ?>
                <?php echo $this->form->renderField('attivo'); ?>
                <?php echo $this->form->renderField('contabile'); ?>
              </fieldset>
          </div>
          <div class="tab-pane" id="referente">
            <fieldset>
              <label for="iduser_ref">Produttore:</label>
              <select name="iduser_ref" id="iduser_ref" onchange="$('#btn_add_referente').show()">
                  <option value="0" selected="">Seleziona...</option>
              <?php 
                    $arRef = array();
                    foreach($this->produttori AS $produttore): 
                    // Check for Referente attuale
                    if( $produttore->iduser_ref == $this->user->iduser ) {
                        $arRef[] = $produttore;
                    } else {
              ?>
                  <option value="<?php echo $produttore->idproduttore; ?>"><?php echo $produttore->ragsoc; ?></option>
              <?php 
                    }
                    endforeach; 
              ?>
              </select>
              <a id="btn_add_referente" class="btn btn-primary btn-sm btn-inform" style="display: none;" href="javascript:void(0)" onclick="jx_AddReferenteUser(<?php echo $this->user->iduser; ?>)"><span class="glyphicon glyphicon-briefcase"></span> Imposta Referente</a>
            </fieldset>
            <fieldset class="border_top">
              <div id="list_user_ref" class="hint">
                  <p><?php echo $this->user->nome; ?> è il <b>Referente</b> di:</p>
              <?php if(count($arRef) > 0): ?>
                  <?php foreach($arRef AS $ref): ?>
                  <h4><?php echo $ref->ragsoc; ?></h4>
                  <?php endforeach; ?>
              <?php else: ?>
                  <p id="no_user_ref"><em>Nessun produttore.</em></p>
              <?php endif; ?>
              </div>
            </fieldset>
          </div>
        </div>

        <?php echo $this->form->renderField('iduser'); ?>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
</form>