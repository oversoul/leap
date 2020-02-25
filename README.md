# Leap

A simple command line app to find FIXME in your projects

## Usage

Run it simply inside your project directory.

or you can use the command

```
php bin/leap.php find ~/path
```

## Config

it's possible to create configuration file inside the searched folder.

path: `my-project/.leap/config.php`

```php
return [
    // folders you want to exclude from the search
    'exclude_folders'   =>  ['vendor'],

    // keywords you want to search for (not case sensitive)
    'keywords'  =>  ['NOTE', 'TODO', 'FIXME']
];
```

You can also define a global config file, at your home directory

```
~/.leap/config.php
``` 


## Tests

`./vendor/bin/phpunit`


## Notes

- PR are welcome
- You can generate a phar file by using [box](https://github.com/box-project/box2)