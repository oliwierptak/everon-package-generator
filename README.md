# Everon Package Generator

Generate (package) files based on schema.

### Installation

```
composer require everon/package-generator --dev
```

### Usage

1. Define schema and specify variables used in templates
2. Run `composer popo` to update schema's scope 
3. Run `bin/everon-packager generate ...`


```shell
Usage:
  generate [options]

Options:
  -s, --schemaFilename=SCHEMAFILENAME                  Path to schema file
  -o, --outputPath=OUTPUTPATH                          Output path
  -f, --overwriteExistingFiles=OVERWRITEEXISTINGFILES  Set to true, to overwrite existing files [default: false]
```
Note: See [tests/fixtures/](tests/fixture/) for examples.

### Schema

`$` is variable definition section.

Note: There can be as many variables and resource section definitions as needed.

#### Package Schema

```yaml
$:
  variableName: <variable-value> 
  variableName: <variable-value> 
  variableName: <variable-value>
  ...

<resource-section>:
  resource: [
    {
      location: <path>,
      content: <file name of the template with file content>
    },
    {
      location: <path>,
      content: <file name of the template with file content>
    }
    ....
  ]}}
  
<resource-section>:
  resource: [
    {
      location: <path>,
      content: <file name of the template with file content>
    },
    {
      location: <path>,
      content: <file name of the template with file content>
    }
      ....
  ]}}

```

#### Package Scope

Specify all placeholders that are defined under variable (`$`) section in package file.

```yaml
Packager:
  SchemaScopeConfigurator:
    property: [
      {name: variableName}
      {name: variableName}
      {name: variableName}
      ...
    ]}}

```


### Example

#### Schema files

`package.yml`

```yaml
$:
  name: Bar
  namespace: ExampleNamespace
  example: Lorem Ipsum

<model>:
  resource: [
    {
      location: src/Model/BarModel.php,
      content: config/templates/Model/BarModel.php
    },
    {
      location: src/Configurator/BarConfigurator.php,
      content: config/templates/Configurator/BarConfigurator.php
    }
  ]}}
```

`package-scope.yml`

```yaml
$:
  config:
    namespace: Everon\PackageGenerator\Configurator
    namespaceRoot: Everon\PackageGenerator\
  default:
    outputPath: src/

Packager:
  SchemaScopeConfigurator:
    property: [
      {name: name}
      {name: namespace}
      {name: example}
    ]}}
```

Note: Run `vendor/bin/popo generate -c config/everon-pacakge-generator-config.yml -s config/everon-pacakge-generator.yml,config/package-scope.yml -o src --php74CompatibleOutput 1` to update data schema scope configuration.

#### Template files

`config/templates/Model/BarModel.php`

```php
<?php declare(strict_types = 1);

namespace %namespace%\Model;

use %namespace%\Configurator\%name%Configurator;

class %name%Model
{
    public function execute(): %name%Configurator
    {
        return new %name%Configurator();
    }
}

```

`config/templates/Configurator/BarConfigurator.php`

```php
<?php declare(strict_types = 1);

namespace %namespace%\Configurator;

class %name%Configurator
{
    public function getExample(): string
    {
        return '%example%'; 
    }
}

```

#### Output

`src/Model/BarModel.php`

```php
<?php declare(strict_types = 1);

namespace ExampleNamespace\Model;

use ExampleNamespace\Configurator\BarConfigurator;

class BarModel
{
    public function execute(): BarConfigurator
    {
        return new BarConfigurator();
    }
}
```

`src/Configurator/BarConfigurator.php`

```php
<?php declare(strict_types = 1);

namespace ExampleNamespace\Configurator;

class BarConfigurator
{
    public function getExample(): string
    {
        return 'Lorem Ipsum'; 
    }
}
```

### Requirements

PHP v7.4+

