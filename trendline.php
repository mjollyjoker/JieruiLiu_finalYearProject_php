<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $postcodeInforAddr = join("", array($hostAddr,'suburb_boundaries/_all_docs?include_docs=true'));
    $dataPostcodeInfor = curlGetData($postcodeInforAddr);

    headBegin('Trendline');
    importPackage_trendline();
    css_general();
    headEnd();
    navigationBar();

    print '<div class="main">
      <div class="lefttab">
         <ul id="myTab" class="nav nav-pills nav-stacked">
            <li id="policeRecord" class="active"><a href="#tabMain" data-toggle="tab"><span class="figure">Police record</span></a></li>
            <li id="unemploy"><a href="#tabMain" data-toggle="tab"><span class="figure">Unemployment rate</span></a></li>
            <li id="avgAge"><a href="#tabMain" data-toggle="tab"><span class="figure">Average age</span></a></li>
            <li id="income"><a href="#tabMain" data-toggle="tab"><span class="figure">Average weekly income</span></a></li>
            <li id="eduLevel"><a href="#tabMain" data-toggle="tab"><span class="figure">Education level (Tertiary)</span></a></li>
         </ul>
      </div>
      <div id="myTabContent" class="tab-content">
         <div class="tab-pane fade in active" id="tabMain">
            <div id="container1" style="margin-top: 80px; width: 900px; height: 500px"></div>
         </div>
      </div>
    </div>';

    print '<script>
        var dataRaw_postcodeInfor = '.$dataPostcodeInfor.';
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

        parseData_postcodeInfor(dataRaw_postcodeInfor);

        function parseData_postcodeInfor(data) {
            for(var i = 0; i < data.rows.length; i++){
                getPcodeData(data.rows[i]);
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

        function drawChartPoliceRecord(){
            var dataInput = [["crime tweets per 100 ppl", "police record per 1000 ppl"]];
            var xMin = 0;
            var xMax = 0;
            var yMin = 0;
            var yMax = 0;
            for (var i = 0; i < policeRecord.length; i++){
                var x = baseLog(10, Number(tweetCount[i])/Number(population[i])*100);
                var y = baseLog(10, Number(policeRecord[i])/Number(population[i])*1000);
                xMax = (x > xMax ? x : xMax);
                xMim = (x < xMin ? x : xMin);
                yMax = (y > yMax ? y : yMax);
                yMim = (y < yMin ? y : yMin);
                var singleLine = [x, y];
                if (singleLine.indexOf(Math.log(-1)) == -1 && singleLine.indexOf(Math.log(0)) == -1 ){
                    dataInput.push(singleLine);
                }
            }
            drawChart("Police record per 1000 ppl", dataInput, xMax, xMin, yMax, yMin, "#2171b5");
        }

        function drawChartUnemploy(){
            var dataInput = [["crime tweets per 100 ppl", "unemployment rate"]];
            var xMin = 0;
            var xMax = 0;
            var yMin = 0;
            var yMax = 0;
            for (var i = 0; i < policeRecord.length; i++){
                var x = baseLog(10, Number(tweetCount[i])/Number(population[i])*100);
                var y = Number(unemployment[i]);
                xMax = (x > xMax ? x : xMax);
                xMim = (x < xMin ? x : xMin);
                yMax = (y > yMax ? y : yMax);
                yMim = (y < yMin ? y : yMin);
                var singleLine = [x, y];
                if (singleLine.indexOf(Math.log(-1)) == -1 && singleLine.indexOf(Math.log(0)) == -1 ){
                    dataInput.push(singleLine);
                }
            }
            drawChart("Unemployment rate", dataInput, xMax, xMin, yMax, yMin, "#ae017e");
        }

        function drawChartAvgAge(){
            var dataInput = [["crime tweets per 100 ppl", "average age"]];
            var xMin = 0;
            var xMax = 0;
            var yMin = 0;
            var yMax = 0;
            for (var i = 0; i < policeRecord.length; i++){
                var x = baseLog(10, Number(tweetCount[i])/Number(population[i])*100);
                var y = Number(avgAge[i]);
                xMax = (x > xMax ? x : xMax);
                xMim = (x < xMin ? x : xMin);
                yMax = (y > yMax ? y : yMax);
                yMim = (y < yMin ? y : yMin);
                var singleLine = [x, y];
                if (singleLine.indexOf(Math.log(-1)) == -1 && singleLine.indexOf(Math.log(0)) == -1 ){
                    dataInput.push(singleLine);
                }
            }
            drawChart("Average age", dataInput, xMax, xMin, yMax, yMin, "#6a51a3");
        }

        function drawChartIncome(){
            var dataInput = [["crime tweets per 100 ppl", "average weekly income"]];
            var xMin = 0;
            var xMax = 0;
            var yMin = 0;
            var yMax = 0;
            for (var i = 0; i < policeRecord.length; i++){
                var x = baseLog(10, Number(tweetCount[i])/Number(population[i])*100);
                var y = Number(avgIncome[i]);
                xMax = (x > xMax ? x : xMax);
                xMim = (x < xMin ? x : xMin);
                yMax = (y > yMax ? y : yMax);
                yMim = (y < yMin ? y : yMin);
                var singleLine = [x, y];
                if (singleLine.indexOf(Math.log(-1)) == -1 && singleLine.indexOf(Math.log(0)) == -1 ){
                    dataInput.push(singleLine);
                }
            }
            drawChart("Average weekly income", dataInput, xMax, xMin, yMax, yMin, "#cc4c02");
        }

        function drawChartEduTertiary(){
            var dataInput = [["crime tweets per 100 ppl", "education level (Tertiary)"]];
            var xMin = 0;
            var xMax = 0;
            var yMin = 0;
            var yMax = 0;
            for (var i = 0; i < policeRecord.length; i++){
                var x = baseLog(10, Number(tweetCount[i])/Number(population[i])*100);
                var y = Number(eduTertiery[i]);
                xMax = (x > xMax ? x : xMax);
                xMim = (x < xMin ? x : xMin);
                yMax = (y > yMax ? y : yMax);
                yMim = (y < yMin ? y : yMin);
                var singleLine = [x, y];
                if (singleLine.indexOf(Math.log(-1)) == -1 && singleLine.indexOf(Math.log(0)) == -1 ){
                    dataInput.push(singleLine);
                }
            }
            drawChart("Education level (Tertiary)", dataInput, xMax, xMin, yMax, yMin, "#238b45");
        }

        function baseLog(base, num){
            return Math.log(num) / Math.log(base);
        }

        function drawChart(chartTitle, dataSet, xMax, xMin, yMax, yMin, colorCode) {
            var data = google.visualization.arrayToDataTable(dataSet);

            var options = {
                title: chartTitle,
                hAxis: {title: dataSet[0][0] ,minValue: xMin, maxValue: xMax},
                vAxis: {title: dataSet[0][1] ,minValue: yMin, maxValue: yMax},
                chartArea: {width:"65%"},
                trendlines: {
                    0: {
                        type: "polynomial",
                        degree: 2,
                        visibleInLegend: true
                    }
                }
            };
            options.colors = [colorCode];

            var chartExponential = new google.visualization.ScatterChart(document.getElementById("container1"));
            chartExponential.draw(data, options);
        }

        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChartPoliceRecord);

        $("#policeRecord").click(function(){
            drawChartPoliceRecord();
        });

        $("#unemploy").click(function(){
            drawChartUnemploy();
        });

        $("#avgAge").click(function(){
            drawChartAvgAge();
        });

        $("#income").click(function (){
            drawChartIncome();
        });

        $("#eduLevel").click(function (){
            drawChartEduTertiary();
        });
    </script>';
    print '</body>';
    print '</html>';
?>