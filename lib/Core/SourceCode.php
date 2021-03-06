<?php

namespace Phpactor\WorseReflection\Core;

class SourceCode
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $path;

    private function __construct(string $source, string $path = null)
    {
        $this->source = $source;
        $this->path = $path;
    }

    public static function fromUnknown($value)
    {
        if ($value instanceof SourceCode) {
            return $value;
        }

        if (is_string($value)) {
            return self::fromString($value);
        }

        throw new \InvalidArgumentException(sprintf(
            'Do not know how to create source code from type "%s"',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    public static function fromString($source)
    {
        return new self($source);
    }

    public static function fromPath(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" does not exist',
                $filePath
            ));
        }

        return new self(file_get_contents($filePath), $filePath);
    }

    public static function empty()
    {
        return new self('');
    }

    public static function fromPathAndString(string $filePath, string $source)
    {
        return new self($source, $filePath);
    }

    public function path()
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->source;
    }
}
