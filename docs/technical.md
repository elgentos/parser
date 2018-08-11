# Technical description
## Service contracts
## Context
## Rules
## Matchers
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
