<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use VitesseCms\Block\DTO\RenderPositionDTO;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;

class BlockPositionListener
{
    private BlockPositionRepository $blockPositionRepository;
    private BlockRepository $blockRepository;

    public function __construct(
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository
    )
    {
        $this->blockPositionRepository = $blockPositionRepository;
        $this->blockRepository = $blockRepository;
    }

    public function getRepository(): BlockPositionRepository
    {
        return $this->blockPositionRepository;
    }

    public function renderPosition(Event $event, RenderPositionDTO $renderPositionDTO): string
    {
        $blockPositions  = $this->blockPositionRepository->findAll(
            new FindValueIterator([
                new FindValue('position', $renderPositionDTO->position),
                new FindValue('roles', ['$in' => [null, $renderPositionDTO->role]])
            ])
        );

        $return = '';
        while ($blockPositions->valid()) {
            $blockPosition = $blockPositions->current();

            $block = $this->blockRepository->getById($blockPosition->getBlock());
            $return .= $this->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK, $block);

            $blockPositions->next();
        }

        return $return;
    }
}