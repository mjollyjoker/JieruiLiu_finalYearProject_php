<?php

    function headBegin($title){
        print '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">';
        print '     <title>'.$title.'</title>';
    }

    function importPackage_home(){
        print ' <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
                <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
                <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
                <script src="http://code.highcharts.com/highcharts.js"></script>
                <script src="http://code.highcharts.com/modules/drilldown.js"></script>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>';
    }

    function importPackage_crime_category(){
        print '<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
               <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
               <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
               <script src="http://code.highcharts.com/highcharts.js"></script>
               <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>
               <script src="http://code.highcharts.com/modules/exporting.js"></script>
               <script src="http://code.highcharts.com/modules/drilldown.js"></script>
               <link rel="stylesheet" type="text/css" href="style_general.css"/>';
    }

    function importPackage_trendline(){
        print ' <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
                <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
                <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>
                <link rel="stylesheet" type="text/css" href="style_general.css"/>';
    }

    function importPackage_compare(){
        print ' <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
                <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
                <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
                <script src="http://code.highcharts.com/highcharts.js"></script>
                <script src="http://code.highcharts.com/modules/drilldown.js"></script>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>
                <link rel="stylesheet" type="text/css" href="style_general.css"/>';
    }

    function importPackage_heatmap(){
        print ' <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
                <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
                <script src="https://maps.googleapis.com/maps/api/js?libraries=visualization"></script>
                <script src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.8/src/markerwithlabel.js"></script>
                <link rel="stylesheet" type="text/css" href="style_maps.css"/>';
    }

    function importPackage_choropleth(){
        print ' <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
                <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
                <script src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.5/src/markerwithlabel_packed.js"></script>
                <link rel="stylesheet" type="text/css" href="style_maps.css"/>';
    }

    function css_general(){
        print '<link rel="stylesheet" type="text/css" href="style_general.css"/>';
    }

    function css_maps(){
        print '<link rel="stylesheet" type="text/css" href="style_maps.css"/>';
    }

    function headEnd(){
        print '</head>';
        print '<body>';
    }

    function navigationBar(){
        print '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand"><span>Melbourne</span></a>
            </div>
            <div>
              <ul class="nav navbar-nav">
                <li><a href="Home.php"><span class="image glyphicon glyphicon glyphicon-th-large"></span><span class="navcontent">General</span></a></li>
                <li><a href="crime_category.php"><span class="image glyphicon glyphicon-list-alt"></span><span class="navcontent">Category</span></a></li>
                <li><a href="trendline.php"><span class="image glyphicon glyphicon-stats"></span><span class="navcontent">Trendline</span></a></li>
                <li><a href="compare.php"><span class="image glyphicon glyphicon-transfer"></span><span class="navcontent">Compare</span></a></li>
                <li><a href="heatmap.php"><span class="image glyphicon glyphicon-fire"></span><span class="navcontent">Heatmap</span></a></li>
                <li><a href="choropleth.php"><span class="image glyphicon glyphicon-globe"></span><span class="navcontent">Choropleth</span></a></li>
              </ul>
            </div>
          </div>
        </nav>';
    }

    function heatmapCanvas(){
        print ' <div id="dataset_panel_heatmap">
                    <b>Data set selection</b><br>
                    <input type="radio" onclick="toggleHeatmapAgstPerson()" name="heatmapData" value="1"> Crime Against Person<br>
                    <input type="radio" onclick="toggleHeatmapAgstProperty()" name="heatmapData" value="2"> Crime Against Property<br>
                    <input type="radio" onclick="toggleHeatmapDrugOffence()" name="heatmapData" value="3"> Drug Offence<br>
                    <input type="radio" onclick="toggleHeatmapCrimeOther()" name="heatmapData" value="4"> Other Crime<br>
                    <input type="radio" onclick="toggleHeatmapFull()" name="heatmapData" value="0" checked> All<br><br>
                    <input type="checkbox" onclick="showTweets()" name="showTweets" value="show">Show random tweets<br>
                    <input type="checkbox" onclick="showCluster()" name="showCluster" value="show">Show cluster centre<br>
                </div>
                <div id="map-canvas"></div>';
    }

    function choroplethCanvas(){
        print '<div id="data_display_panel"><b>Postal Area information</b><br>Hover over a Postal Area</div>
                    <div id="dataset_panel_choropleth">
                    <b>Data set selection</b><br>
                    <input type="radio" onclick="setColorGeneral()" name="choroplethType" value="0" checked> General<br>
                    <input type="radio" onclick="setColorPoliceRecord()" name="choroplethType" value="1"> Police record per 100 ppl<br>
                    <input type="radio" onclick="setColorCrimeTweets()" name="choroplethType" value="2"> Crime Tweets count per 1000 ppl<br>
                    <input type="radio" onclick="setColorPopulation()" name="choroplethType" value="3"> Population<br>
                    <input type="radio" onclick="setColorUnemployment()" name="choroplethType" value="4"> Unemployment rate<br>
                    <input type="radio" onclick="setColorAvgIncome()" name="choroplethType" value="5"> Average weekly income<br>
                    <input type="radio" onclick="setColorEduTertiery()" name="choroplethType" value="6"> Edu level Tertiery<br>
                    <input type="radio" onclick="setColorAlcoholic()" name="choroplethType" value="7"> Alcohol related tweets
                </div>
                <div id="data_legend_outer"></div>
                <div id="map-canvas"></div>';
    }

    function curlGetData($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Accept: */*'
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
?>