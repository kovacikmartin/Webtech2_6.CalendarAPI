function convertDayToSk(day){

    return day.slice(2) + '.' + day.slice(0,2) + '.';
}

function validateDayFormat(day){

     //                        days                  months
     let regex = /^(0?[1-9]|[1-2][0-9]|30|31)[.](0?[1-9]|1[0-2])[.]$/;

     return day.match(regex) ? true : false;
}

function validateName(name){

    return $.isNumeric(name) ? true : false;
}

function convertDayToDb(day){

    day = day.split(".");
    day.pop(); // removes last empty array element, caused by split on the last dot in date

    // convert date into format matching meniny.xml <den>
    for(let i = 0; i < day.length; i++){
        if(day[i].length === 1){
            day[i] = "0" + day[i];
        }
    }
        
    return day[1] + day[0];
}

function getNamedays(){

    let errorMsg = document.getElementById("wrongDayFormat");
    errorMsg.innerHTML = "";

    let day = document.getElementById("inputNamedayDay").value;

    if(validateDayFormat(day))
    {            
        day = convertDayToDb(day);

        $.get("https://wt82.fei.stuba.sk/Foundation_and_Earth/api/namedays/" + day, function(data){

            let response = document.getElementById("response");
            let namedays = data.data;
            response.innerHTML = "";

            for(i in namedays){

                response.innerHTML += namedays[i].name + " " +  namedays[i].country + "<br>";
            }
        });

    }
    else{
        
        errorMsg.innerHTML = "Invalid date, please use DD.MM. format and correct values";
    }   
}

function getDayOfName(){

    let country = document.getElementById("selectNamedayCountry").value;
    let name = document.getElementById("inputNamedayName").value;

    let errorMsg = document.getElementById("nameCountryError");
    errorMsg.innerHTML = "";

    if(name === "" || country === ""){
        errorMsg.innerHTML = "Please fill in the information";
        return;
    }

    $.get("https://wt82.fei.stuba.sk/Foundation_and_Earth/api/nameday/" + name + "/" + country, function(data){

        let response = document.getElementById("response");
        response.innerHTML = "";
        
        let days = data.data;

        for(i in days){

            response.innerHTML += convertDayToSk(days[i].day) + "<br>";
        }

    }).fail(function(){
        response.innerHTML = "Name not found";
    });
}

function getHolidays(){

    let country = document.getElementById("selectHolidaysCountry").value;

    $.get("https://wt82.fei.stuba.sk/Foundation_and_Earth/api/holidays/" + country, function(data){

        let response = document.getElementById("response");
        response.innerHTML = "";

        let holidays = data.data;
    
        for(i in holidays){

            response.innerHTML += convertDayToSk(holidays[i].day) + " " +  holidays[i].holiday + "<br>";
        }
    });
}

function getMemorials(){

    $.get("https://wt82.fei.stuba.sk/Foundation_and_Earth/api/memorials/sk", function(data){

        let response = document.getElementById("response");
        response.innerHTML = "";

        let memorials = data.data;

        for(i in memorials){

            response.innerHTML += convertDayToSk(memorials[i].day) + " " +  memorials[i].memorial + "<br>";
        }
    });
}

function insertName(){

    let day = document.getElementById("inputInsertNamedayDay").value;
    let name = document.getElementById("inputInsertNamedayName").value;
    let response = document.getElementById("response");

    let errorDayMsg = document.getElementById("wrongDayFormatInsert");
    let errorNameMsg = document.getElementById("wrongNameInsert");
   
    errorDayMsg.innerHTML = "";
    errorNameMsg.innerHTML = "";
    response.innerHTML = "";

    if(validateDayFormat(day) && day !== "")
    { 
        if(!$.isNumeric(name) && name !== ""){

            day = convertDayToDb(day);

            var data = {};
            data["name"] = name;
            data["day"] = day;

            $.ajax({
                method: "POST",
                url: "https://wt82.fei.stuba.sk/Foundation_and_Earth/api/nameday",
                data: data,
                dataType: "text",
                success: function(){

                    response.innerHTML = "Name " + name + " added successfully";
                },
                error: function(ex){

                    response.innerHTML = "Sorry, there was an error: " + ex;
                }
            });
        }
        else{
            errorNameMsg.innerHTML = "Invalid name";
            return
        }

    }
    else{
        errorDayMsg.innerHTML = "Invalid date, please use DD.MM. format and correct values";
        return;
    }

}