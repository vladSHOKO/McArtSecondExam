<?php

class MenuEventHandler
{
    public static function configAdminMenuForContentManager(&$globalMenu, &$moduleMenu): bool
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        $userGroups = $USER->GetUserGroupArray();

        $userIsContentManager = in_array(5, $userGroups);

        if (!$userIsContentManager) {
            return false;
        }

        $newGlobalMenu = [];

        if (isset($globalMenu['global_menu_content'])) {
            $newGlobalMenu['global_menu_content'] = $globalMenu['global_menu_content'];
        }

        $newGlobalMenu['fast_access'] = [
            'menu_id' => 'fast_access',
            'text' => 'Быстрый доступ',
            'sort' => 200,
            'items_id' => 'fast_access',
            'items' =>
                [
                    [
                        'text' => 'Ссылка 1',
                        'url' => 'https://test1/',
                        'items_id' => 'fast_access_link_1',
                        'title' => 'Ссылка 1'
                    ],
                    [
                        'text' => 'Ссылка 2',
                        'url' => 'https://test2/',
                        'items_id' => 'fast_access_link_2',
                        'title' => 'Ссылка 2'
                    ]
                ],
        ];

        $globalMenu = $newGlobalMenu;

        $necessaryItems = [
            'Новости',
            'Товары и услуги',
            'Вакансии',
            'Инфоблоки'
        ];

        foreach ($moduleMenu as $key => $value) {

            if (!in_array($value['text'], $necessaryItems)) {
                unset($moduleMenu[$key]);
            }
        }

        return true;
    }
}
