<?php

namespace App\Interface\Service\Api\Inside;

/**
 * Класс управляет директориями контента
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array makeFullDir()
 * @version 1.0.0
 */
interface MakeContentDirInterface
{
    /**
    * Ворекер по созданию директорий
    *
    * @param MakeFullDirMessage $message
    * @return void
    */
    public function makeFullDir(MakeFullDirMessage $message): void;

    

}