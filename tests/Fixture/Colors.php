<?php

namespace Collection\Test\Fixture;

use Collection\Interfaces\Arrayable;

/*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
 */

/**
 * Description of Colors
 *
 * @author thiagoguimaraes
 */
class Colors implements Arrayable
{

    private $colors = [];


    public function setColor($color)
    {
        $this->colors[] = $color;

    }//end setColor()


    public function toArray()
    {
        return $this->colors;

    }//end toArray()


}//end class
