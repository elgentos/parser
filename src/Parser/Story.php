<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 11:48
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\RuleInterface;

class Story implements RuleInterface
{

    /** @var RuleInterface[] */
    private $rules;

    private $total = 0;
    /** @var int */
    private $successful = 0;

    public function __construct(RuleInterface ... $rules)
    {
        $this->rules = $rules;
    }

    public function match(Context $context): bool
    {
        return true;
    }

    public function parse(Context $context): bool
    {
        $result = array_filter($this->rules, function($rule) use ($context) {
            return $this->execute($rule, $context);
        });

        // Register how many rules where executed and successful
        $this->total += count($this->rules);
        $this->successful += count($result);

        return ! empty($result);
    }

    protected function execute(RuleInterface $rule, Context $context): bool
    {
        return $rule->parse($context);
    }

    /**
     * Tell how many rules where successful
     *
     * @return int
     */
    public function getSuccessful(): int
    {
        return $this->successful;
    }

    /**
     * Tell how many rules where executed
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

}
