<h2>Elenco Iscritti al Gruppo</h2>

<div id="content_list1">
<?php if(count($this->list) > 0): ?>

    <div id="list_box">
    <?php foreach ($this->list as $key => $user):
            ?>
        <div class="box_row" id="box_<?php echo $user->iduser;?>">

            <h3 class="dom_title"><?php echo $user->nome . " " . $user->cognome; ?></h3>
            
            <p id="p_details_<?php echo $user->idproduttore;?>">
                Email: <a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a>
            </p>
                
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3>Nessun produttore!</h3>
<?php endif; ?>
</div>
