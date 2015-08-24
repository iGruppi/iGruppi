<?php
/**
 * ListinoBuilder builds group for Condivisione LISTINI
 */
class Model_Builder_GroupSharing_ListinoBuilder 
    extends Model_Builder_GroupSharing_BuilderAbstract
{
    
    /**
     * @return void
     */
    public function createGroup()
    {
        $this->group = new Model_Builder_GroupSharing_Parts_Listino();
    }

}
