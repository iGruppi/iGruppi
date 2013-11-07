
    <div class="box_note">
        <h4>Note consegna</h4>
        <h5><span class="text-muted">Referente:</span> <a href="mailto:<?php echo $this->produttore->email; ?>"><?php echo $this->produttore->nome . " " . $this->produttore->cognome;?></a></h5>
        <p><?php echo $this->ordine->note_consegna; ?></p>
    </div>
