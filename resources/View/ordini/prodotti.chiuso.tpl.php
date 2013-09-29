    <?php 
        $totale = 0;
        foreach ($this->list as $key => $prodotto): ?>
      <div class="row row-myig">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo_op);?></strong> / <strong><?php echo $prodotto->udm; ?></strong><br />
            </p>
        </div>
        <div class="col-md-3">
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
        </div>
      </div>          
    <?php endforeach; ?>

      <div class="row">
          <div class="col-md-12">&nbsp;</div>
      </div>

      <div class="sub_menu">
        <div class="totale">
            <p>Totale</p>
            <h2><?php echo $this->valuta($totale) ?></h2>
        </div>
      </div>
