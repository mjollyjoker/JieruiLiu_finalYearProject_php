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