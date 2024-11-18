<?php

namespace Infuse\DataProcessor\Rest;

use Bitrix\Rest\RestException;
use Infuse\DataProcessor\Core\MainDataProcessor;

class DataProcessorRestService extends \IRestService
{
    /**
     * The method runs on the OnRestServiceBuildDescription event when installing the current module
     * and extends API methods to work with the functionality of the module.
     *
     * @param
     *
     * @return array
     */
    public static function OnRestServiceBuildDescription()
    {
        return [
            'infuse.dataprocessor' => [
                'get.username.vowels' => [__CLASS__, 'getUserNameVowelsRest'],
            ],
        ];
    }
    public static function getUserNameVowelsRest($params, $n, \CRestServer $server)
    {
        $USER_ID = $params['USER_ID'];

        if (empty($USER_ID) || !is_numeric($USER_ID) || $USER_ID <= 0) {
            throw new RestException('USER_ID is empty or invalid data', 'INVALID_USER_ID', \CRestServer::STATUS_WRONG_REQUEST);
        }

        $processor = new MainDataProcessor();
        $result = $processor->getUserNameVowels($USER_ID);

        return $result;
    }

}