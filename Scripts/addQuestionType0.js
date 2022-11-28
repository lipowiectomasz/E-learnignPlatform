var time=60;

function addQuestion(n, id){
    var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
    i = x.length;                                       //Zapisanie ilosci pytan

    var end = document.getElementById("form");              //Dodanie nowego naglowka i pytania przed przyciskiem "Publikuj"
    var elements = document.getElementById("formElements");
    var elementsContent = elements.innerHTML;
    elementsContent += "<h1>Pytanie_"+i+"</h1><textarea cols=\"80\" rows=\"3\" name=\""+i+"\" class=\"editor\">Pytanie_"+i+" tekst</textarea><input type=\"file\" name=\"file"+i+"\" value=\"Przekaz plik\"><h5>Odp A:</h5><textarea cols=\"80\" rows=\"3\" name=\"A"+i+"\"></textarea><input type=\"radio\" id=\"A"+i+"\" name=\"correct"+i+"\" value=\"A\">&nbsp;<label for=\"A"+i+"\">Poprawna</label><h5>Odp B:</h5><textarea cols=\"80\" rows=\"3\" name=\"B"+i+"\"></textarea><input type=\"radio\" id=\"B"+i+"\" name=\"correct"+i+"\" value=\"B\">&nbsp;<label for=\"B"+i+"\">Poprawna</label><h5>Odp C:</h5><textarea cols=\"80\" rows=\"3\" name=\"C"+i+"\"></textarea><input type=\"radio\" id=\"C"+i+"\" name=\"correct"+i+"\" value=\"C\">&nbsp;<label for=\"C"+i+"\">Poprawna</label><h5>Odp D:</h5><textarea cols=\"80\" rows=\"3\" name=\"D"+i+"\"></textarea><input type=\"radio\" id=\"D"+i+"\" name=\"correct"+i+"\" value=\"D\">&nbsp;<label for=\"D"+i+"\">Poprawna</label>";

    elements.innerHTML = elementsContent;
    
    end.setAttribute("action", "zapiszDoBazy.php?typ=0&new="+n+"&id="+id+"&amount="+i+"&time="+time);
}
function setTime(s, n){
    time = time + s;
    if(time<60){
        time=60;
    }
    else if(time>3600){
        time=3600;
    }
    var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
    i = x.length;                                           //Zapisanie ilosci pytan
    var end = document.getElementById("form");

    //&new="+n+"&amount="+i+"&time="+time
    var endLink = end.getAttribute("action");
    endLink = endLink+"&new="+n+"&amount="+i+"&time="+time;
    end.setAttribute("action", endLink);

    var timer = document.getElementById("timer");
    timer.innerHTML = time;
}
