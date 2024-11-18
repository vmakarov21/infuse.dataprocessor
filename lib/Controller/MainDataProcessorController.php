<?php

namespace Infuse\DataProcessor\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Infuse\DataProcessor\Core\MainDataProcessor;
class MainDataProcessorController extends Controller
{
    /**
     * @return array[]
     */
    public function configureActions(): array
    {
        return [
            'getUserNameVowels' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(false),
                ]
            ],
        ];
    }

    /**
     *
     * @param int $USER_ID
     * @return string|array
     */
    public function getUserNameVowelsAction(int $USER_ID)
    {
        $processor = new MainDataProcessor();
        $result = $processor->getUserNameVowels($USER_ID);

        return $result;
    }

}
