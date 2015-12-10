<h3>Sei iscritto al gruppo: <strong><?php echo $this->group->nome; ?></strong></h3>
<p><?php echo $this->group->descrizione; ?></p>

<h3>Ultimi movimenti</h3>
<div class="row">
    <div class="col-md-8">
    <table class="table table-condensed">
        <thead>
          <tr>
            <th>ID</th>
            <th>Data</th>
            <th class="text-right">Importo</th>
            <th>Descrizione</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($this->movimenti AS $movimento): ?>            
            <tr class="<?php echo($movimento->getImporto() > 0) ? "success" : "danger"; ?>">
                <td><i><?php echo $movimento->getId(); ?></i></td>
                <td><?php echo $this->date($movimento->getData(), '%d/%m/%Y %H:%M'); ?></td>
                <td class="text-right"><strong><?php echo $this->valuta($movimento->getImporto()); ?></strong></td>
                <td>
                    <?php if($movimento->isRelatedToOrdine()): ?>
                    <a href="/ordini/viewdettaglio/idordine/<?php echo $movimento->getIdOrdine(); ?>"><?php echo $movimento->getDescrizione(); ?></a><br />
                    Data Ordine: <?php echo $this->date($movimento->getDataOrdine(), '%d/%m/%Y'); ?>
                    <?php else: ?>
                        <?php echo $movimento->getDescrizione(); ?>
                    <?php endif; ?>
                </td>
            </tr>        
<?php endforeach; ?>
        </tbody>
    </table>
        <nav>
          <ul class="pagination">
            <li class="<?php echo ($this->sPager->hasPrev()) ? "" : "disabled"; ?>">
                <?php if($this->sPager->hasPrev()): ?>
                    <a href="<?php echo ($this->sPager->hasPrev()) ? $this->sPager->getURL_Prev() : ""; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                <?php else: ?>
                    <span><span aria-hidden="true">&laquo;</span></span>                
                <?php endif; ?>
            </li>
            <li class="active">
                <a href="#"><?php echo $this->sPager->getPageNumber(); ?> <span class="sr-only">(current)</span></a>
            </li>
            <li class="<?php echo ($this->sPager->hasNext()) ? "" : "disabled"; ?>">
                <?php if($this->sPager->hasNext()): ?>
                <a href="<?php echo ($this->sPager->hasNext()) ? $this->sPager->getURL_Next() : ""; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                <?php else: ?>
                    <span><span aria-hidden="true">&raquo;</span></span>                
                <?php endif; ?>
            </li>
          </ul>
        </nav>        
    </div>
    <div class="col-md-4 dashboard-right">
        <div class="panel <?php echo($this->saldi->SaldoUtente > 0) ? "panel-success" : "panel-danger"; ?>">
            <div class="panel-heading"> <h3 class="panel-title">Saldo: <b><?php echo $this->valuta($this->saldi->SaldoUtente); ?></b></h3> </div>            
            <div class="panel-body">
                Totale Versamenti: <b><?php echo $this->valuta($this->saldi->TotaleVersamenti); ?></b><br />
                Totale Ordini Pagati: <b><?php echo $this->valuta($this->saldi->TotaleOrdiniPagati); ?></b><br />
                Numero Ordini Archiviati: <b><?php echo $this->saldi->NumeroOrdiniArchiviati; ?></b><br />
                
            </div>
        </div>
        <div class="panel <?php echo($this->saldi->ProiezioneSaldo > 0) ? "panel-success" : "panel-danger"; ?>">
            <div class="panel-heading"> <h3 class="panel-title">Proiezione Saldo: <b><?php echo $this->valuta($this->saldi->ProiezioneSaldo); ?></b></h3> </div>            
            <div class="panel-body">
                Stima Spese Prox Ordini: <b><?php echo $this->valuta($this->saldi->StimaSpeseProxOrdini); ?></b><br />
                Numero Ordini InCorso: <b><?php echo $this->saldi->NumeroOrdiniInCorso; ?></b><br />
            </div>
        </div>
    </div>
</div>

