# Elgentos Content parser [![Build Status](https://travis-ci.org/elgentos/parser.svg?branch=master)](https://travis-ci.org/elgentos/parser)
Parse content from json/yaml/csv/text to a usable array

## Description
Use this library to turn your day-to-day configurations into
usable arrays/objects.

Supports json/yaml/csv and plain text.

```php
$data = Elgentos\Parser\Parser::readFile('file.json');
```

## Instalation
To use in your project:

`composer require elgentos/parser`

To support YAML

`composer require symfony/yaml`

## Directives
You can use directives inside your file.

### @import
Load content of other files directly in your current file.

*YAML*
```yaml
othercontent:
  "@import": path/to/other/file.yaml
```
*JSON*
```json
{
  "othercontent": {"@import": "path/to/otherfile.yaml"  }
} 
```
*CSV*
```csv
"@import"
"path/to/file.json"
"path/to/otherfile.yaml"
"path/to/file2.yaml"
```

### @import-dir
Read a directory recusively. 

```yaml
base:
  "@import-dir": "path/to/directory"
```
```json
{
  "base": {"@import-dir": "path/to/directory"}
}
```

## Todo
Things that need some work.

### XML
Parse XML files.

### Object builder
Convert a array to objects based on a set of rules.

## Customization
We rely heavily on service contracts you can easily add your own;
- Rules `\Elgenttos\Parser\Interfaces\RuleInterface`
- Matcher `\Elgenttos\Parser\Interfaces\MatcherInterface`
- Parser `\Elgenttos\Parser\Interfaces\RuleInterface`
- Stories `\Elgenttos\Parser\Interfaces\StoriesInterface`
 
## Technical description
For our technical docs [docs/technical.md].

## Examples
We have a seperate section for some useful [docs/examples.md]


## Context

The parser works with a context, you've to define this once in your code.
The context is used to pass around.

The context will create a reference to the original array.

You'll use them in rules/matchers.

- `$context->getRoot()` will return a pointer to the root object in the context
- `$context->getIndex()` will return the index for the context
- `$context->getCurrent()` will return a pointer to the current element in the root
- `$context->setIndex(string $index)` update the active index
- `$context->changed()` mark the context as dirty
- `$context->isChanged()` tell if the context is dirty

```php
<?php

$root = [];
$context = new \Elgentos\Parser\Context($root);

$root[] = 'test';

var_dump($context->getRoot()); // ['test']
var_dump($root); // ['test']

```

## Rules

We already provide a great number of rules which you can use
out of the box.

Most rules accept a `MatcherInterface` in constructor argument,
see below which matchers we have out of the box.
These are used to determine if a rule should be executed. 

All rules MUST implement `Elgentos\Parser\Interfaces\RuleInterace`
Given rules are located in namespace: `Elgentos\Parser\Rule`

### Logical rules

- `Changed(RuleInterface $rule)`
Provide any other rule to execute until no changes are made to the context.  
- `LoopAll(RuleInterface ...$rules)`
Provide 2 or more rules which will be executed until one fails.
`a->b->c->a->b->c->a` `a` fails the third time. (logical AND)
- `LoopAny(RuleInterface ...$rules)`
Provide 2 or more rules which will be executed as long as one is successful.
`a->b->a->a->a->b->c` `a` fails the first time, try `b`, 
`b` is true, `a` is true 2 times, `a|b|c` all fail. (logical OR)
- `NoLogic(bool $result)`
Does no manipulation, use as dummy or circuit braker.

### String rules

Next are equivalents of string manipulation rules.

- `Explode(*string $delimiter, *MatcherInterface $matcher)`
- `Rename(string $nexIndex, *MatcherInterface $matcher)`
- `Trim(*string $charlist, *MatcherInterface $matcher)`
- `Csv(*bool $firstHasKeys, *string $delimiter, *string $enclosure, *string $escape, *MatcherInterFace $matcher)`
Set firstHasKeys to true if the first row contains keys.
This will use these keys to all following rows.

### Manipulation rules

- `Callback(\Closure $closure, *MatcherInterface $matcher)`
The closure will be called with `Context` and MUST return a bool.
- `Import(string $rootDir, *MatcherInterface $matcher)`
Read contents to current Context, scoped to $rootDir 
- `Json(*MatcherInterface $matcher)`
Parse Json string to array.
- `Yaml(*MatcherInterface $matcher)`
Parse Yaml string to array, requires package `symfony/yaml`
- `Filter(string $pathSeperator, *MatcherInterface $matcher)`
Filter content from context.

### Structure rules 

- `Iterate(RuleInterface $rule, *bool $recursive, *MatcherInterface $matcher)`
Execute `$rule` for every index in the array, recursive will do as it says.
- `MergeDown(bool $mergeRecursive, *MatcherInterface $matcher)`
Merge contents of `Context` to `getRoot()`, `getRoot()` is leading.
- `MergeUp(bool $mergeRecursive, *MatcherInterface $matcher)`
Merge contents of `Context` to `getRoot()`, `getCurrent()`is leading.
    
### `RuleAbstract`

To quickly create a new rule, extend `Elgentos\Parser\Rule\RuleAbstract`
Provide a matcher(`getMatcher()`) and a manipulator(`execute(Context)`)

## Matchers

Most rules rely on matchers to determine if a rule should be applied.
Most default rules use `IsTrue` so they can be used in a Loop.

If you want to add a Rule, implement `Elgentos\Parser\Interfaces\MatcherInterface`

`*string $method` can be used to change which Context method is used.

- `IsCallback(\Closure $closure)`
The matcher will call closure with `Context` to implement your own.
- `IsExact(string $match, *string $method)`
Check if `getIndex/getCurrent` matches the given match.
- `IsRegExp(string $regExp, *string $method)`
Check if `getIndex/getCurrent` preg_matches the given regular expresion.
- `IsExists`
Check if the current index in `Context` is still valid.
- `IsFalse`
Just false.
- `IsTrue`
Just true.
- `MatchAll(MatcherInterface ...$matchers)`
All given matchers MUST return true(logical AND)
- `MatchAny(MatcherInterface ...$matchers)`
Any given matcher MAY return true(logical OR)

### IsType matcher

Check if current value is a type.

Base is `IsType(string $type, *string $method)`

- `IsString`
- `IsBool`
- `IsArray`
- `IsInt`
- `IsNumeric`
- `IsObject`
- `IsNull`
- `IsFloat`
