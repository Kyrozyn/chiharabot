<?php
require __DIR__ . '/vendor/autoload.php';

use LINE\LINEBot;
use Slim\App;
use Medoo\Medoo;
use LINE\LINEBot\Constant\HTTPHeader;
$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new App($configs);
//
$channel_access_token = "Nkvg98JS9Z5KeCVmws/e4KEN2CrT7vdCR8EUUXwo8UmfDunvwLYY9EqeAWmTwApt18V0BsFvwyGHsik7xZR+h68eRz6QWtXbFTnX5iYY2qJ7GxYpPXApnK7ZQvqLaUxh+QbnY58aWT/ZJ/bEDA7J+gdB04t89/1O/w1cDnyilFU=";
$channel_secret = "eba27b50c15c58a656edc8846102b661";
//
Cloudinary::config(array(
    "cloud_name" => "chiharakntl",
    "api_key" => "393212918283268",
    "api_secret" => "v5Jbx2avscmwrEMQCrvUMe4J1TQ",
    "resource_type" => "raw"
));

//
try {
    $db = new Medoo([
        'database_type' => 'pgsql',
        'database_name' => 'd4b69g3c3g5f82',
        'server' => 'ec2-54-235-165-114.compute-1.amazonaws.com',
        'username' => 'mwbyxqqvoryaof',
        'password' => '695edb1c23d62c98e46d1331919340652a3832dd73c01357cf17122d1bf74dce',
    ]);
}
catch(Exception $e){
    file_put_contents("php://stderr",$e->getMessage());
    file_put_contents("php://stderr","\nError on Db... Sorry...");
}
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);
$app->get('/',function (){
    echo 'testMiku';
});
$app->post('/bot',function (\Slim\Http\Request $req, \Slim\Http\Response $res) use ($bot,$db){
    include "messHandler.php";
    include "textParser.php";
    //For log to heroku logs
    //$body = file_get_contents('php://input');
    //file_put_contents('php://stderr', 'Body: '.$body);
    ////////////////////////
    $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
    $result = null;
    if (empty($signature)) {
        return $res->withStatus(400, 'Bad Request');
    }
    $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
    foreach ($events as $event) {
        $messageType = $event['message']['type'];
        $text = new textParser($event->getText());
        if ($event->isUserEvent()){
            messHandler::replyText($event->getReplyToken(),"Hae");
        }
        if ($event->isGroupEvent()){
            switch($text->textKecil){
                case "testdb" :
                    messHandler::replyText($event->getReplyToken(),print_r($db->info(),1));
                    break;
                case "gacha" :
                    $roll = random_int(1,100);
                    if ($roll == 100) {
                        messHandler::replyText($event->getReplyToken(),"5* Servant");
                    } else if ($roll <= 99 && $roll > 96) {
                        messHandler::replyText($event->getReplyToken(),"5* CE");
                    } else if ($roll <= 96 && $roll > 92) {
                        messHandler::replyText($event->getReplyToken(),"4* Servant");
                    }
                    else if ($roll <= 92 && $roll > 84) {
                        messHandler::replyText($event->getReplyToken(),"4* CE");
                    }
                    else if ($roll <= 84 && $roll > 44) {
                        messHandler::replyText($event->getReplyToken(),"3* Servant");
                    }
                    else{
                        messHandler::replyText($event->getReplyToken(),"3* CE");
                    }
                    break;
                case "gacha banyak" :
                case "gacha kontol" :
                    re :
                    $balas = null;
                    $ssr = 0;
                    $sr = 0;
                    $r = 0;
                    for ($a=0;$a<10;$a++){
                        $roll = random_int(1,100);
                        if ($roll == 100) {
                            $balas = $balas."5* Servant";
                            $ssr = $ssr+1;
                        } else if ($roll <= 99 && $roll > 96) {
                            $balas = $balas."5* CE";
                            $ssr = $ssr+1;
                        } else if ($roll <= 96 && $roll > 92) {
                            $balas = $balas."4* Servant";
                            $sr = $sr+1;
                        }
                        else if ($roll <= 92 && $roll > 84) {
                            $balas = $balas."4* CE";
                            $sr = $sr+1;
                        }
                        else if ($roll <= 84 && $roll > 44) {
                            $balas = $balas."3* Servant";
                            $r = $r +1;
                        }
                        else{
                            $balas = $balas."3* CE";
                            $r = $r +1;
                        }
                        if($a!=9 ){
                            $balas = $balas."\n";
                        }
                    }
                    if($ssr == 10 OR $sr == 10 OR $r == 10){
                        goto re;
                    }
                    else {
                        $text1 = messHandler::objText($balas);
                        if($sr < 2 AND $ssr <1){
                            $rand = ["Ampas sekali hidup anda ^_^","Perbanyak tobat agar luck anda meningkat ^_^"];
                            $tx = $rand[array_rand($rand)];
                        }
                        else{
                            $rand = ["Jangan lupa sikat gigi sebelum gacha ^_^","Jangan lupa puasa sebelum gacha ^_^","Jangan lupa makan sebelum gacha ^_^","Jangan lupa minum sebelum gacha ^_^"];
                            $tx = $rand[array_rand($rand)];
                        }
                        $text2 = messHandler::objText("SSR = " . $ssr . "\nSR = " . $sr . "\nR =" . $r . "\n" . $tx);
                        messHandler::more($event->getReplyToken(), [$text1, $text2]);
                    }
                    break;
                case "xp" :
                    $xp = $db->get("xp","xp",["userid" => $event->getUserId()]);
                    if($db->has("xp",["userid" => $event->getUserId()])) {
                        messHandler::replyText($event->getReplyToken(), "Xp kamu sebanyak : " . $xp);
                    }
                    else{
                        messHandler::replyText($event->getReplyToken(),"Data xp kamu tidak ditemukan. Apa kamu sudah add chihara?");
                    }
                    //file_put_contents('php://stderr', 'Body: '."log db : ".print_r($db->error(),1));
                    break;
                case "!leaderboard":
                case "!lb":
                    $userid = $db->select("xp",["userid","xp"],["ORDER" => ["xp"=>"DESC"],"LIMIT" => 10]);
                    $text = "***Leaderboard***";
                    $angka = 0;
                    $balas = null;
                    foreach ($userid as $id){
                        $angka = $angka+1;
                        $profile = $bot->getProfile($id["userid"]);
                        $json = $profile->getJSONDecodedBody();
                        $nama = $json['displayName'];
                        if(empty($nama)){
                            $nama = "????";
                        }
                        $balas = $balas.$angka.". ".$nama." : ".$id["xp"];
                        if($angka<10){
                            $balas = $balas."\n";
                        }
                    }
                    $satu = messHandler::objText($text);
                    $dua = messHandler::objText($balas);
                    messHandler::more($event->getReplyToken(), [$satu, $dua]);
                    //messHandler::replyText($event->getReplyToken(),print_r($userid,1)."\n".print_r($db->error(),1));
                    break;
                case "!lbg":
                    $uaid = $db->get("xp","xp",["userid" => $event->getUserId()]);
                    $a = $event->getUserId();
                    if(isset($a)) {
                        $xp = rand(1, 2);
                        $baru = $xp + $uaid;
                        $db->update("xp", ["xp" => $baru,"groupid"=>$event->getGroupId()], ["userid" => $event->getUserId()]);
                        file_put_contents('php://stderr', "xp ditambahkan : " . $xp . " ke : " . $event->getUserId());
                    }
                    $userid = $db->select("xp",["userid","xp"],["ORDER" => ["xp"=>"DESC"],"LIMIT" => 10,"groupid"=>$event->getGroupId()]);
                    $text = "***Group Leaderboard***";
                    $angka = 0;
                    $balas = null;
                    foreach ($userid as $id){
                        $angka = $angka+1;
                        $profile = $bot->getProfile($id["userid"]);
                        $json = $profile->getJSONDecodedBody();
                        $nama = $json['displayName'];
                        if(empty($nama)){
                            $nama = "????";
                        }
                        $balas = $balas.$angka.". ".$nama." : ".$id["xp"];
                        if($angka<10){
                            $balas = $balas."\n";
                        }
                    }
                    $satu = messHandler::objText($text);
                    $dua = messHandler::objText($balas);
                    messHandler::more($event->getReplyToken(), [$satu, $dua]);
                    //messHandler::replyText($event->getReplyToken(),print_r($userid,1)."\n".print_r($db->error(),1));
                    break;
                default :
                    if(!$db->has("xp",["userid" => $event->getUserId()])){
                        $db->insert("xp",["userid" => $event->getUserId(),"xp" => 0,"groupid"=>$event->getGroupId()]);
                        file_put_contents('php://stderr',"user tambah : ".$event->getUserId());
                    }
                    else{
                        $uaid = $db->get("xp","xp",["userid" => $event->getUserId()]);
                        $a = $event->getUserId();
                        if(isset($a)) {
                            $xp = rand(1, 2);
                            $baru = $xp + $uaid;
                            $db->update("xp", ["xp" => $baru,"groupid"=>$event->getGroupId()], ["userid" => $event->getUserId()]);
                            file_put_contents('php://stderr', "xp ditambahkan : " . $xp . " ke : " . $event->getUserId());
                        }
                        else{
                            //do nothing
                        }
                    }
                    break;
            }

            switch ($text->textBintang[0]){
                case keyword :
                    $admin = $db->has("admin",["userid[=]"=>$event->getUserId()]);
                    if($admin) {
                        $ada = $db->has("keyword", ["then[=]" => "-"]);
                        if (!empty($text->textBintang[1]) AND !$ada) {
                            messHandler::replyText($event->getReplyToken(), "Silahkan upload Gambarnya, goshujin-sama! ^_^");
                            $db->insert("keyword", ["if" => $text->textBintang[1], "then" => "-"]);
                        } else {
                            messHandler::replyText($event->getReplyToken(), "Ada request sebelumnya yg belum selesai..\nSilahkan upload Gambarnya, goshujin-sama! ^_^");
                        }
                    }
                break;
            }
            if($messageType == "image"){
                $admin = $db->has("admin",["userid[=]"=>$event->getUserId()]);
                if($admin) {
                    $picId = $event['message']['id'];
                    $url = "https://chiharabackup.herokuapp.com/index.php/content/" . $picId;
                    $test = Cloudinary\Uploader::upload($url, ["public_id" => $picId, "resource_type" => "auto"]);
                    $decode = json_decode($test);
                    $go = $db->update("keyword", ["then" => $decode["secure_url"]], ["then[=]" => "-"]);
                    if ($go) {
                        messHandler::replyText($event->getReplyToken(), "keyword berhasil ditambahkan, goshujin-sama! ^_^");
                    }
                }
            }
        }
    }
});

$app->get('/refreshname',function (\Slim\Http\Request $req, \Slim\Http\Response $res) use ($bot,$db){
    $database = $db->select("xp",["userid","xp","nama"],["ORDER" => ["xp"=>"DESC"]]);
    $succ = 0;
    foreach ($database as $data){
        if($data["nama"] == "-"){
            $profile = $bot->getProfile($data["userid"]);
            $json = $profile->getJSONDecodedBody();
            $nama = $json['displayName'];
            $db->update("xp",["nama"=> $nama],["userid[=]"=>$data["userid"]]);
            $succ = $succ +1;
        }
    }
});

$app->get('/content/{messageId}', function($req, $res) use ($bot)
{
    // get message content
    $route      = $req->getAttribute('route');
    $messageId = $route->getArgument('messageId');
    $result = $bot->getMessageContent($messageId);

    // set response
    $res->write($result->getRawBody());

    return $res->withHeader('Content-Type', $result->getHeader('Content-Type'));
});

$app->run();