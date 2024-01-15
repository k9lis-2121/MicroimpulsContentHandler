<?php

namespace App\Interface\Service\Api\External\Smarty;

/**
 * @author microimpulse - оригинал на python
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru> - перевод на php
 * @api внешний для смарти
 * @version 2.0.0
 * @method array createVideo()
 * @method array modifyVideo()
 * @method array deleteVideo()
 * @method array createVideoFile()
 * @method array modifyVideoFile()
 * @method array createSeason()
 * @method array deleteSeason()
 * @method array modifySeason()
 * @method array createEpisode()
 * @method array deleteEpisode()
 * @method array modifyEpisode()
 * @source https://microimpuls.github.io/smarty-content-api-docs/ сайт с описанием методов
 * @filesource /www/SCCP/src/Service/PythonAPI/SmartyApiLib.py
 */
interface SmartyContentApiInterface
{

    //VIDEO

    /**
    * @param string $name название фильма/сериала
    * @param string $rating Возрастной рейтинг
    * @param array $kwargs Дополнительные параметры https://microimpuls.github.io/smarty-content-api-docs/#api-Video-VideoCreate
    * @return array Возвращает id созданного фильма/сериала, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function createVideo(string $name, string $rating, array $kwargs): array;

    /**
    * @param integer $id ID фильма/сериала в смарти
    * @param array $kwargs Дополнительные параметры https://microimpuls.github.io/smarty-content-api-docs/#api-Video-VideoCreate
    * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function modifyVideo(int $id, array $kwargs): array;

    /**
    * @param integer $id ID фильма/сериала в смарти
    * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function deleteVideo(int $id): array;

    //ASSET / VIDEOFILE

    /**
    * @param string $name Имя ассета в смарти
    * @param integer $vid ID видеофайла в смарти
    * @param array $kwargs Дополнительные параметры https://microimpuls.github.io/smarty-content-api-docs/#api-Video-VideoCreate
    * @return array Возвращает id созданного ассета, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function createVideoFile(string $name, int $vid, array $kwargs): array;

    /**
    * @param integer $id ID видеофайла в смарти
    * @param array $kwargs Дополнительные параметры https://microimpuls.github.io/smarty-content-api-docs/#api-Video-VideoCreate
    * @return array Возвращает id созданного фильма/сериала, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function modifyVideoFile(int $id, array $kwargs): array;

    //SEASON

    /**
     * Создает новый сезон
     * @param string $name Название сезона
     * @param int $vid Идентификатор сезона
     * @param array $kwargs Дополнительные аргументы (необязательно)
     * @return array Возвращает id созданного сезона, код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function createSeason(string $name, int $vid, array $kwargs): array;

    /**
     * Удаляет сезон по идентификатору
     * @param int $id Идентификатор сезона
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function deleteSeason(int $id): array;
    
    /**
     * Изменяет сезон по идентификатору
     * @param int $season_id Идентификатор сезона
     * @param array $kwargs Дополнительные аргументы (необязательно)
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function modifySeason(int $season_id, array $kwargs): array;

    //EPISODE

    /**
     * Создает новую серию
     * @param int $vid Идентификатор серии
     * @param string $name Название серии
     * @param int $season_id Идентификатор сезона, к которому принадлежит серия
     * @param array $kwargs Дополнительные аргументы (необязательно)
     * @return array Возвращает id созданной серии, код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function createEpisode(int $vid, string $name, int $season_id, array $kwargs): array;
    
    /**
     * Удаляет серию по идентификатору
     * @param int $id Идентификатор серии
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function deleteEpisode(int $id): array;

    /**
     * Изменяет серию по идентификатору
     * @param int $episode_id Идентификатор серии
     * @param array $kwargs Дополнительные аргументы (необязательно)
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function modifyEpisode(int $episode_id, array $kwargs): array;
}