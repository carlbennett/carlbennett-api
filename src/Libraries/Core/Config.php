<?php

namespace CarlBennett\API\Libraries\Core;

class Config
{
    public const JSON_FLAGS = \JSON_BIGINT_AS_STRING
        | \JSON_OBJECT_AS_ARRAY
        | \JSON_PRESERVE_ZERO_FRACTION
        | \JSON_PRETTY_PRINT
        | \JSON_THROW_ON_ERROR;

    public bool $auto_save = false;
    protected string $file_path = '';
    private static ?self $instance = null;
    public array $root = [];

    public function __construct(string $file_path = '')
    {
        $this->setFilePath($file_path);
        $this->load();
    }

    public function __destruct()
    {
        if ($this->auto_save) $this->save();
        $this->root = [];
    }

    public static function defaultFilePath(): string
    {
        return __DIR__ . '/../../../etc/config.json';
    }

    public function getFilePath(): string
    {
        return $this->file_path;
    }

    public static function instance(): self
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function load(): void
    {
        $this->root = \json_decode(\file_get_contents($this->file_path), true, 512, self::JSON_FLAGS);
    }

    public function save(): void
    {
        \file_put_contents($this->file_path, \json_encode($this->root, self::JSON_FLAGS));
    }

    public function setFilePath(string $value = ''): void
    {
        $this->file_path = (empty($value) ? self::defaultFilePath() : $value);
    }
}
