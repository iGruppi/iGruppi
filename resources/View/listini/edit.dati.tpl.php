    <fieldset>
        <?php echo $this->form->renderField('descrizione'); ?>
        <?php echo $this->form->renderField('visibile'); ?>
    </fieldset>        
    <fieldset id="fs_validita" class="border_top">
        <legend>Validità</legend>
        <label for="validita">Validità:</label>
        <select name="validita" id="validita">
            <option value="N" <?php if(!$this->listino->getMyGroup()->getValidita()->isSetValidita()){ echo 'selected=""'; } ?>>Sempre</option>
            <option value="S" <?php if($this->listino->getMyGroup()->getValidita()->isSetValidita()){ echo 'selected=""'; } ?>>Imposta periodo</option>
        </select>
        <br />
        <div id="d_validita" style="display: <?php echo ($this->listino->getMyGroup()->getValidita()->isSetValidita()) ? "block" : "none"; ?>;">
            <?php echo $this->form->renderField('valido_dal'); ?>
            <?php echo $this->form->renderField('valido_al'); ?>
        </div>
    </fieldset>        

