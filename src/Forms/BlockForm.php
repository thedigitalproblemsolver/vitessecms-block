<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

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
        $options = [];
        foreach ($files as $key => $label) :
            $selected = false;
            if ($block->getTemplate() === $key) :
                $selected = true;
            endif;
            $options[] = [
                'value' => $key,
                'label' => $label,
                'selected' => $selected,
            ];
        endforeach;

        $this->addDropdown(
            '%ADMIN_CHOOSE_A_TEMPLATE%',
            'template',
            (new Attributes())->setRequired(true)->setOptions($options)
        );

        if ((bool)$block->getMaincontentWrapper()) :
            $this->addToggle('Maincontent wrapper', 'maincontentWrapper');
        endif;

        $block->getDi()->eventsManager->fire($block->getBlock().':buildBlockForm', $this, $block);

        $this->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
