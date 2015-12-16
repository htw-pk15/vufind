//var data = [['1999', 100]];

$(function() {

            var timeline = $("#timeline");
            var plot = $.plot(timeline, [ data ], {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center",
                        fill:.75
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                },

                colors: ["#0D785B"]

            });

            window.onresize = function(event) {
                plot;
            }

            /*
            $("#timeline").resizable({
                maxWidth: 900,
                maxHeight: 500,
                minWidth: 450,
                minHeight: 250
            });
            */

        });

