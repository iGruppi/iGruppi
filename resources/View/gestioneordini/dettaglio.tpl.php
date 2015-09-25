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
            <h3 class="big-margin-top"><?php echo $titolo; ?></h3>
    <?php if($this->ordine->canViewMultigruppoFunctions()): ?>
            <div class="btn-group hidden-print">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Visualizza per Gruppo <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
        <?php foreach($this->groups AS $idgroup => $group): ?>
                    <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/<?php echo $this->tipo; ?>/idgroup/<?php echo $idgroup; ?>"><?php echo $group->nome_gruppo; ?></a></li>
        <?php endforeach; ?>
                </ul>
            </div>
            <div>
        <?php $group = $this->groups[$this->idgroup]; ?>
                <h4 class="big-margin-top"><strong><?php echo $group->nome_gruppo; ?></strong></h4>
        <?php if(!is_null($group->iduser_incaricato)): ?>
                <p>
                    Incaricato ordine: <b><?php echo $group->cognome_incaricato; ?> <?php echo $group->nome_incaricato; ?></b><br />
                    Tel: <b><?php echo $group->tel_incaricato; ?></b><br />
                    Email: <a href="mailto: <?php echo $group->email_incaricato; ?>"><?php echo $group->email_incaricato; ?></a>
                </p>
        <?php else: ?>
                <p>
                    <b class="text-danger">Nessun incaricato ordine per questo gruppo!</b>
                </p>
        <?php endif; ?>
            </div>
    <?php endif; ?>
          </div>
        </div>
    </div>
    <?php echo $this->partial($tpl, array('ordCalcObj' => $this->ordCalcObj));
    