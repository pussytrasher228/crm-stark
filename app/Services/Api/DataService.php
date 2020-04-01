<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 27.09.18
 * Time: 1:41
 */

namespace App\Services\Api;


use Exception;

class DataService
{

    public function authorize()
    {
        $user=array(
            'USER_LOGIN'=>'test@stark-media.ru', #Ваш логин (электронная почта)
            'USER_HASH'=>'8ebdba84a85d468d747b224f1b73d2447fbce010' #Хэш для доступа к API (смотрите в профиле пользователя)
        );
        $subdomain='starkmedia'; #Наш аккаунт - поддомен
#Формируем ссылку для запроса
        $link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__DIR__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__DIR__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
        curl_close($curl); #Завершаем сеанс cURL
        $code=(int)$code;
        $errors=array(
            301=>'Moved permanently',
            400=>'Bad request',
            401=>'Unauthorized',
            403=>'Forbidden',
            404=>'Not found',
            500=>'Internal server error',
            502=>'Bad gateway',
            503=>'Service unavailable'
        );
        try
        {
            #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
            if($code!=200 && $code!=204)
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
        }
        catch(Exception $E)
        {
            die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
        }

        $Response=json_decode($out,true);
        $Response=$Response['response'];
        if(!isset($Response['auth']))
            return 'Авторизация не удалась';
    }


    public function getApiItems(string $url, array $params = [])
    {
        return $this->getDataByUrl($url, $params)['items'];
    }

    public function getApiUsers(string $url, array $params = [])
    {
        return $this->getDataByUrl($url, $params)['users'];
    }

    public function getDataByUrl(string $url, array $params = [])
    {
        $link = 'https://starkmedia.amocrm.ru' . $url . (!empty($params) ? '?' . http_build_query($params) : '');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__DIR__).'/cookie.txt');
        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__DIR__).'/cookie.txt');

        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        $code=(int)$code;
        $errors=array(
            301=>'Moved permanently',
            400=>'Bad request',
            401=>'Unauthorized',
            403=>'Forbidden',
            404=>'Not found',
            500=>'Internal server error',
            502=>'Bad gateway',
            503=>'Service unavailable'
        );
        try
        {
            if($code!=200 && $code!=204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
            }
        }
        catch(Exception $E)
        {
            die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
        }
        $Response=json_decode($out,true);
        $Response=$Response['_embedded'];
        return $Response;
    }

}
