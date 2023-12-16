<?php

declare(strict_types=1);

namespace VitesseCms\Block\Utils;

use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;

class BlockUtil
{
    public static function getTemplateFiles(string $class, ConfigService $configuration): array
    {
        $files = [];
        $type = array_reverse(explode('\\', $class))[0];
        $type = implode('', explode('Block', $type, 1));

        $directories = [
            $configuration->getCoreTemplateDir() . 'views/blocks/' . $type . '/',
            $configuration->getTemplateDir() . 'views/blocks/' . $type . '/',
            $configuration->getAccountDir() . 'Template/views/blocks/' . $type . '/',
        ];

        foreach ($directories as $key => $dir) {
            if (is_dir($dir)) {
                $files = array_merge($files, DirectoryUtil::getFilelist($dir));
            }
        }

        foreach (SystemUtil::getModules($configuration) as $key => $moduleDir) {
            if (is_dir($moduleDir . '/Template/views/blocks/' . $type . '/')) {
                $files = array_merge(
                    $files,
                    DirectoryUtil::getFilelist($moduleDir . '/Template/views/blocks/' . $type . '/')
                );
            }
        }
        ksort($files);

        $return = [];
        foreach ($files as $directory => $file) {
            $parsedDir = str_replace(
                [
                    '.mustache',
                    $configuration->getCoreTemplateDir(),
                    $configuration->getTemplateDir(),
                    $configuration->getAccountDir() . 'Template/'
                ],
                '',
                $directory
            );

            foreach (SystemUtil::getModules($configuration) as $key => $moduleDir) {
                $parsedDir = str_replace($moduleDir . '/Template/', '', $parsedDir);
            }
            $return[$parsedDir] = ucfirst(str_replace('_', ' ', FileUtil::getName($file)));
        }

        return $return;
    }

    //TODO merge with datafieldUtils ?
    public static function getTypes(array $modules): array
    {
        $files = $types = [];

        foreach ($modules as $key => $directory) :
            $directory .= '/Blocks/';
            if (is_dir($directory)) :
                $files = array_merge($files, DirectoryUtil::getFilelist($directory));
            endif;
        endforeach;

        foreach ($files as $path => $file) :
            $name = FileUtil::getName($file);
            $className = SystemUtil::createNamespaceFromPath($path);
            $types[$className] = $name;
        endforeach;

        $types = array_flip($types);
        ksort($types);

        return array_flip($types);
    }
}
