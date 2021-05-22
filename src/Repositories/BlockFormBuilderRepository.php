<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Factories\BlockFormBuilderFactory;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Form\Blocks\FormBuilder;

class BlockFormBuilderRepository
{
    /**
     * @var BlockRepository
     */
    protected $blockRepository;

    public function __construct(BlockRepository $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    public function getById(string $id, ViewService $view, bool $hideUnpublished = true): ?FormBuilder
    {
        $block = $this->blockRepository->getById($id, $hideUnpublished);
        if ($block !== null):
            $objectClass = $block->getBlock();
            if (class_exists($objectClass)) :
                return BlockFormBuilderFactory::createFromBlock($block, $view);
            endif;
        endif;

        return null;
    }
}
