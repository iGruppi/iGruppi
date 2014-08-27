<?php
/**
 * Director is part of the builder pattern. It knows the interface of the builder
 * and builds a complex object with the help of the builder. 
 */
class Model_Sharing_GroupBuilder_Director
{
    /**
     * @param BuilderInterface $builder
     *
     * @return Parts\Group
     */
    public function build(Model_Sharing_GroupBuilder_BuilderInterface $builder)
    {
        $builder->createGroup();
        $builder->addKeys();
        $builder->addGroupName();
        $builder->addRef();
        $builder->addValidita();
        $builder->addVisibile();
        $builder->addNoteConsegna();

        return $builder->getGroup();
    }
}
