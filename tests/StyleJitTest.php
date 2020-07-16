<?php

declare(strict_types=1);

include_once __DIR__.'/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use StyleJit\StyleJit;

/**
 * @coversDefaultClass  \StyleJit\StyleJit
 */
class StyleJitTest extends TestCase
{
    /**
     * @covers ::fileName
     */
    public function testFileName(): void
    {
        ob_start();
        passthru('php tests/fixtures/exampleTemplate.php', $exitCode);
        $output = ob_get_clean();

        $this->assertEquals(0, $exitCode);

        preg_match('/<link href="(.*\.css)" type="stylesheet">/sim', $output, $matches);

        $this->assertFileExists($matches[1]);
    }

    /**
     * @covers ::renderStyle
     */
    public function testRenderStyle(): void
    {
        // double and single quotes
        $css = StyleJit::renderStyle('<a class="padding:1em">Save</a>');
        $this->assertEquals('.padding\\:1em{padding:1em}', $css);

        $css = StyleJit::renderStyle('<a class="padding:1em">Save</a>');
        $this->assertEquals('', $css); // existing class

        $css = StyleJit::renderStyle("<a class='margin:1em'>Save</a>");
        $this->assertEquals('.margin\\:1em{margin:1em}', $css);

        // abbreviations
        $css = StyleJit::renderStyle('<a class="pad:1em">Save</a>');
        $this->assertEquals('.pad\\:1em{padding:1em}', $css);

        $css = StyleJit::renderStyle('<a class="p-lft:1em">Save</a>');
        $this->assertEquals('.p-lft\\:1em{padding-left:1em}', $css);

        $css = StyleJit::renderStyle('<a class="p-l:1em">Save</a>');
        $this->assertEquals('.p-l\\:1em{padding-left:1em}', $css);

        // value attributes
        $css = StyleJit::renderStyle('<div class="dis:grid gr-t-c:1fr_1fr">Save</div>');
        $this->assertEquals('.dis\\:grid{display:grid}.gr-t-c\\:1fr_1fr{grid-template-columns:1fr 1fr}', $css);
    }

    public function testImport()
    {
      StyleJit::setOptions(['import'=>['style.css']]);
      $css = StyleJit::renderStyle('<div class="dis:block">Save</a>');
      $this->assertEquals('@import \'style.css\';.dis\\:block{display:block}', $css);
    }

}
