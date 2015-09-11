<?php 
    switch ($this->tipo) 
    {
        case "totali": 
            $titolo = "Totale Prodotti ordinati (Multigruppo)";
            $tpl = 'gestioneordini/dettagliomg.totali.tpl.php';
        break;
    
        case "utenti":
            $titolo = "Dettaglio Parziali per utente";
            $tpl = 'gestioneordini/dettagliomg.utenti.tpl.php';
        break;
    }    
?>
            
    <div id="ordine_header">
        <?php include $this->template('gestioneordini/header.tpl.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <h3 class="big-margin-top"><?php echo $titolo; ?></h3>
          </div>
        </div>
    </div>
    <?php echo $this->partial($tpl, array('ordCalcObj' => $this->ordCalcObj));
    