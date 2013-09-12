<!doctype html>
<html>
<head>
    <title>iGruppi - Gruppi di acquisto</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!-- jQuery CDN 
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" />
    <script type="text/javascript" language="JavaScript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    -->
    <!-- jQuery -->
    <script type="text/javascript" language="JavaScript" src="/js/jquery/jquery-1.8.3.js"></script>
    <script type="text/javascript" language="JavaScript" src="/js/jquery/jquery-ui.js"></script>
    <link rel="stylesheet" href="/css/jquery/jquery-ui.css" type="text/css" />
    
    <!-- Personalized CSS and JS -->
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/style_print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="/css/form.css" type="text/css" />
	<script type="text/javascript" language="JavaScript" src="/js/jx.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/functions.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/jxAdmin.js"></script>

<?php if(is_array($this->arOnLoads) && count($this->arOnLoads) > 0): ?> 
<script language="javascript">
	$(document).ready(function(){
    <?php   foreach ($this->arOnLoads AS $js_onload) {
                echo $js_onload;
            } ?>    
	});
</script>
<?php endif; ?>

</head>
<body>
    <div id="container">
        <div id="header">
			<div id="header_top">
                <a href="/"><img id="logo_top" src="/images/igruppi_logo.png" alt="iGruppi logo"></a>
<?php   $auth= Zend_Auth::getInstance();
        if($auth->hasIdentity()):
?>
                <div id="menu_bar">
                    <ul>
                        <li class="page_item"><a href="/dashboard">Home</a></li>
                        <li class="page_item"><a href="/ordini" class="green">Ordini</a>
<!--                            <ul class="children">
                                <li class="page_item"><a href="/ordini">In corso</a></li>
                                <li class="page_item"><a href="/ordini/archivio">Archivio</a></li>
                            </ul> -->
                        </li>
                        <li class="page_item last_item"><a href="/gruppo">Gruppo</a>
                            <ul class="children">
                                <li class="page_item"><a href="/gruppo/iscritti">Utenti Iscritti</a></li>
                                <li class="page_item"><a href="/produttori">Produttori</a></li>
                            </ul>
                        </li>
                        <!--
                        <li class="page_item"><a href="#">Gestione</a>
                            <ul class="children">
                                <li class="page_item"><a href="#">Gestione 1</a></li>
                                <li class="page_item"><a href="#">Utenti</a></li>
                            </ul>
                        </li>
                        -->
                    </ul>
                </div>
                <div class="on_right" style="margin: 42px 0;">
                    <a href="/auth/logout">Esci</a>
                </div>
<?php   else: ?>
                <div class="on_right" style="margin: 42px 0;">
                    <a href="/auth/login">Login</a><br />
                    <a href="/auth/register">Registrati</a>
                </div>
<?php   endif; ?>
            </div>
        </div>
        <div id="content">
            <?php echo (isset($this->content) ? $this->content: ""); ?>
            <div style="clear: both;">&nbsp;</div>
        </div>
        <div id="footer">
            <div id="footer_c">
                <a href="http://igruppi.com">iGruppi</a><br />
                Open Source Software per i Gruppi di acquisto
            </div>
        </div>
    </div>
</body>
</html>