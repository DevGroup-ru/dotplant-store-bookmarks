<?php

namespace DotPlant\StoreBookmarks\providers;

use DevGroup\Frontend\Universal\ActionData;
use DotPlant\Monster\DataEntity\DataEntityProvider;
use DotPlant\StoreBookmarks\Module;

class BookmarksListProvider extends DataEntityProvider
{
    /**
     * @var string the region key
     */
    public $regionKey = 'bookmarks';

    /**
     * @var string the material key
     */
    public $materialKey = 'bookmarksRegion';

    /**
     * @var string the block key
     */
    public $blockKey = 'bookmarksList';

    public function pack()
    {
        return [
            'class' => static::class,
            'entities' => $this->entities,
        ];
    }

    public function getEntities(&$actionData)
    {
        $this->entities = [
            $this->regionKey => [
                $this->materialKey => [
                    $this->blockKey => [
                        Module::getStorage()->getList(),
                    ]
                ],
            ]
        ];
        return $this->entities;
    }
}
