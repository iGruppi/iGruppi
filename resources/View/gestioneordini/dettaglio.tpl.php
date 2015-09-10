<?php 
    switch ($this->tipo) 
    {
        case "totali": 
            $titolo = "Riepilogo Totale Prodotti ordinati";
            $tpl = 'gestioneordini/dettaglio.totali.tpl.php';
        break;
    
        case "utenti":
            $titolo = "Dettaglio Parziali per utente";
            $tpl = 'gestioneordini/dettaglio.utenti.tpl.php';
        break;
    }    
?>
            
    <div id="ordine_header">
        <?php include $this->template('gestioneordini/header.tpl.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <h3 class="big-margin-top"><?php echo $titolo; ?> 
    <?php if($this->ordine->canViewMultigruppoFunctions()): ?>
                (<strong><?php echo $this->groups[$this->idgroup]; ?></strong>)
    <?php endif; ?>
            </h3>
    <?php if($this->ordine->canViewMultigruppoFunctions()): ?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Visualizza per Gruppo <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
        <?php foreach($this->groups AS $idgroup => $nome): ?>
                    <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/<?php echo $this->tipo; ?>/idgroup/<?php echo $idgroup; ?>"><?php echo $nome; ?></a></li>
        <?php endforeach; ?>
                </ul>
            </div>
    <?php endif; ?>
          </div>
        </div>
    </div>
    <?php echo $this->partial($tpl, array('ordCalcObj' => $this->ordCalcObj));
    