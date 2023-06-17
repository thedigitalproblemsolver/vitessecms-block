<?php declare(strict_types=1);

namespace VitesseCms\Block\DTO;

class TemplateFileDTO
{
    public function __construct(public readonly string $value, public readonly string $label){}
}