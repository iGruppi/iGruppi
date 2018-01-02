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
            <?php echo $this->form->renderField('group_nome'); ?>
            <?php echo $this->form->renderField('group_desc'); ?>
            <?php echo $this->form->renderField('provincia'); ?>
        </div>
    </fieldset>
    <fieldset class="border_top_submit">
        <button type="submit" id="submit" class="btn btn-primary btn-mylg">ISCRIVIMI ORA!</button>
        <?php echo $this->form->renderField('registration_type'); ?>
    </fieldset>
</form>
<script>
    function showFormNewGruppo()
    {
        $('#div_add_group').show();
        $('#div_select_group').hide();
        // set mandatory field
        $('#idgroup').prop('required', false);
        $('#group_nome').prop('required', true);
        $('#group_desc').prop('required', true);
        $('#provincia').prop('required', true);
        // set registration_type
        $('#registration_type').val("1");
    }

    function showFormSelectGruppo()
    {
        $('#div_add_group').hide();
        $('#div_select_group').show();
        // set mandatory field
        $('#idgroup').prop('required', true);
        $('#group_nome').prop('required', false);
        $('#group_desc').prop('required', false);
        $('#provincia').prop('required', false);
        // set registration_type
        $('#registration_type').val("0");
    }

    $(function () {


    })
</script>