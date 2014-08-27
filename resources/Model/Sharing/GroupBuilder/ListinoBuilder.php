<?php
/**
 * ListinoBuilder builds group for Condivisione LISTINI
 */
class Model_Sharing_GroupBuilder_ListinoBuilder 
    extends Model_Sharing_GroupBuilder_BuilderInterface
{

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->group = new Model_Sharing_GroupBuilder_Parts_Listino();
    }

}
