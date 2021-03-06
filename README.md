evocms-minifier
=========================

EvolutionCMS 2.* minifier

Install
----------
`php artisan package:installrequire zeeyn/evocms-minifier "^1.2.0"` in you **/core/** folder

How to use
----------
From version 1.2 Minifier calling from Blade directives. Little about them:

- `@minjs(array $files, int $no_laravel_cache = 0, int $minify = 1, string $output_path = '')`

- `@mincss(array $files, int $no_laravel_cache = 0, int $minify = 1, string $output_path = '')`

- `@minifier(array $files, int $no_laravel_cache = 0, int $minify = 1, string $output_path = '')`

As you can see syntax didn't changed too much, as old commands new directives are calling in same places of blade page.
Changes touched optimisation spheres, as example:

In old versions you may use minifier not in all documents, but it will prepares for your document every time.
Directives are preparing and working only if you call them.





How to use (old versions v.1.1.2 and older)
----------

In released version you have 3 general functions:

- `{{ $minifier->activate( $files, $no_laravel_cache = 0, $minify = 1, $output_path = '' ) }}`

- `{{ $minifier->js( $files, $no_laravel_cache = 0, $minify = 1, $output_path = '' ) }}`

- `{{ $minifier->css( $files, $no_laravel_cache = 0, $minify = 1, $output_path = '' ) }}`

Distinctions:
- `activate()` gets `$files` parameter and automatically gets their extension;
- `js()` accepts only `*.js` files to use;
- `css()` accepts only `*.css` files; 

That means that `activate()` method you can use for css and js files (just place it in right place basing on files extensions)
but `js()` and `css()` will work only with `*.js` and `*.css` files and you must place them on right place (`css()` in head, `js()` in bottom of `<body>`)

Explanation
---------- 
- `$minifier` -- document variable, generates automatically.

- `activate()`, `css()`, `js()` -- starting function, all that you will use.

- `$files` -- array of file paths example:
`$file = ['/example/path/file.{css or js}', ...]`

- `$no_laravel_cache`  -- flag that signals to script user or not Laravel cache system, **default == 0**

- `$minify` -- integer variable, activates min file generation, **default == 1**

- `$output_path` -- path where generated min file will saves, **default -- root folder**

- `@minjs` -- Blade directive that do the same as `$minifier->js()` method.

- `@mincss` -- Blade directive that do the same as `$minifier->css()` method.

- `@minifier` -- Blade adaptive directive that do the same as `$minifier->activate()` method.


Example of use
----------
for css:
```
    ...
    <title>Title</title>
 
     {!! $minifier->activate(['css/style.css','css/style2.css', 'css/style3.css']) !!}

    ...
```
or directive
```
    ...
    <title>Title</title>
 
     @mincss(['css/style.css','css/style2.css', 'css/style3.css'])
     //or
     @minifier(['css/style.css','css/style2.css', 'css/style3.css'])
    ...
```

for js:
```
     {!! $minifier->activate(['js/script1.js','js/script2.js', 'js/script3.js']) !!}

```
or directive
```
    ...
    <title>Title</title>
 
     @minjs(['css/style.css','css/style2.css', 'css/style3.css'])
     //or
     @minifier(['css/style.css','css/style2.css', 'css/style3.css'])

    ...
```
That's all, now let's see, what in output:

code:
```
    {!! $minifier->activate(['css/style.css','css/style2.css', 'css/style3.css']) !!}
    //or
    @minifier(['css/style.css','css/style2.css', 'css/style3.css'])
```

devTool:

```
<link rel="stylesheet" href="include.511b12e6f2e99d887bebfc7e392c7b80.min.css">
```

As you can see, minifier creates new file, that contains info from all files before

For js files will be same, but used with `<script>` tag

Rules of use
---

if you want to change generated file you must:

- change raw files (NOT GENERATED)
- clear site cache* 
- refresh site (F5 ets.)

> \*In EvolutionCMS 2.* release you can clear cache from manager panel
> in versions between RC and release you must do `php artisan cache:clear` from your **/core/** folder 


in `$files` array paths to files must stands on order of including:

good:
```
$files = ['jquery.min.js', 'script.js'] //or another js lib
```

bad:
```
$files = ['script.js', 'jquery.min.js'] //or another js lib
```
with the `*.css` files that rule using too


More info
---

- if you set `$minify` variable to `0` script will output ol added files with auto version `*.*?v=*`
- if you set `$no_laravel_cache` variable to `1` script will always generate minified file, not recommended for large `.css` files

