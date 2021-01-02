<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;
use Phalcon\Di;

class BlockForm extends AbstractForm implements RepositoriesInterface
{
    public function build(Block $block): BlockForm
    {
        $this->addText(
            '%CORE_NAME%',
            'name',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )
        ->addText('%ADMIN_CSS_CLASS%', 'class');

        $object = $block->getBlock();
        $type = str_replace(
            [
                Block::class,
                'VitesseCms\\'.ucwords(Di::getDefault()->get('config')->get('account')).'\\Block\\Models\\Block',
                '\\',
            ],
            ''
            ,
            $object
        );

        $files = SystemUtil::getTemplateFiles($type);
        $options = [];
        foreach ($files as $key => $label) :
            $selected = false;
            if ($block->_('template') === $key) :
                $selected = true;
            endif;
            $options[] = [
                'value'    => $key,
                'label'    => $label,
                'selected' => $selected,
            ];
        endforeach;

        $this->addDropdown(
            '%ADMIN_CHOOSE_A_TEMPLATE%',
            'template',
            (new Attributes())->setRequired(true)->setOptions($options)
        );

        if ((bool)$block->_('maincontentWrapper')) :
            $this->addToggle('Maincontent wrapper', 'maincontentWrapper');
        endif;

        /** @var AbstractBlockModel $blockType */
        $blockType = new $object($this->view);
        $blockType->buildBlockForm($this, $block, $this->repositories);

        $this->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
