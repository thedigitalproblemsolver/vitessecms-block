<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Search\Models\Elasticsearch;
use VitesseCms\Sef\Helpers\SefHelper;
use MongoDB\BSON\ObjectID;

/**
 * Class BlockFilterResult
 */
class BlockFilterResult extends AbstractBlockModel
{

    /**
     * {@inheritdoc}
     * @throws \MongoDB\Driver\Exception\InvalidArgumentException
     */
    public function parse(Block $block): void
    {
        parent::parse($block);

        $languageShort = $this->di->configuration->getLanguageShort();

        if ($this->di->request->isAjax()) :
            $return = [];
            if($this->di->request->getPost('firstRun') === '1') :
                $return['results'] = [];
                $return['heading'] = [$languageShort => $block->_('heading')];
                $return['introtext'] = [$languageShort => $block->_('introtext')];
            else :
                $post = $this->di->request->getPost();
                unset($post['csrf']);
                $cacheKey = $this->di->cache->getCacheKey($post);
                $items = $this->di->cache->get($cacheKey);
                if(!$items) :
                    $items = $this->getItems();
                    $this->di->cache->save($cacheKey, $items);
                endif;

                $return['results'] = $items;
                if ( \count($return['results']) === 0 ) :
                    $return['noresultText'] = SefHelper::parsePlaceholders(
                        $block->_('noresultText'),
                        $this->view->getVar('currentId')
                    );
                endif;
            endif;

            $block->set('return', [
                'block' => $return,
                'result' => true,
                'successFunction' => 'filter.fillTarget(response)'
            ]);
        else :
            $block->set('results', $this->getItems());
        endif;
    }

    /**
     * @return AbstractCollection[]
     * @throws \MongoDB\Driver\Exception\InvalidArgumentException
     */
    protected function getItems(): array
    {
        $hasResults = false;
        $items = [];

        foreach ((array)$this->di->request->get('filter', null, []) as $field => $value) :
            if (!empty($value)) :
                $hasResults = true;
                break;
            endif;
        endforeach;

        if ($hasResults) :
            $results = (new Elasticsearch())->search();
            if ($results['hits']['total'] > 0) :
                $ids = [];
                foreach ((array)$results['hits']['hits'] as $hit) :
                    $ids[] = new ObjectID($hit['_id']);
                endforeach;
                Item::addFindOrder('name',1);
                Item::setFindValue('_id', ['$in' => $ids]);

                $items = Item::findAll();
                /** @var Item $item */
                foreach ($items as $key => $item) :
                    ItemHelper::parseBeforeMainContent($item);
                    $items[$key] = $item;
                endforeach;
            endif;
        endif;

        return $items;
    }
}
