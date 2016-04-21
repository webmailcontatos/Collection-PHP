<?php

namespace Collection\Test\Fixture;

use Collection\Interfaces\Jsonable;

/*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
 */

/**
 * Description of Colors.
 *
 * @author thiagoguimaraes
 */
class Cars implements Jsonable
{
    private $types = [];

    public function setType($type)
    {
        $this->types[] = $type;
    }

// end setType()
    public function toJson($options = 0)
    {
        return json_encode($this->types, $options);
    }

// end toJson()
}//end class
