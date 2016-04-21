<?php

namespace  Collection\Test\Fixture;

/*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
 */

/**
 * Description of Person.
 *
 * @author thiagoguimaraes
 */
class Person
{
    private $name;

    private $age;

    private $doc;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

// end setName()
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

// end setAge()
    public function setDoc($doc)
    {
        $this->doc = $doc;

        return $this;
    }

// end setDoc()
    public function getName()
    {
        return $this->name;
    }

// end getName()
    public function getAge()
    {
        return $this->age;
    }

// end getAge()
    public function getDoc()
    {
        return $this->doc;
    }

// end getDoc()
    public function __toString()
    {
        return 'Olá, eu sou '.$this->getName();
    }

// end __toString()
}//end class
