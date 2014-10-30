"use strict";

var now = new Date();
var month = (now.getMonth()+1);
if (month < 10){
    month = '0' + month;
}
var day = (now.getDate());
if (day < 10){
    day = '0' + day;
}
var today = now.getFullYear() + '-' + month + '-' + day;

//$(function() {
$( document ).ready(function() {
console.log('sun.js');
        
    $.ajax({
            url: window.location.origin+'/data/proxy.php?url=http://api.yr.no/weatherapi/sunrise/1.0/?lat=48.492;lon=5.8252;date=' + today,
            type: 'GET',
            async: true,
            dataType: 'xml',
            success: function(sundata){
                window['sunrise'] = sundata.getElementsByTagName('sun')[0].attributes[0].value;
                window['sunset'] = sundata.getElementsByTagName('sun')[0].attributes[1].value;
                window['moonrise'] = sundata.getElementsByTagName('moon')[0].attributes[1].value;
                window['moonset'] = sundata.getElementsByTagName('moon')[0].attributes[2].value;
            }
    });
    
});
