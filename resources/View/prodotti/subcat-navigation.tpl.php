<?php if(count($this->categorie) > 0): ?>
    <ul class="nav bs-sidenav">
        <li><a href="javascript: void(0);" onclick="$('html,body').animate({scrollTop: $('#wrap').offset().top});" class="text-right"><small>Top <span class="glyphicon glyphicon-circle-arrow-up"></span></small></a></li>
        
    <?php foreach ($this->categorie AS $cat): ?>

        <li>
          <a href="#cat_<?php echo $cat->getId(); ?>"><?php echo $cat->getDescrizione(); ?></a>
          <ul class="nav">
        <?php foreach ($cat->getSubcat() AS $subcat): ?>
            <?php 
                $max_length = 26;
                if(strlen($subcat->getDescrizione()) > $max_length): ?>
                <li><a href="#subcat_<?php echo $subcat->getId(); ?>" class="moreThan30" data-toggle="tooltip" data-placement="left" title="<?php echo $subcat->getDescrizione(); ?>"><?php echo substr($subcat->getDescrizione(), 0, $max_length) . "..."; ?></a></li>
            <?php else: ?>
                <li><a href="#subcat_<?php echo $subcat->getId(); ?>"><?php echo $subcat->getDescrizione(); ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
          </ul>
        </li>    

    <?php endforeach; ?>
    </ul>
<script>
    !function ($) {

      $(function(){

        var $window = $(window);
        var $body   = $(document.body);

        var navHeight = $('.header').outerHeight(true) + 10;

        $body.scrollspy({
          target: '.bs-sidebar',
          offset: navHeight
        });

        $window.on('load', function () {
          $body.scrollspy('refresh');
        });

        $('.moreThan30').tooltip('hide');

    })

    }(window.jQuery)

</script>
<?php endif; ?>