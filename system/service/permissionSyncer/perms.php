<?php

namespace system\service\permissionSyncer;

class perms
{
    const DEFAULT = [
        'createGroup' => false,
        'deleteGroup' => false,
        'addWordlistToGroup' => false,
        'removeWordlistFromGroup' => false,
        'seeAllGroups' => false,
        'seeWordlistsOfAGroup' => false,
        'seeWordsOfAGroup' => false,
        'seeGroupsWordsCount' => false,
        'seeAllRoles' => false,
        'seeAllPerms' => false,
        'createNewRole' => false,
        'editRolePermissions' => false,
        'deleteRolePermissions' => false,
        'addNewUser' => false,
        'seeAllUsers' => false,
        'changeOtherUsersRole' => false,
        'seeOtherUsersPermissions' => false,
        'deleteOtherUsersAccount' => false,
        'addNewWordToWordlists' => false,
        'createWordlist' => false,
        'deleteWordlist' => false,
        'seeWordlistWords' => false,
        'seeAllWordlists' => false,
        'seeWordlistsWordsCount' => false,
        'addMultipleWordToWordlists' => false,
        'addWordsToWordlistFromUrl' => false,
    ];
}