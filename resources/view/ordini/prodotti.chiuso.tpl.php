    <div id="list_box">
    <?php 
        $totale = 0;
        foreach ($this->list as $key => $prodotto): ?>
        <div class="box_row">
            <div class="sub_menu">
                <span class="menu_icon_empty" >&nbsp;</span>
                <span class="prod_qta"><?php echo $prodotto->qta;?></span>
                <span class="menu_icon_empty" >&nbsp;</span>
        <?php 
                $subtotale = ($prodotto->qta * $prodotto->costo_op);
                $totale += $subtotale;
        ?>
                <div class="sub_totale"><?php echo $this->valuta($subtotale) ?></div>
            </div>
            <h3 class="dom_title"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo_op);?></strong> &euro; / <strong><?php echo $prodotto->udm; ?></strong><br />
            </p>
        </div>
    <?php endforeach; ?>
        <div class="sub_menu">
            <div class="totale">
                <p>Totale</p>
                <h2 id="totale"><?php echo $this->valuta($totale) ?></h2>
            </div>
        </div>
    </div>
