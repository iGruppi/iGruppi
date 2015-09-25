<h2><strong><?php echo count($this->list); ?></strong> utenti iscritti al Gruppo <strong><?php echo $this->group->nome; ?></strong></h2>

<div class="row">
  <div class="col-md-8">
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $user): ?>
      
      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin"><?php echo $user->nome . " " . $user->cognome; ?></h3>
            <p>
                Email: <a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a><br />
                Telefono: <?php echo $user->tel;?><br />
                Indirizzo: <?php echo $user->indirizzo;?> - <?php echo $user->localita;?> (<?php echo $user->provincia;?>)<br />
            <?php if( $this->yesnoToBool($user->fondatore)): ?>
                <span class="label label-success">Amministratore</span>
            <?php endif; ?>
            <?php if( $this->yesnoToBool($user->contabile)): ?>
                <span class="label label-info">Contabile</span>
            <?php endif; ?>
            <?php if( !$this->yesnoToBool($user->attivo)): ?>
                <span class="label label-danger">Disabilitato</span>
            <?php endif; ?>
            <?php if( $this->yesnoToBool($user->in_prova)): ?>
                <span class="label label-warning">In prova</span>
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4 text-right">
        <?php if($this->aclUserObject->canModifyUser()): ?>
            <a class="btn btn-success" href="/users/edit/iduser/<?php echo $user->iduser;?>" style="margin-bottom: 5px;">Modifica</a>
            <?php if( !$this->yesnoToBool($user->attivo)): ?>
                <a class="btn btn-primary" id="disabled_button_<?php echo $user->iduser;?>"  data-loading-text="Loading..." href="javascript:void(0)" onclick="jx_EnableUser(<?php echo $user->iduser;?>)">Abilita e Invia email</a>
            <?php endif; ?>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun utente registrato!</h3>
<?php endif; ?>
  </div>
</div>
