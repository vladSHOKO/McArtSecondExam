<?php

class BuildGlobalMenuHandler
{
    public static function buildMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        $userGroups = $USER->GetUserGroupArray();

        if (!in_array(CONTENT_EDITORS, $userGroups)) {
            return;
        }

        $necessaryItems = [
            'global_menu_content' =>
                array(
                    'menu_id' => 'content',
                    'text' => 'Контент',
                    'title' => 'Управление контентом сайта',
                    'sort' => 100,
                    'items_id' => 'global_menu_content',
                    'help_section' => 'content',
                    'items' =>
                        array(),
                ),
            'fast_access' =>
                array(
                    'menu_id' => 'fast_access',
                    'text' => 'Быстрый доступ',
                    'title' => 'Быстрый доступ',
                    'sort' => 100,
                    'items_id' => 'fast_access',
                    'help_section' => 'fast_access',
                    'items' =>
                        array(
                            array(
                                'text' => 'Ссылка 1',
                                'title' => 'Ссылка 1',
                                'url' => 'https://test1',
                            ),
                            array(
                                'text' => 'Ссылка 2',
                                'title' => 'Ссылка 2',
                                'url' => 'https://test2',
                            ),
                        ),
                ),
        ];
        $aGlobalMenu = $necessaryItems;

        foreach ($aModuleMenu as $key => $menuItem) {
            if ($menuItem['parent_menu'] !== 'global_menu_content') {
                unset($aModuleMenu[$key]);
            }
        }
    }
}