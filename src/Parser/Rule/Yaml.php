<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 11:37.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Exceptions\RuleSymfonyYamlNotFoundException;
use Elgentos\Parser\Interfaces\RuleInterface;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml implements RuleInterface
{
    /** @var SymfonyYaml */
    private $symfonyYamlParser;

    public function __construct($symfonyYamlParser = SymfonyYaml::class)
    {
        if (!\class_exists($symfonyYamlParser)) {
            throw new RuleSymfonyYamlNotFoundException('symfony/yaml not loaded, make sure to require it in your "composer.json"');
        }

        $this->symfonyYamlParser = new $symfonyYamlParser();
    }

    public function parse(Context $context): bool
    {
        $yamlContent = $context->getCurrent();
        $current = &$context->getCurrent();
        if (!\is_string($yamlContent)) {
            throw new RuleInvalidContextException(sprintf('%s expects a yaml string', self::class));
        }

        $current = $this->symfonyYamlParser->parse($yamlContent);
        $context->changed();

        return true;
    }
}
