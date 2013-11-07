<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<div class="row">
  <div class="col-md-8">
    
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<?php if($this->statusObj->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
<?php endif; ?>
    
<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine, 'produttore' => $this->produttore)); ?>

<?php if(count($this->list) > 0): ?>
    <?php 
        $totale = 0;
        foreach ($this->list as $key => $prodotto): 
            if($prodotto->qta > 0):
            ?>
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
    <?php 
            endif;
        endforeach; ?>

<?php else: ?>
    <h3>Nessun prodotto ordinato o disponibile!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-4 col-right">
<?php if(count((array)$this->list) > 0): ?>      
    <div class="bs-sidebar" data-spy="affix" role="complementary">
        <div class="totale">
            <h4>Totale: <b id="totale"><?php echo $this->valuta($totale) ?></b></h4>
        </div>
    </div>
<?php endif; ?>
  </div>
    
</div>