    <h2><?php echo $this->produttore->ragsoc; ?></h2>
    <p>
        <?php echo $this->produttore->indirizzo; ?><br />
        <?php echo $this->produttore->comune; ?> - <?php echo $this->produttore->provincia; ?><br />
        Telefono: <?php echo $this->produttore->telefono; ?>
    </p>
    <h4>
        Email: <a href="mailto: <?php echo $this->produttore->email; ?>"><?php echo $this->produttore->email; ?></a>
    </h4>
