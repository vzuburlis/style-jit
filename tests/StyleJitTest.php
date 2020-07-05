<?php

include_once(__DIR__.'/../vendor/autoload.php');
include_once(__DIR__.'/../src/StyleJit.php');
use PHPUnit\Framework\TestCase;

class StyleJitTest extends TestCase
{

	public function testRenderStyle()
	{
    // double and single quotes
    $css = StyleJit\StyleJit::renderStyle('<a class="padding:1em">Save</a>');
    $this->assertEquals('.padding\\:1em{padding:1em}', $css);
    $css = StyleJit\StyleJit::renderStyle('<a class="padding:1em">Save</a>');
    $this->assertEquals('', $css); // existing class
    $css = StyleJit\StyleJit::renderStyle("<a class='margin:1em'>Save</a>");
    $this->assertEquals('.margin\\:1em{margin:1em}', $css);

    // abbreviations
    $css = StyleJit\StyleJit::renderStyle('<a class="pad:1em">Save</a>');
    $this->assertEquals('.pad\\:1em{padding:1em}', $css);
    $css = StyleJit\StyleJit::renderStyle('<a class="p-lft:1em">Save</a>');
    $this->assertEquals('.p-lft\\:1em{padding-left:1em}', $css);
    $css = StyleJit\StyleJit::renderStyle('<a class="p-l:1em">Save</a>');
    $this->assertEquals('.p-l\\:1em{padding-left:1em}', $css);

    // value attributes
    $css = StyleJit\StyleJit::renderStyle('<div class="dis:grid gr-t-c:1fr_1fr">Save</a>');
    $this->assertEquals('.dis\\:grid{display:grid}.gr-t-c\\:1fr_1fr{grid-template-columns:1fr 1fr}', $css);
  }

}
