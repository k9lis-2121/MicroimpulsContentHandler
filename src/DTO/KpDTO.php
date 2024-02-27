<?php
namespace App\DTO;

class KpDTO
{
    /**
     * Кинопоиск ID
     *
     * @var integer
     */
    public int $id;

    /**
     * Основное название на Кинопоиске
     *
     * @var string|null
     */
    public string|null $name;

    /**
     * Тип (Фильм, сериал, аниме-сериал и др. по кинопоиску)
     *
     * @var string|null
     */
    public string|null $type;

    /**
     * Является ли сериалом
     *
     * @var boolean|null
     */
    public bool|null $isSeries;

    /**
     * ID на сервисах imdb, tmdb, kpHD
     *
     * @var array
     */
    public array $externalId;

    /**
     * Рейтинги площадок kp, imdb, filmCritics, russianFilmCritics, await
     *
     * @var array
     */
    
    public array $rating; 

    /**
     * Полное описание
     *
     * @var string|null
     */
    public string|null $description;

    /**
     * Краткое описание
     *
     * @var string|null
     */
    public string|null $shortDescription;

    /**
     * Кол-во оценок на плащадках kp, imdb, filmCritics, russianFilmCritics, await
     *
     * @var array
     */
    public array $votes; 
 

    /**
     * Год выпуска
     *
     * @var integer|null
     */
    public int|null $year;

    /**
     * Ссылки на постеры
     *
     * @var array
     */
    public array $poster; 

    /**
     * Жанры
     *
     * @var array
     */
    public array $genres;

    /**
     * Страны
     * 
     * @var array|string
     */
    public array|string $countries;

    /**
     * Информация о кол-ве серий в сезонах
     * 
     * @var array|null
     */
     public array $seasonsInfo;

    /**
     * Актеры и прочие
     * 
     * @var array
     */
    public array $persons;

    /**
     * Директор/режисер
     *
     * @var array|null
     */
    public array|null $director;

    /**
     * Альтернативное название
     * 
     * @var string|null
     */
    public string|null $alternativeName;

    /**
     * Английское название
     *
     * @var string|null
     */
    public string|null $enName;
    
    /**
     * Название на других языках
     *
     * @var array
     */
    public array $names;

    /**
     * Возрастное ограничение
     *
     * @var integer|string|null
     */
    public int|string|null $ageRating;

    /**
     * Полностью ли вышел
     *
     * @var string|null
     */
    public string|null $status;

    /**
     * Длительность фильма
     *
     * @var integer|null
     */
    public int|null $movieLength;

    /**
     * Длительность серии
     *
     * @var integer|null
     */
    public int|null $seriesLength;

    /**
     * Общая длительноть
     *
     * @var integer|null
     */
    public int|null $totalSeriesLength;



