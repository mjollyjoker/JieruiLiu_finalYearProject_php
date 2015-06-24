<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $postcodeInforAddr = join("", array($hostAddr,'suburb_boundaries/_all_docs?include_docs=true'));
    $dataPostcodeInfor = curlGetData($postcodeInforAddr);
    $alcoholicAddr = join("", array($hostAddr,'combined/_design/keywords/_view/keyword_alcohol?group_level=1'));
    $dataAlcoholic = curlGetData($alcoholicAddr);

    headBegin('Heatmap');
    importPackage_choropleth();
    css_maps();
    headEnd();
    navigationBar();
    choroplethCanvas();
    print '<script>
        var dataRaw_postcodeInfor = '.$dataPostcodeInfor.';
        var dataRaw_alcoholic = '.$dataAlcoholic.';
        var heatmap, map;

        var pCodeCoordin = []
        var postcode = []
        var suburbName = []
        var policeRecord = []
        var tweetCount = []
        var population = []
        var avgAge = []
        var unemployment = []
        var avgIncome = []
        var eduPrimary = []
        var eduSecondary = []
        var eduTertiery = []
        var alcoholic = []

        var polygonArray = []
        var markerStringArray = []

        var policeRecordRange = [30, 20, 15, 10, 8, 5, 0]
        var crimeTweetsRange = [100, 50, 25, 10, 5, 2, 0]
        var populationRange = [50000, 20000, 10000, 5000, 2000, 1000, 0]
        var unemploymentRange = [13, 11.5, 10, 8, 6, 4, 0]
        var avgIncomeRange = [1000, 800, 700, 600, 500, 400, 0]
        var eduTertieryRange = [50, 30, 25, 20, 15, 10, 0]
        var alcoholicRange = [50, 20, 15, 10, 5, 1, 0]

        parseData_postcodeInfor(dataRaw_postcodeInfor);
        parseData_alcoholic(dataRaw_alcoholic);

        function parseData_postcodeInfor(data) {
            for(var i = 0; i < data.rows.length; i++){
                getPcodeData(data.rows[i]);
                alcoholic[i] = 0;
            }
        }

        function getPcodeData(data){
            pCodeCoordin.push(data.doc.geometry.coordinates);
            postcode.push(data.doc.properties.postcode);
            suburbName.push(data.doc.properties.name);
            policeRecord.push(data.doc.properties.yr2011_12);
            tweetCount.push(data.doc.properties.tweet_count)
            population.push(data.doc.properties.population);
            avgAge.push(data.doc.properties.averageAge);
            unemployment.push(data.doc.properties.unemployment);
            avgIncome.push(data.doc.properties.averageIncome);
            eduPrimary.push(data.doc.properties.eduPrimary);
            eduSecondary.push(data.doc.properties.eduSecondary);
            eduTertiery.push(data.doc.properties.eduTertiery);
        }

        function parseData_alcoholic(data) {
            for(var i = 0; i < data.rows.length; i++){
                var index = postcode.indexOf(data.rows[i].key[0]);
                alcoholic[index] = data.rows[i].value;
            }
        }

        function initialize() {
            var styles = [
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        { "saturation": -100 },
                        { "lightness": -8 },
                        { "gamma": 1.5 }
                    ]
                }, {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        { "saturation": -100 },
                        { "gamma": 1 },
                        { "lightness": -24 }
                    ]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "administrative",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "transit",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "road",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "administrative",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "landscape",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }, {
                    "featureType": "poi",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }
            ]
            var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});
            var melbourneCoor = new google.maps.LatLng(-37.8602828, 144.979616)
            var mapOptions = {
                zoom: 9,
                center: melbourneCoor,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, "map_style"],
                    position: google.maps.ControlPosition.RIGHT_TOP
                },
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                panControl: false
            };

            map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
            map.mapTypes.set("map_style", styledMap);
            map.setMapTypeId("map_style");

            for (var i = 0; i < pCodeCoordin.length; i++){
                var coordSet = CoordsParser(pCodeCoordin[i])
                var markerString = "<b>" + suburbName[i] + "</b><br>"
                markerString = markerString + \'<table style="width:100%"><tr><td>Postcode</td><td>\' + postcode[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Police record 2011 per 100 ppl</td><td>" + policeRecord[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Crime tweets per 1000 ppl</td><td>" + tweetCount[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Population</td><td>" + population[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Average age</td><td>" + avgAge[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Unemployment rate</td><td>" + unemployment[i] + " %</td></tr>"
                markerString = markerString + "<tr><td>Avarage income</td><td>" + avgIncome[i] + "</td></tr>"
                markerString = markerString + "<tr><td>Edu Lvl Primary</td><td>" + eduPrimary[i] + " %</td></tr>"
                markerString = markerString + "<tr><td>Edu Lvl Secondary</td><td>" + eduSecondary[i] + " %</td></tr>"
                markerString = markerString + "<tr><td>Edu Lvl Tertiery</td><td>" + eduTertiery[i] + " %</td></tr>"
                markerString = markerString + "<tr><td>Alcohol related tweets</td><td>" + alcoholic[i] + "</td></tr></table>"

                var poly = new google.maps.Polygon({
                    paths: coordSet,
                    strokeColor: "#FFFFFF",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#737373",
                    fillOpacity: 0.7,
                    map: map
                });

                polygonArray.push(poly);
                markerStringArray.push(markerString);
            }

            addPolygonListeners();
        }

        function CoordsParser(Coords){
            var listOfCoords = []
            for (var i = 0; i < Coords.length; i++){
                singleSet = Coords[i];
                listOfCoords.push(new google.maps.LatLng(singleSet[0],singleSet[1]));
            }
            return listOfCoords;
        }

        function addPolygonListeners(){
            for (var i = 0; i < polygonArray.length; i++){
                var poly = polygonArray[i];
                google.maps.event.addListener(poly, "mouseover", function(event) {
                    this.setOptions({strokeColor: "#000000"});
                    this.setOptions({strokeWeight: 4});
                    document.getElementById("data_display_panel").innerHTML = markerStringArray[polygonArray.indexOf(this)];
                });
                google.maps.event.addListener(poly, "mouseout", function(event) {
                    this.setOptions({strokeColor: "#FFFFFF"});
                    this.setOptions({strokeWeight: 1});
                    document.getElementById("data_display_panel").innerHTML = "<b>Postal Area information</b><br>Hover over a Postal Area";
                });
            }
        }

        google.maps.event.addDomListener(window, "load", initialize);

        function setColorGeneral(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:"#737373",fillOpacity:0.7})
            }
            document.getElementById("data_legend_panel").innerHTML = "";
        }

        function setColorPoliceRecord(){
            for (var i = 0; i < polygonArray.length; i++){
                var recordPer100 = policeRecord[i]/population[i]*100;
                polygonArray[i].setOptions({fillColor:getPoliceRecordColor(recordPer100),
                                          fillOpacity:0.7})
            }
            policeRecordLegend();
        }

        function setColorCrimeTweets(){
            for (var i = 0; i < polygonArray.length; i++){
                var tweetPer1000 = tweetCount[i]/population[i]*1000;
                polygonArray[i].setOptions({fillColor:getCrimeTweetsColor(tweetPer1000),
                                          fillOpacity:0.7})
            }
            crimeTweetsLenged();
        }

        function setColorPopulation(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:getPopulationColor(population[i]),
                                          fillOpacity:0.7})
            }
            populationLegend();
        }

        function setColorUnemployment(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:getUnemploymentColor(unemployment[i]),
                                          fillOpacity:0.7})
            }
            unemploymentLegend();
        }

        function setColorAvgIncome(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:getAvgIncomeColor(avgIncome[i]),
                                          fillOpacity:0.7})
            }
            avgIncomeLegend();
        }

        function setColorEduTertiery(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:getEduTertieryColor(eduTertiery[i]),
                                          fillOpacity:0.9})
            }
            eduTertieryLegend();
        }

        function setColorAlcoholic(){
            for (var i = 0; i < polygonArray.length; i++){
                polygonArray[i].setOptions({fillColor:getAlcoholicColor(alcoholic[i]),
                                          fillOpacity:0.9})
            }
            alcoholicLegend();
        }

        function getPoliceRecordColor(d) {
            return d > policeRecordRange[0]  ? "#084594" :
                   d > policeRecordRange[1]  ? "#2171b5" :
                   d > policeRecordRange[2]  ? "#4292c6" :
                   d > policeRecordRange[3]  ? "#6baed6" :
                   d > policeRecordRange[4]  ? "#9ecae1" :
                   d > policeRecordRange[5]  ? "#c6dbef" :
                                                "#eff3ff";
        }

        function getCrimeTweetsColor(d) {
            return d > crimeTweetsRange[0]  ? "#b10026" :
                   d > crimeTweetsRange[1]  ? "#e31a1c" :
                   d > crimeTweetsRange[2]  ? "#fc4e2a" :
                   d > crimeTweetsRange[3]  ? "#fd8d3c" :
                   d > crimeTweetsRange[4]  ? "#feb24c" :
                   d > crimeTweetsRange[5]  ? "#fed976" :
                                               "#ffffb2";
        }

        function getPopulationColor(d) {
            return d > populationRange[0]  ? "#4a1486" :
                   d > populationRange[1]  ? "#6a51a3" :
                   d > populationRange[2]  ? "#807dba" :
                   d > populationRange[3]  ? "#9e9ac8" :
                   d > populationRange[4]  ? "#bcbddc" :
                   d > populationRange[5]  ? "#dadaeb" :
                                              "#f2f0f7";
        }

        function getUnemploymentColor(d) {
            return d > unemploymentRange[0]  ? "#7a0177" :
                   d > unemploymentRange[1]  ? "#ae017e" :
                   d > unemploymentRange[2]  ? "#dd3497" :
                   d > unemploymentRange[3]  ? "#f768a1" :
                   d > unemploymentRange[4]  ? "#fa9fb5" :
                   d > unemploymentRange[5]  ? "#fcc5c0" :
                                                "#feebe2";
        }

        function getAvgIncomeColor(d) {
            return d > avgIncomeRange[0]  ? "#8c2d04" :
                   d > avgIncomeRange[1]  ? "#cc4c02" :
                   d > avgIncomeRange[2]  ? "#ec7014" :
                   d > avgIncomeRange[3]  ? "#fe9929" :
                   d > avgIncomeRange[4]  ? "#fec44f" :
                   d > avgIncomeRange[5]  ? "#fee391" :
                                             "#ffffd4";
        }

        function getEduTertieryColor(d) {
            return d > eduTertieryRange[0]  ? "#005824" :
                   d > eduTertieryRange[1]  ? "#238b45" :
                   d > eduTertieryRange[2]  ? "#41ae76" :
                   d > eduTertieryRange[3]  ? "#66c2a4" :
                   d > eduTertieryRange[4]  ? "#99d8c9" :
                   d > eduTertieryRange[5]  ? "#ccece6" :
                                              "#edf8fb" ;
        }

        function getAlcoholicColor(d) {
            return d > alcoholicRange[0]  ? "#91003f" :
                   d > alcoholicRange[1]  ? "#ce1256" :
                   d > alcoholicRange[2]  ? "#e7298a" :
                   d > alcoholicRange[3]  ? "#df65b0" :
                   d > alcoholicRange[4]  ? "#c994c7" :
                   d >= alcoholicRange[5]  ? "#d4b9da" :
                                            "#f1eef6" ;
        }

        function policeRecordLegend() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = policeRecordRange.length-2; i >= -1 ; i--) {
                var from = policeRecordRange[i + 1];
                var to = policeRecordRange[i]-1;
                content += 
                \'<i style="background:\' + getPoliceRecordColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function crimeTweetsLenged() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = crimeTweetsRange.length-2; i >= -1 ; i--) {
                var from = crimeTweetsRange[i + 1];
                var to = crimeTweetsRange[i]-1;
                content += 
                \'<i style="background:\' + getCrimeTweetsColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function populationLegend() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = populationRange.length-2; i >= -1 ; i--) {
                var from = populationRange[i + 1];
                var to = populationRange[i]-1;
                content += 
                \'<i style="background:\' + getPopulationColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function unemploymentLegend() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = unemploymentRange.length-2; i >= -1 ; i--) {
                var from = unemploymentRange[i + 1];
                var to = unemploymentRange[i]-1;
                content += 
                \'<i style="background:\' + getUnemploymentColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function avgIncomeLegend() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = avgIncomeRange.length-2; i >= -1 ; i--) {
                var from = avgIncomeRange[i + 1];
                var to = avgIncomeRange[i]-1;
                content += 
                \'<i style="background:\' + getAvgIncomeColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function eduTertieryLegend() {
            var content = \'<div id="data_legend_panel">\';
            for (var i = eduTertieryRange.length-2; i >= -1 ; i--) {
                var from = eduTertieryRange[i + 1];
                var to = eduTertieryRange[i]-1;
                content += 
                \'<i style="background:\' + getEduTertieryColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+");
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

        function alcoholicLegend() {
        var content = \'<div id="data_legend_panel">\';
            for (var i = alcoholicRange.length-2; i >= -1 ; i--) {
                var from = alcoholicRange[i + 1];
                var to = alcoholicRange[i]-1;
                (from == to ? content += \'<i style="background:\' + getAlcoholicColor(from) + \'">__</i> \' +
                from + "<br>": content += 
                \'<i style="background:\' + getAlcoholicColor(from + 1) + \'">__</i> \' +
                from + (to ? "&ndash;" + to + "<br>": "+"));
            }
            document.getElementById("data_legend_outer").innerHTML = content + "</div>";
        }

    </script>';
    print '</body>';
    print '</html>';
?>