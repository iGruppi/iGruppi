<h2>Elenco Iscritti al Gruppo <strong><?php echo $this->group->nome; ?></strong></h2>

<div class="row">
  <div class="col-md-8">
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $user): ?>
      
      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin"><?php echo $user->nome . " " . $user->cognome; ?></h3>
            <p>
                Email: <a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a><br />
            <?php if( !$this->yesnoToBool($user->attivo)): ?>
                <strong class="alert_red">Disabilitato</strong>
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4">
        <?php if($this->imFondatore): ?>
            <a class="btn btn-success" href="/users/edit/iduser/<?php echo $user->iduser;?>">Modifica</a>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun utente registrato!</h3>
<?php endif; ?>
  </div>
</div>
