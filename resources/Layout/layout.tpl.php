<!DOCTYPE html>
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
    <script type="text/javascript" language="JavaScript" src="/jquery/jquery.js"></script>
    <script type="text/javascript" language="JavaScript" src="/js/jquery-migrate-1.2.1.js"></script>        
    <script type="text/javascript" language="JavaScript" src="/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="/jquery-ui/jquery-ui.css" type="text/css" />
    <!-- Bootstrap -->
    <script type="text/javascript" language="JavaScript" src="/bootstrap/js/bootstrap.min.js"></script>
    <!-- DateTimePicker -->
    <script type="text/javascript" language="JavaScript" src="/datetimepicker/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" href="/datetimepicker/jquery.datetimepicker.css" type="text/css" />
    <!-- Moment -->
    <script type="text/javascript" language="JavaScript" src="/moment/moment.js"></script>
    
    <!-- Personalized CSS and JS -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/custom-bs.css" type="text/css" />
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
    <div id="wrap">
      <div class="container">
        <div class="header hidden-print">
            <a class="pull-left" href="/"><img id="logo_top" src="/images/igruppi_logo.png" alt="iGruppi logo"></a>
<?php   $auth= Zend_Auth::getInstance();
        if($auth->hasIdentity()):
            $userData = $auth->getStorage()->read();
?>
            <div id="userdata">
                <b><?php echo $userData->nome . " " . $userData->cognome; ?></b><br />
                <em>Gruppo:</em> <b><?php echo $userData->gruppo; ?></b>
            </div>                
            <ul class="nav nav-pills pull-right">
              <li><a class="btn btn-default btn-mylg" href="/dashboard"><span class="glyphicon glyphicon-home"></span> Home</a></li>
              <li><a class="btn btn-success btn-mylg" href="/ordini"><span class="glyphicon glyphicon-shopping-cart"></span> Ordini</a></li>
              <li>
                <div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle btn-mylg" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-glass"></span> Gruppo <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="/gruppo/iscritti">Utenti</a></li>
                    <li><a href="/produttori">Produttori</a></li>
                  </ul>
                </div>            
              </li>
              <li><a class="btn btn-default btn-mylg" href="/auth/logout"><span class="glyphicon glyphicon-log-out"></span> Esci</a></li>
            </ul>
<?php   else: ?>
            <ul class="nav nav-pills pull-right">
              <li><a class="btn btn-success btn-mylg" href="/auth/login">Login</a></li>
              <li><a class="btn btn-primary btn-mylg" href="/auth/register"><span class="glyphicon glyphicon-user"></span> Nuovo utente</a></li>
            </ul>                
<?php   endif; ?>
            <div class="clearfix">&nbsp;</div>            
        </div>
        
        <div class="content">
            <?php echo (isset($this->content) ? $this->content: ""); ?>
        </div>
      </div>
    </div>
    
    <div id="footer" class="hidden-print">
      <div class="container">
            <div class="info">
                <p><a href="http://igruppi.com"><b>iGruppi</b></a><br /><small>Open Source Software per i Gruppi di acquisto</small></p>
                <p class="icon_social github"><a href="https://github.com/Jazzo/iGruppi"><small>Code hosted by Github</small></a></p>
            </div>
            <div class="social">
                <p class="icon_social twitter"><a href="https://twitter.com/iGruppi"><small>@igruppi</small></a></p>
                <p class="icon_social wordpress"><a href="http://igruppi.com"><small>Blog</small></a></p>
            </div>
      </div>
    </div>

</body>
</html>
