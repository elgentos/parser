<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 20:58.
 */
class FactoryTestConstrutor
{
    public $argument1;
    public $argument2;

    public function __construct($argument1, $argument2)
    {
        $this->argument1 = $argument1;
        $this->argument2 = $argument2;
    }
}
