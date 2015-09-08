<?php 
    switch ($this->tipo) 
    {
        case "totali": ?>
            <div id="ordine_header">
                <?php include $this->template('gestioneordini/header.tpl.php'); ?>
                <div class="row">
                  <div class="col-md-12">
                    <h3 class="big-margin-top">Riepilogo Totale Prodotti ordinati</h3>
                  </div>
                </div>
            </div>
            <?php echo $this->partial('gestioneordini/dettaglio.totali.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            
        break;
    
        case "utenti": ?>
            <div id="ordine_header">
                <?php include $this->template('gestioneordini/header.tpl.php'); ?>
                <div class="row">
                  <div class="col-md-12">
                    <h3 class="big-margin-top">Dettaglio Parziali per utente</h3>
                  </div>
                </div>
            </div>
            
            <?php echo $this->partial('gestioneordini/dettaglio.utenti.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
        break;

        default:
        break;
    }    