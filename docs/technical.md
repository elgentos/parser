# Technical description
## Service contracts
## Context
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
A Story is a bunch of rules, it will parse every rule in the story.
It's a good way to start building a parser.

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
