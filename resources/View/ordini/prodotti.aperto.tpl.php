
    <form id="prod_ordini_form" action="/ordini/ordina/idordine/<?php echo $this->ordine->idordine;?>" method="post">

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
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $prodotto->idprodotto;?>, '<?php echo $prodotto->costo_op;?>', '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $prodotto->idprodotto;?>" name="prod_qta[<?php echo $prodotto->idprodotto;?>]" value="<?php echo $prodotto->qta;?>" />
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $prodotto->idprodotto;?>, '<?php echo $prodotto->costo_op;?>', '-')">-</a>
        <?php 
                $subtotale = ($prodotto->qta * $prodotto->costo_op);
                $totale += $subtotale;
        ?>
                <div class="sub_totale" id="subtotale_<?php echo $prodotto->idprodotto;?>"><?php echo $this->valuta($subtotale) ?></div>
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
                <input disabled id="f_totale" type="hidden" name="f_totale" value="<?php echo $totale; ?>" />
                <h2><?php echo $this->valuta($totale) ?></h2>
            </div>
      </div>

        <fieldset class="for_submit">
            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
        </fieldset>
    </form>
