<?php

use DotPlant\Monster\models\ServiceTemplate;
use DotPlant\Monster\models\Template;
use DotPlant\Monster\models\TemplateRegion;
use DotPlant\StoreBookmarks\providers\BookmarksListProvider;
use yii\db\Migration;
use yii\helpers\Json;

class m161122_095002_bookmarks_list_template extends Migration
{
    public function up()
    {
        $this->insert(
            Template::tableName(),
            [
                'name' => 'Bookmarks list',
                'key' => 'bookmarksList',
                'packed_json_providers' => Json::encode(
                    [
                        [
                            'class' => BookmarksListProvider::class,
                        ],
                    ]
                ),
            ]
        );
        $templateId = $this->db->lastInsertID;
        $this->insert(
            TemplateRegion::tableName(),
            [
                'template_id' => $templateId,
                'name' => 'Bookmarks list',
                'key' => 'bookmarks',
                'entity_dependent' => 0,
                'packed_json_content' => Json::encode(
                    [
                        'bookmarksRegion' => [
                            'material' => 'site.site.bookmarks.list',
                        ],
                    ]
                ),
            ]
        );
        $this->insert(
            ServiceTemplate::tableName(),
            [
                'name' => 'Bookmarks list',
                'key' => 'bookmarksList',
                'template_id' => $templateId,
                'layout_id' => 1,
            ]
        );
    }

    public function down()
    {
        echo "m161122_095002_bookmarks_list_template cannot be reverted.\n";
        return false;
    }
}
