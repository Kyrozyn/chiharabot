<?php
/**
 * Created by IntelliJ IDEA.
 * User: Andreas
 * Date: 30/11/2017
 * Time: 20:36
 */
require __DIR__ . '/vendor/autoload.php';
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
class objectMess
{
    /**
     * @param string $text
     * @return TextMessageBuilder
     */
    public static function objText($text){
        return new TextMessageBuilder($text);
    }

    /**
     * @param string $title
     * @param null $text
     * @param null $imageUrl
     * @param array $button
     * @return TemplateMessageBuilder|null
     */
    public static function objButton($title,$text = null,$imageUrl = null,$button)
    {
        if(is_array($button)) {
            $button0 = new PostbackTemplateActionBuilder($button[0]["label"], $button[0]["data"]);
            if (isset($button[1]) AND !isset($button[2])) {
                $button1 = new PostbackTemplateActionBuilder($button[1]["label"], button[1]["data"]);
                $tombol = new ButtonTemplateBuilder($title, $text, $imageUrl, [$button0, $button1]);
            } else if (isset($button[2]) AND isset($button[1])) {
                $button1 = new PostbackTemplateActionBuilder($button[1]["label"], button[1]["title"]);
                $button2 = new PostbackTemplateActionBuilder($button[2]["label"], button[2]["data"]);
                $tombol = new ButtonTemplateBuilder($title, $text, $imageUrl, [$button0, $button1, $button2]);
            } else {
                $tombol = new ButtonTemplateBuilder($title, $text, $imageUrl, [$button0]);
            }
            return new TemplateMessageBuilder($title, $tombol);
        }
        else{
            file_put_contents("php://stderr","var button on objButton must be array");
            return null;
        }
    }

    /**
     * @param $link
     * @return ImageMessageBuilder
     */
    public static function objPic($link){
        return new ImageMessageBuilder($link,$link);
    }

}