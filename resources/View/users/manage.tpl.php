
<h2>Titolo2: <?php echo $this->eprint($this->title_2); ?></h2>


<h3>Users:</h3>
<?php foreach ($this->users as $key => $value): ?>
<pre>
    <?php Zend_Debug::dump($value); ?>
</pre>
<?php endforeach; ?>
