<?php
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
    Bitrix\Main\IO\Directory,
    Bitrix\Main\IO\File,
    Bitrix\Main\Application,
    Bitrix\Main\Loader;

Loc::loadMessages( __FILE__ );

Class infuse_dataprocessor extends CModule
{
    private $namespaceClases = [];
    const NAMESPACE = "Infuse\DataProcessor";
    const MODULE_ID = "infuse.dataprocessor";
    var $MODULE_ID = "infuse.dataprocessor";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = "";
    var $modulePath = "";
    var $connection = NULL;
    var $isLocal = false;

    function __construct()
    {
        $arModuleVersion = [];
        include(dirname(__FILE__)."/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage(self::MODULE_ID."_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage(self::MODULE_ID."_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage(self::MODULE_ID."_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage(self::MODULE_ID."_PARTNER_URI");
        $this->modulePath = getLocalPath("modules/".self::MODULE_ID);

        $this->connection = Application::getConnection();
        $this->isLocal = boolval(strpos(__DIR__, "local") !== false);

        $declaredClasses = get_declared_classes();
        foreach($declaredClasses as $class)
        {
            if(strpos($class, self::NAMESPACE) !== false)
                $this -> _addNamespaceClass($class);
        }
    }

    private function _addNamespaceClass(string $className)
    {
        if(!in_array($className, $this->_getNamespaceClasses()))
            $this -> namespaceClases[] = $className;
    }

    private function _deleteNamespaceClass(string $className)
    {
        if(in_array($className, $this->_getNamespaceClasses()))
        {
            $key = array_search($className, $this->_getNamespaceClasses());
            if($key !== false)
                unset($this->namespaceClases[$key]);

            $this->namespaceClases = array_values($this->_getNamespaceClasses());
        }
    }

    private function _getNamespaceClasses()
    {
        return $this->namespaceClases;
    }

    function installEvents()
    {
        foreach($this->_getNamespaceClasses() as $class)
        {
            if(method_exists($class, "registerEvents"))
                call_user_func_array([$class, "registerEvents"], []);
        }

        RegisterModuleDependences(
            "rest",
            "OnRestServiceBuildDescription",
            self::MODULE_ID,
            'Infuse\DataProcessor\Rest\DataProcessorRestService',
            'OnRestServiceBuildDescription'
        );

    }

    function unInstallEvents()
    {
        foreach($this->_getNamespaceClasses() as $class)
        {
            if(method_exists($class, "unRegisterEvents"))
                call_user_func_array([$class, "unRegisterEvents"], []);
        }

        UnRegisterModuleDependences(
            "rest",
            "OnRestServiceBuildDescription",
            self::MODULE_ID,
            'Infuse\DataProcessor\Rest\DataProcessorRestService',
            'OnRestServiceBuildDescription'
        );

    }

    function installAgents()
    {

    }

    function unInstallAgents()
    {

    }

    public function installDb()
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__ ."/db/".strtolower($DB->type)."/install.sql");
        return true;
    }

    public function unInstallDb()
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__ . "/db/".strtolower($DB->type)."/uninstall.sql");
        return true;
    }

    public function unInstallOptions()
    {
        Option::delete(self::MODULE_ID);
    }

    function DoInstall()
    {
        RegisterModule(self::MODULE_ID);
        $this->installDb();
        $this->installEvents();
        $this->installAgents();
    }

    function DoUninstall()
    {
        $this->unInstallDb();
        $this->unInstallEvents();
        $this->unInstallAgents();
        $this->unInstallOptions();
        UnRegisterModule(self::MODULE_ID);
    }
}