    <form id="prod_ordini_form" action="/ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>" method="post">
    <?php if($this->updated): ?>
        <div id="updated">
            <div class="isa_success">La tua lista prodotti è stata aggiornata con successo!</div>
        </div>
    <?php endif; ?>
        <div id="list_box">
        <?php 
            $totale = 0;
            foreach ($this->list as $key => $prodotto): ?>
            <div class="box_row">
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
                <h3 class="dom_title"><?php echo $prodotto->descrizione;?></h3>
                <p>
                    Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                    Costo: <strong><?php echo $this->valuta($prodotto->costo_op);?></strong> / <strong><?php echo $prodotto->udm; ?></strong><br />
                </p>
            </div>
        <?php endforeach; ?>
            <div class="sub_menu">
                <div class="totale">
                    <p>Totale</p>
                    <input disabled id="f_totale" type="hidden" name="f_totale" value="<?php echo $totale; ?>" />
                    <h2 id="totale"><?php echo $this->valuta($totale) ?></h2>
                </div>
            </div>
        </div>
        <fieldset class="for_submit">
            <input type="submit" id="submit" value="SALVA" />
        </fieldset>
    </form>

<script language="javascript">
	$(document).ready(function(){
        $('#updated').fadeOut(3000);
	});
</script>
