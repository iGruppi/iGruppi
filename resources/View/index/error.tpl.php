<h1>Siamo spiacenti, si è verificato un errore...</h1>

<?php switch ($this->errorCode):
    
        case 404: // Page not found! ?>
    <h3>Errore <strong><?php echo $this->errorCode; ?></strong>: Pagina non trovata!</h3>
    <h4>La pagina richiesta non è disponibile.</h4>
<?php   break; ?>
    
<?php   case 401: // Unauthorized ?>
    <h3>Errore <strong><?php echo $this->errorCode; ?></strong>: Non autorizzato!</h3>
    <h4>L'accesso alla pagina richiesta non è consentito.</h4>
<?php   break; ?>

<?php   default: ?>

<?php   break; 
      endswitch;
?>
