<?php

namespace DotPlant\StoreBookmarks\components;

interface BookmarksStorageInterface
{
    /**
     * Add a new bookmark
     * @param int $id
     * @param int|null $groupId
     * @return int
     */
    public function add($id, $groupId = null);

    /**
     * Move the bookmark to group
     * @param int $id
     * @param int $groupId
     * @return int
     */
    public function move($id, $groupId);

    /**
     * Remove the bookmark
     * @param int $id
     * @return int
     */
    public function remove($id);

    /**
     * Get bookmarks list as array
     * @param int $groupId
     * @return array in the
     */
    public function getList($groupId = null);

    public function addGroup($name);

    public function removeGroup($id);

    public function getGroups();
}
