<?php

namespace App\Service\DbAssist;

use App\Interface\Service\DbAssist\SmartyDbAssistInterface;
use App\Service\DbAssist\SmartyDbConnectorService;
use App\DTO\SearchDTO;
/**
 * Класс для установки параметров sql запроса и его отправки
 * 
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @method void smartyDbConnect()
* @version 1.0.0
*/
class SmartyDbAssistService implements SmartyDbAssistInterface
{
    private array $body;
    private $dbQuery;

    public function __construct(SmartyDbConnectorService $dbQuery)
    {
        $this->dbQuery = $dbQuery;
    }

    public function setVid(int $vid): void
    {
        $this->body['id'] = $vid;
    }

    public function setKinopoiskId(int $kinopoiskId): void
    {
        $this->body['kinopoisk_id'] = $kinopoiskId;
    }

    public function setGenres(array $genres):void
    {
        $this->body['genres'] = $genres;
    }

    public function setAdditionalTariffs(array $tariffs): void
    {
        $this->body['tariff'] = $tariffs;
    }

    public function setPosterUrl(string $poster): void
    {
        $this->body['poster'] = $poster;
    }

    public function flashQuery(): void
    {
        $sqlToAddKp = "UPDATE smarty.tvmiddleware_video SET kinopoisk_id=". $this->body['kinopoisk_id'].", poster_big='". $this->body['poster']."', poster_small='". $this->body['poster']."' WHERE id = ".$this->body['id'].";";
        $this->dbQuery->smartyDbQuery($sqlToAddKp);

        foreach($this->body['tariff'] as $tariffId){
            $sqlToTariffAdd = "INSERT INTO smarty.tvmiddleware_video_tariffs (video_id, tariff_id) VALUES(".$this->body['id'].", ".$tariffId.");";
            $this->dbQuery->smartyDbQuery($sqlToTariffAdd);
        }

        foreach($this->body['genres'] as $generId){
            $sqlToGenerAdd = "INSERT INTO smarty.tvmiddleware_video_genres (video_id, genre_id) VALUES(".$this->body['id'].", ".$generId.");";
            $this->dbQuery->smartyDbQuery($sqlToGenerAdd);
        }
    }

    public function searchGenre(string $genre){
        $sqlToGEnerGet = "SELECT id FROM smarty.tvmiddleware_genre WHERE LOWER(name) = LOWER('".$genre."');";
        dump($sqlToGEnerGet);
        $result = $this->dbQuery->smartyDbQuery($sqlToGEnerGet);
        dump($result);
        return $result;
    }

    public function searchActor(string $name, string $name_orig){
        $sqlToGEnerGet = "SELECT id FROM smarty.tvmiddleware_actor WHERE name='$name' OR name_orig='$name_orig' LIMIT 1;";
        dump($sqlToGEnerGet);
        $result = $this->dbQuery->smartyDbQuery($sqlToGEnerGet);
        dump($result);
        return $result;
    }

    public function setActor(int $videoId, int $actorId){
        $sql = "INSERT INTO smarty.tvmiddleware_video_actors_set (video_id, actor_id) VALUES($videoId, $actorId);";
        $this->dbQuery->smartyDbQuery($sql);
    }
    public function addActor(string $name, string $name_orig, string $photo): int {
        $profession = 'Актер';
    
        $sql = "INSERT INTO smarty.tvmiddleware_actor (name, name_orig, photo, profession, client_id, biography, biography_lang1,
        biography_lang2,
        biography_lang3,
        biography_lang4,
        biography_lang5,
        person_type
        )
                VALUES ('$name', '$name_orig', '$photo', '$profession', 1, '', '','','','','', 0)";
    
        $this->dbQuery->smartyDbQuery($sql);
    
        $sqlGetId = "SELECT id FROM smarty.tvmiddleware_actor ORDER BY id DESC LIMIT 1";
        $newId = $this->dbQuery->smartyDbQuery($sqlGetId);
    
        return $newId[0]['id'];
    }
    
    public function setScreenEpisode($episode_id){
        $src = "upload/tvmiddleware/video/episode/$episode_id/screen1.jpg";
        $sql = "UPDATE smarty.tvmiddleware_episode
        SET preview_img='$src' WHERE id=$episode_id;";
        $this->dbQuery->smartyDbQuery($sql);
    }

    public function getVideoIdByKinopoiskId(int $kpId)
    {
        $sql = "SELECT id FROM smarty.tvmiddleware_video WHERE kinopoisk_id = '".$kpId."';";
        return $this->dbQuery->smartyDbQuery($sql);
    }

    // public function setTariffForVideo(array $id)
    // {
    //     $sql =  "INSERT INTO smarty.tvmiddleware_video_tariffs (video_id, tariff_id) VALUES(".$id['video'].", ".$id['tariffid'].");";
    //     $this->inserter($sql);
    // }
    // public function setVideoGener($videoId, $generId)
    // {
    //     $sql = "INSERT INTO smarty.tvmiddleware_video_genres (video_id, genre_id) VALUES(".$videoId.", ".$generId.");";
    //     $this->inserter($sql);
    // }

    // public function setNewGener($gener)
    // {
    //    $sql =  "INSERT INTO smarty.tvmiddleware_genre (name, sort, client_id, is_category, name_lang1, name_lang2, name_lang3, name_lang4, name_lang5, enabled, epg_genre_id, hide_in_videolist, rating) VALUES(".$gener.", 6, 1, 0, '', '', '', '', '', 1, NULL, 0, 0);";
    //     $this->inserter($sql);
    // }

}