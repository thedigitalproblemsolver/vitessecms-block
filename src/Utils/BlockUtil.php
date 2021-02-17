<?php declare(strict_types=1);

namespace VitesseCms\Block\Utils;

use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;

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
                ['.mustache', $configuration->getRootDir()],
                '',
                $directory)] = ucfirst(str_replace('_', ' ', FileUtil::getName($file))
            );
        endforeach;

        return $return;
    }
}
