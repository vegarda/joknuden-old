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

	if (typeof warningHeader !== 'undefined'){
		var warningStrong = document.createElement('strong');
		warningStrong.textContent = warningHeader;
		alertDiv.appendChild(warningStrong);
	}
    alertDiv.appendChild(document.createTextNode(warningText));
    
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
            $(obsdata).find("[forecast_origin='VV_Obsvarsel']").each(function(index, value){
                $(value).find("location[name='Rogaland']").each(function(index, value){
                    var warningText = $(value).find('in')[0].textContent;

                    var warning = alertSchema(warningText, 3, "OBS-varsel for Rogaland: ");
                    document.getElementById('warning').appendChild(warning);
                    document.getElementById('warning').parentNode.style.display = 'block';
                });
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
                        document.getElementById('warning').appendChild(warning);
                        document.getElementById('warning').parentNode.style.display = 'block';
                    }
                    else if (dangerIndex >= 40){
                        var warning = alertSchema("Stor skogbrannfare", 3);
                        document.getElementById('warning').appendChild(warning);
                        document.getElementById('warning').parentNode.style.display = 'block';
                    }
                    else if (dangerIndex >= 20){
                        var warning = alertSchema("Skogbrannfare", 2);
                        document.getElementById('warning').appendChild(warning);
                        document.getElementById('warning').parentNode.style.display = 'block';
                    };
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

			var warnings = $(galedata).find("location[name='Åna-Sira - Obrestad']");
			
			if (warnings.length > 0){
				var firstWarning = warnings.find("in")[0].textContent;
            
				var warning = alertSchema(warningText, 3, "Kulingvarsel for Åna-Sira - Obrestad: ");
				document.getElementById('warning').appendChild(warning);
				document.getElementById('warning').parentNode.style.display = 'block';
			};
        }
    });
});