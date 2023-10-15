<?php declare(strict_types=1);

namespace VitesseCms\Block\DTO;

class TemplateFileListDTO
{
    public function __construct(
        readonly string $blockName,
        readonly string $selectedValue,
        public array $options = []
    ){}

    public function addOption(TemplateFileDTO $templateFileDTO):void
    {
        $this->options[] = [
                'value' => $templateFileDTO->value,
                'label' => $templateFileDTO->label,
                'selected' => $templateFileDTO->value === $this->selectedValue?true:false
        ];
    }
}