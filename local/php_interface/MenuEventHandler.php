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

        $newModuleMenu = [
            [
                'text' => 'Новости',
                'url' => 'iblock_admin.php?type=news&amp;lang=ru&amp;admin=N',
                'more_url' =>
                    [
                        0 => 'iblock_admin.php?type=news&lang=ru&admin=N',
                    ],
                'title' => 'Новости',
                'parent_menu' => 'global_menu_content',
                'sort' => 200,
                'icon' => 'iblock_menu_icon_types',
                'page_icon' => 'iblock_page_icon_types',
                'module_id' => 'iblock',
                'items_id' => 'menu_iblock_/news',
                'dynamic' => true,
                'items' =>
                    [
                        0 =>
                            [
                                'text' => 'Новости',
                                'url' => 'iblock_element_admin.php?IBLOCK_ID=1&type=news&lang=ru&apply_filter=Y',
                                'more_url' =>
                                    [
                                        0 => 'iblock_element_edit.php?type=news&lang=ru&IBLOCK_ID=1',
                                        1 => 'iblock_history_list.php?type=news&lang=ru&IBLOCK_ID=1',
                                        2 => 'iblock_element_admin.php?IBLOCK_ID=1&type=news&lang=ru',
                                    ],
                                'title' => 'Новости',
                                'items_id' => 'menu_iblock_/news/1',
                                'icon' => 'iblock_menu_icon_iblocks',
                                'page_icon' => 'iblock_page_icon_iblocks',
                                'skip_chain' => true,
                                'module_id' => 'iblock',
                                'items' =>
                                    [],
                            ],
                    ],
            ],
            2 =>
                [
                    'text' => 'Товары и услуги',
                    'url' => 'iblock_admin.php?type=products&amp;lang=ru&amp;admin=N',
                    'more_url' =>
                        [
                            0 => 'iblock_admin.php?type=products&lang=ru&admin=N',
                        ],
                    'title' => 'Товары и услуги',
                    'parent_menu' => 'global_menu_content',
                    'sort' => 201,
                    'icon' => 'iblock_menu_icon_types',
                    'page_icon' => 'iblock_page_icon_types',
                    'module_id' => 'iblock',
                    'items_id' => 'menu_iblock_/products',
                    'dynamic' => true,
                    'items' =>
                        [
                            0 =>
                                [
                                    'text' => '[s1] Продукция',
                                    'url' => 'iblock_section_admin.php?IBLOCK_ID=2&type=products&lang=ru&find_section_section=0&SECTION_ID=0&apply_filter=Y',
                                    'more_url' =>
                                        [
                                            0 => 'iblock_section_admin.php?IBLOCK_ID=2&type=products&lang=ru&find_section_section=0',
                                            1 => 'iblock_section_admin.php?IBLOCK_ID=2&type=products&lang=ru&find_section_section=-1',
                                            2 => 'iblock_section_edit.php?IBLOCK_ID=2&type=products&find_section_section=-1',
                                            3 => 'iblock_section_edit.php?IBLOCK_ID=2&type=products&find_section_section=0',
                                            4 => 'iblock_element_edit.php?IBLOCK_ID=2&type=products&find_section_section=-1',
                                            5 => 'iblock_element_edit.php?IBLOCK_ID=2&type=products&find_section_section=0',
                                            6 => 'iblock_history_list.php?IBLOCK_ID=2&type=products&find_section_section=-1',
                                            7 => 'iblock_start_bizproc.php?document_type=iblock_2',
                                            8 => 'iblock_element_edit.php?IBLOCK_ID=2&type=products',
                                            9 => 'iblock_history_list.php?IBLOCK_ID=2&type=products',
                                        ],
                                    'title' => '[s1] Продукция',
                                    'icon' => 'iblock_menu_icon_iblocks',
                                    'page_icon' => 'iblock_page_icon_iblocks',
                                    'skip_chain' => true,
                                    'module_id' => 'iblock',
                                    'items_id' => 'menu_iblock_/products/2',
                                    'dynamic' => true,
                                    'items' =>
                                        [],
                                ],
                            1 =>
                                [
                                    'text' => '[s1] Услуги',
                                    'url' => 'iblock_section_admin.php?IBLOCK_ID=3&type=products&lang=ru&find_section_section=0&SECTION_ID=0&apply_filter=Y',
                                    'more_url' =>
                                        [
                                            0 => 'iblock_section_admin.php?IBLOCK_ID=3&type=products&lang=ru&find_section_section=0',
                                            1 => 'iblock_section_admin.php?IBLOCK_ID=3&type=products&lang=ru&find_section_section=-1',
                                            2 => 'iblock_section_edit.php?IBLOCK_ID=3&type=products&find_section_section=-1',
                                            3 => 'iblock_section_edit.php?IBLOCK_ID=3&type=products&find_section_section=0',
                                            4 => 'iblock_element_edit.php?IBLOCK_ID=3&type=products&find_section_section=-1',
                                            5 => 'iblock_element_edit.php?IBLOCK_ID=3&type=products&find_section_section=0',
                                            6 => 'iblock_history_list.php?IBLOCK_ID=3&type=products&find_section_section=-1',
                                            7 => 'iblock_start_bizproc.php?document_type=iblock_3',
                                            8 => 'iblock_element_edit.php?IBLOCK_ID=3&type=products',
                                            9 => 'iblock_history_list.php?IBLOCK_ID=3&type=products',
                                        ],
                                    'title' => '[s1] Услуги',
                                    'icon' => 'iblock_menu_icon_iblocks',
                                    'page_icon' => 'iblock_page_icon_iblocks',
                                    'skip_chain' => true,
                                    'module_id' => 'iblock',
                                    'items_id' => 'menu_iblock_/products/3',
                                    'dynamic' => true,
                                    'items' =>
                                        [],
                                ],
                        ],
                ],
            3 =>
                [
                    'text' => 'Вакансии',
                    'url' => 'iblock_admin.php?type=vacancies&amp;lang=ru&amp;admin=N',
                    'more_url' =>
                        [
                            0 => 'iblock_admin.php?type=vacancies&lang=ru&admin=N',
                        ],
                    'title' => 'Вакансии',
                    'parent_menu' => 'global_menu_content',
                    'sort' => 202,
                    'icon' => 'iblock_menu_icon_types',
                    'page_icon' => 'iblock_page_icon_types',
                    'module_id' => 'iblock',
                    'items_id' => 'menu_iblock_/vacancies',
                    'dynamic' => true,
                    'items' =>
                        [
                            0 =>
                                [
                                    'text' => 'Вакансии',
                                    'url' => 'iblock_section_admin.php?IBLOCK_ID=4&type=vacancies&lang=ru&find_section_section=0&SECTION_ID=0&apply_filter=Y',
                                    'more_url' =>
                                        [
                                            0 => 'iblock_section_admin.php?IBLOCK_ID=4&type=vacancies&lang=ru&find_section_section=0',
                                            1 => 'iblock_section_admin.php?IBLOCK_ID=4&type=vacancies&lang=ru&find_section_section=-1',
                                            2 => 'iblock_section_edit.php?IBLOCK_ID=4&type=vacancies&find_section_section=-1',
                                            3 => 'iblock_section_edit.php?IBLOCK_ID=4&type=vacancies&find_section_section=0',
                                            4 => 'iblock_element_edit.php?IBLOCK_ID=4&type=vacancies&find_section_section=-1',
                                            5 => 'iblock_element_edit.php?IBLOCK_ID=4&type=vacancies&find_section_section=0',
                                            6 => 'iblock_history_list.php?IBLOCK_ID=4&type=vacancies&find_section_section=-1',
                                            7 => 'iblock_start_bizproc.php?document_type=iblock_4',
                                            8 => 'iblock_element_edit.php?IBLOCK_ID=4&type=vacancies',
                                            9 => 'iblock_history_list.php?IBLOCK_ID=4&type=vacancies',
                                        ],
                                    'title' => 'Вакансии',
                                    'icon' => 'iblock_menu_icon_iblocks',
                                    'page_icon' => 'iblock_page_icon_iblocks',
                                    'skip_chain' => true,
                                    'module_id' => 'iblock',
                                    'items_id' => 'menu_iblock_/vacancies/4',
                                    'dynamic' => true,
                                    'items' =>
                                        [],
                                ],
                        ],
                ],
            4 =>
                [
                    'parent_menu' => 'global_menu_content',
                    'section' => 'iblock',
                    'sort' => 300,
                    'text' => 'Инфоблоки',
                    'title' => 'Настройка информационных блоков',
                    'icon' => 'iblock_menu_icon_settings',
                    'page_icon' => 'iblock_page_icon_settings',
                    'items_id' => 'menu_iblock',
                    'module_id' => 'iblock',
                    'items' =>
                        [
                            0 =>
                                [
                                    'text' => 'Импорт',
                                    'title' => 'Импорт данных в формат CSV',
                                    'url' => 'iblock_data_import.php?lang=ru',
                                    'items_id' => 'iblock_import',
                                    'module_id' => 'iblock',
                                    'items' =>
                                        [
                                            0 =>
                                                [
                                                    'text' => 'CSV',
                                                    'url' => 'iblock_data_import.php?lang=ru',
                                                    'module_id' => 'iblock',
                                                    'more_url' =>
                                                        [
                                                            0 => 'iblock_data_import.php',
                                                        ],
                                                ],
                                        ],
                                ],
                            1 =>
                                [
                                    'text' => 'Инструменты',
                                    'title' => 'Инструменты',
                                    'module_id' => 'iblock',
                                    'items_id' => 'iblock_redirect',
                                    'items' =>
                                        [
                                            0 =>
                                                [
                                                    'text' => 'Перейти к инфоблоку / разделу / элементу',
                                                    'title' => 'Перейти на страницу списка элементов инфоблока / страницу редактирования раздела или элемента инфоблока',
                                                    'url' => 'iblock_redirect_entity.php?lang=ru',
                                                    'module_id' => 'iblock',
                                                ],
                                        ],
                                ],
                        ],
                ],
        ];

        $moduleMenu = $newModuleMenu;

        return true;
    }
}
