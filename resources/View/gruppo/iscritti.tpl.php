<h2>Elenco Iscritti al Gruppo</h2>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $user):
            ?>
        <div class="box_row" id="box_<?php echo $user->iduser;?>">
        <?php if($this->imFondatore): ?>
            <div class="sub_menu">
                <a class="menu" href="/users/edit/iduser/<?php echo $user->iduser;?>">Modifica</a>
            </div>
        <?php endif; ?>

            <h3 class="dom_title"><?php echo $user->nome . " " . $user->cognome; ?></h3>
            <p>
                Email: <a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a><br />
                Abilitato: <strong class="attivo_<?php echo $user->attivo; ?>"><?php echo $this->yesno($user->attivo); ?></strong>
            </p>
                
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun utente registrato!</h3>
<?php endif; ?>
</div>
