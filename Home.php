<?php
    include 'myLib.php';
    $hostAddr = 'localhost:5984/';
    $monthAddr = join("", array($hostAddr,'combined/_design/general/_view/created_month?group_level=2'));
    $dataMonth = curlGetData($monthAddr);
    $weekAddr = join("", array($hostAddr,'combined/_design/general/_view/created_week?group_level=2'));
    $dataWeek = curlGetData($weekAddr);
    $dayAddr = join("", array($hostAddr,'combined/_design/general/_view/created_day?group_level=2'));
    $dataDay = curlGetData($dayAddr);
    $hourAddr = join("", array($hostAddr,'combined/_design/general/_view/created_hour?group_level=2'));
    $dataHour = curlGetData($hourAddr);
    $langAddr = join("", array($hostAddr,'combined/_design/general/_view/language?group_level=2'));
    $dataLang = curlGetData($langAddr);

    headBegin('General');
    importPackage_home();
    css_general();
    headEnd();
    navigationBar();
    print '<div class="main">
      <div class="lefttab">
         <ul id="myTab" class="nav nav-pills nav-stacked">
            <li id="heatmap" class="active"><a href="#home" data-toggle="tab"><span class="figure">Crime Tweets Count(Month)</span></a></li>
            <li id="piechart"><a href="#ios" data-toggle="tab"><span class="figure">Crime Tweets Count(Weekday)</span></a></li>
            <li id="columnchart"><a href="#jmeter" data-toggle="tab"><span class="figure">Crime Tweets Count(Hour)</span></a></li>
            <li id="linechart"><a href="#ejb" data-toggle="tab"><span class="figure">Language Distributions</span></a></li>
         </ul>
      </div>
      <div id="myTabContent" class="tab-content">
         <div class="tab-pane fade in active" id="home">
            <div id="container" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
         </div>
         <div class="tab-pane fade" id="ios">
            <div id="container0" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
         </div>
         <div class="tab-pane fade" id="jmeter">
            <div id="container1" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
         </div>
         <div class="tab-pane fade" id="ejb">
            <div id="container2" style="margin-top: 80px; width: 900px; height: 500px; float: left"></div>
         </div>
      </div>
    </div>';

    print '<script>
        $(function () {
            var dataRaw_Month = '.$dataMonth.';
            var dataRaw_Week = '.$dataWeek.';
            var dataRaw_Day = '.$dataDay.';
            var dataRaw_Hour = '.$dataHour.';
            var dataRaw_Lang = '.$dataLang.';
            var month = [];
            var week = [];
            var day = [];
            var hour = [];
            var languageData = [{id: "other",
                                name: "Other languages",
                                data: []
                                }];
            var mainPie = [];
            var englishData;
            var otherLangTotal = 0;

            parseData_Month(dataRaw_Month);
            parseData_Week(dataRaw_Week);
            parseData_Day(dataRaw_Day);
            parseData_Hour(dataRaw_Hour);
            parseData_Lang(dataRaw_Lang);
            drawMonthChart();

            function parseData_Month(data){
                for(var i = 0; i < data.rows.length; i++){
                    month.push(data.rows[i].value);
                }
            }

            function parseData_Week(data){
                for(var i = 0; i < data.rows.length; i++){
                    week.push(data.rows[i].value);
                }
            }

            function parseData_Day(data){
                for(var i = 0; i < data.rows.length; i++){
                    day.push(data.rows[i].value);
                }
            }

            function parseData_Hour(data){
                for(var i = 0; i < data.rows.length; i++){
                    hour.push(data.rows[i].value);
                }
            }

            function parseData_Lang(data) {
                for(var i = 0; i < data.rows.length; i++){
                    if (data.rows[i].key == "en"){
                        englishData = [data.rows[i].key, data.rows[i].value];
                    } else {
                        languageData[0].data.push([
                            data.rows[i].key,
                            data.rows[i].value
                        ]);
                        otherLangTotal += data.rows[i].value;
                    }
                }
                mainPie.push({
                    name : englishData[0],
                    y : englishData[1]/(englishData[1]+otherLangTotal)*100,
                    drilldown: null
                });
                mainPie.push({
                    name : "Other",
                    y : otherLangTotal/(englishData[1]+otherLangTotal)*100,
                    drilldown: "other"
                });
                for (var i = 0; i < languageData[0].data.length; i ++){
                    languageData[0].data[i][1] = languageData[0].data[i][1]/otherLangTotal*100;
                }
            }

            function drawMonthChart(){
                $("#container").highcharts({
                    chart: {
                        type: "column"
                    },
                    title: {
                        text: "Crime Tweets count (month)"
                    },
                    xAxis: {
                        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
                    },
                    yAxis: {
                        title: {
                            text: "Number of tweets"
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: "bold",
                                color: (Highcharts.theme && Highcharts.theme.textColor) || "gray"
                            }
                        }
                    },
                    legend: {
                        align: "right",
                        x: -30,
                        verticalAlign: "top",
                        y: 25,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || "white",
                        borderColor: "#CCC",
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return "<b>" + this.x + "</b><br/>" +
                                this.series.name + ": " + this.y + "<br/>" +
                                "Total: " + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: "normal",
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || "white",
                                style: {
                                    textShadow: "0 0 3px black"
                                }
                            }
                        }
                    },
                    series: [{
                        name: "1",
                        data: [month[2], month[5], month[8], month[11], month[14], month[17], month[20], month[23], month[26], month[29], month[32], month[35]]
                    }, {
                        name: "0",
                        data: [month[1], month[4], month[7], month[10], month[13], month[16], month[19], month[22], month[25], month[28], month[31], month[34]]
                    }, {
                        name: "-1",
                        data: [-month[0], -month[3], -month[6], -month[9], -month[12], -month[15], -month[18], -month[21], -month[24], -month[27], -month[30], -month[33]]
                    },{
                        type: "spline",
                        name: "Total",
                        data: [month[0] + month[1] + month[2],
                        month[3] + month[4] + month[5],
                        month[6] + month[7] + month[8],
                        month[9] + month[10] + month[11],
                        month[12] + month[13] + month[14],
                        month[15] + month[16] + month[17],
                        month[18] + month[19] + month[20],
                        month[21] + month[22] + month[23],
                        month[24] + month[25] + month[26],
                        month[27] + month[28] + month[29],
                        month[30] + month[31] + month[32],
                        month[33] + month[34] + month[35]
                        ],
                        marker: {
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: "white"
                        }
                    }]
                });
            }

            $("#piechart").click(function(){

                $("#container0").highcharts({
                    chart: {
                        type: "column"
                    },
                    title: {
                        text: "Crime Tweets count (weekday)"
                    },
                    xAxis: {
                        categories: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
                    },
                    yAxis: {
                        title: {
                            text: "Number of tweets"
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: "bold",
                                color: (Highcharts.theme && Highcharts.theme.textColor) || "gray"
                            }
                        }
                    },
                    legend: {
                        align: "right",
                        x: -30,
                        verticalAlign: "top",
                        y: 25,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || "white",
                        borderColor: "#CCC",
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return "<b>" + this.x + "</b><br/>" +
                                this.series.name + ": " + this.y + "<br/>" +
                                "Total: " + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: "normal",
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || "white",
                                style: {
                                    textShadow: "0 0 3px black"
                                }
                            }
                        }
                    },
                    series: [{
                        name: "1",
                        data: [week[2], week[5], week[8], week[11], week[14], week[17], week[20]]
                    }, {
                        name: "0",
                        data: [week[1], week[4], week[7], week[10], week[13], week[16], week[19]]
                    }, {
                        name: "-1",
                        data: [-week[0], -week[3], -week[6], -week[9], -week[12], -week[15], -week[18]]
                    },{
                        type: "spline",
                        name: "Total",
                        data: [week[0] + week[1] + week[2],
                        week[3] + week[4] + week[5],
                        week[6] + week[7] + week[8],
                        week[9] + week[10] + week[11],
                        week[12] + week[13] + week[14],
                        week[15] + week[16] + week[17],
                        week[18] + week[19] + week[20]
                        ],
                        marker: {
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: "white"
                        }
                    }]
                });

            });

            $("#columnchart").click(function(){
                $("#container1").highcharts({
                    chart: {
                        type: "column"
                    },
                    title: {
                        text: "Crime Tweets count (hour)"
                    },
                    xAxis: {
                        categories: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"]
                    },
                    yAxis: {
                        title: {
                            text: "Number of tweets"
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: "bold",
                                color: (Highcharts.theme && Highcharts.theme.textColor) || "gray"
                            }
                        }
                    },
                    legend: {
                        align: "right",
                        x: -30,
                        verticalAlign: "top",
                        y: 25,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || "white",
                        borderColor: "#CCC",
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function () {
                            return "<b>" + this.x + "</b><br/>" +
                                this.series.name + ": " + this.y + "<br/>" +
                                "Total: " + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: "normal",
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || "white",
                                style: {
                                    textShadow: "0 0 3px black"
                                }
                            }
                        }
                    },
                    series: [{
                        name: "1",
                        data: [hour[2], hour[5], hour[8], hour[11], hour[14], hour[17], hour[20], hour[23], hour[26], hour[29], hour[32], hour[35],
                        hour[38], hour[41], hour[44], hour[47], hour[50], hour[53], hour[56], hour[59], hour[62], hour[65], hour[68], hour[71]]
                    }, {
                        name: "0",
                        data: [hour[1], hour[4], hour[7], hour[10], hour[13], hour[16], hour[19], hour[22], hour[25], hour[28], hour[31], hour[34],
                        hour[37], hour[40], hour[43], hour[46], hour[49], hour[52], hour[55], hour[58], hour[61], hour[64], hour[67], hour[70]]
                    }, {
                        name: "-1",
                        data: [-hour[0], -hour[3], -hour[6], -hour[9], -hour[12], -hour[15], -hour[18], -hour[21], -hour[24], -hour[27], -hour[30], -hour[33],
                        -hour[36], -hour[39], -hour[42], -hour[45], -hour[48], -hour[51], -hour[54], -hour[57], -hour[60], -hour[63], -hour[66], -hour[69]]
                    },{
                        type: "spline",
                        name: "Total",
                        data: [hour[0] + hour[1] + hour[2],
                        hour[3] + hour[4] + hour[5],
                        hour[6] + hour[7] + hour[8],
                        hour[9] + hour[10] + hour[11],
                        hour[12] + hour[13] + hour[14],
                        hour[15] + hour[16] + hour[17],
                        hour[18] + hour[19] + hour[20],
                        hour[21] + hour[22] + hour[23],
                        hour[24] + hour[25] + hour[26],
                        hour[27] + hour[28] + hour[29],
                        hour[30] + hour[31] + hour[32],
                        hour[33] + hour[34] + hour[35],
                        hour[36] + hour[37] + hour[38],
                        hour[39] + hour[40] + hour[41],
                        hour[42] + hour[43] + hour[44],
                        hour[45] + hour[46] + hour[47],
                        hour[48] + hour[49] + hour[50],
                        hour[51] + hour[52] + hour[53],
                        hour[54] + hour[55] + hour[56],
                        hour[57] + hour[58] + hour[59],
                        hour[60] + hour[61] + hour[62],
                        hour[63] + hour[64] + hour[65],
                        hour[66] + hour[67] + hour[68],
                        hour[69] + hour[70] + hour[71],
                        ],
                        marker: {
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: "white"
                        }
                    }]
                });
            });

            $("#linechart").click(function (){
                $("#container2").highcharts({
                    chart: {
                        type: "pie"
                    },
                    title: {
                        text: "Language distributions"
                    },
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                format: "{point.name}: {point.y:.1f}%"
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
                        series: languageData
                    }
                });
            });
             
        });
    </script>';
    print '</body>';
    print '</html>';
?>