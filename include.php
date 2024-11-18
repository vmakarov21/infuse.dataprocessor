<?php
if(!Bitrix\Main\Loader::includeModule("main")) return;
require_once __DIR__."/functions.php";
Bitrix\Main\Loader::registerAutoloadClasses(
    "infuse.dataprocessor",
    [
        // lib
        "\\Infuse\\DataProcessor\\Controller\\MainDataProcessorController" => "lib/Controller/MainDataProcessorController.php",
        "\\Infuse\\DataProcessor\\Core\\MainDataProcessor" => "lib/Core/MainDataProcessor.php",
        "\\Infuse\\DataProcessor\\Rest\\DataProcessorRestService" => "lib/Rest/DataProcessorRestService.php",
    ]
);