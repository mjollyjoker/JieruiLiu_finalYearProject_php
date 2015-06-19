<?php

    function headerBegin($title){
        print '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">';
        print '     <title>'.$title.'</title>';
    }

    function importPackage_general(){
        print '  <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
                <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
                <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
                <script src="http://code.highcharts.com/highcharts.js"></script>
                <script src="http://code.highcharts.com/modules/drilldown.js"></script>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>';
    }

    function css_General(){
        
    }

    function navigationBar(){
        print '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand"><span>Melbourne</span></a>
            </div>
            <div>
              <ul class="nav navbar-nav">
                <li><a href="general.html"><span class="image glyphicon glyphicon glyphicon-th-large"></span><span class="navcontent">General</span></a></li>
                <li><a href="crime_category.html"><span class="image glyphicon glyphicon-list-alt"></span><span class="navcontent">Category</span></a></li>
                <li><a href="trendline.html"><span class="image glyphicon glyphicon-stats"></span><span class="navcontent">Trendline</span></a></li>
                <li><a href="compare.html"><span class="image glyphicon glyphicon-transfer"></span><span class="navcontent">Compare</span></a></li>
                <li><a href="heatmap.html"><span class="image glyphicon glyphicon-fire"></span><span class="navcontent">Heatmap</span></a></li>
                <li><a href="choropleth.html"><span class="image glyphicon glyphicon-globe"></span><span class="navcontent">Choropleth</span></a></li>
              </ul>
            </div>
          </div>
        </nav>';
    }

?>