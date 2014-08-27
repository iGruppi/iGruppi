<fieldset>
<?php if($this->listino->canManageCondivisione()): ?>
    <?php echo $this->form->renderField('condivisione'); ?>
    <div id="d_sharing" class="hint" style="display: block;">
    <?php foreach ($this->groups as $group):
            if($this->listino->getGroups()->getMasterGroup()->getIdGroup() != $group->idgroup): ?>
        <p><input type="checkbox" name="groups[]" value="<?php echo $group->idgroup; ?>" 
           <?php if($this->listino->getGroups()->issetGroup($group->idgroup)) { echo "checked='checked'"; } ?> /> <b><?php echo $group->nome; ?></b></p>
    <?php   endif; 
          endforeach; ?>
    </div>
<?php else: ?>
    <p>Condiviso dal gruppo <strong><?php echo $this->listino->getGroups()->getMasterGroup()->getGroupName(); ?></strong></p>
<?php endif; ?>
</fieldset>
