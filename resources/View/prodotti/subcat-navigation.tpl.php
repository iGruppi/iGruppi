<ul class="nav bs-sidenav">
<?php foreach ($this->listSubCat as $idcat => $cat): ?>
    
    <li>
      <a href="#cat_<?php echo $idcat; ?>"><?php echo $cat["categoria"]; ?></a>
      <ul class="nav">
    <?php foreach ($cat["subcat"] as $idsubcat => $subcat): ?>
        <?php 
            $max_length = 26;
            if(strlen($subcat) > $max_length): ?>
            <li><a href="#subcat_<?php echo $idsubcat; ?>" class="moreThan30" data-toggle="tooltip" data-placement="left" title="<?php echo $subcat; ?>"><?php echo substr($subcat, 0, $max_length) . "..."; ?></a></li>
        <?php else: ?>
            <li><a href="#subcat_<?php echo $idsubcat; ?>"><?php echo $subcat; ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
      </ul>
    </li>    
    
<?php endforeach; ?>
</ul>