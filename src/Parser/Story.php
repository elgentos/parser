<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 11:48
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\RuleInterface;

class Story implements RuleInterface
{

    /** @var string */
    private $name;
    /** @var RuleInterface[] */
    private $rules;

    /** @var int */
    private $pages;
    /** @var int */
    private $read = 0;
    /** @var int */
    private $successful = 0;

    public function __construct(string $name, RuleInterface ... $rules)
    {
        $this->name = $name;
        $this->rules = $rules;

        $this->pages = count($rules);
    }

    public function match(Context $context): bool
    {
        return true;
    }

    public function parse(Context $context): bool
    {
        $successful = array_reduce($this->rules, function($succesful, $rule) use ($context) {
            $this->read++;
            if (! $this->execute($rule, $context)) {
                return $succesful;
            }
            return $succesful + 1;
        }, 0);

        // Update statistics
        $this->successful += $successful;

        return $successful > 0;
    }

    protected function execute(RuleInterface $rule, Context $context): bool
    {
        return $rule->parse($context);
    }

    /**
     * Tell how many pages(rules) where read
     * * count every $rule for every $story->parse()
     *
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * Tell how many pages(rules) where successful
     * * count $rule->parse() === true for $story->parse()
     *
     * @return int
     */
    public function getSuccessful(): int
    {
        return $this->successful;
    }

    /**
     * How often is this story told
     * * count every $story->parse()
     *
     * @return int
     */
    public function getRead(): int
    {
        return $this->read;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
