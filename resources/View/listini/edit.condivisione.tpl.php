<fieldset>
<?php if($this->listino->canManageCondivisione()): ?>
    <?php echo $this->form->renderField('condivisione'); ?>
    <div id="d_sharing">
        <?php echo $this->form->renderField('groups'); ?>
    </div>
<?php else: ?>
    <p>Condiviso dal gruppo <strong><?php echo $this->listino->getMasterGroup()->getGroupName(); ?></strong></p>
<?php endif; ?>
</fieldset>
