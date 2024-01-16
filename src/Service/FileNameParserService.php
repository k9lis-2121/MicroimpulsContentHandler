<?php

namespace App\Service;

use App\Interfaces\Service\FileNameParserInterface;

class FileNameParserService implements FileNameParserInterface
{
    private array $templates = [
        'sxxexx' => [
            'enabled' => true,
            'regex' => [
                '/([S][0-9]+[E][0-9]+)|([s][0-9]+[e][0-9]+)/',
                '/S[0-9]+|s[0-9]+/',
                '/E[0-9]+|e[0-9]+/',
            ],
        ],
        'xxnd_season_xx' => [
            'enabled' => true,
            'regex' => [
                '/[0-9]+nd_[S-s]eason_[0-9]+/',
                '/[0-9]+nd_[S-s]eason/',
                '/[S-s]eason_[0-9]+/',
            ],
        ],
        'episode_xx' => [
            'enabled' => false,
            'regex' => '/[E-e]pisode_[0-9]+/',
        ],
        'episodexx' => [
            'enabled' => false,
            'regex' => '/[E-e]pisode[0-9]+/',
        ],
        'epxx' => [
            'enabled' => false,
            'regex' => '/[E-e]p[0-9]+/',
        ],
        'ep_xx' => [
            'enabled' => false,
            'regex' => '/[E-e]p_[0-9]+/',
        ],
        '|x|x' => [
            'enabled' => true,
            'regex' => [
                '/\|\d+\|/',
                '/(\d+)$/',
            ],
        ],
    ];

    /**
     * @inheritDoc
     */
    public function getClearName(string $fileName): array
    {
        return $this->clearFileName($fileName);
    }

    /**
     * @inheritDoc
     */
    public function getParsingName(string $fileName): array
    {
        return $this->parseName($fileName);
    }

    private function parseArray(string $fileName, array $parseRes = ['info' => false]): array
    {
        foreach ($this->templates as $template) {
            if ($template['enabled']) {
                foreach ($template['regex'] as $reg) {
                    preg_match($reg, $fileName, $result);
                    if ($result !== null) {
                        $parseRes[] = $result[0];
                        $parseRes['info'] = true;
                    }
                }
            }
        }
        return $parseRes;
    }

    private function parseNoArray(string $fileName, array $parseRes = ['info' => false]): array
    {
        foreach ($this->templates as $template) {
            if (!$template['enabled']) {
                preg_match($template['regex'], $fileName, $result);
                if ($result !== null) {
                    $parseRes[] = $result[0];
                    $parseRes['info'] = true;
                }
            }
        }
        return $parseRes;
    }

    private function numberStandartize(string $number): string
    {
        $number = trim($number, '0');
        if ($number < 10) {
            $number = '0' . $number;
        }
        return $number;
    }

    private function parseName(string $fileName): array|bool
    {
        $parseRes = $this->parseArray($fileName);
        if ($parseRes['info']) {
            if (isset($parseRes[2])) {
                $season = preg_replace('/[^0-9]/', '', $parseRes[1]);
                $episode = preg_replace('/[^0-9]/', '', $parseRes[2]);
            } else {
                $season = preg_replace('/[^0-9]/', '', $parseRes[0]);
                $episode = preg_replace('/[^0-9]/', '', $parseRes[1]);
            }

            $newName['season'] = $this->numberStandartize($season);
            $newName['episode'] = $this->numberStandartize($episode);
            return $newName;
        } else {
            return false;
        }
    }

    private function clearFileName(string $fileName): array
    {
        if (gettype($fileName) !== 'boolean') {
            if (isset($fileName[2])) {
                $clearFileName[1] = preg_replace('/[^0-9]/', '', $fileName[1]);
                $clearFileName[2] = preg_replace('/\s/', '_', $fileName[2]);
            } else {
                $clearFileName[1] = preg_replace('/[^0-9]/', '', $fileName[0]);
                $clearFileName[2] = preg_replace('/\s/', '_', $fileName[1]);
            }
        } else {
            $clearFileName[1] = 'errorType';
        }
        return $clearFileName;
    }
}