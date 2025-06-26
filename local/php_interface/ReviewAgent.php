<?php

class ReviewAgent
{
    public static function Agent_ex_610(): string
    {
        $previousStartDate = self::getPreviousAgentStartDate();

        $currentReviewQuantity = self::countChangedReviewsFromDate($previousStartDate);

        self::logInfo($currentReviewQuantity, $previousStartDate);

        return 'ReviewAgent::Agent_ex_610();';
    }

    public static function getPreviousAgentStartDate(): string
    {
        $recentStart = COption::GetOptionString("main", "agent_recent_start");

        if (empty($recentStart)) {
            COption::SetOptionString("main", "agent_recent_start", ConvertTimeStamp(time(), "FULL"));
            return 'first start';
        }

        return $recentStart;
    }

    private static function countChangedReviewsFromDate($previousStartDate): int
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $reviewIBlockId,
            '>TIMESTAMP_X' => $previousStartDate,
        ];

        $res = CIBlockElement::GetList([], $arFilter, [], [], ['ID', 'TIMESTAMP_X']);

        $reviews = [];

        while ($arItem = $res->Fetch()) {
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