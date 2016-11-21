<?php

namespace DotPlant\StoreBookmarks\components;

interface BookmarksStorageInterface {

    public function addBookmark($id, $groupId = null);

    public function addGroup($name);

    public function moveBookmark($id, $groupId);

    public function removeBookmark($id);

    public function getGroups();

    public function removeGroup($id);

    public function getList();
}