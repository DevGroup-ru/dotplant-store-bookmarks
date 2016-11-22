<?php

namespace DotPlant\StoreBookmarks\components;

interface BookmarksStorageInterface
{
    public function add($id, $groupId = null);

    public function move($id, $groupId);

    public function remove($id);

    public function getList();

    public function addGroup($name);

    public function removeGroup($id);

    public function getGroups();
}
