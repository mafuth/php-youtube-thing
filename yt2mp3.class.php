<?php
class yt2mp3{
    public function int(){
        $IDS = array();
        $PAGE = file_get_contents("https://www.youtube.com/channel/UC-9-kyTW8ZkZNDHQJ6FgpwQ");
        $VIDEOS = explode('/watch?v=',$PAGE);
        $i = 1;
        while($VIDEOS[$i] != ""){
            $VIDEO = explode('"',$VIDEOS[$i])[0];
            $VIDEO = explode('\u',$VIDEO)[0];
            if(!in_array($VIDEO,$IDS)){
                array_push($IDS,$VIDEO);
            }
            $i++;
        }
        return $IDS;
    }
    public function getMp3Link($ID){
        $PAGE = file_get_contents("https://api.download-lagu-mp3.com/@api/json/mp3/$ID");
    
        $TITLE = explode('data-yt-title="',$PAGE)[1];
        $TITLE = urlencode(explode('">',$TITLE)[0]);
    
        $DATA_HASH = explode('data-mp3-tag="',$PAGE)[1];
        $DATA_HASH = explode('">',$DATA_HASH)[0];
        
        
        return "https://api.vevioz.com/download/$DATA_HASH/$TITLE.mp3" ;

    }
    public function getMp4Link($ID){
        $PAGE = file_get_contents("https://api.vevioz.com/@api/button/videos/$ID");
    
        $TITLE = explode('data-yt-title="',$PAGE)[1];
        $TITLE = urlencode(explode('">',$TITLE)[0]);
    
        $DATA_HASH = explode('data-mp4-tag="',$PAGE)[3];
        $DATA_HASH = explode('">',$DATA_HASH)[0];
        
        
        return "https://api.vevioz.com/download/$DATA_HASH/$TITLE.mp4" ;

    }
    public function getThumb($ID){
        return "https://img.youtube.com/vi/$ID/mqdefault.jpg";
    }
    public function getTitle($ID){
        return explode('</title>', explode('<title>', file_get_contents("https://www.youtube.com/watch?v=$ID"))[1])[0];
    }
}
