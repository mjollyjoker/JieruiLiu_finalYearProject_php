<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $crimeAgstPersonAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_against_person?group_level=9'));
    $dataCrimeAgstPerson = curlGetData($crimeAgstPersonAddr);
    $crimeAgstPropertyAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_against_property?group_level=9'));
    $dataCrimeAgstProperty = curlGetData($crimeAgstPropertyAddr);
    $drugOffenceAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_drug_offence?group_level=9'));
    $dataDrugOffence = curlGetData($drugOffenceAddr);
    $crimeOtherAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_crime_other?group_level=9'));
    $dataCrimeOther = curlGetData($crimeOtherAddr);
    $clusterCentreAddr = join("", array($hostAddr,'cluster_centre/_all_docs?include_docs=true'));
    $dataClusterCentre = curlGetData($clusterCentreAddr);

    headBegin('Heatmap');
    importPackage_heatmap();
    css_maps();
    headEnd();
    navigationBar();
    heatmapCanvas();
    print '<script>
    var dataRaw_crimeAgstPerson = '.$dataCrimeAgstPerson.';
    var dataRaw_crimeAgstProperty = '.$dataCrimeAgstProperty.';
    var dataRaw_drugOffence = '.$dataDrugOffence.';
    var dataRaw_crimeOther = '.$dataCrimeOther.';
    var dataRaw_clusterCentre = '.$dataClusterCentre.';

    var map;
    // 2D array content
    // [0] tweet coordintes : google.maps.LatLng
    // [1] tweet text : string
    // [2] heatmap layer : google.maps.visualization.HeatmapLayer
    var crimeAgainstPerson = [[],[],[]]
    var crimeAgainstProperty = [[],[],[]]
    var crimeDrugOffence = [[],[],[]]
    var crimeOther = [[],[],[]]
    var clusterCentre = [[],[]]
    var fullHeatmap;
    var markerLabelAgstPeron = []
    var markerLabelAgstProperty = []
    var markerLabelDrugOffence = []
    var markerLabelCrimeOther = []
    var markerLabelClusterCentre = []
    var showTweetsOn = false;
    var showClusterOn = false;
    var currentDataSet = 0;

    parseData_crimeAgainstPerson(dataRaw_crimeAgstPerson);
    parseData_crimeAgainstProperty(dataRaw_crimeAgstProperty);
    parseData_crimeDrugOffence(dataRaw_drugOffence);
    parseData_crimeOther(dataRaw_crimeOther);
    parseData_clusterCentre(dataRaw_clusterCentre);

    function parseData_crimeAgainstPerson(data) {
        for(var i = 0; i < data.rows.length; i++){
            myCoord = data.rows[i].key[1].coordinates;
            crimeAgainstPerson[0].push(new google.maps.LatLng(myCoord[1],myCoord[0]));
            crimeAgainstPerson[1].push(data.rows[i].key[3]);
        }
    }

    function parseData_crimeAgainstProperty(data) {
        for(var i = 0; i < data.rows.length; i++){
            myCoord = data.rows[i].key[1].coordinates;
            crimeAgainstProperty[0].push(new google.maps.LatLng(myCoord[1],myCoord[0]));
            crimeAgainstProperty[1].push(data.rows[i].key[3]);
        }
    }

    function parseData_crimeDrugOffence(data) {
        for(var i = 0; i < data.rows.length; i++){
            myCoord = data.rows[i].key[1].coordinates;
            crimeDrugOffence[0].push(new google.maps.LatLng(myCoord[1],myCoord[0]));
            crimeDrugOffence[1].push(data.rows[i].key[3]);
        }
    }

    function parseData_crimeOther(data) {
        for(var i = 0; i < data.rows.length; i++){
            myCoord = data.rows[i].key[1].coordinates;
            crimeOther[0].push(new google.maps.LatLng(myCoord[1],myCoord[0]));
            crimeOther[1].push(data.rows[i].key[3]);
        }
    }

    function parseData_clusterCentre(data) {
        for(var i = 0; i < data.rows.length; i++){
            myCoord = data.rows[i].doc.coordinates;
            clusterCentre[0].push(new google.maps.LatLng(myCoord[1],myCoord[0]));
            clusterCentre[1].push(data.rows[i].doc.count.toString() + " tweets belong to this cluster");
        }
    }

    function initialize() {
        var melbourneCoor = new google.maps.LatLng(-37.8151381,144.9961886)
        var mapOptions = {
            zoom: 12,
            center: melbourneCoor,
            mapTypeId: google.maps.MapTypeId.SATELLITE,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            panControl: false
        };

        map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

        var pointArray1 = new google.maps.MVCArray(crimeAgainstPerson[0]);
        crimeAgainstPerson[2] = new google.maps.visualization.HeatmapLayer({
            data: pointArray1,
            maxIntensity: Math.ceil(crimeAgainstPerson[0].length/500)
        });

        var pointArray2 = new google.maps.MVCArray(crimeAgainstProperty[0]);
        crimeAgainstProperty[2] = new google.maps.visualization.HeatmapLayer({
            data: pointArray2,
            maxIntensity: Math.ceil(crimeAgainstProperty[0].length/500)
        });

        var pointArray3 = new google.maps.MVCArray(crimeDrugOffence[0]);
        crimeDrugOffence[2] = new google.maps.visualization.HeatmapLayer({
            data: pointArray3,
            maxIntensity: Math.ceil(crimeDrugOffence[0].length/500)
        });

        var pointArray4 = new google.maps.MVCArray(crimeOther[0]);
        crimeOther[2] = new google.maps.visualization.HeatmapLayer({
            data: pointArray4,
            maxIntensity: Math.ceil(crimeOther[0].length/500)
        });

        allData = crimeAgainstPerson[0].concat(crimeAgainstProperty[0]);
        allData = allData.concat(crimeDrugOffence[0]);
        allData = allData.concat(crimeOther[0]);
        var pointArray5 = new google.maps.MVCArray(allData);
        fullHeatmap = new google.maps.visualization.HeatmapLayer({
            data: pointArray5,
            maxIntensity: Math.ceil(allData.length/600)
        });
        fullHeatmap.setMap(map);

        markerLoop(crimeAgainstPerson, markerLabelAgstPeron, "#ffffff");
        markerLoop(crimeAgainstProperty, markerLabelAgstProperty, "#ffffff");
        markerLoop(crimeDrugOffence, markerLabelDrugOffence, "#ffffff");
        markerLoop(crimeOther, markerLabelCrimeOther, "#ffffff");
        markerLoop(clusterCentre, markerLabelClusterCentre, "#ff0000");
    }

    function markerLoop(inputArray, storage, colorChoice){
        var number = Math.ceil(inputArray[0].length/100);
        for (var i = 0; i < inputArray[0].length; i ++){
            if (i % number == 0){
                var markerLabelPair = generateMarkers(inputArray[0][i], inputArray[1][i], colorChoice);
                storage.push(markerLabelPair);
            }
        }
    }

    function generateMarkers(latLngs, titleStrings, colorChoice){
        var marker = new google.maps.Marker({
            position: latLngs,
            map: map,
            visible: false,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillOpacity: 0.6,
                fillColor: colorChoice,
                strokeOpacity: 0.8,
                strokeColor: "#000000",
                strokeWeight: 2.0, 
                scale: 5 //pixels
            }
        });

        var label = new MarkerWithLabel({
            position: new google.maps.LatLng(0,0),
            draggable: false,
            raiseOnDrag: false,
            map: map,
            labelContent: titleStrings,
            labelAnchor: new google.maps.Point(-20, 20),
            labelClass: "labels", // the CSS class for the label
            labelStyle: {opacity: 1.0},
            icon: "http://placehold.it/1x1",
            visible: false
        });

        google.maps.event.addListener(marker, "mouseover", function(event) {
            label.setPosition(event.latLng);
            label.setVisible(true);
        });

        google.maps.event.addListener(marker, "mouseout", function(event) {
            label.setVisible(false);
        });

        return [marker, label];
    }

    function toggleHeatmapFull() {
        currentDataSet = 0;
        fullHeatmap.setMap(map);
        crimeAgainstPerson[2].setMap(null);
        crimeAgainstProperty[2].setMap(null);
        crimeDrugOffence[2].setMap(null);
        crimeOther[2].setMap(null);
        if(showTweetsOn){
            toggleMarkerFull(true);
        }else{
            toggleMarkerFull(false);
        }
    }

    function toggleHeatmapAgstPerson() {
        currentDataSet = 1;
        fullHeatmap.setMap(null);
        crimeAgainstPerson[2].setMap(map);
        crimeAgainstProperty[2].setMap(null);
        crimeDrugOffence[2].setMap(null);
        crimeOther[2].setMap(null);
        toggleMarkerFull(false);
        if(showTweetsOn){
            toggleMarkerAgstPerson(true);
        }
    }

    function toggleHeatmapAgstProperty() {
        currentDataSet = 2;
        fullHeatmap.setMap(null);
        crimeAgainstPerson[2].setMap(null);
        crimeAgainstProperty[2].setMap(map);
        crimeDrugOffence[2].setMap(null);
        crimeOther[2].setMap(null);
        toggleMarkerFull(false);
        if(showTweetsOn){
            toggleMarkerAgstProperty(true);
        }
    }

    function toggleHeatmapDrugOffence() {
        currentDataSet = 3;
        fullHeatmap.setMap(null);
        crimeAgainstPerson[2].setMap(null);
        crimeAgainstProperty[2].setMap(null);
        crimeDrugOffence[2].setMap(map);
        crimeOther[2].setMap(null);
        toggleMarkerFull(false);
        if(showTweetsOn){
            toggleMarkerDrugOffence(true);
        }
    }

    function toggleHeatmapCrimeOther() {
        currentDataSet = 4;
        fullHeatmap.setMap(null);
        crimeAgainstPerson[2].setMap(null);
        crimeAgainstProperty[2].setMap(null);
        crimeDrugOffence[2].setMap(null);
        crimeOther[2].setMap(map);
        toggleMarkerFull(false);
        if(showTweetsOn){
            toggleMarkerCrimeOther(true);
        }
    }

    function toggleMarkerFull(onOff) {
        toggleMarkerAgstPerson(onOff);
        toggleMarkerAgstProperty(onOff);
        toggleMarkerDrugOffence(onOff);
        toggleMarkerCrimeOther(onOff);
    }

    function toggleMarkerAgstPerson(onOff) {
        for (var i = 0; i < markerLabelAgstPeron.length; i++){
            markerLabelAgstPeron[i][0].setVisible(onOff);
        }
    }

    function toggleMarkerAgstProperty(onOff) {
        for (var i = 0; i < markerLabelAgstProperty.length; i++){
            markerLabelAgstProperty[i][0].setVisible(onOff);
        }
    }

    function toggleMarkerDrugOffence(onOff) {
        for (var i = 0; i < markerLabelDrugOffence.length; i++){
            markerLabelDrugOffence[i][0].setVisible(onOff);
        }
    }

    function toggleMarkerCrimeOther(onOff) {
        for (var i = 0; i < markerLabelCrimeOther.length; i++){
            markerLabelCrimeOther[i][0].setVisible(onOff);
        }
      }

    function toggleMarkerCluster(onOff){
        for (var i = 0; i < markerLabelClusterCentre.length; i++){
            markerLabelClusterCentre[i][0].setVisible(onOff);
        }
    }

    function showTweets(){
        showTweetsOn = !showTweetsOn;
        switch(currentDataSet){
            case 0:
                toggleMarkerFull(showTweetsOn);
                break;
            case 1:
                toggleMarkerAgstPerson(showTweetsOn);
                break;
            case 2:
                toggleMarkerAgstProperty(showTweetsOn);
                break;
            case 3:
                toggleMarkerDrugOffence(showTweetsOn);
                break;
            case 4:
                toggleMarkerCrimeOther(showTweetsOn);  
                break;
        }
    }

    function showCluster(){
        showClusterOn = !showClusterOn;
        toggleMarkerCluster(showClusterOn);
    }

    google.maps.event.addDomListener(window, "load", initialize);

    </script>';
    print '</body>';
    print '</html>';
?>