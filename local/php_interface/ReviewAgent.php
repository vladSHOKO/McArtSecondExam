<?php

class ReviewAgent
{
    public static function Agent_ex_610(): string
    {
        $previousStartDate = self::getPreviousAgentStartDate();

        $currentReviewQuantity = self::countChangedReviewsFromDate($previousStartDate);

        self::logInfo($currentReviewQuantity, $previousStartDate);

        self::setNewAgentStartDate();

        return 'ReviewAgent::Agent_ex_610();';
    }

    private static function getPreviousAgentStartDate(): string
    {
        $recentStart = \Bitrix\Main\Config\Option::get('main', 'agent_recent_start');

        if (empty($recentStart)) {
            \Bitrix\Main\Config\Option::set("main", "agent_recent_start", ConvertTimeStamp(time(), "FULL"));
            return 'first start';
        }

        return $recentStart;
    }

    private static function setNewAgentStartDate(): void
    {
        \Bitrix\Main\Config\Option::set("main", "agent_recent_start", ConvertTimeStamp(time(), "FULL"));
    }

    private static function countChangedReviewsFromDate($previousStartDate): int
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        $arFilter = [
            'ACTIVE' => 'Y',
            '>TIMESTAMP_X' => $previousStartDate,
        ];

        $dataClass = \Bitrix\Iblock\Iblock::wakeUp($reviewIBlockId)->getEntityDataClass();
        $res = $dataClass::getList([
            'select' => [
                'ID', 'TIMESTAMP_X'
            ],
            'filter' => $arFilter
        ]);

        $reviews = [];

        while ($arItem = $res->fetch()) {
            $reviews[] = $arItem;
        }

        AddMessage2Log($reviews);

        return count($reviews);
    }

    private static function logInfo($currentQuantity, $recentDate): void
    {
        CEventLog::Add([
            'DESCRIPTION' => "Запуск агента ex2_610. С {$recentDate} изменилось {$currentQuantity} рецензий"
        ]);
    }
}