    /**
     * Get кинопоиск ID
     *
     * @return  integer
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set кинопоиск ID
     *
     * @param  integer  $id  Кинопоиск ID
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get основное название на Кинопоиске
     *
     * @return  string|null
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set основное название на Кинопоиске
     *
     * @param  string|null  $name  Основное название на Кинопоиске
     *
     * @return  self
     */ 
    public function setName(string|null $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get тип (Фильм, сериал, аниме-сериал и др. по кинопоиску)
     *
     * @return  string|null
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set тип (Фильм, сериал, аниме-сериал и др. по кинопоиску)
     *
     * @param  string|null  $type  Тип (Фильм, сериал, аниме-сериал и др. по кинопоиску)
     *
     * @return  self
     */ 
    public function setType(string|null $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get является ли сериалом
     *
     * @return  bool|null
     */ 
    public function getIsSeries()
    {
        return $this->isSeries;
    }

    /**
     * Set является ли сериалом
     *
     * @param  bool|null  $isSeries  Является ли сериалом
     *
     * @return  self
     */ 
    public function setIsSeries(bool|null $isSeries)
    {
        $this->isSeries = $isSeries;

        return $this;
    }

    /**
     * Get iD на сервисах imdb, tmdb, kpHD
     *
     * @return  array
     */ 
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set iD на сервисах imdb, tmdb, kpHD
     *
     * @param  array  $externalId  ID на сервисах imdb, tmdb, kpHD
     *
     * @return  self
     */ 
    public function setExternalId(array $externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @return  self
     */ 
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get полное описание
     *
     * @return  string|null
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set полное описание
     *
     * @param  string|null  $description
     *
     * @return  self
     */ 
    public function setDescription(string|null $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get краткое описание
     *
     * @return  string|null
     */ 
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set краткое описание
     *
     * @param  string|null  $shortDescription  Краткое описание
     *
     * @return  self
     */ 
    public function setShortDescription(string|null $shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get кол-во оценок на плащадках kp, imdb, filmCritics, russianFilmCritics, await
     *
     * @return  array
     */ 
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set кол-во оценок на плащадках kp, imdb, filmCritics, russianFilmCritics, await
     *
     * @param  array  $votes  Кол-во оценок на плащадках kp, imdb, filmCritics, russianFilmCritics, await
     *
     * @return  self
     */ 
    public function setVotes(array $votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get год выпуска
     *
     * @return  integer|null
     */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set год выпуска
     *
     * @param  integer|null  $year  Год выпуска
     *
     * @return  self
     */ 
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get ссылки на постеры
     *
     * @return  array
     */ 
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set ссылки на постеры
     *
     * @param  array  $poster  Ссылки на постеры
     *
     * @return  self
     */ 
    public function setPoster(array $poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get жанры
     *
     * @return  array
     */ 
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Set жанры
     *
     * @param  array  $genres  Жанры
     *
     * @return  self
     */ 
    public function setGenres(array $genres)
    {
        $this->genres = $genres;

        return $this;
    }

    /**
     * Get страны
     *
     * @return  array|string
     */ 
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set страны
     *
     * @param  array|string  $countries  Страны
     *
     * @return  self
     */ 
    public function setCountries(array $countries)
    {
        $this->countries = $countries;

        return $this;
    }

     /**
      * Get информация о кол-ве серий в сезонах
      *
      * @return  array
      */ 
     public function getSeasonsInfo()
     {
          return $this->seasonsInfo;
     }

     /**
      * Set информация о кол-ве серий в сезонах
      *
      * @param  array  $seasonsInfo  Информация о кол-ве серий в сезонах
      *
      * @return  self
      */ 
     public function setSeasonsInfo($seasonsInfo)
     {
          $this->seasonsInfo = $seasonsInfo;

          return $this;
     }

    /**
     * Get актеры и прочие
     *
     * @return  array
     */ 
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Set актеры и прочие
     *
     * @param  array  $persons  Актеры и прочие
     *
     * @return  self
     */ 
    public function setPersons(array $persons)
    {
        $this->persons = $persons;

        return $this;
    }

    /**
     * Get альтернативное название
     *
     * @return  string|null
     */ 
    public function getAlternativeName()
    {
        return $this->alternativeName;
    }

    /**
     * Set альтернативное название
     *
     * @param  string|null  $alternativeName  Альтернативное название
     *
     * @return  self
     */ 
    public function setAlternativeName(string|null $alternativeName)
    {
        $this->alternativeName = $alternativeName;

        return $this;
    }

    /**
     * Get английское название
     *
     * @return  string|null
     */ 
    public function getEnName()
    {
        return $this->enName;
    }

    /**
     * Set английское название
     *
     * @param  string|null  $enName  Английское название
     *
     * @return  self
     */ 
    public function setEnName(string|null $enName)
    {
        $this->enName = $enName;

        return $this;
    }

    /**
     * Get название на других языках
     *
     * @return  array
     */ 
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Set название на других языках
     *
     * @param  array  $names  Название на других языках
     *
     * @return  self
     */ 
    public function setNames(array $names)
    {
        $this->names = $names;

        return $this;
    }

    /**
     * Get возрастное ограничение
     *
     * @return  integer|string|null
     */ 
    public function getAgeRating()
    {
        return $this->ageRating;
    }

    /**
     * Set возрастное ограничение
     *
     * @param  integer|string|null  $ageRating  Возрастное ограничение
     *
     * @return  self
     */ 
    public function setAgeRating($ageRating)
    {
        $this->ageRating = $ageRating;

        return $this;
    }

    /**
     * Get полностью ли вышел
     *
     * @return  string|null
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set полностью ли вышел
     *
     * @param  string|null  $status  Полностью ли вышел
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get длительность фильма
     *
     * @return  integer|null
     */ 
    public function getMovieLength()
    {
        return $this->movieLength;
    }

    /**
     * Set длительность фильма
     *
     * @param  integer|null  $movieLength  Длительность фильма
     *
     * @return  self
     */ 
    public function setMovieLength($movieLength)
    {
        $this->movieLength = $movieLength;

        return $this;
    }

    /**
     * Get длительность серии
     *
     * @return  integer|null
     */ 
    public function getSeriesLength()
    {
        return $this->seriesLength;
    }

    /**
     * Set длительность серии
     *
     * @param  integer|null  $seriesLength  Длительность серии
     *
     * @return  self
     */ 
    public function setSeriesLength($seriesLength)
    {
        $this->seriesLength = $seriesLength;

        return $this;
    }

    /**
     * Get общая длительноть
     *
     * @return  integer|null
     */ 
    public function getTotalSeriesLength()
    {
        return $this->totalSeriesLength;
    }

    /**
     * Set общая длительноть
     *
     * @param  integer|null  $totalSeriesLength  Общая длительноть
     *
     * @return  self
     */ 
    public function setTotalSeriesLength($totalSeriesLength)
    {
        $this->totalSeriesLength = $totalSeriesLength;

        return $this;
    }

    /**
     * Get директор/режисер
     *
     * @return  array|null
     */ 
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set директор/режисер
     *
     * @param  array|null  $director  Директор/режисер
     *
     * @return  self
     */ 
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }
}