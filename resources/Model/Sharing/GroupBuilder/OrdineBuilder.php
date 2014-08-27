<?php
/**
 * OrdineBuilder builds group for Condivisione ORDINI
 */
class Model_Sharing_GroupBuilder_OrdineBuilder 
    extends Model_Sharing_GroupBuilder_BuilderInterface
{

    /**
     * @return void
     */
    public function addNoteConsegna()
    {
        $this->group->setPart('note_consegna', new Model_Sharing_GroupBuilder_Parts_Text());
    }

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->group = new Model_Sharing_GroupBuilder_Parts_Ordine();
    }

}
