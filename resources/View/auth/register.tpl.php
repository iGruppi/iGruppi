<h1>Registrazione nuovo utente</h1>

<form id="loginForm" action="<?php echo $this->form->getAction(); ?>" method="post" class="f1n200">

    <fieldset>
        <?php echo $this->form->renderField('nome'); ?>
        <?php echo $this->form->renderField('cognome'); ?>
        <?php echo $this->form->renderField('num_members'); ?>
    </fieldset>
    <fieldset class="border_top">
        <legend>Dati di accesso</legend>
        <?php echo $this->form->renderField('email'); ?>
        <?php echo $this->form->renderField('password'); ?>
        <?php echo $this->form->renderField('password2'); ?>
    </fieldset>
    <fieldset class="border_top">
        <legend>Gruppo</legend>
        <div id="div_select_group">
            <?php echo $this->form->renderField('idgroup'); ?>
            <a style="margin-left: 202px;" class="btn btn-success btn-xs" role="button" href="javascript: void(0);" onclick="showFormNewGruppo();">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Crea nuovo gruppo
            </a>
        </div>
        <div id="div_add_group" style="display: none;">
            <a style="margin-left: 202px;" class="btn btn-success" role="button" href="javascript: void(0);" onclick="showFormSelectGruppo();">
                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
            </a>
            <br/>
            <br/>
            <label for="group_nome">Nome Gruppo:</label>
            <input type="text" name="group_nome" id="group_nome" size="40" maxlength="50" required="">
            <br/>
            <label for="group_desc">Descrizione:</label>
            <input type="text" name="group_desc" id="group_desc" size="50" maxlength="45" required="">
            <br/>
            <?php echo $this->form->renderField('provincia'); ?>
        </div>
    </fieldset>
    <fieldset class="border_top_submit">
        <button type="submit" id="submit" class="btn btn-primary btn-mylg">ISCRIVIMI ORA!</button>
    </fieldset>
</form>
<script>
    function showFormNewGruppo()
    {
        $('#div_add_group').show();
        $('#div_select_group').hide();
    }

    function showFormSelectGruppo()
    {
        $('#div_add_group').hide();
        $('#div_select_group').show();
    }

    $(function () {


    })
</script>