<?php

namespace App\Service\DiskHandler;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Сервис для работы с диском
 *  
 * @author Валерий Ожерельев 
 * @method array checkFreeSize()
 * @method integer getFreeDisk()
 * @version 1.0.0
 */
class CheckFreeSizeService
{
    private array $hddArray;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->hddArray = explode(',', $parameterBag->get("HDD_ARRAY"));
    }


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

    public function getAllDiskFreeSpace():array
    {
        foreach($this->hddArray as $hdd){
            $allInfo = $this->checkFreeSize($hdd);
            if($allInfo['bytes'] < 500000000000){
                $hddFree[] = "<b class='text-danger' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }elseif($allInfo['bytes'] < 1000000000000){
                $hddFree[] = "<b style='color: #a67e06;' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }elseif($allInfo['bytes'] > 1000000000000){
                $hddFree[] = "<b class='text-success' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }else{                
                $hddFree[] = "<b class='text-secondary' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }
            
        }
        return $hddFree;
    }
}