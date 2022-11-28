function addQuestion(n, id, typ){
    var x = document.getElementsByClassName("editor");      //Pobranie listy wszystkich pytan
    var i = x.length;                                       //Zapisanie ilosci pytan

    var end = document.getElementById("form");              //Dodanie nowego naglowka i pytania przed przyciskiem "Publikuj"

    var elements = document.getElementById("formElements");
    var elementsContent = elements.innerHTML;
    elementsContent += "<h1>Podpunkt_"+i+"</h1><textarea cols=\"80\" rows=\"3\" name=\""+i+"\" class=\"editor\">Podpunkt_"+i+" tekst</textarea><input type=\"file\" name=\"file"+i+"\" value=\"Przekaz plik\"/>";

    elements.innerHTML = elementsContent;

    //var endLink = end.getAttribute("action");
    //endLink = endLink+"&new="+n+"&amount="+i;
    
    
    var link = "zapiszDoBazy.php?typ="+typ+"&new="+n+"&id="+id+"&amount="+i+"";
    end.setAttribute("action", link);
}