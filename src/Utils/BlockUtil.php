<?php declare(strict_types=1);

namespace VitesseCms\Block\Utils;

use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;

class BlockUtil
{
    public static function getTemplateFiles(string $type, ConfigService $configuration): array
    {
        $files = [];

        $directories = [
            $configuration->getCoreTemplateDir() . 'views/blocks/' . $type . '/',
            $configuration->getTemplateDir() . 'views/blocks/' . $type . '/',
            $configuration->getAccountDir() . 'views/blocks/' . $type . '/',
        ];

        foreach ($directories as $directory) :
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;
        ksort($files);

        $return = [];
        foreach ($files as $directory => $file) :
            $return[str_replace(
                [
                    '.mustache',
                    $configuration->getCoreTemplateDir(),
                    $configuration->getTemplateDir(),
                    $configuration->getAccountDir()
                ],
                '',
                $directory)] = ucfirst(str_replace('_', ' ', FileUtil::getName($file))
            );
        endforeach;

        return $return;
    }

    public static function getTypes(string $rootDir, string $accountDir, array $modules): array
    {
        $files = $types = [];

        $modules['rootdir'] = $rootDir . '../block/src/Models/';
        $modules['accountdir'] = $accountDir . 'src/block/Blocks/';

        foreach ($modules as $key => $directory) :
            if($key !== 'rootdir' && $key !== 'accountdir' ) :
                $directory .= '/Blocks/';
            endif;
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;

        foreach ($files as $path => $file) :
            $name = FileUtil::getName($file);
            $className = SystemUtil::createNamespaceFromPath($path);
            $types[$className] = $name;
        endforeach;

        $types = array_flip($types);
        ksort($types);
        $types = array_flip($types);

        return $types;
    }
}
