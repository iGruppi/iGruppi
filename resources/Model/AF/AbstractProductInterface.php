<?php
/**
 * AbstractProductInterface
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
interface Model_AF_AbstractProductInterface
{

    /**
     * every Product has to implement this
     * @param mixed $values Object or Array to set values
     * @return void
     */
    public function initDatiByObject($values);
}
