    <div class="bs-callout bs-callout-info">
      <h4>Invio ordine</h4>
      <form id="forminvioordine" action="/gestione-ordini/invia/idordine/<?php echo $this->ordine->idordine; ?>" method="post">
        <p><input type="checkbox" name="invia_dettaglio" value="S" /> Invia <b>Dettaglio Prodotti utenti</b></p>
        <button type="submit" id="submit" class="btn btn-success btn-mylg">INVIA!</button>
      </form>
    </div>
