    <h4 class="ordine <?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></h3>
    <p>
<?php foreach($this->ordine->getArrayPrevStati() AS $label => $data): ?>
        <em><?php echo $label; ?></em>: <strong><?php echo $this->date($data, '%d/%m/%Y');?></strong> alle <?php echo $this->date($data, '%H:%M');?></strong><br />
<?php endforeach; ?>
    </p>