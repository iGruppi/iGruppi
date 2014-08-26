<?php

namespace Model\Sharing\GroupBuilder;

/**
 * OrdineBuilder builds group for Condivisione ORDINI
 */
class OrdineBuilder implements BuilderInterface
{
    /**
     * @var Parts\Ordine
     */
    protected $ordine;

    /**
     * @return void
     */
    public function addIdgroup()
    {
        $this->ordine->setPart('idgroup', new Parts\Idgroup());
    }

    /**
     * @return void
     */
    public function addValidita()
    {
        $this->ordine->setPart('valido_dal', new Parts\Validita());
        $this->ordine->setPart('valido_dal', new Parts\Validita());
    }

    /**
     * @return void
     */
    public function addVisibile()
    {
        $this->ordine->setPart('visibile', new Parts\Visibile());
    }

    /**
     * @return void
     */
    public function createGroup()
    {
        $this->ordine = new Parts\Ordine();
    }

    /**
     * @return Parts\Ordine
     */
    public function getGroup()
    {
        return $this->ordine;
    }
}
