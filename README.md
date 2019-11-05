# evocms-minifier

EvolutionCMS 2.* minifier

## Install
`php artisan package:installrequire zeeyn/evocms-minifier '*'` in you **core/** folder

## Use

`{{ $minifier->FUNC
(['FILE_PATH', ...], MINIFICATION[1|0], OUTPUT_PATH )
 }}`

**explanation**
---------- 
FUNC => function name, 2 variants: css() and js()

FILE_PATH => array of strings, that contains path to files you want to minify, **required**

MINIFICATION => integer, if you want to contain all your raw files to one, **default == 1**

OUTPUT_PATH => string,  path to result files, **default == ''** (empty string, means, that files will be created in root folder of site)
 

