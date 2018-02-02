JustRouter - Super fast lightweight NON - regex based router library for PHP
=======================================

Regexes are powerful, but can be slow. This library started as an experiment to see if the dependency
of regexes in the routing world could be reduced, in order to increase speed and scalability.  
I ended up with completely non-regex routing library(except
for the route variables where you can specify regexes to limit the input).

Usage
-----



```php
<?php

require '/path/to/vendor/autoload.php';

$routes = new JustRouter\RouteCollection();

$routes->addRoute(['GET'], '/in/{world}/{person}/shouted', function() {
    echo "Where is Padme?";
    exit();
});

$pathFromServer = '/in/coruscant/lordVader/shouted';

$matcher = new JustRouter\Matcher($routes, new JustRouter\Parser());
$match = $matcher->matchRequest($test);

var_dump($match) =>  [
    '1',                             // 1|0 Route has been matched| ... or not,
    [
        'world'  => 'coruscant',     // Vars
        'person' => 'lordVader'
    ]
    'object(Closure)#'               // The controller, in this case the callable,
    '/in/{world}/a/{person}/shouted' // The path that was matched
]

```
Version
-----

This library is still in beta version, it is missing some features to be production ready


Todo
-----

1.) Add more detailed documentation  
2.) Add route groups  
3.) Add route caching  
4.) Add some benchmarks  


Speed
-----

How fast is the library?

I still don't have full benchmarks to backup my claims, but from what I have tested
so far on my local machine :

With 115 routes and 2 dynamic variables :

1.First route - from 0.003 to 0.01 seconds  
2.Last route  - from 0.003 to 0.014 seconds  
3.Route in the middle  - 0.05 to 0.11  

These benchmarks don't paint the full pictures because of the way the library is designed :


How it works
-----

This library does not use regexes in the matching proccess at all. Unless there
is a regex limited variable.

From what I remember when I was writing it (#Leave more comments next time) basically it's matching  
"algorithm" is heavily based on grouping the routes themselves before the matching even   
begins.  

It splits the path into segments separated by "/".  

Then it groups them by the number of those segments, so this makes it insanely scalable. It doesn't matter  
if you add 1000 or 100 routes, because it performs search and match only on those groups  
depending on the number of segments of the URI you provide(or the server provides).  

The downside of course is what happens when we have routes with same number of segments.  
This is the example in my benchmark. Even then it's insanely fast. In a real world case,  
it would take full benefith from this way of matching.

Credits
--------

The API of the library and the class design have been largely influenced by nikic's [fastRoute](https://github.com/nikic/FastRoute)  
and [Symfony's router](https://github.com/symfony/routing)


