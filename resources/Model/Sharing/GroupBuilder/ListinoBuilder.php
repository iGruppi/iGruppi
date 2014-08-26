<?php

namespace Model\Sharing\GroupBuilder;

/**
 * ListinoBuilder builds group for Condivisione LISTINI
 */
class ListinoBuilder implements BuilderInterface
{
    /**
     * @var Parts\Listino
     */
    protected $listino;

    /**
     * @return void
     */
    public function addIdgroup()
    {
        $this->listino->setPart('idgroup', new Parts\Idgroup());
    }

    /**
     * @return void
     */
    public function addValidita()
    {
        $this->listino->setPart('valido_dal', new Parts\Validita());
        $this->listino->setPart('valido_dal', new Parts\Validita());
    }

    /**
     * @return void
     */
    public function addVisibile()
    {
        $this->listino->setPart('visibile', new Parts\Visibile());
    }

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->listino = new Parts\Listino();
    }

    /**
     * @return Parts\Listino
     */
    public function getGroup()
    {
        return $this->listino;
    }
}
