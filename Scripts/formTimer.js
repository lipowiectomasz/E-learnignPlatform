window.onload=function(){
    watchTime();
}
var t = document.getElementById("timer").innerHTML;
var time = parseInt(t);

function watchTime(){
    var minuty = time/60;
    var sekundy = time%60;
    if(sekundy<10){
        document.getElementById("timer").innerHTML = parseInt(minuty)+":0"+sekundy;
    }
    else{
        document.getElementById("timer").innerHTML = parseInt(minuty)+":"+sekundy;
    }
    if(time==0){
        clearTimeout(t);
        cutOff();
    }
    time=time-1;
    var t = setTimeout(watchTime,1000);
}
function cutOff(){
    var answers = document.getElementsByClassName("ans");
    var amt = answers.length;
    amt--;
    while(amt>=0){
        answers[amt].disabled = true;
        amt--;
    }
    alert("Koniec czasu!");
}
