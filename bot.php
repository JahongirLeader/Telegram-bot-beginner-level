
 
<?php

require("db.php");
class TeleBot{

const API_URL="https://api.telegram.org/bot1166188738:AAHY8k8-KtXcyEdqZXXSoFtMScbuNuyyNJA/";

public function setWebhook($url)
{


return $this->request('setWebhook',['url'=>$url]);


}


 public function request($method,$data)
 {


$ch = curl_init();

$url=self::API_URL.$method;




curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));


$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

return $result;
 }


          public function  sendMessage($chat_id,$text)
          {
             return $this->request("sendMessage",['chat_id'=>$chat_id,'text'=>$text]);

         }
          public function Updates()
          { 
          	$updates=json_decode(file_get_contents("php://input"));
            return $updates->message; 
          }

         public function sendPhoto($chat_id,$photo,$caption)
         {
          return $this->request("sendPhoto",['chat_id'=>$chat_id,'photo'=>$photo,'caption'=>$caption]);
         } 



public function action($chat_id,$step)
{
  if($step==1){$this-sendMessage($chat_id,"Familiyani kiriting");}
  if($step==2){$this-sendMessage($chat_id,"Rasmingizni yuboring");}
  if($step==1){$this-sendMessage($chat_id,"Congratulation send me /me");}

}






}


$bot=new TeleBot();

//echo $bot->setWebhook("https://goforituz.000webhostapp.com/index.php");


$data=$bot->Updates();
$text=$data->text;
$chat_id=$data->chat->id;





$step=$db->select($chat_id);
if($step){$step=$step['step'];}
else $step=0;

if($text=="/start" && $step==0){$bot->sendMessage($chat_id,"Salom royhatdan o'ting");

$db->insert($chat_id);
$bot->action($chat_id,1);

}

if(!empty($text) && $step==1)
{
    $opt="fullname = '$text',step=2 ";
    $db->update($opt,$chat_id);
    $bot->action($chat_id,2);

}
 if ($data->photo &&  $step==2) 
 {
   
   $photo=$data->photo[0]->file_id;
   $opt="photo = '$photo',step=3"; 
    if($db->update($opt,$chat_id))
   {
    $bot->action($chat_id,3);
   }
 }

if($text=="/me" && $step==3)
{
   $user=$db->select($chat_id);
 $name=$user['fullname'];
 $photo=$user['photo'];
 $bot->sendPhoto($chat_id,$photo,$name);

}








?>