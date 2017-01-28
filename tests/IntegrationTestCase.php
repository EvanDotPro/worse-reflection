<?php

namespace DTL\WorseReflection\Tests;

use DTL\WorseReflection\Reflector;
use DTL\WorseReflection\SourceLocator;
use PhpParser\ParserFactory;
use DTL\WorseReflection\SourceContextFactory;

class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    public function getReflector()
    {
        return new Reflector(
            $this->prophesize(SourceLocator::class)->reveal(),
            new SourceContextFactory($this->getParser())
        );
    }

    public function getParser()
    {
        return (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function getSourceLocator()
    {
        static $autoloader;

        if (!$autoloader) {
            $autoloader = require(__DIR__ . '/../../vendor/autoload.php');
        }

        return new ComposerSourceLocator($autoloader);
    }

}
