<?php 

$myFile = fopen("busStopsInfoJson.txt", "r");
$busStopsIdsTxt = fread($myFile, filesize("busStopsInfoJson.txt"));
fclose($myFile);
$arr = json_decode($busStopsIdsTxt, true);

foreach($arr as $arrays){
  $busStopsIds[] = $arrays['busstopId'];
  $coordinatesLat[] = $arrays['location']['coordinates'][1];
  $coordinatesLon[] = $arrays['location']['coordinates'][0];
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
 	<title></title>
 </head>
 <body style="width: 100% height: 100%; margin: 0; padding: 0;">
  <div style="style=width: 100vw; height: 100vh;" id="map"></div>

  <script type="text/javascript">



  	var myIcon = L.icon({
    iconUrl: 'includes/images/Bus_stop_symbol.png',
    iconSize: [27.8, 25.6],
});

        var iconBus = L.icon({
    iconUrl: 'includes/images/bus-icon.png',
    iconSize: [27.8*2, 25.6*2],
});

      var map = L.map('map').setView([-34.872972, -56.14629063], 13);


  L.tileLayer.wms(
    'http://geoserver.montevideo.gub.uy/geoserver/gwc/service/wms?', {
        maxZoom: 18,
        layers: 'stm_carto_basica',
        format: 'image/png',
        transparent: true,
        version: '1.3.0',
        tiled: true,
        srs: 'EPSG:3857',
        attribution: "Intendencia de Montevideo"
    }).addTo(map);

  map.locate({setView: true});

  function onLocationFound(e) {
    var radius = e.accuracy;

    var marker = L.marker(e.latlng).addTo(map)
        .bindPopup("Te encuentras a " + radius + " metros a la redonda.").openPopup();
    clickCircle = L.circle(e.latlng, radius).addTo(map);

    marker.getPopup().on('remove', function() {
           map.removeLayer(clickCircle);
        });  

        marker.getPopup().on('add', function() {
           clickCircle = L.circle(e.latlng, radius).addTo(map);
        });  
    }

map.on('locationfound', onLocationFound);

var markerStops = [];
var popUpStops = [];
var latlng = [];
var shelterMarkers = new L.FeatureGroup();
var busesMarkers = new L.FeatureGroup();

<?php 
    for($i=0;$i<count($busStopsIds);$i++){
      ?>
      markerStops[<?php echo($i); ?>] = L.marker([<?php echo($coordinatesLat[$i]); ?>, <?php echo($coordinatesLon[$i]); ?>], {icon: myIcon,autoPan: false}).on('click', onClick);
      markerStops[<?php echo($i); ?>].myID = <?php echo($i); ?>;
      markerStops[<?php echo($i); ?>].busStopID = <?php echo($busStopsIds[$i]); ?>;
      
      latlng[<?php echo($i); ?>] = L.latLng(<?php echo($coordinatesLat[$i]); ?>, <?php echo($coordinatesLon[$i]); ?>);
      
      <?php
    }


    ?>
   var timeoutID = {};
    function onClick(e){ 
      clickedMarkers.clearLayers();
       var text = "Lineas: ";
       var idBus = [];
       var lonBus = [];
       var latBus = [];
      var request = new XMLHttpRequest();
      request.onreadystatechange = function() {
          if(request.readyState == 4 && request.status == 200) {
              //alert(request.responseText);
              text+=request.responseText;
              text+="<br>";
              //console.log(text);
                var requestNext = new XMLHttpRequest();
                requestNext.onreadystatechange = function() { 
                  if(requestNext.readyState == 4 && requestNext.status == 200) {
                    text+=requestNext.responseText;

                  var requestBuses = new XMLHttpRequest();
                function busesInterval(){
                requestBuses.onreadystatechange = function() {
                  if(requestBuses.readyState == 4 && requestBuses.status == 200) {
                    busesMarkers.clearLayers();
                    response = requestBuses.responseText;
                    decoded = JSON.parse(response);
                    for(let i = 0; i < Object.keys(decoded).length;i++){
                      index = "bus"+i.toString();
                      markerBus = L.marker([decoded[index].lat, decoded[index].lon], {icon: iconBus,autoPan: false}).bindTooltip(decoded[index].busLine+" - "+decoded[index].subline,{permanent: true, direction: 'top'});
                      busesMarkers.addLayer(markerBus);
                      busesMarkers.setZIndex(1000);
                      map.addLayer(busesMarkers);
                    }
                   popupVariable(e.target.myID,text,e.target.busStopID);
                }
              }
              requestBuses.open("GET","buses.php?id="+e.target.busStopID,true);
              requestBuses.send();

              timeoutID = setTimeout(busesInterval, 10000);
            }
              busesInterval();
                }
              }
              requestNext.open("GET","nextBuses.php?id="+e.target.busStopID,true);
              requestNext.send();
          }
      }
         request.open("GET","busStopsLines.php?id="+e.target.busStopID,true);
       request.send();

    }

popVar=-1;
clicked = false;
var popUpStop;
var clickedMarkers = new L.FeatureGroup();

    function popupVariable(varPop,text,busID){
      popVar = varPop;
      clicked = true;
      clickedMarkers.addLayer(markerStops[popVar]);
      shelterMarkers.removeLayer(markerStops[popVar]);
      map.addLayer(clickedMarkers);
      map.removeLayer(shelterMarkers);

      console.log(popUpStop);

      if(popUpStop === undefined || popUpStop.isPopupOpen() == false){
        popUpStop = markerStops[popVar].bindPopup("<b>Parada número: "+busID+"</b><br>"+text).openPopup();
      }


      markerStops[popVar].getPopup().on('remove', function() {
          clearTimeout(timeoutID);
            
            clicked = false;
            clickedMarkers.removeLayer(markerStops[popVar]);
            shelterMarkers.addLayer(markerStops[popVar]);
            map.removeLayer(clickedMarkers);
            map.removeLayer(busesMarkers);
            if (zoom >= 17){   
              map.addLayer(shelterMarkers);
            }
          });  
    };

    


  


  map.on('moveend', () => {
    bounds = map.getBounds();
    if(clicked != true){
      for(let i=0;i<latlng.length;i++){
          if(bounds.contains(latlng[i])){
            shelterMarkers.addLayer(markerStops[i]);
          }
          else{
              shelterMarkers.removeLayer(markerStops[i]);
          }
        }  
      }
     
})

    map.on('zoomend', function() {
      zoom = map.getZoom();
      if(clicked!=true){
          if (zoom >= 17){   
              map.addLayer(shelterMarkers);
          }
          else {
                map.removeLayer(shelterMarkers);
            }        
      }

});




</script>
 </body>
 </html>