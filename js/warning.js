"use strict";

var now = new Date();
var today = now.getFullYear() + '-' + (now.getMonth()+1) + '-' + now.getDate() + '/';
var nveurl = '/data/proxy.php?url=http://api01.nve.no/hydrology/forecast/flood/v1.0.2/api/WarningByMunicipality/1119/1/' + today + today + '&type=json';


function alertSchema(warningText, warningLevel){
    var alertDiv = document.createElement('div');
        var dismissButton = document.createElement('button');
            dismissButton.setAttribute('type', 'button');
            dismissButton.setAttribute('class', 'close');
            dismissButton.setAttribute('data-dismiss', 'alert');
            var dismissButtonSpan1 = document.createElement('span');
                dismissButtonSpan1.setAttribute('aria-hidden', 'true');
                dismissButtonSpan1.innerHTML = '&times;';
            var dismissButtonSpan2 = document.createElement('span');
                dismissButtonSpan2.setAttribute('class', 'sr-only');
                dismissButtonSpan2.innerHTML = 'Close';

    alertDiv.setAttribute('role', 'alert');
    alertDiv.className = 'col-md-12 alert alert-dismissable';
    if (warningLevel == 2){
        alertDiv.className = alertDiv.className + ' alert-warning';
    }
    else if (warningLevel == 3){
        alertDiv.className = alertDiv.className + ' alert-amber';
    }
    else{
        alertDiv.className = alertDiv.className + ' alert-danger';
    }
    dismissButton.appendChild(dismissButtonSpan1);
    dismissButton.appendChild(dismissButtonSpan2);
    alertDiv.appendChild(dismissButton);
    alertDiv.appendChild(document.createTextNode(warningText));
    
    return alertDiv;
}

$( document ).ready(function() {
console.log('warning.js');
        
    $.ajax({
        url: nveurl,
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function(nvedata){
            console.log('Activitylevel: ' + nvedata[0]['ActivityLevel']);
            if(nvedata[0]['ActivityLevel'] > 1){
                var ActivityLevel = nvedata[0]['ActivityLevel'];
                var MainText = nvedata[0]['MainText'];
                var WarningText = nvedata[0]['WarningText'];

                var warning = alertSchema(MainText, ActivityLevel);
                document.getElementById('warning').appendChild(warning);
                document.getElementById('warning').parentNode.style.display = 'block';
            }
        }
    });
    
    $.ajax({
        url: 'http://api.yr.no/weatherapi/textforecast/1.6/?forecast=obs;language=nb',
        type: 'GET',
        async: true,
        dataType: 'xml',
        success: function(obsdata){
            $(obsdata).find("[forecast_origin='VV_Obsvarsel']").each(function(index, value){
                $(value).find("location[name='Rogaland']").each(function(index, value){
                    //console.log($(value).find('in')[0]);
                    var warningText = $(value).find('in')[0].textContent;

                    var warning = alertSchema(warningText, 2);
                    document.getElementById('warning').appendChild(warning);
                    document.getElementById('warning').parentNode.style.display = 'block';
                });
            });
        }
    });
});