# Elgentos Content parser

Parse content from json/yaml/csv to a usable arrays

## Description

Use this library to turn your day-to-day configurations into
usable arrays/objects.

It's easy to setup your own requirements.

# Example

Consider you have a basic requirement for a configuration file
and you want to do some additional stuff:

- merging
- filtering
- importing multiple files

`path/to/file.json`
```json
{
  "db": {
    "user": "me",
    "password": ""
  },
  "data": [
    {
      "key": "value"
    }
  ]
}
```

`parse.php`
```php
<?php

namespace Elgentos\Parser;

// Create a target context
$target = [
    '@import' => 'path/to/file.json',
    'db' => [
        'password' => '$ecr3t'            
    ],
    'data' => [
        [],            
        [
            'key2' => 'value2'                
        ]
    ]
];
$context = new Context($target);

$metrics = new StoryMetrics;

$mainStory = $metrics->createStory(
    'Main story',
    // Loop as long as there are changes
    new Rule\Changed(
        // Iterate over Context
        new Rule\Iterate(
            // Loop until one rule fails
            new Rule\LoopAll(
                // Read contents of file on '@import' index
                new Rule\Import(
                    __DIR__,
                    new Matcher\IsExact('@import')
                ),
                // Add a new story for the metrics
                $metrics->createStory(
                    'Json imported', 
                    // Decode json
                    new Rule\Json
                ),
                // Merge current root down over imported data
                new Rule\MergeDown(true),
                // Circuit braker, stop
                new Rule\NoLogic(false)
            ),
            true
        )                
    ) 
);

$mainStory->parse($context);

var_dump($metrics->getStatistics());
/**
 *  '"Main story" has 1 page(s) and are read 0 of 1 time(s) successfully'
 *  '"Json imported" has 1 page(s) and are read 1 of 1 time(s) successfully'
 */

echo json_encode($context->getRoot());
//echo json_encode($target); // Would give the same output
```

`result`
```json
{
  "db": {
    "user": "me",
    "password": "$ecr3t"
  },
  "data": [
    {
      "key": "value"
    },
    {
      "key2": "value2"
    }
  ]
}
```

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

## Stories

Story is a bunch of pages(`RuleInterface $rules`), it will parse every page(rule) of the story.
It's a good way to start building a parser.

Stories don't care about the result of the executed pages,
they'll just execute them all.

Because it also is a `Elgentos\Parser\Interfaces\RuleInterface`
you can use it recursive.

```php
<?php

namespace Elgentos\Parser;

$root = [];
$context = new Context($root);

$story = new Story(
        'Fancy name',
        new Rule\NoLogic(false),
        new Rule\NoLogic(true),
        new Rule\NoLogic(true),
        new Rule\NoLogic(false)
);

// Will call all 4 rules
$story->parse($context);
// 4 pages in the story, 4 executed, 2 successful

// Will call all 4 rules again
$story->parse($context);
// 4 pages in the story, 8 executed, 4 successful


```

## Statistics

If you want some statistics on parsing, `Elgentos\Parser\StoryMetrics`
is ideal to collect them, it takes `Story` as input and create some totals.

- `addStories(Story ...$stories)`
Add stories to the metrics to monitor.
- `createStory(string $name, RuleInterface ...$rules)`
Create a story right here.
- `getPages`
Number of pages(rules)
- `getRead`
How often are the pages(rules) executed.
- `getSuccessful`
How many rules were successful for the stories
- `getStatistics(*string $message)`
Creates a human readable array,
fun fact; you could create csv output which you can then parse.  

## Todo

- turn into objects
Give some rules and create objects
- add stories for common tasks
Create stories which will import files so you can start working right away
 
