<?php

include_once(__DIR__.'/../vendor/autoload.php');
include_once(__DIR__.'/../src/StyleJit.php');
use PHPUnit\Framework\TestCase;

class ClassStyleJit extends TestCase
{

	public function testRenderStyle()
	{
    $css = StyleJit\StyleJit::renderStyle('<a class="padding:1em">Save</a>');
    $this->assertEquals('.padding\\:1em{padding:1em}', $css);
    $css = StyleJit\StyleJit::renderStyle('<a class="pad:1em">Save</a>');
    $this->assertEquals('.pad\\:1em{padding:1em}', $css);
    $css = StyleJit\StyleJit::renderStyle('<a class="p-lft:1em">Save</a>');
    $this->assertEquals('.p-lft\\:1em{padding-left:1em}', $css);
	}

}
