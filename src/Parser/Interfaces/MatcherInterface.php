<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25
 */

namespace Elgentos\Parser\Interfaces;

use Elgentos\Parser\Context;

interface MatcherInterface
{

    public function validate(Context $context): bool;

}
