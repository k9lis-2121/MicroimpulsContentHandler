<?php

namespace App\Service\DiskHandler;

/**
 * Сервис для работы с диском
 *  
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array checkFreeSize()
 * @method integer getFreeDisk()
 * @version 1.0.0
 */
class CheckFreeSizeService
{
    private array $hddArray = [1, 11, 14,15,20,21,3,5,8,9];
    /**
     * Получить информацию о свободном месте на диске
     *
     * @param integer $diskNumber
     * @return array
     */
    public function checkFreeSize(int $diskNumber): array{
        $diskPath = "/HDD/$diskNumber/VOD/content";

        $bytesFree = disk_free_space($diskPath);
        $terabytesFree = $bytesFree / (1024 * 1024 * 1024 * 1024);
        $gigabytesFree = $bytesFree / (1024 * 1024 * 1024);
        $megabytesFree = $bytesFree / (1024 * 1024);

        if($terabytesFree > 1){
            $result = round($terabytesFree, 2) . " ТБ";
        }elseif( $gigabytesFree > 1 and $terabytesFree < 1){
            $result = round($gigabytesFree,2) . " ГБ";
        }elseif($gigabytesFree < 1 and $terabytesFree < 1){
            $result = round($megabytesFree,2) . " МБ";
        }

        return ["string" => "HDD$diskNumber: $result", "bytes" => $bytesFree];
    }

    /**
     * Получить номер самого свободного диска
     *
     * @return integer
     */
    public function getFreeDisk():int{

        foreach($this->hddArray as $hdd){
            $allInfo = $this->checkFreeSize($hdd);
            $hddFree[$hdd] = $allInfo['bytes'];            
        }
        $freeDisk = max($hddFree);
        $selectedDisk = array_search($freeDisk, $hddFree);

        return $selectedDisk;
    }
}