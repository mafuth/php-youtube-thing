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
$yt2mp3 = new yt2mp3();
if(!isset($_GET['id'])){
    $VIDEOS = $yt2mp3->int();
    $i = 0;
    echo '<h1>Todays top</h1>';
    while($VIDEOS[$i] != ""){
        echo '<a href="?id='.$VIDEOS[$i].'"><img src="'.$yt2mp3->getThumb($VIDEOS[$i]).'"></a>';
        $i++;
    }
}
if(isset($_GET['id'])){ ?>


<!DOCTYPE html>
<html lang="en" >

<head>

  <title>MP3 PLAYER</title>
  
  
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-bootstrap/0.5pre/assets/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap'>
  
<style>
html,
body {
  background-color: #dfe7ef;
  align-items: center;
  justify-content: center;
  font-family: "Montserrat", sans-serif;
  color: #000;
}

.player {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 10px;
  user-select: none;
}
.player .circle {
  max-width: 120px;
}
.player .circle img {
  max-width: 150px;
  border-radius: 50%;
  margin-bottom: 10px;
  position: absolute;
  top: 10px;
}
.player .circle .active {
  text-transform: uppercase;
  animation-name: example;
  animation: record 3s linear 0s infinite forwards;
}
@keyframes record {
  0% {
    transform: rotateZ(0deg);
  }
  100% {
    transform: rotateZ(360deg);
  }
}
.player .circle .circ {
  position: absolute;
  width: 30px;
  height: 30px;
  left: 15%;
  top: 30%;
  background-color: #fff;
  border-radius: 50%;
  z-index: 1;
}
.player .player-track {
  background-color: #eef3f7;
  opacity: 0.7;
  top: 0;
  margin-left: 15px;
  margin-right: 15px;
  padding: 15px 15px 5px 160px;
  border-radius: 15px 15px 0px 0px;
}
.player .player-track .active {
  top: -100px;
}
.player .player-track .time {
  font-size: 16px;
  margin: 10px 0px;
  height: 6px;
}
.player .player-track .progress-bar {
  background-color: #d0d8e6;
  height: 6px;
  border-radius: 20px;
}
.player .player-track .progress-bar .fillBar {
  background-color: #a3b3ce;
  width: 0;
  height: 6px;
  border-radius: 20px;
}
.player .player-track .artist-name {
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 10px;
}
.player .player-track .music-name {
  font-size: 14px;
  margin-bottom: 10px;
}
.player .player-control {
  background-color: #eef3f7;
  width: 450px;
  height: 100px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0px 15px 35px -5px rgba(206, 206, 206, 0.32);
}
.player .player-control #play {
  border-radius: 50%;
}
.player .player-control i {
  padding: 30px;
  font-size: 35px;
  cursor: pointer;
  text-align: center;
}
.player .player-control i:before {
  color: #000;
  padding: 5px;
}
.player .player-control i:hover:before {
  color: #000;
  opacity: 0.7;
}

.github {
  position: absolute;
  bottom: 50px;
  left: 50px;
  color: #000;
  font-size: 30px;
  text-align: center;
}
.circle img{
  width:150px;
  height:150px;
  object-fit:cover;
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


</head>

<body translate="no" >
  <div class="player">
    <div class="player-track">
      <div class="artist-name"></div>
      <div class="music-name"></div>
      <div class="progress-bar">
        <div class="fillBar"></div>
      </div>
      <div class="time"></div>
    </div>
    <div class="circle">
      <div class="circ"></div>
      <img src="<?php echo $yt2mp3->getThumb($_GET['id']); ?>" alt="">
    </div>
    <div class="player-control">
      <i id="prev" class="fas fa-backward"></i>
      <i id="play" class="fas fa-play"></i>
      <i id="next" class="fas fa-forward"></i>
    </div>
  </div>
  <a target="_blank" href="https://github.com/cuneydbolukoglu">
    <i class="fab fa-github github"></i>
  </a>
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-1b93190375e9ccc259df3a57c1abc0e64599724ae30d7ea4c6877eb615f89387.js"></script>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
      <script id="rendered-js" >
$(document).ready(function () {
  var playing = false,
  artistname = $(".artist-name"),
  musicName = $(".music-name"),
  time = $(".time"),
  fillBar = $(".fillBar");

  let audioData = [];

  var song = new Audio();
  var CurrentSong = 0;
  window.onload = load();

  function load() {
    audioData = [
    {
      name: "listen",
      artist: "Player",
      src:
      "<?php echo $yt2mp3->getMp3Link($_GET['id']); ?>" }];



    artistname.html(audioData[CurrentSong].artist);
    musicName.html(audioData[CurrentSong].name);
    song.src = audioData[CurrentSong].src;
  }

  function playSong() {
    var curSong = audioData[CurrentSong];
    artistname.html(curSong.artist);
    musicName.html(curSong.name);
    song.src = curSong.src;
    song.play();
    $("#play").addClass("fa-pause");
    $("#play").removeClass("fa-play");
    $("img").addClass("active");
    $(".player-track").addClass("active");
  }

  song.addEventListener("timeupdate", function () {
    var position = 100 / song.duration * song.currentTime;
    var current = song.currentTime;
    var duration = song.duration;
    var durationMinute = Math.floor(duration / 60);
    var durationSecond = Math.floor(duration - durationMinute * 60);
    var durationLabel = durationMinute + ":" + durationSecond;
    currentSecond = Math.floor(current);
    currentMinute = Math.floor(currentSecond / 60);
    currentSecond = currentSecond - currentMinute * 60;
    // currentSecond = (String(currentSecond).lenght > 1) ? currentSecond : ( String("0") + currentSecond );
    if (currentSecond < 10) {
      currentSecond = "0" + currentSecond;
    }
    var currentLabel = currentMinute + ":" + currentSecond;
    var indicatorLabel = currentLabel + " / " + durationLabel;

    fillBar.css("width", position + "%");

    $(".time").html(indicatorLabel);
  });

  $("#play").click(function playOrPause() {
    if (song.paused) {
      song.play();
      playing = true;
      $("#play").addClass("fa-pause");
      $("#play").removeClass("fa-play");
      $("img").addClass("active");
    } else {
      song.pause();
      playing = false;
      $("#play").removeClass("fa-pause");
      $("#play").addClass("fa-play");
      $("img").removeClass("active");
    }
  });

  $("#prev").click(function prev() {
    CurrentSong--;
    if (CurrentSong < 0) {
      CurrentSong = 2;
    }
    playSong();
  });

  $("#next").click(function next() {
    CurrentSong++;
    if (CurrentSong > 2) {
      CurrentSong = 0;
    }
    playSong();
  });
});
//# sourceURL=pen.js
    </script>

  

</body>

</html>
 

<?php } ?>




