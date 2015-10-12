<h2>Saldi di Cassa</h2>
<div class="row">
  <div class="col-md-12">
    <table class="table table-condensed" style="">
        <thead>
          <tr>
            <th>Utente</th>
            <th class="text-right">Totale Versamenti</th>
            <th class="text-right">Totale Ordini Pagati</th>
            <th class="text-right">N. Ordini Archiviati</th>
            <th class="text-right">N. Ordini In Corso</th>
            <th class="text-right">Stima Spese Prox Ordini</th>
            <th class="text-right">Saldo Utente</th>
            <th class="text-right">Proiezione Saldo</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($this->saldi AS $saldo): ?>
            <tr class="<?php echo($saldo->SaldoUtente >= 0) ? "success" : "danger"; ?>">
                <td><?php echo $saldo->Utente; ?></td>
                <td class="text-right"><?php echo $saldo->TotaleVersamenti; ?></td>
                <td class="text-right"><?php echo $saldo->TotaleOrdiniPagati; ?></td>
                <td class="text-right"><?php echo $saldo->NumeroOrdiniArchiviati; ?></td>
                <td class="text-right"><?php echo $saldo->NumeroOrdiniInCorso; ?></td>
                <td class="text-right"><?php echo $saldo->StimaSpeseProxOrdini; ?></td>
                <td class="text-right"><?php echo $saldo->SaldoUtente; ?></td>
                <td class="text-right"><?php echo $saldo->ProiezioneSaldo; ?></td>
            </tr>        
<?php endforeach; ?>
        </tbody>
    </table>
  </div>
</div>
      