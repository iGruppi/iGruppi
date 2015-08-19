    <h2 class="titolo">Listino <strong><?php echo $this->listino->getDescrizione(); ?></strong></h2>
    <h3 class="subtitolo">
        Produttore: <strong><?php echo $this->listino->getProduttoreName(); ?></strong><br />
        <small>Data Listino: <strong id="listino_user_update"><?php echo $this->date( $this->listino->getDataListino(), '%d/%m/%Y' ); ?></strong></small>
    </h3>
    <div class="row">
        <div class="col-md-12">
        <em>Validità:</em> 
        <?php if(!$this->listino->getMyGroup()->getValidita()->isSetValidita()): ?>
            <b>SEMPRE</b>
        <?php else: ?> 
            dal <b><?php echo $this->listino->getMyGroup()->getValidita()->getDal('d/m/Y'); ?></b> al <b><?php echo $this->listino->getMyGroup()->getValidita()->getAl('d/m/Y'); ?></b> 
        <?php endif; ?><br />
        
        <?php echo $this->partial('listini/edit.prodotti.tpl.php', array('listino' => $this->listino)); ?>

        </div>
    </div>
