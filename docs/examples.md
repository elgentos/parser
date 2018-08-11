# Examples

## Example

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


## Stories
Stories don't care about the result of the executed rules,
they'll just execute all of them.

```php
<?php

namespace Elgentos\Parser;

$root = [];
$context = new Context($root);

$story = new Story(
        'Reverence name',
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
