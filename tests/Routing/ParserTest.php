<?php

namespace Tests\Routing;

use JustRouter\Route;
use JustRouter\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{	
	/**
	  * @dataProvider providertestIsSegmentStatic
	*/
	public function testIsSegmentStatic($segment)
	{
		$this->assertEquals(Parser::isSegmentStatic($segment), 1);
	}

	/**
	  * @dataProvider providertestIsSegmentNotStatic
	*/
	public function testIsSegmentNotStatic($segment)
	{
		$this->assertEquals(!Parser::isSegmentStatic($segment), 1);
	}

	/**
	  * @dataProvider providertestSegmentsMatch
	*/
	public function testSegmentsMatch($first, $second)
	{	
		$this->assertEquals(Parser::segmentsMatch($first, $second), 1);
	}

	/**
	  * @dataProvider providertestParsePath
	*/	
	public function testParsePath($string, $segments, $segmentCount)
	{	
		$parser = new Parser();
		$results = $parser->parsePath($string);

		$this->assertEquals($results['segments'], $segments);
		$this->assertEquals($results['segcount'], $segmentCount);
	}

	public function providertestIsSegmentStatic()
	{			
		return [
				['asd'],
				['923jsdj'],
				['posts'],
				['posd834&$']
			];
	}

	public function providertestIsSegmentNotStatic()
	{			
		return [
				['{var}'],
				['{a}'],
				['{name}'],
				['{[postid}']
			];
	}

	public function providertestSegmentsMatch()
	{
		return [
			['test', 'test'],
			['peanuts', 'peanuts'],
			['strawberries', 'strawberries']
		];
	}

	public function providertestParsePath()
	{
		return [
			['/hi/this/is/me', 
				[
				 'hi',
				 'this',
				 'is',
				 'me'
				],
				4
			],

			['/articles/1/update/user/3', 
				[
				 'articles',
				 '1',
				 'update',
				 'user',
				 '3'
				],
				5
			],

			['/posts/{post_id}/patch',
			   [
			   	'posts',
			   	'{post_id}',
			   	'patch'
			   ],
			   3
			]
		];
	}

}