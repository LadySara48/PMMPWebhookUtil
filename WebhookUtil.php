<?php

declare(strict_types=1);

namespace libs\hearlov\utils;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

/**
 * @WebhookUtil
 * @required AsyncWebhookSender
 *
 * EN: This software made by xLadySara https://hearlov.net
 * PL: To oprogramowanie stworzone przez xLadySara https://hearlov.net
 * TR: Bu yazılım xLadySara tarafından hazırlanmıştır https://hearlov.net
 */
class WebhookUtil{

    /**
     * @var $webhook
     * to String Webhook URL
     */
    private $webhook;

    /**
     * @var $json_array
     * Array Discord Webhook JSON Decode
     */
    private $json_array = [];

    /**
     * @var $embed
     * Array Discord Webhook Embed JSON Decode
     */
    private $embed = [];

    /**
     * @param string $webhok
     * new webhook utilization
     * yeni webhooku başlatır
     */
    public function __construct(String $webhook){
        $this->webhook = $webhook;
    }

    /**
     * @param string $name
     */
    public function setUsername(String $name){
        $this->json_array["username"] = $name;
    }

    /**
     * @param String $message
     * set Message Content in Discord
     */
    public function setContent(String $message){
        $this->json_array["content"] = $message;
    }

    /**
     * @param String $avt
     * set Discord sender profile Avatar
     */
    public function setAvatarURL(String $avt){
        $this->json_array["avatar_url"] = $avt;
    }

    /**
     * @param bool $tf
     * Enable or Disable Message Read Voice
     */
    public function setTTS(Bool $tf) {
        $this->json_array["tts"] = $tf;
    }

    /**
     * @param String $title
     * new Embed Maker
     */
    public function newEmbed(String $title){
        $this->embed["title"] = $title;
    }

    /**
     * @param String $url
     * Embed Title URL
     */
    public function setEmbedTitleURL(String $url){
        $this->embed["url"] = $url;
    }

    /**
     * @param String $txt
     * Embed content
     */
    public function setEmbedDescription(String $txt){
        $this->embed["description"] = $txt;
    }

    /**
     * add Embed to current Timestamp
     */
    public function setEmbedCurrentTimestamp(){
        $this->embed["timestamp"] = date("c", strtotime("now"));
    }

    /**
     * @param String $hexcolor
     * Set Embed Color
     * set 6 length hex color or "random"
     */
    public function setEmbedColor(String $hexcolor){
        if($hexcolor == "random"){
            $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
            $color = $rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
            $this->embed["color"] = hexdec($color);
            return;
        }
        $this->embed["color"] = hexdec($hexcolor);
    }

    /**
     * @param String $text
     * @param String $icon_url
     * add Embed footer
     */
    public function setEmbedFooter(String $text, String $icon_url){
        $this->embed["footer"] = ["text" => $text, "icon_url" => $icon_url];
    }

    /**
     * @param String $url
     * add Embed Image
     */
    public function setEmbedImage(String $url){
        $this->embed["image"] = ["url" => $url];
    }

    /**
     * @param String $url
     * add Embed Thumbnail
     */
    public function setEmbedThumbnail(String $url){
        $this->embed["thumbnail"] = ["url" => $url];
    }

    /**
     * @param String $name
     * @param String $imageurl
     * add Embed Author
     */
    public function setEmbedAuthor(String $name, String $imageurl){
        $this->embed["author"] = ["name" => $name, "url" => $imageurl];
    }

    /**
     * @param String $name
     * @param String $content
     * @param bool $inline
     * add Embed Field
     */
    public function addEmbedField(String $name, String $content, Bool $inline = false){
        $this->embed["fields"][] = ["name" => $name, "value" => $content, "inline" => $inline];
    }

    /**
     * add Embed for all Last changes
     */
    public function addLatestEmbed(){
        if(!isset($this->embed["title"])) return;
        $this->json_array["embeds"][] = $this->embed;
        $this->embed = [];
    }

    /**
     * @return void
     *
     * send AsyncWorker(cli)
     */
    public function sendWebhook(){
        Server::getInstance()->getAsyncPool()->submitTask(new AsyncWebhookSender($this->webhook, json_encode($this->json_array)));
    }

}

/**
 * @AsyncWebhookSender
 * @AsyncTask
 * @required WebhookUtil
 *
 * EN: This software made by xLadySara https://hearlov.net
 * PL: To oprogramowanie stworzone przez xLadySara https://hearlov.net
 * TR: Bu yazılım xLadySara tarafından hazırlanmıştır https://hearlov.net
 */
class AsyncWebhookSender extends AsyncTask
{
    private $webhook, $json;

    public function __construct($webhook, $json)
    {
        $this->webhook = $webhook;
        $this->curlopts = $json;
    }

    public function onRun(): void
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhook);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->json);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
    }


}
