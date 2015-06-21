<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $melbCrimeAgstPersonAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords_against_person'));
    $dataMelbCrimeAgstPerson = curlGetData($melbCrimeAgstPersonAddr);
    $melbCrimeAgstPropertyAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords_against_property'));
    $dataMelbCrimeAgstProperty = curlGetData($melbCrimeAgstPropertyAddr);
    $melbDrugOffenceAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords_drug_offence'));
    $dataMelbDrugOffence = curlGetData($melbDrugOffenceAddr);
    $melbCrimeOtherAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords_crime_other'));
    $dataMelbCrimeOther = curlGetData($melbCrimeOtherAddr);
    $phxCrimeAgstPersonAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords_against_person'));
    $dataPhxCrimeAgstPerson = curlGetData($phxCrimeAgstPersonAddr);
    $phxCrimeAgstPropertyAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords_against_property'));
    $dataPhxCrimeAgstProperty = curlGetData($phxCrimeAgstPropertyAddr);
    $phxDrugOffenceAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords_drug_offence'));
    $dataPhxDrugOffence = curlGetData($phxDrugOffenceAddr);
    $phxCrimeOtherAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords_crime_other'));
    $dataPhxCrimeOther = curlGetData($phxCrimeOtherAddr);
    $allKeywordsMelbAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords'));
    $dataAllKeywordsMelb = curlGetData($allKeywordsMelbAddr);
    $allKeywordsPhxAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords'));
    $dataAllKeywordsPhx = curlGetData($allKeywordsPhxAddr);
    $totalTweetsMelbAddr = join("", array($hostAddr,'unfiltered_tweets/_all_docs?limit=0'));
    $dataTotalTweetsMelb = curlGetData($totalTweetsMelbAddr);
    $totalTweetsPhxAddr = join("", array($hostAddr,'phoenix_tweets/_all_docs?limit=0'));
    $dataTotalTweetsPhx = curlGetData($totalTweetsPhxAddr);
    $sentimentMelbAddr = join("", array($hostAddr,'unfiltered_tweets/_design/keywords/_view/allKeywords?group_level=1'));
    $dataSentimentMelb = curlGetData($sentimentMelbAddr);
    $sentimentPhxAddr = join("", array($hostAddr,'phoenix_tweets/_design/keywords/_view/allKeywords?group_level=1'));
    $dataSentimentPhx = curlGetData($sentimentPhxAddr);

    headBegin('Compare');
    importPackage_compare();
    css_general();
    headEnd();
    navigationBar();

    print '<div class="main">
        <div class="lefttab">
            <ul id="myTab" class="nav nav-pills nav-stacked">
                <li id="mainChart" class="active"><a href="#tab_main" data-toggle="tab"><span class="figure">Summary</span></a></li>
                <li id="sentiment"><a href="#tab_sentiment" data-toggle="tab"><span class="figure">Sentiment</span></a></li>
                <li id="categories"><a href="#tab_categories" data-toggle="tab"><span class="figure">Crime Category</span></a></li>
            </ul>
        </div>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="tab_main">
                <div id="container" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
            </div>
            <div class="tab-pane fade" id="tab_sentiment">
                <div id="container0" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
            </div>
            <div class="tab-pane fade" id="tab_categories">
                  <div id="container1" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
                  <input type="checkbox" onclick="removeOther()" name="removeOther" value="show"><span style="color: white">Remove other crime</span>
            </div>
        </div>
    </div>';

    print '<script>
    var dataRaw_melbCrimeAgstPerson = '.$dataMelbCrimeAgstPerson.';
    var dataRaw_melbCrimeAgstProperty = '.$dataMelbCrimeAgstProperty.';
    var dataRaw_melbDrugOffence = '.$dataMelbDrugOffence.';
    var dataRaw_melbCrimeOther = '.$dataMelbCrimeOther.';
    var dataRaw_phxCrimeAgstPerson = '.$dataPhxCrimeAgstPerson.';
    var dataRaw_phxCrimeAgstProperty = '.$dataPhxCrimeAgstProperty.';
    var dataRaw_phxDrugOffence = '.$dataPhxDrugOffence.';
    var dataRaw_phxCrimeOther = '.$dataPhxCrimeOther.';
    var dataRaw_melbAllKeywords = '.$dataAllKeywordsMelb.';
    var dataRaw_phxAllKeywords = '.$dataAllKeywordsPhx.';
    var dataRaw_melbTotalTweets = '.$dataTotalTweetsMelb.';
    var dataRaw_phxTotalTweets = '.$dataTotalTweetsPhx.';
    var dataRaw_melbSentiment = '.$dataSentimentMelb.';
    var dataRaw_phxSentiment = '.$dataSentimentPhx.';

    var crimeData = [[],[]];
    var totalKeywords = [];
    var totalTweets = [];
    var sentimentData = [[],[]];
    var crimeCateTitles = ["Crime against person", "Crime against property", "Drug offence", "Other crime"];
    var removeOthers = false;

    $(function() {
        getAgstPersonMelb(dataRaw_melbCrimeAgstPerson);
        getAgstPropertyMelb(dataRaw_melbCrimeAgstProperty);
        getDrugOffenceMelb(dataRaw_melbDrugOffence);
        getOtherCrimeMelb(dataRaw_melbCrimeOther);
        getAgstPersonPhx(dataRaw_phxCrimeAgstPerson);
        getAgstPropertyPhx(dataRaw_phxCrimeAgstProperty);
        getDrugOffencePhx(dataRaw_phxDrugOffence);
        getOtherCrimePhx(dataRaw_phxCrimeOther);
        getAllKeywordsMelb(dataRaw_melbAllKeywords);
        getAllKeywordsPhx(dataRaw_phxAllKeywords);
        getTotalTweetsMelb(dataRaw_melbTotalTweets);
        getTotalTweetsPhx(dataRaw_phxTotalTweets);
        getSentimentMelb(dataRaw_melbSentiment);
        getSentimentPhx(dataRaw_phxSentiment);
        drawMainChart();
        
        function getAgstPersonMelb(data){
            crimeData[0].push(data.rows[0].value);
        }

        function getAgstPropertyMelb(data){
            crimeData[0].push(data.rows[0].value);
        }

        function getDrugOffenceMelb(data){
            crimeData[0].push(data.rows[0].value);
        }

        function getOtherCrimeMelb(data){
            crimeData[0].push(data.rows[0].value);
        }

        function getAgstPersonPhx(data){
            crimeData[1].push(data.rows[0].value);
        }

        function getAgstPropertyPhx(data){
            crimeData[1].push(data.rows[0].value);
        }

        function getDrugOffencePhx(data){
            crimeData[1].push(data.rows[0].value);
        }

        function getOtherCrimePhx(data){
            crimeData[1].push(data.rows[0].value);
        }

        function getAllKeywordsMelb(data){
            totalKeywords.push(data.rows[0].value);
            for (var i = 0; i < crimeData[0].length; i++){
                crimeData[0][i] = Number((crimeData[0][i]/data.rows[0].value*100).toFixed(2));
            }
        }

        function getAllKeywordsPhx(data){
            totalKeywords.push(data.rows[0].value);
            for (var i = 0; i < crimeData[1].length; i++){
                crimeData[1][i] = Number((crimeData[1][i]/data.rows[0].value*100).toFixed(2));
            }
        }

        function getTotalTweetsMelb(data){
            totalTweets.push(data.total_rows);
        }

        function getTotalTweetsPhx(data){
            totalTweets.push(data.total_rows);
        }

        function getSentimentMelb(data){
            for (var i = 0; i < data.rows.length; i ++){
                sentimentData[0].push(Number((data.rows[i].value/totalKeywords[0]*100).toFixed(2)));
            }
        }

        function getSentimentPhx(data){
            for (var i = 0; i < data.rows.length; i ++){
                sentimentData[1].push(Number((data.rows[i].value/totalKeywords[1]*100).toFixed(2)));
            }
        }

        function drawMainChart(){
            $("#container").highcharts({
                chart: {
                    type: "column"
                },
                title: {
                    text: "Crime tweets percentage"
                },
                xAxis: {
                    categories: ["Melbourne", "Phoenix"]
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: "Melbourne vs Phoenix"
                    }
                },
                tooltip: {
                    pointFormat: \'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>\',
                    shared: true
                },
                plotOptions: {
                    column: {
                        stacking: "percent"
                    }
                },
                series: [{
                    name: "Other Tweets",
                    data: [Number(((totalTweets[0]-totalKeywords[0])/totalTweets[0]*100).toFixed(2)), Number(((totalTweets[1]-totalKeywords[1])/totalTweets[1]*100).toFixed(2))]
                }, {
                    name: "Crime related Tweets",
                    data: [Number((totalKeywords[0]/totalTweets[0]*100).toFixed(2)), Number((totalKeywords[1]/totalTweets[1]*100).toFixed(2))]
                }]
            });
        }

        $("#sentiment").click(function(){
            $("#container0").highcharts({
                chart: {
                    type: "column"
                },
                title: {
                    text: "Crime related sentiment compare"
                },
                xAxis: {
                    categories: ["Negative", "Neutral", "Positive"]
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: "Sentiment"
                    }
                },
                tooltip: {
                    formatter: function () {
                        return "<b>" + this.x + "</b><br/>" +
                            this.series.name + ": " + this.y + "%<br/>";
                    }
                },
                plotOptions: {
                    column: {
                        stacking: "normal"
                    }
                },
                series: [{
                    name: "Melbourne",
                    data: sentimentData[0],
                    stack: "male"
                }, {
                    name: "Phoenix",
                    data: sentimentData[1],
                    stack: "male1"
                }]
            });
        });

        $("#categories").click(function(){
            drawCrimeCategoryChart(crimeCateTitles, crimeData[0], crimeData[1]);
        });
    });

        function drawCrimeCategoryChart(titleArray, data1, data2){
              $("#container1").highcharts({
                  chart: {
                      type: "column"
                  },
                  title: {
                      text: "Crime category compare"
                  },
                  xAxis: {
                      categories: titleArray
                  },
                  yAxis: {
                      allowDecimals: false,
                      min: 0,
                      title: {
                          text: "Sentiment"
                      }
                  },
                  tooltip: {
                      formatter: function () {
                          return "<b>" + this.x + "</b><br/>" +
                              this.series.name + ": " + this.y + "<br/>";
                      }
                  },
                  plotOptions: {
                      column: {
                          stacking: "normal"
                      }
                  },
                  series: [{
                      name: "Melbourne",
                      data: data1,
                      stack: "male"
                  }, {
                      name: "Phoenix",
                      data: data2,
                      stack: "male1"
                  }]
              });
        }

        function removeOther(){
            removeOthers = !removeOthers;
            if(removeOthers){
              drawCrimeCategoryChart(crimeCateTitles.slice(0,3), crimeData[0].slice(0,3), crimeData[1].slice(0,3));
            }else{
              drawCrimeCategoryChart(crimeCateTitles, crimeData[0], crimeData[1]);
            }
        }
    </script>';
    print '</body>';
    print '</html>';
?>