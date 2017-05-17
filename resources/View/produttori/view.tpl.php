    <h2><?php echo $this->produttore->ragsoc; ?></h2>
    <p>
        <?php echo $this->produttore->indirizzo; ?><br />
        <?php echo $this->produttore->comune; ?> - <?php echo $this->produttore->provincia; ?><br />
        Telefono: <?php echo $this->produttore->telefono; ?>
    </p>
    <h4>
        Email: <a href="mailto: <?php echo $this->produttore->email; ?>"><?php echo $this->produttore->email; ?></a>
    </h4>

    <h2>Abstract</h2>
    <div id="desc_abstract"><?php echo $this->produttore->desc_abstract; ?></div>
    <hr />
    <h2>Presentazione</h2>
    <div id="desc_presentazione"><?php echo $this->produttore->desc_presentazione; ?></div>
    <hr />
    <h2>Storia</h2>
    <div id="desc_storia"><?php echo $this->produttore->desc_storia; ?></div>
    <hr />
    <h2>Certificazioni</h2>
    <div id="desc_certificazioni"><?php echo $this->produttore->desc_certificazioni; ?></div>
    <hr />
    <h2>Attenzioni all'ambiente</h2>
    <div id="desc_ambiente"><?php echo $this->produttore->desc_ambiente; ?></div>
    <hr />
    <h2>Servizi</h2>
    <div id="desc_servizi"><?php echo $this->produttore->desc_servizi; ?></div>
    <hr />
    <h2>Scelto perchè...</h2>
    <div id="desc_scelto"><?php echo $this->produttore->desc_scelto; ?></div>
    <hr />
