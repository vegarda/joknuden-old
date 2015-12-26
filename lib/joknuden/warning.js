'use strict';

var now = new Date();
var today = now.getFullYear() + '-' + (now.getMonth()+1) + '-' + now.getDate() + '/';

function alertSchema(warningText, warningLevel, warningHeader){
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
    alertDiv.style.cssText = 'position: relative; overflow: hidden;';
    if (warningLevel == 2){
        alertDiv.className = alertDiv.className + ' alert-warning';
    }
    else if (warningLevel == 3){
        alertDiv.className = alertDiv.className + ' alert-amber';
        //alertDiv.className = alertDiv.className + ' alert-warning';
    }
    else if (warningLevel == 4){
        alertDiv.className = alertDiv.className + ' alert-danger';
    }
    dismissButton.appendChild(dismissButtonSpan1);
    dismissButton.appendChild(dismissButtonSpan2);
    alertDiv.appendChild(dismissButton);
	
	var iconDiv = document.createElement('div');
	iconDiv.className = "warning-icon-div";
	var warningIcon = document.createElement('i');
	warningIcon.className = "warning-icon wi";
	iconDiv.appendChild(warningIcon);
	alertDiv.appendChild(iconDiv);
	
	var warningTextSpan = document.createElement('span');
	warningTextSpan.className = "warning-span";
	
	if (typeof warningHeader !== 'undefined'){
		var warningStrong = document.createElement('strong');
		warningStrong.textContent = warningHeader;
		warningTextSpan.appendChild(warningStrong);
	}

	warningTextSpan.appendChild(document.createTextNode(warningText));
	
	alertDiv.appendChild(warningTextSpan);

	var warningYrSpan = document.createElement('span');
	warningYrSpan.appendChild(document.createTextNode("Forecast from yr.no"));
    warningYrSpan.className = 'warning-credits';
    //warningYrSpan.style.cssText = 'font-size: 10px; float: right; position: relative; top: 5px;';
	
	alertDiv.appendChild(warningYrSpan);
	
    return alertDiv;
	
}

$( document ).ready(function() {
console.log('warning.js');
    
	/*
	 * Flom
	 */
    $.ajax({
        url: window.location.origin+'/data/flood.php',
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function(nvedata){
            $(nvedata).each(function(index, value){
                if(value['ActivityLevel'] > 1){
                    var ActivityLevel = value['ActivityLevel'];
                    var MainText = value['MainText'];
                    var WarningText = value['WarningText'];

					var ValidFrom = new Date(value['ValidFrom']);

                    var warning = alertSchema(WarningText, ActivityLevel, value['ValidFrom'].slice(0,10)  + " - Flomvarsling for Hå kommune: ");
					var icon = warning.getElementsByTagName("i")[0];
					icon.className = icon.className + " wi-flood";
                    document.getElementById('warning').appendChild(warning);
                    document.getElementById('warning').parentNode.style.display = 'block';
                }
            })
        }
    });

	/*
	 * OBS varsel
	 */
    $.ajax({
        url: window.location.origin+'/data/obs.php',
        type: 'GET',
        async: true,
        dataType: 'xml',
        success: function(obsdata){
			$(obsdata).find('time').each(function(index, value){
                $(value).find("location[name*='Rogaland']").each(function(index, value){
                    var warningText = $(value).find('in')[0].textContent;
					var location = $(value).find('header')[0].textContent;
					
                    var warning = alertSchema(warningText, 3, "OBS-varsel for " + location + ": ");
					var icon = warning.getElementsByTagName("i")[0];
					icon.className = icon.className + " wi-small-craft-advisory";
                    document.getElementById('warning').appendChild(warning);
                    document.getElementById('warning').parentNode.style.display = 'block';
                });
				/*
                $(value).find("location[id='0505']").each(function(index, value){
                    var warningText = $(value).find('in')[0].textContent;
					var location = $(value).find('header')[0].textContent;

                    var warning = alertSchema(warningText, 3, "OBS-varsel for " + location + ": ");
					var icon = warning.getElementsByTagName("i")[0];
					icon.className = icon.className + " wi-small-craft-advisory";
                    document.getElementById('warning').appendChild(warning);
                    document.getElementById('warning').parentNode.style.display = 'block';
                });*/
            });
        }
    });

	/*
	 * Skogbrann
	 */
    $.ajax({
        url: window.location.origin+'/data/forestfire.php',
        type: 'GET',
        async: true,
        dataType: 'xml',
        success: function(forestfiredata){
            $(forestfiredata).find('time').each(function(index, value){
                // limit to one day warning
                if (index == 0){
                    var dangerIndex = $(value).find("location[stationid='44300']").find("forest-fire[unit='danger-index']")[0].attributes[1].value;
                    if (dangerIndex > 70){
                        var warning = alertSchema("Meget stor skogbrannfare", 4);
                    }
                    else if (dangerIndex >= 40){
                        var warning = alertSchema("Stor skogbrannfare", 3);
                    }
                    else if (dangerIndex >= 20){
                        var warning = alertSchema("Skogbrannfare", 2);
                    };
					if (dangerIndex >= 20){
						var icon = warning.getElementsByTagName("i")[0];
						icon.className = icon.className + " wi-fire";
                        document.getElementById('warning').appendChild(warning);
                        document.getElementById('warning').parentNode.style.display = 'block';
					}
                }
            });
        }
    });

	/*
	 * Stormvarsel
	 */
    $.ajax({
        url: window.location.origin+'/data/gale.php',
        type: 'GET',
        async: true,
        dataType: 'xml',
        success: function(galedata){

			var warnings = $(galedata).find("location[name^='Åna-Sira - ']");
			
			if (warnings.length > 0){
				var firstWarning = warnings.find("in")[0].textContent;
				var firstLocation = warnings.find("header")[0].textContent;
				var beaufort =  warnings.find("monitor")[0].attributes["beaufort"].value; //<monitor beaufort="8"/>
				console.log(beaufort);
            
				var warning = alertSchema(firstWarning, 3, "Kulingvarsel for " + firstLocation + ": ");
				var icon = warning.getElementsByTagName("i")[0];
				if (beaufort == 12){
					icon.className = icon.className + " wi-hurricane-warning";
				}
				else if (beaufort >= 10){
					icon.className = icon.className + " wi-storm-warning";
				}
				else if (beaufort >= 8){
					icon.className = icon.className + " wi-gale-warning";
				}
				else{
					icon.className = icon.className + " wi-small-craft-advisory";
				}
				document.getElementById('warning').appendChild(warning);
				document.getElementById('warning').parentNode.style.display = 'block';
			};
        }
    });
});