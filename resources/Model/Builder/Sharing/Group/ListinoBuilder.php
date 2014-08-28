<?php
/**
 * ListinoBuilder builds group for Condivisione LISTINI
 */
class Model_Builder_Sharing_Group_ListinoBuilder 
    extends Model_Builder_Sharing_Group_BuilderInterface
{

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->group = new Model_Builder_Sharing_Group_Parts_Listino();
    }

}
