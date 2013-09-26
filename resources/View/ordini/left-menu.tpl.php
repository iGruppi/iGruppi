<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
          Produttore
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body">
    <?php 
    if(count($this->produttori) > 0):
        foreach ($this->produttori as $key => $produttore): ?>
        <?php if( $produttore->idproduttore == $this->idproduttore ): ?>
        <h4><?php echo $produttore->ragsoc; ?></h3>
        <?php else: ?>
        <h4><a href="/ordini/index/idproduttore/<?php echo $produttore->idproduttore; ?>"><?php echo $produttore->ragsoc; ?></a></h3>
        <?php endif; ?>
    <?php 
        endforeach; 
    endif; ?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Stato
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
        <h4><a href="/ordini/index/stato/Pianificato">Pianificato</a></h3>
        <h4><a href="/ordini/index/stato/Aperto">Aperto</a></h3>
        <h4><a href="/ordini/index/stato/Chiuso">Chiuso</a></h3>
        <h4><a href="/ordini/index/stato/Archiviato">Archiviato</a></h3>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
          Periodo
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <h4><a href="/ordini/index/periodo/Ott-2013">Ottobre 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Settembre 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Agosto 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Luglio 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Giugno 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Maggio 2013</a></h3>
        <h4><a href="/ordini/index/periodo/Ott-2013">Aprile 2013</a></h3>
      </div>
    </div>
  </div>
</div>