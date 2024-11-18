<?php
if(!function_exists("recursiveAutoload"))
{
    function recursiveAutoload(\Bitrix\Main\IO\Directory $directory)
    {
        if($directory->isExists())
        {
            $ioChildren = $directory->getChildren();
            foreach($ioChildren as $ioChild)
            {
                if($ioChild->isFile())
                {
                    require_once ($ioChild->getPath());
                }
                elseif($ioChild->isDirectory())
                {
                    recursiveAutoload($ioChild);
                }
            }
        }
    }
}
if(!function_exists('PR'))
{
    function PR($o)
    {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        ?>
        <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
            <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt["file"] ?>
                [<?= $bt["line"] ?>]
            </div>
            <pre style='padding:10px;'><? print_r($o) ?></pre>
        </div>
        <?
    }
}
if(!function_exists('logToFile'))
{
    function logToFile($path, $var, $trace = false)
    {
        $backtrace = debug_backtrace();
        $cp = ConvertTimeStamp(time(), 'FULL') . ' — '  . $backtrace[0]['file'] . ', ' . $backtrace[0]['line'].PHP_EOL;

        $puttedData = PHP_EOL.$cp.print_r($var, true);
        if($trace)
            $puttedData .= print_r($backtrace, true);

        $io = CBXVirtualIo::GetInstance();
        $pathDir = substr($path, 0, strrpos($path, '/') + 1);
        if(!$io->DirectoryExists($io->RelativeToAbsolutePath($pathDir)))
            $io->CreateDirectory($io->RelativeToAbsolutePath($pathDir));

        file_put_contents($_SERVER['DOCUMENT_ROOT'].$path, $puttedData, FILE_APPEND);
    }
}