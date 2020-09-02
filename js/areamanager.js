/**
* areamanages category form event handlers and google map function
*/
"use strict";
     var map; // gooogle map objektum 
	  var poligonMap; // poligon drawing google object
	  var central_lat = 47; // map central pont
	  var central_lng = 20;

      /**
      * javascript DOM event handler chane "central" field value
      */
      function centralChange() {
		// ha még nem definiált a poligon akkor a "center" vagy a "name" mező alapján
		// set map center point, and draw defaultPoligon
		if (document.getElementById('poligon').innerHTML == '') {
    		const geocoder = new google.maps.Geocoder();
    		var address = document.getElementById('central').value;
    		if (address == '') {
    			address = document.getElementById('name').value;
    		}
    		if (address == '') {
    			address = 'Budapest';
    		}
            geocoder.geocode( { 'address': address}, function(results, status) {
                  if (status == 'OK') {
                    central_lat = results[0].geometry.location.lat();
                    central_lng = results[0].geometry.location.lng();
                    map.setCenter(results[0].geometry.location);
                  } else {
                    central_lat = 47;
                    central_lng = 20;
                    map.setCenter({"lat": central_lat, "lng": central_lng});
                  }
                  drawDefPoligon(map, central_lat, central_lng);
            });
        }
      }
      
      /**
      * javascript DOM event handler form submit
      * save adjusted poligon into "poligon" field
      */
      function formSubmit() {
      	console.log(poligonMap.getPath().getArray());
      	var w = poligonMap.getPath().getArray();
      	var j = poligonMap.getPath().length;
      	var coords = [];
      	for (var i = 0; i < j; i++) {
      		coords.push({"lat":w[i].lat(), "lng":w[i].lng()});
      	}
      	document.getElementById('poligon').innerHTML = JSON.stringify(coords);
      	return true;
      }
      
	  /**
	  * draw default quadruple around the center
	  */	
	  function drawDefPoligon(g) {
        const coords = [
          {
            lat: central_lat - 0.2,
            lng: central_lng - 0.5
          },
          {
            lat: central_lat + 0.2,
            lng: central_lng - 0.5
          },
          {
            lat: central_lat + 0.2,
            lng: central_lng + 0.5 
          },
          {
            lat: central_lat - 0.2,
            lng: central_lng + 0.5
          },
          {
            lat: central_lat - 0.2,
            lng: central_lng - 0.5
          }
          
        ]; // Construct the polygon.
        
        // if old poligon exists remove it
        if (poligonMap) {
        	poligonMap.setMap(null);
        }
         
        // draw poligon 
        poligonMap = new google.maps.Polygon({
          paths: coords,
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          editable: true
        });
        poligonMap.setMap(map);
	  }	

	  /**
	  * draw poligon from poligon textarea value
	  */
	  function drawPoligon() {
		const jsonStr = document.getElementById('poligon').innerHTML;
		if (jsonStr != '') {
			const coords = JSON.parse(jsonStr);
			if (coords.length > 0) {
    			// calculate center point
    			central_lat = 0;
    			central_lng = 0;
    			for (var i = 0; i < coords.length; i++) {
    				central_lat = central_lat + coords[i].lat;
    				central_lng = central_lng + coords[i].lng;
    			}
    			central_lat = central_lat / coords.length;
    			central_lng = central_lng / coords.length;
    			map.setCenter({"lat": central_lat, "lng": central_lng});
    			
                // if old poligon exists remove it
                if (poligonMap) {
                	poligonMap.setMap(null);
                }

                poligonMap = new google.maps.Polygon({
                  paths: coords,
                  strokeColor: "#FF0000",
                  strokeOpacity: 0.8,
                  strokeWeight: 2,
                  fillColor: "#FF0000",
                  fillOpacity: 0.35,
                  editable: true
                });
                poligonMap.setMap(map);
			}
		}
	  }	

	  /**
	  * google map init 
	  */
      function initMap() {
        // create map object in 0,0 central point
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 7,
          center: {
            lat: 47,
            lng: 20
          },
          mapTypeId: "terrain"
        }); 
      
		  // adjust map center and poligon
		  if (document.getElementById('poligon').innerHTML == '') {
			// set map osition and draw default poligon from "central"|"name" value
			centralChange();
		  } else {
			drawPoligon();
		  }	
      }
      
      jQuery(function() {
		  if (document.getElementById('map')) {
	      	initMap();
		  }
	      // set form submit event handler to edit form
	      var form = document.getElementById('edittag');
	      if (form) {
	      	form.addEventListener('submit', formSubmit);
	      }
		  // set form event handler add form
		  // sajnos itt a for submit eventet a woo használja
		  // ezért kellet a mosedown ls keydon -ra menni	
	      var form = document.getElementById('addtag');
	      if (form) {
			var submitBtn = document.getElementById('submit');
	      	submitBtn.addEventListener('mousedown', formSubmit);
	      	submitBtn.addEventListener('keydown', formSubmit);
	      }
      });
      
            