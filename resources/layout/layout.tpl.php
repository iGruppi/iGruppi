<!doctype html>
<html>
<head>
    <title>iGruppi - Gruppi di acquisto</title>
    <meta charset="UTF-8">
    <!-- jQuery CDN -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" />
    <script type="text/javascript" language="JavaScript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    
    <!-- Personalized CSS and JS -->
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/style_print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="/css/form.css" type="text/css" />
	<script type="text/javascript" language="JavaScript" src="/js/jx.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/functions.js"></script>
	<script type="text/javascript" language="JavaScript" src="/js/jxAdmin.js"></script>
    
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
                        <li class="page_item"><a href="/dashboard">Dashboard</a></li>
                        <li class="page_item"><a href="#" class="green">Ordini</a>
                            <ul class="children">
                                <li class="page_item"><a href="/ordini">In corso</a></li>
                                <li class="page_item"><a href="/ordini/archivio">Archivio</a></li>
                            </ul>
                        </li>
                        <li class="page_item"><a href="#">Gruppo</a>
                            <ul class="children">
                                <li class="page_item"><a href="/gruppo/iscritti">Utenti Iscritti</a></li>
                                <li class="page_item"><a href="/produttori">Produttori</a></li>
                            </ul>
                        </li>
                        <li class="page_item"><a href="#">Gestione</a>
                            <ul class="children">
                                <li class="page_item"><a href="#">Gestione 1</a></li>
                                <li class="page_item"><a href="#">Utenti</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <a class="on_right" style="margin: 42px 0;" href="/auth/logout">Esci</a>
<?php   else: ?>
                <a class="on_right" style="margin: 42px 0;" href="/auth/login">Login</a>
<?php   endif; ?>
            </div>
        </div>
        <div id="content">
            <?php echo (isset($this->content) ? $this->content: ""); ?>
            <div style="clear: both;">&nbsp;</div>
        </div>
        <div id="footer">
            <div id="footer_c">
                iGruppi<br />
                Open Source Software per i Gruppi di acquisto
            </div>
        </div>
    </div>
</body>
</html>