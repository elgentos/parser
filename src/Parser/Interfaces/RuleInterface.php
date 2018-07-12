<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:29
 */

namespace Dutchlabelshop\Parser\Interfaces;

use Dutchlabelshop\Parser\Context;

interface RuleInterface
{

    public function match(Context $context): bool;

    public function parse(Context $context): bool;

    public function addRule(RuleInterface $rule);//: void;
    public function executeRule(Context $context): bool;

}
