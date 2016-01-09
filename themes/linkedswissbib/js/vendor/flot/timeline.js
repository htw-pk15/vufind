/*
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

        //});
/*
axis.min = data[0] - 0.5;
axis.max = data[data.length] - 0.5;
*/

$(function() {

    // Shim allowing us to get the state of the check-box on jQuery versions
    // prior to 1.6, when prop was added.  The reason we don't just use attr
    // is because it doesn't work in jQuery versions 1.9.x and later.

    // TODO: Remove this once Flot's minimum supported jQuery reaches 1.6.
    if (typeof $.fn.prop != 'function') {
        $.fn.prop = $.fn.attr;
    }
    var colors = ["#0D785B", "#0D785B", "#0D785B", "#0D785B", "#0D785B", "#0D785B", "#0D785B"];
    var options = {
        series: {

            bars: {
                show: true,
                lineWidth: 0,
                barWidth: 0.6,
                align: "center",
                fill: .75,
                zero: false

            },
            shadowSize: 0,

        },


        xaxis: {
            mode: "categories",
            tickLength: 0,
            tickDecimals: 0,
            //ticks: [2000, 2002],
            zero: true,
            //panRange: [data[0], data[data.length]]
        },


        colors: colors,

        yaxis: {
            min: 0
        },
        grid: {
            hoverable: true,
            clickable: true
        },
        selection: {
            color: ["transparent"],
            mode: "x",
            minSize: 0

        }
    };

    var placeholder = $("#placeholder");

    placeholder.bind("plotselected", function (event, ranges) {

        $("#selection").text(ranges.xaxis.from.toFixed(1) + " to " + ranges.xaxis.to.toFixed(1));

        var zoom = $("#zoom").prop("checked");

        if (zoom) {
            $.each(plot.getXAxes(), function (_, axis) {
                var opts = axis.options;
                opts.min = ranges.xaxis.from;
                opts.max = ranges.xaxis.to;
            });
            plot.setupGrid();
            plot.draw();
            plot.clearSelection();
        }
    });

    placeholder.bind("plotunselected", function (event) {
        $("#selection").text("");
    });

    var plot = $.plot(placeholder, data, options);


    //Funktion für den auswahlaufheben Button
    $("#auswahlaufheben").click(function () {
        //Alle Arrays werden geleert
        filtern = [];
        change = [];


        //Das Barchart wird neu aufgerufen, mit der Variablen Options
        $.plot(placeholder, data, options);

    });


    /*Funktion für den select year button
    $("#setSelection").click(function () {
        plot.setSelection({
            xaxis: {
                from: 2001.5,
                to: 2002.5
            }
        });
    });
*/

    //Funktion für den Klick auf den Balken
    placeholder.bind("plotclick", function (event, pos, obj) {
        // falls nicht ein Objekt angeklickt wurde, passiert nichts
        if (!obj) {
            return;
        }

        // Test an welcher Position steht der Balken, der angeklickt wurde

        for (i = 0; i < data.length; i++) {
            if (i == filtern[filtern.length - 1]) {
            //if (data[i]["data"][0][0] == filtern[filtern.length - 1]) {
                change.push(i);
            }
        }

        //die Variable colors wird leer damit sie neu gfüllt werden kann
        colors = [];


        // überprüfung welche Balken müssen farbig werden
        for (i = 0; i < data.length; i++) {
            for (k = 0; k < change.length; k++) {
                // Falls der Balken angeklickt wird
                if (i === change[k]) {
                    colors[i] = "#2EFEF7";
                }
                // Variante, damit Balken, die bereits eine helle Farbe haben nicht wieder dunkel werden
                else if (colors[i] == "#2EFEF7") {
                    continue
                }
                // Balken, die noch nicht angeklickt bleiben dunkel
                else {
                    colors[i] = "#0D785B";
                }

            }
        }
        //alert(change);


        //barchart wird neu initalisiert
        //funktioniert nicht mit Variabel options, Balken bleiben dunkel

        $.plot(placeholder, data, {
            series: {
                bars: {
                    show: true,
                    lineWidth: 0,
                    barWidth: 0.6,
                    align: "center",
                    fill: .75
                },
                shadowSize: 0,

            },

            xaxis: {
                mode: "categories",
                tickLength: 0,
                tickDecimals: 0
            },

            colors: colors,

            yaxis: {
                min: 0
            },

            grid: {
                hoverable: true,
                clickable: true
            },

            selection: {
                color: ["transparent"],
                mode: "x",
                minSize: 0
            }
        });

        /* Filtern der Suchergebnisse */

        /* Variable: String, der an die URL anzuhängen ist */

        var urlString = "&daterange[]=publishDate&publishDatefrom=";
        for (i = 0; i < change.length; i++) {
            dataPos = change[i];
            jahr = data[dataPos]["data"][0][0];
            urlString += jahr + "&publishDateto=" + jahr;
        }

        var request = new XMLHttpRequest();
        //URL of current page plus filter
        wholeUrl = window.location.href + urlString;
        request.open('GET', window.location.href + urlString, true);
        request.onreadystatechange = function() {
            if ((request.readyState === 4) && (request.status === 200)) {
                //alert(request.responseText);
                json = JSON.parse(request.responseText) ;
                alert(json);
            }
        }

        request.send();

    });

    window.onresize = function(event) {
        $.plot(placeholder, data, {
            series: {
                bars: {
                    show: true,
                    lineWidth: 0,
                    barWidth: 0.6,
                    align: "center",
                    fill: .75
                },
                shadowSize: 0,

            },

            xaxis: {
                mode: "categories",
                tickLength: 0,
                tickDecimals: 0
            },

            colors: colors,

            yaxis: {
                min: 0
            },

            grid: {
                hoverable: true,
                clickable: true
            },

            selection: {
                color: ["transparent"],
                mode: "x",
                minSize: 0
            }
        });

    }

});