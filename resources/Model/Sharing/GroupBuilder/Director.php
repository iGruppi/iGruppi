<?php

namespace Model\Sharing\GroupBuilder;

/**
 * Director is part of the builder pattern. It knows the interface of the builder
 * and builds a complex object with the help of the builder. 
 * 
 * You can also inject many builders instead of one to build more complex objects
 */
class Director
{

    /**
     * The director don't know 'bout concrete part
     *
     * @param BuilderInterface $builder
     *
     * @return Parts\Group
     */
    public function build(BuilderInterface $builder)
    {
        $builder->createGroup();
        $builder->addIdgroup();
        $builder->addValidita();
        $builder->addVisibile();

        return $builder->getGroup();
    }
}
