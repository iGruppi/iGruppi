<?php
/**
 * OrdineBuilder builds group for Condivisione ORDINI
 */
class Model_Builder_GroupSharing_OrdineBuilder 
    extends Model_Builder_GroupSharing_BuilderAbstract
{

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->group = new Model_Builder_GroupSharing_Parts_Ordine();
    }

}
