<?php
/**
 * Director is part of the builder pattern. It knows the interface of the builder
 * and builds a complex object with the help of the builder. 
 */
class Model_Builder_GroupSharing_Director
{
    /**
     * @param BuilderInterface $builder
     *
     * @return Parts\Group
     */
    public function build(Model_Builder_GroupSharing_BuilderAbstract $builder)
    {
        $builder->createGroup();
        $builder->addId();
        $builder->addGroupKeys();
        $builder->addGroupName();
        $builder->addReferente();
        $builder->addIncaricato();
        $builder->addValidita();
        $builder->addVisibile();
        $builder->addExtra();
        $builder->addNoteConsegna();

        return $builder->getGroup();
    }
}
