<?php if( $this->statusObj->is_Chiuso()): ?>
<div id="invia_ordine">
    <h2>Invio ordine</h2>
    <form id="forminvioordine" action="/gestione-ordini/invia/idordine/<?php echo $this->ordine->idordine; ?>" method="post">
        <p><input type="checkbox" name="invia_dettaglio" value="S" /> Invia anche <b>Dettaglio Prodotti utenti</b></p>
        <input type="submit" id="submit" value="INVIA!" style="margin-left: 0;" />
    </form>
</div>
<?php endif; ?>
<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<p>
    Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M:%S');?></strong><br />
    Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M:%S');?></strong>
</p>    
<div id="content_list1" style="margin-top: 50px;">
    <h1>Riepilogo Prodotti ordinati</h1>
<?php if(count($this->riepilogo) > 0): ?>
    <div id="list_box">
    <?php 
        $totale = 0;
        foreach ($this->riepilogo as $idprodotto => $prodotto): ?>
        <div class="box_row">
            <div class="sub_menu">
                <div class="sub_totale">
                <h3><?php 
                    $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                    $totale += $subtotale;
                    echo $this->valuta($subtotale);
                    ?></h3>
                </div>
            </div>
            <h3 class="dom_title"><strong><?php echo $prodotto["qta_ord"];?></strong> x <?php echo $prodotto["descrizione"];?></h3>
            <p>Costo: <strong><?php echo $prodotto["costo_op"];?></strong> &euro; / <strong><?php echo $prodotto["udm"]; ?></strong></p>
        </div>
    <?php endforeach; ?>
        <div class="totale_line">
            <div class="sub_menu">
                <h2 class="totale">
                    <?php echo $this->valuta($totale) ?>
                </h2>
            </div>                    
            <h2>Totale:</h2>
            <div class="my_clear" style="clear:both;">&nbsp;</div>
        </div>
    </div>
<?php else: ?>
    <h3>Nessun prodotto ordinato!</h3>
<?php endif; ?>
    
    <h1>Dettaglio Prodotti utenti</h1>
<?php if(count($this->dettaglio) > 0): ?>
    <div id="list_box">
    <?php foreach ($this->dettaglio as $iduser => $user): ?>
        <h2><strong><?php echo $user["cognome"] . " " . $user["nome"]; ?></strong></h2>
        <?php 
        $totale = 0;        
        foreach ($user["prodotti"] as $idprodotto => $prodotto): ?>
        <div class="box_row">
            <div class="sub_menu">
                <div class="sub_totale">
                <h3><?php 
                    $subtotale = ($prodotto["qta_ord"] * $prodotto["costo_op"]);
                    $totale += $subtotale;
                    echo $this->valuta($subtotale);
                    ?></h3>
                </div>
            </div>
            <h3 class="dom_title"><strong><?php echo $prodotto["qta_ord"];?></strong> x <?php echo $prodotto["descrizione"];?></h3>
            <p>Costo: <strong><?php echo $prodotto["costo_op"];?></strong> &euro; / <strong><?php echo $prodotto["udm"]; ?></strong></p>
        </div>
        <?php endforeach; ?>
        <div class="sub_menu">
            <div class="totale">
                <p>Totale</p>
                <h2 id="totale"><?php echo $this->valuta($totale) ?></h2>
            </div>
        </div>
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <h3>Nessun prodotto ordinato!</h3>
<?php endif; ?>
    
</div>
