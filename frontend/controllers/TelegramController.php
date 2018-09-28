<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;
use \yii\web\Controller;

/**
 * Description of TelegramController
 *
 * @author Lex
 */
class TelegramController extends Controller {
    
    public function actionReceive() {
        
        $bot = \Yii::$app->bot;
        /*
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         * 
         */
        $bot->setCurlOption(CURLOPT_TIMEOUT, 20);
        $bot->setCurlOption(CURLOPT_CONNECTTIMEOUT, 10);
        $bot->setCurlOption(CURLOPT_HTTPHEADER, ['Expect:']);
        //$bot->sendMessage(452997916, 'Hello world!');
        
        $updates = \Yii::$app->bot->getUpdates();
        
        //$updates = $bot->getUpdates();
        var_dump($updates);
    }
    
}
