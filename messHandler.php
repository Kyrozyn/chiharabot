<?php
/**
 * Created by IntelliJ IDEA.
 * User: Andreas
 * Date: 21/11/2017
 * Time: 10:04
 */
require __DIR__ . '/vendor/autoload.php';
require "objectMess.php";
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;


class messHandler extends objectMess
{

    /**
     * @param $result
     */
    private static function check(\LINE\LINEBot\Response $result){
        if($result->isSucceeded()){
            file_put_contents('php://stderr',"Succeed!!");
        }
        else{
            $hasil = $result->getJSONDecodedBody();
            file_put_contents('php://stderr',print_r($hasil,1));
        }
    }

    /**
     * Buat replyText..
     * @param string $replyToken
     * @param string $text
     */
    public static function replyText($replyToken,$text){
        global $bot;
        $result = $bot->replyText($replyToken,$text);
        self::check($result);
    }

    /**
     * @param string $replyToken
     * @param string$text1
     * @param string|null $text2
     * @param string|null $text3
     * @param string|null $text4
     */
    public static function replyTextMore($replyToken,$text1,$text2 = null, $text3 = null, $text4 = null){
        global $bot;
        $more = new MultiMessageBuilder();
        $one = self::objText("$text1");
        $more->add($one);
        if(isset($text2)){
            $two = self::objText($text2);
            $more->add($two);
        }
        if(isset($text3)){
            $three = self::objText($text3);
            $more->add($three);
        }
        if(isset($text4)){
            $four = self::objText($text4);
            $more->add($four);
        }
        $result = $bot->replyMessage($replyToken,$more);
        self::check($result);
    }

    /**
     * @param string $replyToken
     * @param string $title
     * @param string|null $text
     * @param string|null $imageUrl
     * @param array $button
     */
    static function replyButton($replyToken,$title,$text = null,$imageUrl = null,$button){
        global $bot;
        $button = self::objButton($title,$text,$imageUrl,$button);
        $result = $bot->replyMessage($replyToken,$button);
        self::check($result);
    }

    static function more($replyToken,$more){
        global $bot;
        if(is_array($more)){
            $multi = new MultiMessageBuilder();
            $multi->add($more[0]);
            $multi->add($more[1]);
            if(isset($more[2])){
                $multi->add($more[2]);
            }
            if(isset($more[3])){
                $multi->add($more[3]);
            }
            $result = $bot->replyMessage($replyToken,$multi);
            self::check($result);
        }
        else {
            file_put_contents("php://stderr","var more must be array");
        }

    }
    public static function replyPic($replyToken,$link){
        global $bot;
        $pic = self::objPic($link);
        $result = $bot->replyMessage($replyToken,$pic);
        self::check($result);
    }
}