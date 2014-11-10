<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>
<div class="row">
    <div class="col-md-8">
        <form id="ordineform" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">
            <fieldset>
            <?php if($this->ordine->canManageCondivisione()): ?>
                <?php echo $this->form->renderField('condivisione'); ?>
                <div id="d_sharing" class="hint" style="display: block;">
                <?php foreach ($this->groups as $group):
                        if($this->ordine->getMasterGroup()->getIdGroup() != $group->idgroup): ?>
                    <p><input type="checkbox" name="groups[]" value="<?php echo $group->idgroup; ?>" 
                       <?php if($this->ordine->issetGroup($group->idgroup)) { echo "checked='checked'"; } ?> /> <b><?php echo $group->nome; ?></b></p>
                <?php   endif; 
                      endforeach; ?>
                </div>
            <?php else: ?>
                <p>Condiviso dal gruppo <strong><?php echo $this->ordine->getMasterGroup()->getGroupName(); ?></strong></p>
            <?php endif; ?>
            </fieldset>
            
            <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
            
        </form>
    </div>
    <div class="col-md-4 col-right">
        &nbsp;
    </div>    
</div>

