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

    public static function getTypes(string $rootDir, string $accountDir): array
    {
        $exclude = ['Block', 'BlockPosition'];
        $files = $types = [];

        $directories = [
            $rootDir . '../block/src/Models/',
            $accountDir . 'src/block/Models/',
        ];

        foreach ($directories as $directory) :
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;
        ksort($files);

        foreach ($files as $path => $file) :
            if (!in_array(FileUtil::getName($file), $exclude, true)) :
                $name = FileUtil::getName($file);
                $className = SystemUtil::createNamespaceFromPath($path);
                $types[$className] = substr($name, 5, strlen($name));
            endif;
        endforeach;

        return $types;
    }
}
