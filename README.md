evocms-minifier
=========================

EvolutionCMS 2.* minifier

Install
----------
`php artisan package:installrequire zeeyn/evocms-minifier '*'` in you **core/** folder

How to use
----------
`{{ $minifier->activate( $files, $minify = 1, $output_path = '' ) }}`

Explanation
---------- 
- `$minifier` -- document variable, generates automatically.

- `activate()` -- starting function, all that you will use.

- `$files` -- array of file paths example:
`$file = ['/example/path/file.{css or js}', ...]`

- `$minify` -- integer variable, activates min file generation, **default == 1**

- `$output_path` -- path where generated min file will saves, **default -- root folder**


Example of use
----------
for css:
```
    ...
    <title>Title</title>
 
     {!! $minifier->activate(['css/style.css','css/style2.css', 'css/style3.css']) !!}

    ...
```

for js:
```
     {!! $minifier->activate(['js/script1.js','js/script2.js', 'js/script3.js']) !!}

```

That's all, now let's see, what in output:

code:
```
    {!! $minifier->activate(['css/style.css','css/style2.css', 'css/style3.css']) !!}
```

devTool:

```
<link rel="stylesheet" href="include.511b12e6f2e99d887bebfc7e392c7b80.min.css">
```

As you can see, minifier creates new file, that contains info from all files before

For js files will be same, but used with `<script>` tag

###Some errors


if you want to change generated file you must:

- change raw files (NOT GENERATED)
- clear site cache* 
- refresh site (F5 ets.)

> \*In EvolutionCMS 2.* release you can clear cache from manager panel
> in versions between RC and release you must do `php artisan cahce:clear` from your **core** folder 


###More info

if you set `$minify` variable to `0` script will output ol added files with auto version `*.*?v=*`
