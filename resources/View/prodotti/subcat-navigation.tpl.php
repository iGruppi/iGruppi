<?php if(count($this->listSubCat) > 0): ?>
    <ul class="nav bs-sidenav">
    <?php foreach ($this->listSubCat as $idcat => $cat): ?>

        <li>
          <a href="#cat_<?php echo $idcat; ?>"><?php echo $cat["categoria"]; ?></a>
          <ul class="nav">
        <?php foreach ($cat["subcat"] as $idsubcat => $subcat): ?>
            <?php 
                $max_length = 26;
                if(strlen($subcat) > $max_length): ?>
                <li><a href="#subcat_<?php echo $idsubcat; ?>" class="moreThan30" data-toggle="tooltip" data-placement="left" title="<?php echo $subcat; ?>"><?php echo substr($subcat, 0, $max_length) . "..."; ?></a></li>
            <?php else: ?>
                <li><a href="#subcat_<?php echo $idsubcat; ?>"><?php echo $subcat; ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
          </ul>
        </li>    

    <?php endforeach; ?>
    </ul>
<script>
!function ($) {

  $(function(){

    var $window = $(window)
    var $body   = $(document.body)

    var navHeight = $('.header').outerHeight(true) + 10

    $body.scrollspy({
      target: '.bs-sidebar',
      offset: navHeight
    })

    $window.on('load', function () {
      $body.scrollspy('refresh')
    })

    // back to top
    setTimeout(function () {
      var $sideBar = $('.bs-sidebar')

      $sideBar.affix({
        offset: {
          top: function () {
            var offsetTop      = $sideBar.offset().top
            var sideBarMargin  = parseInt($sideBar.children(0).css('margin-top'), 10)
            var navOuterHeight = $('.header').height()

            return (this.top = offsetTop - navOuterHeight - sideBarMargin)
          }
        , bottom: function () {
            return (this.bottom = $('.bs-footer').outerHeight(true))
          }
        }
      })
    }, 100)

    setTimeout(function () {
      $('.bs-top').affix()
    }, 100)
    
    $('.moreThan30').tooltip('hide');

})

}(window.jQuery)

</script>
<?php endif; ?>