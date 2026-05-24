<!DOCTYPE html>
<html>
    <head>
        <title>Place Autocomplete</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">

        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


        <style>
            #pac-container {
                padding: 5px;
            }

            .prefix {
                font-size: 20px!important;
                color: #d3a409;
                margin: 10px;
                margin-top: 6px;
            }

            .pac-card, .card {
                background-color: #ffffff;
            }

            #map {
                width: 100%;
                height: 500px;
                display: none;  
            }

            body {
                background-color: #fafafa;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <div class="pac-card" id="pac-card">

            <div id="pac-container" class="z-depth-1"  >
                <div class="input-field row"  style="width: 100%" >
                    <i class="material-icons prefix">trip_origin</i>
                    <input type="text" required  id="pac-input" class="validate" placeholder="from" >
                </div>
                <div class="input-field " style="width: 100%" >
                    <i class="material-icons prefix">map</i>
                    <input id="pac-input2" type="text" class="validate" placeholder="to" >
                </div>

            </div>
        </div>
        <div id="map" ></div>
        
        <br><br><br><br>
        <div class="locations" ></div>
        
        <div id="infowindow-content" style="display: none">
            <img src="" width="16" height="16" id="place-icon">
            <span id="place-name"  class="title"></span><br>
            <span id="place-address"></span>
        </div>

        <script>
            // This example requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

            var value1 = "";
            var value2 = "";
            var json = [];
            var autocomplete = null;
            var autocomplete2 = null;
            var center = {};
            var map = {};
            
            
                function showPosition(position) {
                    alert(); 
                    
                    $(".locations")[0].innerHTML += position.coords.latitude + "," + position.coords.longitude + "<br>";
                      
                }
                
            function initMap() {
                
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } 
                

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -33.8688, lng: 151.2195},
                    zoom: 13
                });
                
        var geocoder = new google.maps.Geocoder;
        //var infowindow = new google.maps.InfoWindow;
                
                
                setInterval(function(){
                    navigator.geolocation.getCurrentPosition(showPosition);
                }, 30000);
                
                var card = document.getElementById('pac-card');

                card.style.width = window.innerWidth + "px";
                var input = document.getElementById('pac-input');
                var input2 = document.getElementById('pac-input2');
                var types = document.getElementById('type-selector');
                var strictBounds = document.getElementById('strict-bounds-selector');

                //console.log(google.maps.ControlPosition);
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(card);

                autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete2 = new google.maps.places.Autocomplete(input2);


                autocomplete.id = 0;
                autocomplete2.id = 1;
                autocomplete.input = input;
                autocomplete2.input = input2;
                var action = function (autocomplete) {
                    var row = {};
                    row.place = autocomplete.input.value;
                    row.lng = autocomplete.lng;
                    row.lat = autocomplete.lat;

                    json[autocomplete.id] = row;
                };
                setAutoCompleteControls(autocomplete, map, action);
                setAutoCompleteControls(autocomplete2, map, action);

                // Sets a listener on a radio button to change the filter type on Places
                // Autocomplete.


                //setupClickListener('changetype-all', []);
                //setupClickListener('changetype-address', ['address']);
                //setupClickListener('changetype-establishment', ['establishment']);
                //setupClickListener('changetype-geocode', ['geocode']);

//                document.getElementById('use-strict-bounds')
//                        .addEventListener('click', function () {
//                            //console.log('Checkbox clicked! New state=' + this.checked);
//                            autocomplete.setOptions({strictBounds: this.checked});
//                            autocomplete2.setOptions({strictBounds: this.checked});
//                        });
            }

            function setAutoCompleteControls(autocomplete, map, action) {

                // Bind the map's bounds (viewport) property to the autocomplete object,
                // so that the autocomplete requests use the current map bounds for the
                // bounds option in the request.
                autocomplete.bindTo('bounds', map);
                var this_action = action;

                // Set the data fields to return when the user selects a place.
                autocomplete.setFields(
                        ['address_components', 'geometry', 'icon', 'name']);


                autocomplete.addListener('place_changed', function () {
                    var infowindow = new google.maps.InfoWindow();
                    var infowindowContent = document.getElementById('infowindow-content');
                    infowindow.setContent(infowindowContent);
                    var marker = new google.maps.Marker({
                        map: map,
                        anchorPoint: new google.maps.Point(0, -29)
                    });

                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();

                    autocomplete.lng = place.geometry.location.lng();
                    autocomplete.lat = place.geometry.location.lat();


                    this_action(autocomplete);
                    if (!place.geometry) {
                        // User entered the name of a Place that was not suggested and
                        // pressed the Enter key, or the Place Details request failed.
                        window.alert("No details available for input: '" + place.name + "'");
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);  // Why 17? Because it looks good.
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    var address = '';
                    if (place.address_components) {
                        address = [
                            (place.address_components[0] && place.address_components[0].short_name || ''),
                            (place.address_components[1] && place.address_components[1].short_name || ''),
                            (place.address_components[2] && place.address_components[2].short_name || '')
                        ].join(' ');
                    }

                    infowindowContent.children['place-icon'].src = place.icon;
                    infowindowContent.children['place-name'].textContent = place.name;
                    infowindowContent.children['place-address'].textContent = address;
                    infowindow.open(map, marker);
                });

            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzl7l4tzN1dWBVu3dL_62EkHteripsaqc&libraries=places&callback=initMap"
        async defer></script>

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
</html>