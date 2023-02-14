<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
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
    private Manager $eventsManager;

    public function __construct(
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository,
        Manager $eventsManager
    )
    {
        $this->blockPositionRepository = $blockPositionRepository;
        $this->blockRepository = $blockRepository;
        $this->eventsManager = $eventsManager;
    }

    public function getRepository(): BlockPositionRepository
    {
        return $this->blockPositionRepository;
    }

    public function renderPosition(Event $event, RenderPositionDTO $renderPositionDTO): string
    {
        $findValueIterator = new FindValueIterator([new FindValue('position', $renderPositionDTO->position)]);
        if(count($renderPositionDTO->roles) > 0 ) {
            $findValueIterator->append(new FindValue('roles', ['$in' => $renderPositionDTO->roles]));
        }

        if(count($renderPositionDTO->datagroups) > 0 ) {
            $findValueIterator->append(new FindValue('datagroup', ['$in' => $renderPositionDTO->datagroups]));
        }

        $blockPositions  = $this->blockPositionRepository->findAll($findValueIterator);

        $return = '';
        while ($blockPositions->valid()) {
            $blockPosition = $blockPositions->current();

            $block = $this->blockRepository->getById($blockPosition->getBlock());
            if( $block !== null ) {
                $return .= $this->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK->value, $block);
            }

            $blockPositions->next();
        }

        return $return;
    }
}