<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Block\DTO\TemplateFileDTO;
use VitesseCms\Block\DTO\TemplateFileListDTO;
use VitesseCms\Block\DTO\TemplateFilesDTO;
use VitesseCms\Block\Enum\BlockFormEnum;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Utils\BlockUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockForm extends AbstractForm implements AdminModelFormInterface
{
    public function buildForm(): void
    {
        $this->addText('%CORE_NAME%', 'name', (new Attributes())->setRequired()->setMultilang())
            ->addText('%ADMIN_CSS_CLASS%', 'class')
        ;

        if($this->entity !== null && $this->entity->getBlock() !== null) {
            $blockName = array_reverse(explode('\\', $this->entity->getBlock()))[0];
            $blockName = implode('', explode('Block', $blockName, 1));

            $files = BlockUtil::getTemplateFiles($this->entity->getBlock(), $this->configuration);

            $templateFileListDTO = new TemplateFileListDTO($blockName, $this->entity->getTemplate());
            $templateFileListDTO->addOption(new TemplateFileDTO('', '%FORM_CHOOSE_AN_OPTION%'));
            foreach ($files as $key => $label) :
                $templateFileListDTO->addOption(new TemplateFileDTO($key, $label));
            endforeach;

            $this->eventsManager->fire(BlockFormEnum::LISTENER_GET_TEMPLATE_FILES->value, $templateFileListDTO);

            $this->addDropdown(
                '%ADMIN_CHOOSE_A_TEMPLATE%',
                'template',
                (new Attributes())->setRequired(true)->setOptions($templateFileListDTO->options)
            );

            if ((bool)$this->entity->getMaincontentWrapper()) :
                $this->addToggle('Maincontent wrapper', 'maincontentWrapper');
            endif;

            $this->eventsManager->fire($this->entity->getBlock() . ':buildBlockForm', $this, $this->entity);
        } else {
            $this->addDropdown(
                '%ADMIN_BLOCK%',
                'block',
                (new Attributes())->setRequired(true)
                    ->setOptions(
                        ElementHelper::arrayToSelectOptions(
                            BlockUtil::getTypes(SystemUtil::getModules($this->configuration))
                        )
                    )
            );
        }
        $this->addSubmitButton('%CORE_SAVE%');
    }
}
