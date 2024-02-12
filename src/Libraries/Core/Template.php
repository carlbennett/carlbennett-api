<?php

namespace CarlBennett\API\Libraries\Core;

class Template
{
    private mixed $context;
    private string $default_i18n = 'en-us';
    public array $opengraph = [];
    private string $template_directory = 'Templates';
    private string $template_extension = '.phtml';
    private string $template_file = '';

    public function __construct(mixed $context, string $template_file)
    {
        $this->setContext($context);
        $this->setTemplateFile($template_file);
    }

    public function getContext(): mixed
    {
        return $this->context;
    }

    public function getTemplateDirectory(): string
    {
        return $this->template_directory;
    }

    public function getTemplateExtension(): string
    {
        return $this->template_extension;
    }

    public function getTemplateFile(): string
    {
        return $this->template_file;
    }

    public function invoke(): void
    {
        try
        {
            $cwd = \getcwd();
            $separator = \DIRECTORY_SEPARATOR;
            \chdir($cwd . $separator . $this->template_directory . $separator . $this->default_i18n);
            if (!\file_exists($this->template_file))
            {
                throw new \CarlBennett\API\Exceptions\TemplateNotFoundException($this);
            }
            \ob_start();
            require($this->template_file);
            \ob_end_flush();
        }
        finally
        {
            \chdir($cwd); // always change back to last work directory
        }
    }

    public function setContext(mixed &$context): void
    {
        $this->context = $context;
    }

    public function setTemplateDirectory(string $template_directory): void
    {
        $this->template_directory = $template_directory;
    }

    public function setTemplateExtension(string $template_extension): void
    {
        $this->template_extension = $template_extension;
    }

    public function setTemplateFile(string $template_file): void
    {
        $this->template_file = \sprintf('.%s%s%s',
            \DIRECTORY_SEPARATOR,
            \str_replace('/', \DIRECTORY_SEPARATOR, $template_file),
            $this->template_extension
        );
    }
}
