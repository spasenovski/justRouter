<?php

namespace Tests;

use JustRouter\RouteCollection;
use JustRouter\Parser;
use JustRouter\Matcher;

class MatcherTest extends \PHPUnit_Framework_TestCase
{	
	protected $routes = null;

	protected function setUp()
	{
		$this->routes = new RouteCollection();

		$this->routes->addRoute(['GET'], '/hi', '');
		$this->routes->addRoute(['GET'], '/hi/there', '');
		$this->routes->addRoute(['GET'], '/hi/there/{name}', '');
		$this->routes->addRoute(['GET'], '/hi/there/{name}/how/are/you', '');
		$this->routes->addRoute(['GET'], '/', '');
		$this->routes->addRoute(['GET'], '/some/really/long/static/casual/path/that/i/want/to/check', '');
		$this->routes->addRoute(['GET'], '/{number:^[0-9]+$}/is/my/lucky/number', '');
		$this->routes->addRoute(['GET'], '/{thing:^[a-zA-Z]+$}/is/my/lucky/thing', '');
		$this->routes->addRoute(['GET'], '/{person}/is/{something}', '');
	}

	/**
	  * @dataProvider providertestMatchRequest	
	*/	
	public function testMatchRequest($path, $match)
	{													 
		$matcher = new Matcher($this->routes, new Parser());
		$results = $matcher->matchRequest($path);

		$this->assertEquals($results[0], $match[0]);
		$this->assertEquals($results[1], $match[1]);
		$this->assertEquals($results[2], $match[2]);
	}

	/**
	  * @dataProvider providetestNotMatchRequest
	*/
	public function testNotMatchRequest($path, $match)
	{	
		$matcher = new Matcher($this->routes, new Parser());
		$results = $matcher->matchRequest($path);
		
		$this->assertEquals($results[0], $match[0]);
	}

	public function providertestMatchRequest()
	{
		return [
			['/hi', 
				[
					1,
					[],
					'',
					'/hi'
				]
			], 
			['/hi/there/beauty',
				[
					1,
					["name" => "beauty"],
					'',
					'/hi/there/{name}'
				]
			],
			['/hi/there/man/how/are/you',
				[
					1,
					["name" => "man"],
					'',
					'/hi/there/{name}/how/are/you'
				]
			],
			['/1/is/my/lucky/number',
				[
					1,
					["number" => "1"],
					'',
					'{number}/is/my/lucky/number'
				]
			],
			['/he/is/beautiful',
				[
					1,
					["person" => "he", "something" => "beautiful"],
					'',
					'{person}/is/beautiful'
				]
			],
			['/',
				[
					1,
					[],
					'',
					'/'
				]
			]
		];
	}

	public function providetestNotMatchRequest()
	{
		return [
			['/string/is/my/lucky/number',
				[
					0,
					[],
					''
				]
			],
			['/random/nonexistant/url',
					0,
					[],
					''
			]
		];
	}
}

