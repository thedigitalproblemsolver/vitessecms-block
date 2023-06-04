<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\DTO\TemplateFileDTO;
use VitesseCms\Block\DTO\TemplateFileListDTO;
use VitesseCms\Block\DTO\TemplateFilesDTO;
use VitesseCms\Block\Enum\BlockFormEnum;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Utils\BlockUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;

class BlockForm extends AbstractForm implements RepositoriesInterface
{
    public function build(Block $block): BlockForm
    {
        $this->addText('%CORE_NAME%', 'name', (new Attributes())->setRequired()->setMultilang())
            ->addText('%ADMIN_CSS_CLASS%', 'class')
        ;

        $files = BlockUtil::getTemplateFiles($block->getBlock(), $this->configuration);
        $templateFileListDTO = new TemplateFileListDTO($block->getTemplate());
        foreach ($files as $key => $label) :
            $templateFileListDTO->addOption(new TemplateFileDTO($key, $label));
        endforeach;

        $this->eventsManager->fire(BlockFormEnum::LISTENER_GET_TEMPLATE_FILES->value,$templateFileListDTO);

        $this->addDropdown(
            '%ADMIN_CHOOSE_A_TEMPLATE%',
            'template',
            (new Attributes())->setRequired(true)->setOptions($templateFileListDTO->options)
        );

        if ((bool)$block->getMaincontentWrapper()) :
            $this->addToggle('Maincontent wrapper', 'maincontentWrapper');
        endif;

        $this->getDi()->get('eventsManager')->fire($block->getBlock().':buildBlockForm', $this, $block);

        $this->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
