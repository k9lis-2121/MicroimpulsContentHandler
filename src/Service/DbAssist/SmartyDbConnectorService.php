<?php

namespace App\Service\DbAssist;

use App\Interface\Service\DbAssist\SmartyDbConnectorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Класс для прямого подключения к базе смарти.
 * 
* @author Валерий Ожерельев 
* @method void smartyDbQuery()
* @version 1.0.0
*/
class SmartyDbConnectorService implements SmartyDbConnectorInterface
{
    private $host;
    private $db;
    private $user;
    private $password;


    public function __construct(
                                    #[Autowire('%env(SMARTY_DB_URL)%')]     $host, 
                                    #[Autowire('%env(SMARTY_DB_USER)%')]    $user, 
                                    #[Autowire('%env(SMARTY_DB)%')]         $db, 
                                    #[Autowire('%env(SMARTY_DB_PASS)%')]    $password
                                )
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Подключение и отправка запроса
     *
     * @param string $query
     */
    public function smartyDbQuery(string $query)
    {
        $connect = mysqli_connect($this->host, $this->user, $this->password, $this->db);

        if (!$connect) {
            die('Ошибка подключение к базе данных smarty ' . mysqli_connect_error());
        }

        $result = mysqli_query($connect, $query);

        if ($result === false) {
            die('Ошибка выполнения запроса: ' . mysqli_error($connect));
        }
    
        $data = array();
    
        if (preg_match("/^SELECT/i", $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
    
        mysqli_close($connect);
    
        return $data;

    }

}