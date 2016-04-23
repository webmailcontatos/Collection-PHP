<?php

namespace Collection\Interfaces;

interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param integer $options Opções
     *
     * @return string
     */
    public function toJson($options = 0);
}//end interface
