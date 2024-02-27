<?php

namespace App\Service\Api\External\Kinopoisk;

use App\Interface\Service\Api\External\Kinopoisk\KinopoiskProcessorInterface;
use App\Service\Cleaner\StringCleanerService;
use App\Service\Api\External\Kinopoisk\PretrainingDataService;
use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\DbAssist\SmartyDbAssistService;
use App\Service\ImageHandler\ImageHandlerService;
use App\Interface\Service\Api\External\Kinopoisk\ActorHelperInterface;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method object dataProcessing()
* @version 1.0.3
*/
class KinopoiskProcessorService implements KinopoiskProcessorInterface
{

    private $stringProcessor;
    private $smartyApi;
    private $smartyDb;
    private $pretrainingData;
    private $imgHandler;
    private $actorHelper;

    public function __construct(
                                StringCleanerService $cleaner, 
                                SmartyContentApiService $smartyApi, 
                                PretrainingDataService $pretrainingData, 
                                SmartyDbAssistService $smartyDb, 
                                ImageHandlerService $imgHandler,
                                ActorHelperInterface $actorHelper
                                ){
        $this->stringProcessor = $cleaner;
        $this->smartyApi = $smartyApi;
        $this->smartyDb = $smartyDb;
        $this->pretrainingData = $pretrainingData;
        $this->imgHandler = $imgHandler;
        $this->actorHelper = $actorHelper;
    }

    /**
    * @param array json ответ от кинопоиска преобразованный в массив
    * @return array Обработанный массив с данными готовыми для записи
    */
    public function dataProcessing(array $data): array
    {
        $dataProcessed = $data;

        dump('----------------------------------');
        dump($data['name']);
        dump('----------------------------------');
        //Clean string
        $dataProcessed['name'] = $this->stringProcessor->cleanName($data['name']);
        $dataProcessed['description'] = $this->stringProcessor->cleanDescription($data['description']);
        $dataProcessed['shortDescription'] = $this->stringProcessor->cleanDescription($data['shortDescription']);
        
        //Array to streang
        $dataProcessed['countries'] = $this->pretrainingData->getArrToString($data['countries']);

        //Not null
        $dataProcessed['kinopoisk_rating'] = $this->pretrainingData->notNull($data['rating']['kp']);
        $dataProcessed['imdb_rating'] = $this->pretrainingData->notNull($data['rating']['imdb']);
        $dataProcessed['duration'] = $this->pretrainingData->notNull($data['movieLength']);
        $dataProcessed['is_season'] = $this->pretrainingData->notNull($data['type']);

        //Unic Check
        $dataProcessed['is_season'] = $this->pretrainingData->isSeason($dataProcessed['is_season']);
        $dataProcessed['name_orig'] = $this->pretrainingData->getOrigName($data['names']);
        $dataProcessed['description'] = $this->pretrainingData->lenghtDescriptionCheck(['description' => $data['description'], 'shortDescription' => $data['shortDescription']]);
        $dataProcessed['parent_control'] = $this->pretrainingData->parentControll($data['ageRating']);
        $dataProcessed['director'] = $this->pretrainingData->searchFilmDirector($data['persons']);


        //Clean new string

        $dataProcessed['name_orig'] = $this->stringProcessor->CleanName($dataProcessed['name_orig']);
        $dataProcessed['director'] = $this->stringProcessor->CleanFull($dataProcessed['director']);

        foreach($data['persons'] as $key => $person){
            if($person['enProfession'] == 'actor' OR $person['profession'] == 'актеры'){
                $dataProcessed['actors'][$key]['name'] = $this->stringProcessor->CleanFull($person['name']);
                $dataProcessed['actors'][$key]['enName'] = $this->stringProcessor->CleanFull($person['enName']);
                $dataProcessed['actors'][$key]['photo'] = $person['photo'];
                $dataProcessed['actors'][$key]['id'] = $person['id'];
            }
        }

        dump($dataProcessed);
        return $dataProcessed;
    }


    public function sendDataInSmarty($data, $selectedDisk){
        // dump($data['actors']);


       if($data->isIsSeason()){
        $genre = 33;
       }else{
        $genre = 34;
       }

       $streamServiceArr = [
        1 => 21,
        11 => 22,
        14 => 23,
        15 => 24,
        20 => 25,
        21 => 26,
        3 => 27,
        5 => 27,
        8 => 29,
        9 => 30
       ];
        
       if($data->isIsSeason() == ''){
        $isSeason = 0;
       }else{
        $isSeason = 1;
       }

        $params = 
        [
            'name' => $data->getName(),
            'name_orig' => $data->getNameOrig(),
            'description' => $data->getDescription(),
            'year' => $data->getYear(),
            'countries' => $data->getCountries(),
            'stream_services' => $streamServiceArr[$selectedDisk],
            // 'actors_set' => 1,
            'kinopoisk_rating' => $data->getKinopoiskRating(),
            'imdb_rating' => $data->getImdbRating(),
            'duration' => $data->getDuration(),
            'is_season' => $isSeason,
            'parent_control' => $data->getParentControl(),
        ];


        /*
            костыль
        */
        // if($data->getAgeRating() == null){
        //     $test = '0';
        // }else{
        //     $test = (string)$data->getAgeRating();
        // }

        
        $result = $this->smartyApi->createVideo($data->getName(), '0', $params);
        foreach($data->getGenres() as $ganre){   
            $name = $ganre['name'];
            $response[] = $this->smartyDb->searchGenre($name);
        }
        $categories[0] = $genre;
        foreach($response as $col){
            $categories[] = $col[0]['id'];
        }
        
        dump($categories);

        $this->smartyDb->setVid($result['id']);
        $this->smartyDb->setKinopoiskId((int)$data->getKinopoiskId());
        $this->smartyDb->setGenres($categories);
        $this->smartyDb->setAdditionalTariffs([15, 19]);
        $this->smartyDb->setPosterUrl('upload/tvmiddleware/posters/'.$result['id'].'/pb_poster.jpg');


        /**
         * @ticket Проблема в скорости ответа диска/директории с актерами
         */

    //     if(!empty($data['actors'])){
    //     foreach($data['actors'] as $actor){
    //         $actorId = $this->actorHelper->getIdActor($actor['name'], $actor['enName'], $actor['photo'], $actor['id']);
    //         dd('выход');
    //         $this->smartyDb->setActor($result['id'], $actorId);
    //     }
    // }else{
    //     dd('oi');
    // }
        $this->smartyDb->flashQuery();


        dump('url to poster');
        dump($data->getPoster());
        $tmpImgDir = $this->imgHandler->imageConvert($data->getPoster(), 'default');
        dump($tmpImgDir);

        $this->imgHandler->loadImageToSmarty($result['id'], $tmpImgDir);

        return $result;
    }
}