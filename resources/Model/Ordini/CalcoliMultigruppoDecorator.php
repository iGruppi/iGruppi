<?php
/**
 * Description of CalcoliMultigruppoDecorator, it is a DECORATOR of Model_Ordini_Ordine
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Ordini_CalcoliMultigruppoDecorator extends Model_Ordini_CalcoliAbstract
{

    /**
     *Array of groups
     * @var array
     */
    protected $arGroups = array();
    
    /**
     * Set array groups
     * @param array $groups
     */
    public function setGroups(array $groups)
    {
        $this->arGroups = $groups;
    }
    
    
    
    
}