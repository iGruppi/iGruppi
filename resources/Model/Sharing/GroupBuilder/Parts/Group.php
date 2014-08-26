<?php

namespace Model\Sharing\GroupBuilder\Parts;

/**
 * GroupInterface is a contract for a group
 */
abstract class Group
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setPart($key, $value)
    {
        $this->data[$key] = $value;
    }
}
