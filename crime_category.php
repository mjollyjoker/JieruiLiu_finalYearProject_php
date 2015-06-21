<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $crimeAgstPersonAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_against_person?group_level=1'));
    $dataCrimeAgstPerson = curlGetData($crimeAgstPersonAddr);
    $crimeAgstPropertyAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_against_property?group_level=1'));
    $dataCrimeAgstProperty = curlGetData($crimeAgstPropertyAddr);
    $drugOffenceAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_drug_offence?group_level=1'));
    $dataDrugOffence = curlGetData($drugOffenceAddr);
    $otherCrimeAddr = join("", array($hostAddr,'combined/_design/keywords/_view/allKeywords_crime_other?group_level=1'));
    $dataOtherCrime = curlGetData($otherCrimeAddr);

    headBegin('Crime category');
    importPackage_crime_category();
    css_general();
    headEnd();
    navigationBar();
    print '<div id="container" style="margin-top: 120px; width: 900px; height: 500px"></div>';
    print '<script>
        $(function () {
            var dataRaw_crimeAgstPerson = '.$dataCrimeAgstPerson.';
            var dataRaw_crimeAgstProperty = '.$dataCrimeAgstProperty.';
            var dataRaw_drugOffence = '.$dataDrugOffence.';
            var dataRaw_otherCrime = '.$dataOtherCrime.'; 
            var crimeData = [{      
                                id: "crimeAgstPerson",
                                name: "Crime Against Person",
                                data: []
                            },{
                                id: "crimeAgstProperty",
                                name: "Crime Against Property",
                                data: []
                            },{
                                id: "drugOffence",
                                name: "Drug Offence",
                                data: []
                            },{
                                id: "otherCrime",
                                name: "Other Crime",
                                data: []
                            }];
            var mainPie = [];
            var totalTweets = 0;

            parseData_crimeAgstPerson(dataRaw_crimeAgstPerson);
            parseData_crimeAgstProperty(dataRaw_crimeAgstProperty);
            parseData_drugOffence(dataRaw_drugOffence);
            parseData_otherCrime(dataRaw_otherCrime);
            drawChart();

            function parseData_crimeAgstPerson(data) {
                var subTotal = 0;
                for(var i = 0; i < data.rows.length; i++){
                    var sentiment = "neutral";
                    sentiment = (data.rows[i].key[0] == -1 ? "negative" : "neutral");
                    if (sentiment == "neutral"){
                        sentiment = (data.rows[i].key[0] == 1 ? "positive" : "neutral");
                    }
                    crimeData[0].data.push([
                        sentiment,
                        data.rows[i].value,
                        subTotal += data.rows[i].value
                    ]);
                }
                mainPie.push({
                    name : "Crime Against Person",
                    y : subTotal,
                    drilldown: "crimeAgstPerson"
                });
                for (var i = 0; i < crimeData[0].data.length; i ++){
                    crimeData[0].data[i][1] = crimeData[0].data[i][1]/subTotal*100;
                }
                totalTweets += subTotal;
            }

            function parseData_crimeAgstProperty(data) {
                var subTotal = 0;
                for(var i = 0; i < data.rows.length; i++){
                    var sentiment = "neutral";
                    sentiment = (data.rows[i].key[0] == -1 ? "negative" : "neutral");
                    if (sentiment == "neutral"){
                        sentiment = (data.rows[i].key[0] == 1 ? "positive" : "neutral");
                    }
                    crimeData[1].data.push([
                        sentiment,
                        data.rows[i].value,
                        subTotal += data.rows[i].value
                    ]);
                }
                mainPie.push({
                    name : "Crime Against Property",
                    y : subTotal,
                    drilldown: "crimeAgstProperty"
                });
                for (var i = 0; i < crimeData[1].data.length; i ++){
                    crimeData[1].data[i][1] = crimeData[1].data[i][1]/subTotal*100;
                }
                totalTweets += subTotal;
            }

            function parseData_drugOffence(data) {
                var subTotal = 0;
                for(var i = 0; i < data.rows.length; i++){
                    var sentiment = "neutral";
                    sentiment = (data.rows[i].key[0] == -1 ? "negative" : "neutral");
                    if (sentiment == "neutral"){
                        sentiment = (data.rows[i].key[0] == 1 ? "positive" : "neutral");
                    }
                    crimeData[2].data.push([
                        sentiment,
                        data.rows[i].value,
                        subTotal += data.rows[i].value
                    ]);
                }
                mainPie.push({
                    name : "Drug Offence",
                    y : subTotal,
                    drilldown: "drugOffence"
                });
                for (var i = 0; i < crimeData[2].data.length; i ++){
                    crimeData[2].data[i][1] = crimeData[2].data[i][1]/subTotal*100;
                }
                totalTweets += subTotal;
            }

            function parseData_otherCrime(data) {
                var subTotal = 0;
                for(var i = 0; i < data.rows.length; i++){
                    var sentiment = "neutral";
                    sentiment = (data.rows[i].key[0] == -1 ? "negative" : "neutral");
                    if (sentiment == "neutral"){
                        sentiment = (data.rows[i].key[0] == 1 ? "positive" : "neutral");
                    }
                    crimeData[3].data.push([
                        sentiment,
                        data.rows[i].value,
                        subTotal += data.rows[i].value
                    ]);
                }
                mainPie.push({
                    name : "Other Crime",
                    y : subTotal,
                    drilldown: "otherCrime"
                });
                for (var i = 0; i < crimeData[3].data.length; i ++){
                    crimeData[3].data[i][1] = crimeData[3].data[i][1]/subTotal*100;
                }
                totalTweets += subTotal;
            }

            function drawChart(){
                for (var i = 0; i < mainPie.length; i++){
                    mainPie[i].y = mainPie[i].y/totalTweets*100;
                }

                // Create the chart
                $("#container").highcharts({
                    chart: {
                        type: "pie"
                    },
                    title: {
                        text: "Crime category distribution"
                    },
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                format: \'{point.name}: {point.y:.1f}%\'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: \'<span style="font-size:11px">{series.name}</span><br>\',
                        pointFormat: \'<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>\'
                    },
                    series: [{
                        name: "Brands",
                        colorByPoint: true,
                        data: mainPie
                    }],
                    drilldown: {
                        series: crimeData
                    }
                });
            }
        });
    </script>';
    print '</body>';
    print '</html>';
?>