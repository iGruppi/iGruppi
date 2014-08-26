<?php

namespace Model\Sharing\GroupBuilder;

/**
 *
 */
interface BuilderInterface
{
    /**
     * @return mixed
     */
    public function createGroup();

    /**
     * @return mixed
     */
    public function addIdgroup();

    /**
     * @return mixed
     */
    public function addValidita();

    /**
     * @return mixed
     */
    public function addVisibile();

    /**
     * @return mixed
     */
    public function getGroup();
}
