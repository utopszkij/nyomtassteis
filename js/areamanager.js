/**
* areamanages category form event handlers and google map function
*/
// "  u s e strict";
      var map = false; // container for gooogle map objektum 
	  var poligonMap; // poligon drawing google object
	  var central_lat0 = 47; // default map central pont
	  var central_lng0 = 20;
	  var central_lat = central_lat0; // actual central pont
	  var central_lng = central_lng0;
      
	  /**
	  * draw default quadruple around the center
	  */	
	  function drawDefPoligon() {
		console.log('draw def poligon');
		
		// calculate default size for areaType
		var type = jQuery('#type').val();
		var x = 0.2;
		if (type == 'continent') {
			x = 1.5;
		}
		if (type == 'country') {
			x = 0.8;
		}
		
		// create pdefault poligon
        const coords = [
          {
            lat: central_lat - x,
            lng: central_lng - (2*x)
          },
          {
            lat: central_lat + x,
            lng: central_lng - (2*x)
          },
          {
            lat: central_lat + x,
            lng: central_lng + (2*x) 
          },
          {
            lat: central_lat - x,
            lng: central_lng + (2*x)
          },
          {
            lat: central_lat - x,
            lng: central_lng - (2*x)
          }
          
        ]; 
        
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
		console.log('draw poligon');
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
			} else {
				drawDefPoligon();
			}
		} else {
			drawDefPoligon();
		}
	  }	

	  /**
	  * google map init , ezt a google.js hivja be, egészen korán, amikor még
	  * a jQuery esemény kezelők nem aktivak
	  */
      function initMap() {
	      console.log('init map');
		  console.log(map);
		  if (!map) {
		        // create map object 
		        map = new google.maps.Map(document.getElementById("map"), {
			      zoom: 7,
		          center: {
		            lat: central_lat0,
		            lng: central_lng0
		          },
		          mapTypeId: "terrain"
		        }); 
	      
			  /* adjust map center and poligon nem jó mert még nincsenek esemény kezelők
			  if ((jQuery('#poligon').val() == '[]') | (jQuery('#poligon').val() == '')){
				// set map position and draw default poligon from "central"|"name" value
				console.log('def poligon');
				jQuery('#central').change();
			  } else {
				console.log('actual poligon');
				drawPoligon();
			  }	
			  */
		}
      }
      

	  // jQUery event handler form loaded
      jQuery(function() {
	
	// meg kellene oldani, hogy a megfelelő két képernyőn (edittag, addtag) fusson
	
		  /**
	      * Javascript DOM event handler, change "type". adjust show/hide arrea info division
		  * if show adjust poligon	
		  */
		  jQuery('#type').change(function() {
			console.log('type change ',jQuery('#type').val());
				var type = jQuery('#type').val();	
				if (type == 'notarea') {
					jQuery('#areamanagerAreaInfo').hide();
				} else {
					if ((jQuery('#poligon').val() == '[]') | (jQuery('#poligon').val() == '')) {
						// set map position and draw default poligon from "central"|"name" value
						console.log('def poligon');
						jQuery('#central').change();
					} else {
						console.log('draw actual poligon');
						drawPoligon();
					}
					if (type == 'continent') {
						map.setZoom(3);
					} else 	if (type == 'country') {
						map.setZoom(5);
					} else {
						map.setZoom(7);
					}	
					jQuery('#areamanagerAreaInfo').show();
				}
		  });	

		  /**
		   * DOM event handler name change. if center == '' copy name into center
		  */	
		  jQuery('#tag-name').change(function() {
				if (jQuery('#central').val() == '') {
					jQuery('#central').val(jQuery('#tag-name').val());
					jQuery('#central').change();
				}
		  });	

		  /**
	      * Javascript DOM event handler, click function for delPoligon Button
		  */
		  jQuery('#delpoligonBtn').click(function() {
				drawDefPoligon();
		  });

	      /**
	      * javascript DOM event handler chane "central" field value. 
		  * if poligon is empty adjus map position, and draw def. poligon
	      */
	      jQuery('#central').change(function() {
			// ha még nem definiált a poligon akkor a "center" vagy a "name" mező alapján
			// set map center point, and draw defaultPoligon
			
			console.log('central change '+jQuery('#poligon').val());
			
			if ((jQuery('#poligon').val() == '[]') | (jQuery('#poligon').val() == '')) {
	    		const geocoder = new google.maps.Geocoder();
	    		var address = jQuery('#central').val();
	    		if (address == '') {
	    			address = jQuery('#tag-name').val();
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
	      });

	      // javascript DOM event handler  for form submit
		  // (sajnos a valódi formsubmit esemény kezelő már foglalt a woocommerce -ben)
		  jQuery('input:submit').mousedown(function() {
		      	console.log(poligonMap.getPath().getArray());
		      	var w = poligonMap.getPath().getArray();
		      	var j = poligonMap.getPath().length;
		      	var coords = [];
		      	for (var i = 0; i < j; i++) {
		      		coords.push({"lat":w[i].lat(), "lng":w[i].lng()});
		      	}
		      	jQuery('#poligon').val(JSON.stringify(coords));
		      	return true;
		  });
		  jQuery('input:submit').keydown(function() {
			jQuery('input:submit').mousedown();
		  });	

		  // adjust area info div show/hide from type	
		  jQuery('#type').change();
      });
      
            