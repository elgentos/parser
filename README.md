# Elgentos Content parser [![Build Status](https://travis-ci.org/elgentos/parser.svg?branch=master)](https://travis-ci.org/elgentos/parser)
Parse content from json/yaml/csv/text to a usable array

## Description
Use this library to turn your day-to-day configurations into
usable arrays/objects.

Supports json, yaml, csv, xml and plain text.

```php
<?php
/**
 * Read a file, import recursive and return a merged array
 */
$data = Elgentos\Parser::readFile('file.json');
```

```php
<?php
/**
 * Read a file, no recursion
 */
$data = Elgentos\Parser::readSimple('file.json');
```

## Instalation
To use in your project require

`composer require elgentos/parser`

To support YAML also require:

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
Read a directory recursively.

```yaml
base:
  "@import-dir": "path/to/directory"
```
```json
{
  "base": {"@import-dir": "path/to/directory"}
}
```

## Customization
Due Service Contracts you can easily add your own functionality:
- Rules `\Elgenttos\Parser\Interfaces\RuleInterface`
- Matcher `\Elgenttos\Parser\Interfaces\MatcherInterface`
- Parser `\Elgenttos\Parser\Interfaces\RuleInterface`
- Stories `\Elgenttos\Parser\Interfaces\StoriesInterface`

## Automated creation of objects



## Technical description
For our technical docs [docs/technical.md](docs/technical.md).
