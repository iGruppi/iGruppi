<div id="ordine_header">
    <?php include $this->template('gestioneordini/header.tpl.php'); ?>
    <div class="row">
      <div class="col-md-12">
        <h3 class="big-margin-top">Elenco variazioni</h3>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-md-11">
<?php if(count($this->logs) > 0): ?>
    <table class="table table-condensed">
        <tbody>
    <?php foreach ($this->logs as $key => $log): ?>
            <tr>
                <td><em><?php echo $this->date($log->data, '%d/%m/%Y %H:%M');?></em><br /><?php echo $log->descrizione;?></td>
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Non è stata effettuata alcuna variazione a quest'ordine.</p>
<?php endif; ?>
  </div>
</div>

