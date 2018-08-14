<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 22:16
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Xml implements RuleInterface
{

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();
        if (! \is_string($current)) {
            throw new RuleInvalidContextException(sprintf("%s expects a xml string", self::class));
        }

        $current = $this->fromXml($current);
        return true;
    }

    private function fromXml(string $current): array
    {
        $xml = \simplexml_load_string($current, \SimpleXMLIterator::class);

        $result = $this->walkXml($xml);
        if (! \is_array($result)) {
            return [$result];
        }

        return $result;
    }

    private function walkXml(\SimpleXMLIterator $parent)
    {
        $parent->rewind();

        $attributes = $parent->attributes();
        $hasAttributes = $attributes->count() > 0;
        $hasChildren = $parent->count() > 0;

        if (! $hasAttributes && ! $hasChildren) {
            return (string)$parent;
        }

        $result = [];
        if ($hasAttributes) {
            $result['@attributes'] = ((array)$attributes)['@attributes'];
        }

        if (! $hasChildren) {
            $result['@value'] = (string)$parent;
            return $result;
        }

        $children = [];
        for (; $parent->valid(); $parent->next()) {

            $key = $parent->key();
            /** @var \SimpleXMLIterator $current */
            $current = $parent->current();

                if (! isset($children[$key])) {
                $children[$key] = [];
            }

            $children[$key][] = $this->walkXml($current);
        }

        $children = \array_map(function(&$child) {
            if (\count($child) > 1) {
                return $child;
            }
            return $child[0];
        }, $children);

        $result = \array_merge($result, $children);

        return $result;
    }


}
