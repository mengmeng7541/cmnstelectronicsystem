

var App = function () {

    var isMainPage = false;
    var isMapPage = false;
    var isIE8 = false;

    base_url = document.location.href.split('index.php')[0];//for global usage
	site_url = base_url+'index.php/';
	
    var handleJQVMAP = function () {

        if (!sample_data) {
            return;
        }

        var showMap = function (name) {
            jQuery('.vmaps').hide();
            jQuery('#vmap_' + name).show();
        }

        var setMap = function (name) {
            var data = {
                map: 'world_en',
                backgroundColor: null,
                borderColor: '#333333',
                borderOpacity: 0.5,
                borderWidth: 1,
                color: '#c6c6c6',
                enableZoom: true,
                hoverColor: '#3daced',
                hoverOpacity: null,
                values: sample_data,
                normalizeFunction: 'linear',
                scaleColors: ['#e8e8e8', '#b0b0b0'],
                selectedColor: '#3daced',
                selectedRegion: null,
                showTooltip: true,
                onLabelShow: function (event, label, code) {

                },
                onRegionOver: function (event, code) {
                    if (code == 'ca') {
                        event.preventDefault();
                    }
                },
                onRegionClick: function (element, code, region) {
                    var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
                    alert(message);
                }
            };

            data.map = name + '_en';
            var map = jQuery('#vmap_' + name);
            map.width(map.parent().parent().width());
            map.show();
            map.vectorMap(data);
            map.hide();
        }

        setMap("world");
        setMap("usa");
        setMap("europe");
        setMap("russia");
        setMap("germany");

        showMap("world");

        jQuery('#regional_stat_world').click(function () {
            showMap("world");
        });

        jQuery('#regional_stat_usa').click(function () {
            showMap("usa");
        });

        jQuery('#regional_stat_europe').click(function () {
            showMap("europe");
        });
        jQuery('#regional_stat_russia').click(function () {
            showMap("russia");
        });
        jQuery('#regional_stat_germany').click(function () {
            showMap("germany");
        });

        $('#region_statistics_loading').hide();
        $('#region_statistics_content').show();
    }

    var handleAllJQVMAP = function () {

        if (!sample_data) {
            return;
        }

        var setMap = function (name) {
            var data = {
                map: 'world_en',
                backgroundColor: null,
                borderColor: '#333333',
                borderOpacity: 0.5,
                borderWidth: 1,
                color: '#c6c6c6',
                enableZoom: true,
                hoverColor: '#3daced',
                hoverOpacity: null,
                values: sample_data,
                normalizeFunction: 'linear',
                scaleColors: ['#e8e8e8', '#b0b0b0'],
                selectedColor: '#3daced',
                selectedRegion: null,
                showTooltip: true,
                onRegionOver: function (event, code) {
                    //sample to interact with map
                    if (code == 'ca') {
                        event.preventDefault();
                    }
                },
                onRegionClick: function (element, code, region) {
                    //sample to interact with map
                    var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
                    alert(message);
                }
            };
            data.map = name + '_en';
            var map = jQuery('#vmap_' + name);
            map.width(map.parent().width());
            map.vectorMap(data);
        }

        setMap("world");
        setMap("usa");
        setMap("europe");
        setMap("russia");
        setMap("germany");
    }



    var handleDashboardCalendar = function () {

        if (!jQuery().fullCalendar) {
            return;
        }

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var h = {};

        if ($(window).width() <= 320) {
            h = {
                left: 'title, prev,next',
                center: '',
                right: 'today,month,agendaWeek,agendaDay'
            };
        } else {
            h = {
                left: 'title',
                center: '',
                right: 'prev,next,today,month,agendaWeek,agendaDay'
            };
        }

        $('#calendar').html("");
        $('#calendar').fullCalendar({
            header: h,
            editable: true,
            events: [{
                title: 'All Day Event',
                start: new Date(y, m, 1),
                className: 'label label-default',
            }, {
                title: 'Long Event',
                start: new Date(y, m, d - 5),
                end: new Date(y, m, d - 2),
                className: 'label label-success',
            }, {
                title: 'Repeating Event',
                start: new Date(y, m, d - 3, 16, 0),
                allDay: false,
                className: 'label label-default',
            }, {
                title: 'Repeating Event',
                start: new Date(y, m, d + 4, 16, 0),
                allDay: false,
                className: 'label label-important',
            }, {
                title: 'Meeting',
                start: new Date(y, m, d, 10, 30),
                allDay: false,
                className: 'label label-info',
            }, {
                title: 'Lunch',
                start: new Date(y, m, d, 12, 0),
                end: new Date(y, m, d, 14, 0),
                allDay: false,
                className: 'label label-warning',
            }, {
                title: 'Birthday Party',
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                allDay: false,
                className: 'label label-success',
            }, {
                title: 'Click for Google',
                start: new Date(y, m, 28),
                end: new Date(y, m, 29),
                url: 'http://google.com/',
                className: 'label label-warning',
            }]
        });

    }

    var handleCalendar = function () {

        if (!jQuery().fullCalendar) {
            return;
        }

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var h = {};

        if ($(window).width() <= 320) {
            h = {
                left: 'title, prev,next',
                center: '',
                right: 'today,month,agendaWeek,agendaDay'
            };
        } else {
            h = {
                left: 'title',
                center: '',
                right: 'prev,next,today,month,agendaWeek,agendaDay'
            };
        }

        var initDrag = function (el) {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim(el.text()) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            el.data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            el.draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0 //  original position after the drag
            });
        }

        var addEvent = function (title, priority) {
            title = title.length == 0 ? "Untitled Event" : title;
            priority = priority.length == 0 ? "default" : priority;

            var html = $('<div data-class="label label-' + priority + '" class="external-event label label-' + priority + '">' + title + '</div>');
            jQuery('#event_box').append(html);
            initDrag(html);
        }

        $('#external-events div.external-event').each(function () {
            initDrag($(this))
        });

        $('#event_add').click(function () {
            var title = $('#event_title').val();
            var priority = $('#event_priority').val();
            addEvent(title, priority);
        });

        //modify chosen options
        var handleDropdown = function () {
            $('#event_priority_chosen .chosen-search').hide(); //hide search box
            $('#event_priority_chosen_o_1').html('<span class="label label-default">' + $('#event_priority_chosen_o_1').text() + '</span>');
            $('#event_priority_chosen_o_2').html('<span class="label label-success">' + $('#event_priority_chosen_o_2').text() + '</span>');
            $('#event_priority_chosen_o_3').html('<span class="label label-info">' + $('#event_priority_chosen_o_3').text() + '</span>');
            $('#event_priority_chosen_o_4').html('<span class="label label-warning">' + $('#event_priority_chosen_o_4').text() + '</span>');
            $('#event_priority_chosen_o_5').html('<span class="label label-important">' + $('#event_priority_chosen_o_5').text() + '</span>');
        }

        $('#event_priority_chosen').click(handleDropdown);

        //predefined events
        addEvent("My Event 1", "default");
        addEvent("My Event 2", "success");
        addEvent("My Event 3", "info");
        addEvent("My Event 4", "warning");
        addEvent("My Event 5", "important");
        addEvent("My Event 6", "success");
        addEvent("My Event 7", "info");
        addEvent("My Event 8", "warning");
        addEvent("My Event 9", "success");
        addEvent("My Event 10", "default");

        $('#calendar').fullCalendar({
            header: h,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function (date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                copiedEventObject.className = $(this).attr("data-class");

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            events: [{
                title: 'All Day Event',
                start: new Date(y, m, 1),
                className: 'label label-default',
            }, {
                title: 'Long Event',
                start: new Date(y, m, d - 5),
                end: new Date(y, m, d - 2),
                className: 'label label-success',
            }, {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d - 3, 16, 0),
                allDay: false,
                className: 'label label-default',
            }, {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d + 4, 16, 0),
                allDay: false,
                className: 'label label-important',
            }, {
                title: 'Meeting',
                start: new Date(y, m, d, 10, 30),
                allDay: false,
                className: 'label label-info',
            }, {
                title: 'Lunch',
                start: new Date(y, m, d, 12, 0),
                end: new Date(y, m, d, 14, 0),
                allDay: false,
                className: 'label label-warning',
            }, {
                title: 'Birthday Party',
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                allDay: false,
                className: 'label label-success',
            }, {
                title: 'Click for Google',
                start: new Date(y, m, 28),
                end: new Date(y, m, 29),
                url: 'http://google.com/',
                className: 'label label-warning',
            }]
        });

    }

    var handleChat = function () {
        var cont = $('#chats');
        var list = $('.chats', cont);
        var form = $('.chat-form', cont);
        var input = $('input', form);
        var btn = $('.btn', form);

        var handleClick = function () {
            var text = input.val();
            if (text.length == 0) {
                return;
            }

            var time = new Date();
            var time_str = time.toString('MMM dd, yyyy HH:MM');
            var tpl = '';
            tpl += '<li class="out">';
            tpl += '<img class="avatar" alt="" src="img/avatar1.jpg"/>';
            tpl += '<div class="message">';
            tpl += '<span class="arrow"></span>';
            tpl += '<a href="#" class="name">Sumon Ahmed</a>&nbsp;';
            tpl += '<span class="datetime">at ' + time_str + '</span>';
            tpl += '<span class="body">';
            tpl += text;
            tpl += '</span>';
            tpl += '</div>';
            tpl += '</li>';

            var msg = list.append(tpl);
            input.val("");
            $('.scroller', cont).slimScroll({
                scrollTo: list.height()
            });
        }

        btn.click(handleClick);
        input.keypress(function (e) {
            if (e.which == 13) {
                handleClick();
                return false; //<---- Add this line
            }
        });
    }

    var handleClockfaceTimePickers = function () {

        if (!jQuery().clockface) {
            return;
        }

        $('#clockface_1').clockface();

        $('#clockface_2').clockface({
            format: 'HH:mm',
            trigger: 'manual'
        });

        $('#clockface_2_toggle-btn').click(function (e) {
            e.stopPropagation();
            $('#clockface_2').clockface('toggle');
        });

        $('#clockface_3').clockface({
            format: 'H:mm'
        }).clockface('show', '14:30');
    }

    var handlePortletSortable = function () {
        if (!jQuery().sortable) {
            return;
        }
        $(".sortable").sortable({
            connectWith: '.sortable',
            iframeFix: false,
            items: 'div.widget',
            opacity: 0.8,
            helper: 'original',
            revert: true,
            forceHelperSize: true,
            placeholder: 'sortable-box-placeholder round-all',
            forcePlaceholderSize: true,
            tolerance: 'pointer'
        });

    }

    var handleMainMenu = function () {
        jQuery('#sidebar .has-sub > a').click(function () {
            var last = jQuery('.has-sub.open', $('#sidebar'));
            last.removeClass("open");
            jQuery('.arrow', last).removeClass("open");
            jQuery('.sub', last).slideUp(200);
            var sub = jQuery(this).next();
            if (sub.is(":visible")) {
                jQuery('.arrow', jQuery(this)).removeClass("open");
                jQuery(this).parent().removeClass("open");
                sub.slideUp(200);
            } else {
                jQuery('.arrow', jQuery(this)).addClass("open");
                jQuery(this).parent().addClass("open");
                sub.slideDown(200);
            }
        });
    }

    var handleWidgetTools = function () {
        jQuery('.widget .tools .icon-remove').click(function () {
            jQuery(this).parents(".widget").parent().remove();
        });

        jQuery('.widget .tools .icon-refresh').click(function () {
            var el = jQuery(this).parents(".widget");
            App.blockUI(el);
            window.setTimeout(function () {
                App.unblockUI(el);
            }, 1000);
        });

        jQuery('.widget .tools .icon-chevron-down, .widget .tools .icon-chevron-up').click(function () {
            var el = jQuery(this).parents(".widget").children(".widget-body");
            if (jQuery(this).hasClass("icon-chevron-down")) {
                jQuery(this).removeClass("icon-chevron-down").addClass("icon-chevron-up");
                el.slideUp(200);
            } else {
                jQuery(this).removeClass("icon-chevron-up").addClass("icon-chevron-down");
                el.slideDown(200);
            }
        });
    }

    var handleDashboardCharts = function () {

        // used by plot functions
        var data = [];
        var totalPoints = 200;

        // random data generator for plot charts
        function getRandomData() {
            if (data.length > 0) data = data.slice(1);
            // do a random walk
            while (data.length < totalPoints) {
                var prev = data.length > 0 ? data[data.length - 1] : 50;
                var y = prev + Math.random() * 10 - 5;
                if (y < 0) y = 0;
                if (y > 100) y = 100;
                data.push(y);
            }
            // zip the generated y values with the x values
            var res = [];
            for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
            return res;
        }

        if (!jQuery.plot) {
            return;
        }

        function randValue() {
            return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
        }

        var pageviews = [
            [1, randValue()],
            [2, randValue()],
            [3, 2 + randValue()],
            [4, 3 + randValue()],
            [5, 5 + randValue()],
            [6, 10 + randValue()],
            [7, 15 + randValue()],
            [8, 20 + randValue()],
            [9, 25 + randValue()],
            [10, 30 + randValue()],
            [11, 35 + randValue()],
            [12, 25 + randValue()],
            [13, 15 + randValue()],
            [14, 20 + randValue()],
            [15, 45 + randValue()],
            [16, 50 + randValue()],
            [17, 65 + randValue()],
            [18, 70 + randValue()],
            [19, 85 + randValue()],
            [20, 80 + randValue()],
            [21, 75 + randValue()],
            [22, 80 + randValue()],
            [23, 75 + randValue()],
            [24, 70 + randValue()],
            [25, 65 + randValue()],
            [26, 75 + randValue()],
            [27, 80 + randValue()],
            [28, 85 + randValue()],
            [29, 90 + randValue()],
            [30, 95 + randValue()]
        ];
        var visitors = [
            [1, randValue() - 5],
            [2, randValue() - 5],
            [3, randValue() - 5],
            [4, 6 + randValue()],
            [5, 5 + randValue()],
            [6, 20 + randValue()],
            [7, 25 + randValue()],
            [8, 36 + randValue()],
            [9, 26 + randValue()],
            [10, 38 + randValue()],
            [11, 39 + randValue()],
            [12, 50 + randValue()],
            [13, 51 + randValue()],
            [14, 12 + randValue()],
            [15, 13 + randValue()],
            [16, 14 + randValue()],
            [17, 15 + randValue()],
            [18, 15 + randValue()],
            [19, 16 + randValue()],
            [20, 17 + randValue()],
            [21, 18 + randValue()],
            [22, 19 + randValue()],
            [23, 20 + randValue()],
            [24, 21 + randValue()],
            [25, 14 + randValue()],
            [26, 24 + randValue()],
            [27, 25 + randValue()],
            [28, 26 + randValue()],
            [29, 27 + randValue()],
            [30, 31 + randValue()]
        ];

        $('#site_statistics_loading').hide();
        $('#site_statistics_content').show();

        var plot = $.plot($("#site_statistics"), [{
            data: pageviews,
            label: "Unique Visits"
        }, {
            data: visitors,
            label: "Page Views"
        }], {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.05
                        }, {
                            opacity: 0.01
                        }]
                    }
                },
                points: {
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#eee",
                borderWidth: 0
            },
            colors: ["#A5D16C", "#FCB322", "#32C2CD"],
            xaxis: {
                ticks: 11,
                tickDecimals: 0
            },
            yaxis: {
                ticks: 11,
                tickDecimals: 0
            }
        });


        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 15,
                border: '1px solid #333',
                padding: '4px',
                color: '#fff',
                'border-radius': '3px',
                'background-color': '#333',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $("#site_statistics").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });

        //server load
        var options = {
            series: {
                shadowSize: 1
            },
            lines: {
                show: true,
                lineWidth: 0.5,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.1
                    }, {
                        opacity: 1
                    }]
                }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickFormatter: function (v) {
                    return v + "%";
                }
            },
            xaxis: {
                show: false
            },
            colors: ["#A5D16C"],
            grid: {
                tickColor: "#eaeaea",
                borderWidth: 0
            }
        };

        $('#load_statistics_loading').hide();
        $('#load_statistics_content').show();

        var updateInterval = 30;
        var plot = $.plot($("#load_statistics"), [getRandomData()], options);

        function update() {
            plot.setData([getRandomData()]);
            plot.draw();
            setTimeout(update, updateInterval);
        }
        update();
    }

    var handleCharts = function () {

        // used by plot functions
        var data = [];
        var totalPoints = 250;

        // random data generator for plot charts
        function getRandomData() {
            if (data.length > 0) data = data.slice(1);
            // do a random walk
            while (data.length < totalPoints) {
                var prev = data.length > 0 ? data[data.length - 1] : 50;
                var y = prev + Math.random() * 10 - 5;
                if (y < 0) y = 0;
                if (y > 100) y = 100;
                data.push(y);
            }
            // zip the generated y values with the x values
            var res = [];
            for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
            return res;
        }


        if (!jQuery.plot) {
            return;
        }

        if ($("#chart_1").size() == 0) {
            return;
        }

        //Basic Chart
        function chart1() {
            var d1 = [];
            for (var i = 0; i < Math.PI * 2; i += 0.25)
            d1.push([i, Math.sin(i)]);

            var d2 = [];
            for (var i = 0; i < Math.PI * 2; i += 0.25)
            d2.push([i, Math.cos(i)]);

            var d3 = [];
            for (var i = 0; i < Math.PI * 2; i += 0.1)
            d3.push([i, Math.tan(i)]);

            $.plot($("#chart_1"), [{
                label: "sin(x)",
                data: d1
            }, {
                label: "cos(x)",
                data: d2
            }, {
                label: "tan(x)",
                data: d3
            }], {
                series: {
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                xaxis: {
                    ticks: [0, [Math.PI / 2, "\u03c0/2"],
                        [Math.PI, "\u03c0"],
                        [Math.PI * 3 / 2, "3\u03c0/2"],
                        [Math.PI * 2, "2\u03c0"]
                    ]
                },
                yaxis: {
                    ticks: 10,
                    min: -2,
                    max: 2
                },
                grid: {
                    backgroundColor: {
                        colors: ["#fff", "#eee"]
                    }
                }
            });

        }

        //Interactive Chart
        function chart2() {
            function randValue() {
                return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
            }
            var pageviews = [
                [1, randValue()],
                [2, randValue()],
                [3, 2 + randValue()],
                [4, 3 + randValue()],
                [5, 5 + randValue()],
                [6, 10 + randValue()],
                [7, 15 + randValue()],
                [8, 20 + randValue()],
                [9, 25 + randValue()],
                [10, 30 + randValue()],
                [11, 35 + randValue()],
                [12, 25 + randValue()],
                [13, 15 + randValue()],
                [14, 20 + randValue()],
                [15, 45 + randValue()],
                [16, 50 + randValue()],
                [17, 65 + randValue()],
                [18, 70 + randValue()],
                [19, 85 + randValue()],
                [20, 80 + randValue()],
                [21, 75 + randValue()],
                [22, 80 + randValue()],
                [23, 75 + randValue()],
                [24, 70 + randValue()],
                [25, 65 + randValue()],
                [26, 75 + randValue()],
                [27, 80 + randValue()],
                [28, 85 + randValue()],
                [29, 90 + randValue()],
                [30, 95 + randValue()]
            ];
            var visitors = [
                [1, randValue() - 5],
                [2, randValue() - 5],
                [3, randValue() - 5],
                [4, 6 + randValue()],
                [5, 5 + randValue()],
                [6, 20 + randValue()],
                [7, 25 + randValue()],
                [8, 36 + randValue()],
                [9, 26 + randValue()],
                [10, 38 + randValue()],
                [11, 39 + randValue()],
                [12, 50 + randValue()],
                [13, 51 + randValue()],
                [14, 12 + randValue()],
                [15, 13 + randValue()],
                [16, 14 + randValue()],
                [17, 15 + randValue()],
                [18, 15 + randValue()],
                [19, 16 + randValue()],
                [20, 17 + randValue()],
                [21, 18 + randValue()],
                [22, 19 + randValue()],
                [23, 20 + randValue()],
                [24, 21 + randValue()],
                [25, 14 + randValue()],
                [26, 24 + randValue()],
                [27, 25 + randValue()],
                [28, 26 + randValue()],
                [29, 27 + randValue()],
                [30, 31 + randValue()]
            ];

            var plot = $.plot($("#chart_2"), [{
                data: pageviews,
                label: "Unique Visits"
            }, {
                data: visitors,
                label: "Page Views"
            }], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.05
                            }, {
                                opacity: 0.01
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#eee",
                    borderWidth: 0
                },
                colors: ["#FCB322", "#A5D16C", "#52e136"],
                xaxis: {
                    ticks: 11,
                    tickDecimals: 0
                },
                yaxis: {
                    ticks: 11,
                    tickDecimals: 0
                }
            });


            function showTooltip(x, y, contents) {
                $('<div id="tooltip">' + contents + '</div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y + 5,
                    left: x + 15,
                    border: '1px solid #333',
                    padding: '4px',
                    color: '#fff',
                    'border-radius': '3px',
                    'background-color': '#333',
                    opacity: 0.80
                }).appendTo("body").fadeIn(200);
            }

            var previousPoint = null;
            $("#chart_2").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));

                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(2);

                        showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        }

        //Tracking Curves
        function chart3() {
            //tracking curves:

            var sin = [],
                cos = [];
            for (var i = 0; i < 14; i += 0.1) {
                sin.push([i, Math.sin(i)]);
                cos.push([i, Math.cos(i)]);
            }

            plot = $.plot($("#chart_3"), [{
                data: sin,
                label: "sin(x) = -0.00"
            }, {
                data: cos,
                label: "cos(x) = -0.00"
            }], {
                series: {
                    lines: {
                        show: true
                    }
                },
                crosshair: {
                    mode: "x"
                },
                grid: {
                    hoverable: true,
                    autoHighlight: false
                },
                colors: ["#FCB322", "#A5D16C", "#52e136"],
                yaxis: {
                    min: -1.2,
                    max: 1.2
                }
            });

            var legends = $("#chart_3 .legendLabel");
            legends.each(function () {
                // fix the widths so they don't jump around
                $(this).css('width', $(this).width());
            });

            var updateLegendTimeout = null;
            var latestPosition = null;

            function updateLegend() {
                updateLegendTimeout = null;

                var pos = latestPosition;

                var axes = plot.getAxes();
                if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max || pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) return;

                var i, j, dataset = plot.getData();
                for (i = 0; i < dataset.length; ++i) {
                    var series = dataset[i];

                    // find the nearest points, x-wise
                    for (j = 0; j < series.data.length; ++j)
                    if (series.data[j][0] > pos.x) break;

                    // now interpolate
                    var y, p1 = series.data[j - 1],
                        p2 = series.data[j];
                    if (p1 == null) y = p2[1];
                    else if (p2 == null) y = p1[1];
                    else y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

                    legends.eq(i).text(series.label.replace(/=.*/, "= " + y.toFixed(2)));
                }
            }

            $("#chart_3").bind("plothover", function (event, pos, item) {
                latestPosition = pos;
                if (!updateLegendTimeout) updateLegendTimeout = setTimeout(updateLegend, 50);
            });
        }

        //Dynamic Chart
        function chart4() {
            //server load
            var options = {
                series: {
                    shadowSize: 1
                },
                lines: {
                    show: true,
                    lineWidth: 0.5,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.1
                        }, {
                            opacity: 1
                        }]
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    tickFormatter: function (v) {
                        return v + "%";
                    }
                },
                xaxis: {
                    show: false
                },
                colors: ["#6ef146"],
                grid: {
                    tickColor: "#a8a3a3",
                    borderWidth: 0
                }
            };

            var updateInterval = 30;
            var plot = $.plot($("#chart_4"), [getRandomData()], options);

            function update() {
                plot.setData([getRandomData()]);
                plot.draw();
                setTimeout(update, updateInterval);
            }
            update();
        }

        //bars with controls
        function chart5() {
            var d1 = [];
            for (var i = 0; i <= 10; i += 1)
            d1.push([i, parseInt(Math.random() * 30)]);

            var d2 = [];
            for (var i = 0; i <= 10; i += 1)
            d2.push([i, parseInt(Math.random() * 30)]);

            var d3 = [];
            for (var i = 0; i <= 10; i += 1)
            d3.push([i, parseInt(Math.random() * 30)]);

            var stack = 0,
                bars = true,
                lines = false,
                steps = false;

            function plotWithOptions() {
                $.plot($("#chart_5"), [d1, d2, d3], {
                    series: {
                        stack: stack,
                        lines: {
                            show: lines,
                            fill: true,
                            steps: steps
                        },
                        bars: {
                            show: bars,
                            barWidth: 0.6
                        }
                    }
                });
            }

            $(".stackControls input").click(function (e) {
                e.preventDefault();
                stack = $(this).val() == "With stacking" ? true : null;
                plotWithOptions();
            });
            $(".graphControls input").click(function (e) {
                e.preventDefault();
                bars = $(this).val().indexOf("Bars") != -1;
                lines = $(this).val().indexOf("Lines") != -1;
                steps = $(this).val().indexOf("steps") != -1;
                plotWithOptions();
            });

            plotWithOptions();
        }

        //graph
        function graphs() {

            var graphData = [];
            var series = Math.floor(Math.random() * 10) + 1;
            for (var i = 0; i < series; i++) {
                graphData[i] = {
                    label: "Series" + (i + 1),
                    data: Math.floor((Math.random() - 1) * 100) + 1
                }
            }

            $.plot($("#graph_1"), graphData, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: function (label, series) {
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                            },
                            background: {
                                opacity: 0.8
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });


            $.plot($("#graph_2"), graphData, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: function (label, series) {
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                            },
                            background: {
                                opacity: 0.5
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });

            $.plot($("#graph_3"), graphData, {
                series: {
                    pie: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
            $("#graph_3").bind("plothover", pieHover);
            $("#graph_3").bind("plotclick", pieClick);

            function pieHover(event, pos, obj) {
                if (!obj) return;
                percent = parseFloat(obj.series.percent).toFixed(2);
                $("#hover").html('<span style="font-weight: bold; color: ' + obj.series.color + '">' + obj.series.label + ' (' + percent + '%)</span>');
            }

            function pieClick(event, pos, obj) {
                if (!obj) return;
                percent = parseFloat(obj.series.percent).toFixed(2);
                alert('' + obj.series.label + ': ' + percent + '%');
            }

            $.plot($("#graph_4"), graphData, {
                series: {
                    pie: {
                        innerRadius: 0.5,
                        show: true
                    }
                }
            });
        }

        chart1();
        chart2();
        chart3();
        chart4();
        chart5();
        graphs();
    }

    var handleFancyBox = function () {
        if (!jQuery().fancybox) {
            return;
        }

        if (jQuery(".fancybox-button").size() > 0) {
            jQuery(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    }

    var handleLoginForm = function () {
		jQuery('#login-btn').click(function () {
            jQuery('#loginform').submit(function(e){
				if($(this).find("input:checked").val()=='admin')
				{
					$(this).prop("action",site_url+"admin/login");
				}
			});
        });
		
        jQuery('#forget-password').click(function () {
            jQuery('#loginform').hide();
            jQuery('#forgotform').show(200);
        });

        jQuery('#forget-btn').click(function () {
			jQuery("#forgotform").submit();
        });
		
		jQuery("#forgotform").submit(function(e){
			e.preventDefault();
			
			$.ajax({
			  url: $(this).prop("action"),
			  type: "POST",
			  data: {
			  	email: $("#input-email").val(),
			  },
			}).always(function(data) {
			  alert(data);
			  jQuery('#loginform').slideDown(200);
              jQuery('#forgotform').slideUp(200);
			});
		});
    }

    var handleFixInputPlaceholderForIE = function () {
        //fix html5 placeholder attribute for ie7 & ie8
        if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) <= 9) { // ie7&ie8
            jQuery('input[placeholder], textarea[placeholder]').each(function () {

                var input = jQuery(this);

                jQuery(input).val(input.attr('placeholder'));

                jQuery(input).focus(function () {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                jQuery(input).blur(function () {
                    if (input.val() == '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    }

    var handleStyler = function () {
        var scrollHeight = '25px';

        jQuery('#theme-change').click(function () {
            if ($(this).attr("opened") && !$(this).attr("opening") && !$(this).attr("closing")) {
                $(this).removeAttr("opened");
                $(this).attr("closing", "1");

                $("#theme-change").css("overflow", "hidden").animate({
                    width: '20px',
                    height: '22px',
                    'padding-top': '3px'
                }, {
                    complete: function () {
                        $(this).removeAttr("closing");
                        $("#theme-change .settings").hide();
                    }
                });
            } else if (!$(this).attr("closing") && !$(this).attr("opening")) {
                $(this).attr("opening", "1");
                $("#theme-change").css("overflow", "visible").animate({
                    width: '190px',
                    height: scrollHeight,
                    'padding-top': '3px'
                }, {
                    complete: function () {
                        $(this).removeAttr("opening");
                        $(this).attr("opened", 1);
                    }
                });
                $("#theme-change .settings").show();
            }
        });

        jQuery('#theme-change .colors span').click(function () {
            var color = $(this).attr("data-style");
            setColor(color);
        });

        jQuery('#theme-change .layout input').change(function () {
            setLayout();
        });

        var setColor = function (color) {
            $('#style_color').attr("href", "css/style_" + color + ".css");
        }

    }

    var handlePulsate = function () {
        if (!jQuery().pulsate) {
            return;
        }

        if (isIE8 == true) {
            return; // pulsate plugin does not support IE8 and below
        }

        if (jQuery().pulsate) {
            jQuery('#pulsate-regular').pulsate({
                color: "#bf1c56"
            });

            jQuery('#pulsate-once').click(function () {
                $(this).pulsate({
                    color: "#399bc3",
                    repeat: false
                });
            });

            jQuery('#pulsate-hover').pulsate({
                color: "#5ebf5e",
                repeat: false,
                onHover: true
            });

            jQuery('#pulsate-crazy').click(function () {
                $(this).pulsate({
                    color: "#fdbe41",
                    reach: 50,
                    repeat: 10,
                    speed: 100,
                    glow: true
                });
            });
        }
    }

    var handlePeity = function () {
        if (!jQuery().peity) {
            return;
        }

        if (jQuery.browser.msie && jQuery.browser.version.substr(0, 2) <= 8) { // ie7&ie8
            return;
        }

        $(".stat.bad .line-chart").peity("line", {
            height: 20,
            width: 50,
            colour: "#d12610",
            strokeColour: "#666"
        }).show();

        $(".stat.bad .bar-chart").peity("bar", {
            height: 20,
            width: 50,
            colour: "#d12610",
            strokeColour: "#666"
        }).show();

        $(".stat.ok .line-chart").peity("line", {
            height: 20,
            width: 50,
            colour: "#37b7f3",
            strokeColour: "#757575"
        }).show();

        $(".stat.ok .bar-chart").peity("bar", {
            height: 20,
            width: 50,
            colour: "#37b7f3"
        }).show();

        $(".stat.good .line-chart").peity("line", {
            height: 20,
            width: 50,
            colour: "#52e136"
        }).show();

        $(".stat.good .bar-chart").peity("bar", {
            height: 20,
            width: 50,
            colour: "#52e136"
        }).show();
        //

        $(".stat.bad.huge .line-chart").peity("line", {
            height: 20,
            width: 40,
            colour: "#d12610",
            strokeColour: "#666"
        }).show();

        $(".stat.bad.huge .bar-chart").peity("bar", {
            height: 20,
            width: 40,
            colour: "#d12610",
            strokeColour: "#666"
        }).show();

        $(".stat.ok.huge .line-chart").peity("line", {
            height: 20,
            width: 40,
            colour: "#37b7f3",
            strokeColour: "#757575"
        }).show();

        $(".stat.ok.huge .bar-chart").peity("bar", {
            height: 20,
            width: 40,
            colour: "#37b7f3"
        }).show();

        $(".stat.good.huge .line-chart").peity("line", {
            height: 20,
            width: 40,
            colour: "#52e136"
        }).show();

        $(".stat.good.huge .bar-chart").peity("bar", {
            height: 20,
            width: 40,
            colour: "#52e136"
        }).show();
    }

    var handleDeviceWidth = function () {
        function fixWidth(e) {
            var winHeight = $(window).height();
            var winWidth = $(window).width();
            //alert(winWidth);
            //for tablet and small desktops
            if (winWidth < 1125 && winWidth > 767) {
                $(".responsive").each(function () {
                    var forTablet = $(this).attr('data-tablet');
                    var forDesktop = $(this).attr('data-desktop');
                    if (forTablet) {
                        $(this).removeClass(forDesktop);
                        $(this).addClass(forTablet);
                    }

                });
            } else {
                $(".responsive").each(function () {
                    var forTablet = $(this).attr('data-tablet');
                    var forDesktop = $(this).attr('data-desktop');
                    if (forTablet) {
                        $(this).removeClass(forTablet);
                        $(this).addClass(forDesktop);
                    }
                });
            }
        }

        fixWidth();

        running = false;
        jQuery(window).resize(function () {
            if (running == false) {
                running = true;
                setTimeout(function () {
                    // fix layout width
                    fixWidth();
                    // fix calendar width by just reinitializing
                    handleDashboardCalendar();
                    if (isMainPage) {
                        handleDashboardCalendar(); // handles full calendar for main page
                    } else {
                        handleCalendar(); // handles full calendars
                    }
                    // fix vector maps width
                    if (isMainPage) {
                        jQuery('.vmaps').each(function () {
                            var map = jQuery(this);
                            map.width(map.parent().parent().width());
                        });
                    }
                    if (isMapPage) {
                        jQuery('.vmaps').each(function () {
                            var map = jQuery(this);
                            map.width(map.parent().width());
                        });
                    }
                    // fix event form chosen dropdowns
                    $('#event_priority_chosen').width($('#event_title').width() + 15);
                    $('#event_priority_chosen .chosen-drop').width($('#event_title').width() + 13);

                    $(".chosen-select").val('').trigger("chosen:updated");
                    //finish
                    running = false;
                }, 200); // wait for 200ms on resize event           
            }
        });
    }

    var handleGritterNotifications = function () {
        if (!jQuery.gritter) {
            return;
        }
        $('#gritter-sticky').click(function () {
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a sticky notice!',
                // (string | mandatory) the text inside the notification
                text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: 'img/avatar-mini.png',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });
            return false;
        });

        $('#gritter-regular').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a regular notice!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: 'img/avatar-mini.png',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,
                // (int | optional) the time you want it to be alive for before fading out
                time: ''
            });

            return false;

        });

        $('#gritter-max').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a notice with a max of 3 on screen at one time!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: 'img/avatar-mini.png',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,
                // (function) before the gritter notice is opened
                before_open: function () {
                    if ($('.gritter-item-wrapper').length == 3) {
                        // Returning false prevents a new gritter from opening
                        return false;
                    }
                }
            });
            return false;
        });

        $('#gritter-without-image').click(function () {
            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a notice without an image!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.'
            });

            return false;
        });

        $('#gritter-light').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a light notification',
                // (string | mandatory) the text inside the notification
                text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                class_name: 'gritter-light'
            });

            return false;
        });

        $("#gritter-remove-all").click(function () {

            $.gritter.removeAll();
            return false;

        });
    }

    var handleTooltip = function () {
        jQuery('.tooltips').tooltip();
    }

    var handlePopover = function () {
        jQuery('.popovers').popover();
    }

    var handleChoosenSelect = function () {
        if (!jQuery().chosen) {
            return;
        }
        $(".chosen").chosen({
			search_contains: true
		});
        $(".chosen-with-diselect").chosen({
			search_contains: true,
            allow_single_deselect: true
        });
    }

    var handleUniform = function () {
        if (!jQuery().uniform) {
            return;
        }

		if (test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle)")) {
            test.uniform().addClass('toggle');
        }
    }

    var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5();
        }
    }

    var handleToggleButtons = function () {
        if (!jQuery().toggleButtons) {
            return;
        }
        $('.basic-toggle-button').toggleButtons();
        $('.text-toggle-button').toggleButtons({
            width: 200,
            label: {
                enabled: "Lorem Ipsum",
                disabled: "Dolor Sit"
            }
        });
        $('.danger-toggle-button').toggleButtons({
            style: {
                // Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
                enabled: "danger",
                disabled: "info"
            }
        });
        $('.info-toggle-button').toggleButtons({
            style: {
                enabled: "info",
                disabled: ""
            }
        });
        $('.success-toggle-button').toggleButtons({
            style: {
                enabled: "success",
                disabled: "danger"
            }
        });
        $('.warning-toggle-button').toggleButtons({
            style: {
                enabled: "warning",
                disabled: "success"
            }
        });

        $('.height-toggle-button').toggleButtons({
            height: 100,
            font: {
                'line-height': '100px',
                'font-size': '20px',
                'font-style': 'italic'
            }
        });

        $('.not-animated-toggle-button').toggleButtons({
            animated: false
        });

        $('.transition-value-toggle-button').toggleButtons({
            transitionspeed: 1 // default value: 0.05
        });

    }
	var handleOrganization = function(){
		var table_org_list = $("#table_org_list").dataTable({
	        "sAjaxSource": site_url+"org/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [],
        });
        $("#table_org_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url: site_url+'org/del/'+$(this).val(),
        		beforeSend: function(){
					showRequest();
				},
        	}).always(function(data){
        		showResponse(data);
        		table_org_list.fnReloadAjax(null,null,true);
        	});
        });
	}
	var handleBoss = function(){
		var table_boss_list = $("#table_boss_list").dataTable({
	        "sAjaxSource": site_url+"boss/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [],
        });
        $("#table_boss_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url: site_url+'boss/del/'+$(this).val(),
        		beforeSend: function(){
        			showRequest();
        		},
        	}).always(function(data){
        		showResponse(data);
        		table_boss_list.fnReloadAjax(null,null,true);
        	});
        });
	}
    var handleTablesNanomark = function () {
        if (!jQuery().dataTable) {
            return;
        }
		
		$("#table_list_quotation").dataTable({
			"bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": site_url+"nanomark/list_quotation_query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc']],
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 3 ],
				  "bSortable": false,
			    }
			],
        });

        $("#table_list_application").dataTable({
			"bProcessing": true,
	        "sAjaxSource": site_url+"nanomark/query_application",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                },
				
            },
			"aaSorting": [[0,'desc']],
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 4 ],
				  "bSortable": false,
			    }
			],
		});
		
		$("#table_list_outsourcing").dataTable({
			"bProcessing": true,
	        "sAjaxSource": site_url+"nanomark/list_outsourcing_query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                },
				
            },		
			"aaSorting": [[0,'desc']],
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 4 ],
				  "bSortable": false,
			    }
			],
		});
		
		$("#table_list_customer_survey").dataTable({
			"bProcessing": true,
	        "sAjaxSource": site_url+"nanomark/list_customer_survey_query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                },
				
            },
			"aaSorting": [[0,'desc']],
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 3 ],
				  "bSortable": false,
			    }
			],
		});
		
		$("#table_list_report_revision").dataTable({
			"bProcessing": true,
	        "sAjaxSource": site_url+"nanomark/list_report_revision_query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                },
				
            },
			"aaSorting": [[0,'desc']],
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 4 ],
				  "bSortable": false,
			    }
			],
		});
		
		var table_specimen_facility_booking_list = $("#table_specimen_facility_booking_list").dataTable({
	        "sAjaxSource": site_url+"nanomark/query_booking",
            "sDom": "t",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                },
            },
			"aaSorting": [],
			"fnServerParams": function ( aoData ) {
				aoData.push({"name":"specimen_ID","value":$("#specimen_ID").val()});
	        },
		});
		$("#table_specimen_facility_booking_list").on("click","button[name='del']",function(){
			$.ajax({
				url: site_url+'nanomark/del_booking/'+$(this).val(),
				beforeSend: function(){
					showRequest();
				}
			}).always(function(data){
				showResponse(data);
//				table_specimen_facility_booking_list.fnReloadAjax(null,null,true);
//				table_facility_booking_available_time.fnReloadAjax();
			});
			
		});
    }

    var handleDateTimePickers = function () {

        if (!jQuery().daterangepicker) {
            return;
        }

        $('.date-range').daterangepicker();

        $('#dashboard-report-range').daterangepicker({
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({
                    days: -6
                }), 'today'],
                'Last 30 Days': [Date.today().add({
                    days: -29
                }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: 'left',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: '01/01/2012',
            maxDate: '12/31/2014',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },

        function (start, end) {
            App.blockUI(jQuery("#page"));
            setTimeout(function () {
                App.unblockUI(jQuery("#page"));
                $.gritter.add({
                    title: 'Dashboard',
                    text: 'Dashboard date range updated.'
                });
                App.scrollTo();
            }, 1000);
            $('#dashboard-report-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));

        });

        $('#dashboard-report-range span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));

        $('#form-date-range').daterangepicker({
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({
                    days: -6
                }), 'today'],
                'Last 30 Days': [Date.today().add({
                    days: -29
                }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: 'right',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: '01/01/2012',
            maxDate: '12/31/2014',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },

        function (start, end) {
            $('#form-date-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
        });

        $('#form-date-range span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));


        if (!jQuery().datepicker || !jQuery().timepicker) {
            return;
        }
        $.fn.datepicker.defaults.autoclose = true;
		$('.date-picker').datepicker({ format: 'yyyy-mm-dd'});
		$('.date-picker-mm').datepicker({	format: 'yyyy-mm',
											viewMode: 'months',
											minViewMode: 'months'});
		
        $('.timepicker-default').timepicker();
		
		//24小時制，30秒為單位
        $('.timepicker-24').timepicker({
			secondStep: 30,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
			defaultTime: false
        });
        //24小時制，30分鐘為單位
		$('.timepicker-24-30m').timepicker({
            minuteStep: 30,
            showMeridian: false,
			defaultTime: false
        });
    }

    var handleColorPicker = function () {
        if (!jQuery().colorpicker) {
            return;
        }
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('.colorpicker-rgba').colorpicker();
    }

    var handleAccordions = function () {
        $(".accordion").collapse().height('auto');
    }

    var handleScrollers = function () {
        if (!jQuery().slimScroll) {
            return;
        }

        $('.scroller').each(function () {
            $(this).slimScroll({
                //start: $('.blah:eq(1)'),
                height: $(this).attr("data-height"),
                alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
            });
        });
    }
	
	//系統通用函式-----------------------------------------------------
	
	var globalFunc = function(){
		if($.browser.msie && $.browser.version < 8)
		{
			alert("很抱歉，您的IE瀏覽器過舊，本網站已不再支援。若您使用IE8.0以上的瀏覽器仍出現此訊息，請關閉相容模式，謝謝您。");
		}
		if($.browser.msie && $.browser.version <= 8)//FIX IE8 FORM SUBMIT
		{
			$("form").attr("enctype","multipart/form-data");
		}
		//fix for ie always cache in AJAX mode
		$.ajaxSetup ({
		    // Disable caching of AJAX responses */
		    cache: false
		});
		//設定moment plugin時間格式語言
		moment.lang('zh-tw');
		
		// override jquery validate plugin defaults
		$.validator.setDefaults({
		    highlight: function(element) {
		        $(element).closest('.control-group').addClass('error');
		    },
		    unhighlight: function(element) {
		        $(element).closest('.control-group').removeClass('error');	 
				//$(element).removeProp("style");//fix span without .group-control
		    },
		    errorElement: 'span',
		    errorClass: 'help-inline',
		    errorPlacement: function(error, element) {
		        if(element.is(':radio') || element.is(':checkbox')) {//fix radio and checkbox error placement
					element.closest('label').siblings('label').addBack().css('color','#b94a48');
					element.closest('label').siblings('label').addBack().last().after(error);
		        } else if(element.is(":hidden(select)"))//fix for chosen error placement
				{
					element.next().after(error);
				}
				else
				{
		            error.insertAfter(element);
		        }
				error.css('color','#b94a48');//fix span without .group-control 
				//element.css('border-color','#b94a48');//fix span without .group-control 
		    },
			ignore: ":hidden:not(select)",//fix chosen not validated
		});
		// 台灣身份證字號格式檢查程式
		jQuery.validator.addMethod("TWID", function(value, element, param)
		{
		    var a = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'W', 'Z', 'I', 'O');
		    var b = new Array(1, 9, 8, 7, 6, 5, 4, 3, 2, 1);
		    var c = new Array(2);
		    var d;
		    var e;
		    var f;
		    var g = 0;
		    var h = /^[a-z](1|2)\d{8}$/i;
		    if (value.search(h) == -1)
		    {
		        return false;
		    }
		    else
		    {
		        d = value.charAt(0).toUpperCase();
		        f = value.charAt(9);
		    }
		    for (var i = 0; i < 26; i++)
		    {
		        if (d == a[i])//a==a
		        {
		            e = i + 10; //10
		            c[0] = Math.floor(e / 10); //1
		            c[1] = e - (c[0] * 10); //10-(1*10)
		            break;
		        }
		    }
		    for (var i = 0; i < b.length; i++)
		    {
		        if (i < 2)
		        {
		            g += c[i] * b[i];
		        }
		        else
		        {
		            g += parseInt(value.charAt(i - 1)) * b[i];
		        }
		    }
		    if ((g % 10) == f)
		    {
		        return true;
		    }
		    if ((10 - (g % 10)) != f)
		    {
		        return false;
		    }
		    return true;
		}, "請輸入有效的身份證字號");

		ajaxSubmitOptions = { 
		    beforeSubmit:  showRequest,  // pre-submit callback 
		    success:       showResponse,  // post-submit callback 
	    };
		$("button[type='submit'],input[type='submit']").click(function(){
			ajaxSubmitOptions = { 
			    beforeSubmit:  showRequest,  // pre-submit callback 
			    success:       showResponse,  // post-submit callback 
			    data:{
					action_btn:$(this).attr('name')
				},
		    }; 
		});
		$("form").submit(function(e){
			e.preventDefault();
			
			if($(this).valid())
			{
				$(this).ajaxSubmit(ajaxSubmitOptions);
			}
			
		});
		
		$("button[name='form_modal_submit']").click(function(){
			$(this).parents("div.modal").find("form").submit();
		});
		$("button[name='print_btn']").click(function(){
			$('div.print_area').jqprint();
		});
		
	}
	function showRequest(formData, jqForm, options)
	{
		$("div.modal").modal({backdrop:'static',keyboard:true,show:false});
		$("div.modal").modal('hide');
		
		$("#info_modal").modal('show');
		$("#info_modal").find(".modal-body").html("上傳中...<img src='/assets/pre-loader/Atom.gif'>");
		$("#info_modal").find(".modal-footer").hide();
	}
	function showResponse(responseText, statusText, xhr, $form)
	{

		$("#info_modal").html(responseText);
	}
	
//	function myAjaxSubmit(form)
//	{
//		$.ajax({
//			url: form.prop("action"),
//			type: "POST",
//			data: form.serialize(),
//			beforeSend:function(){
//				$("div.modal").modal({backdrop:'static',keyboard:true,show:false});
//				$("div.modal").modal('hide');
//				
//				$("#info_modal").modal('show');
//				$("#info_modal").find(".modal-body").html("上傳中...<img src='/assets/pre-loader/Atom.gif'>");
//				$("#info_modal").find(".modal-footer").hide();
//			},
//		}).always(function(data){
//			$("#info_modal").html(data);
//		});
//	}
	
	//帳號管理系統
	var handleTableAccountList = function(){
		
		$('#user_list_table').dataTable({
			"bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": site_url+"user/list/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 7 ],
				  "bSortable": false,
				  "mData": null,
			      "mRender": function ( data, type, full ) {
			        return '<a href='+site_url+'user/edit/'+full[0]+' class="btn btn-warning">編輯</a>';
			      }
			    }
			],
        });
		
		$('#admin_list_table').dataTable({
	        "sAjaxSource": site_url+"admin/list/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
        });
	}
	var handleFormAccount = function(){
		//表單驗證
		$('#form_user_register').validate
		(
			{
				rules:{
					ID:{required:true,minlength:3},
					name:{required:true},
					passwd:{required:true,minlength:8},
					passwd2:{equalTo:$("input[name='passwd']")},
					organization:{required:true},
					sex:{required:true},
					department:{required:true},
					tel:{required:true},
					mobile:{required:true},
					address:{required:true},
					email:{required:true,email:true},
					status:{required:true},
					boss_name:{required:true},
					boss_department:{required:true},
					boss_email:{required:true,email:true},
					boss_tel:{required:true},
				},
				messages:{
					ID:{required:'請輸入身分證字號，外國人請輸入護照號碼',minlength:'請輸入身分證字號，外國人請輸入護照號碼'},
					name:{required:'請輸入您的姓名'},
					passwd:{required:'請輸入您自行設定的密碼',minlength:'不可小於8個位元'},
					passwd2:{equalTo:'密碼不同'},
					organization:{required:'請選擇您的學校名稱'},
					sex:{required:'請輸入您的性別'},
					department:{required:'請選擇您的學校系所或公司單位'},
					tel:{required:'請選擇您的電話號碼'},
					mobile:{required:'請輸入您的行動電話'},
					address:{required:'請輸入您的通訊地址'},
					email:{required:'請輸入您的email',email:'請輸入正確的email格式'},
					status:{required:'請選擇您的身分'},
					boss_name:{required:'請輸入老師或主管的姓名'},
					boss_department:{required:'請輸入老師或主管的所屬單位'},
					boss_email:{required:'請輸入老師或主管的email',email:'請輸入正確的email格式'},
					boss_tel:{required:'請輸入老師或主管的連絡電話'},
				},
			}
		);
		$("#form_admin_register").validate
		(
			{
				rules:{
					ID:{required:true,minlength:3},
					name:{required:true},
//					passwd:{required:true,minlength:8},
					passwd2:{equalTo:$("input[name='passwd']")},
					mobile:{required:true},
					email:{required:true,email:true},
				},
				messages:{
					ID:{required:'請輸入ID',minlength:'不可小於3個位元'},
					name:{required:'請輸入您的姓名'},
//					passwd:{required:'請輸入您自行設定的密碼',minlength:'不可小於8個位元'},
					passwd2:{equalTo:'密碼不同'},
					mobile:{required:'請輸入您的行動電話'},
					email:{required:'請輸入您的email',email:'請輸入正確的email格式'},
				},
			}
		);
	}
	
	//儀器預約系統
	var handleFormFacility = function(){
		
		//-----------------------儀器維修表單-----------------------
		$("#form_facility_maintenance input[name='subject']").change(function(){
			var outsourcing_maintenance = $("#facility_maintenance_outsourcing").prop("checked");
			if(outsourcing_maintenance)
			{
				$(".advanced_data").show();
				$("#booking_time_table").hide();
			}else{
				$(".advanced_data").hide();
				$("#booking_time_table").show();
			}
		});
	}
	
	var handleTablesFacility = function(){
		if (!jQuery().dataTable) {
            return;
        }
		//------------------------儀器可預約時間列表---------------------------
		var fixedheader;
		table_facility_booking_available_time = $("#table_facility_booking_available_time").dataTable({
			"bPaginate": false,
			"bSort": false,
			"bFilter": false,
			"bProcessing": true,
			"sServerMethod": "GET",
	        "sAjaxSource": site_url+"facility/time/query/",
            "sDom": "<'row-fluid'r>t<'row-fluid'>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sInfo": "",
            },
			"fnServerParams": function ( aoData ) {
				aoData.push(
					{"name":"query_date","value":$("#query_facility_booking_date").val()}
				);
				if($("input[name='facility_ID'],select[name='facility_ID']").length){
					aoData.push({"name":"facility_ID","value":$("input[name='facility_ID'],select[name='facility_ID']").val()});
				}else if($("select[name='facility_ID[]']").length){
					$("select[name='facility_ID[]'] option:selected").each(function(index){
						aoData.push({"name":"facility_ID["+index+"]","value":$(this).val()});
					});
				}
				if($("input[name='booking_ID']").length){
					aoData.push({"name":"booking_ID","value":$("input[name='booking_ID']").val()});
				}else if($("input[name='booking_ID[]']").length){
					$("input[name='booking_ID[]']").each(function(index){
						aoData.push({"name":"booking_ID["+index+"]","value":$(this).val()});
					});
				}
	        },
	        "fnInitComplete": function(oSettings, json) {
	        	fixedheader = new $.fn.dataTable.FixedHeader( table_facility_booking_available_time );//固定DATATABLE頭部
		    },
	        "fnDrawCallback": function( oSettings ) {
		    	handleUniform();
		    	
		    },
		    "fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		    	nHead.getElementsByTagName('th')[0].innerHTML = "";
		    	
		    	var query_date = $("#query_facility_booking_date").val();
		    	if(!query_date) {
		    		query_date = moment().add('days', 2).format('YYYY-MM-DD');
				}
		    		
		    	nHead.getElementsByTagName('th')[1].innerHTML = moment(query_date).add('days', -2).format("YYYY MMMM Do dddd");
			    nHead.getElementsByTagName('th')[2].innerHTML = moment(query_date).add('days', -1).format("YYYY MMMM Do dddd");
			    nHead.getElementsByTagName('th')[3].innerHTML = moment(query_date).format("YYYY MMMM Do dddd");
			    nHead.getElementsByTagName('th')[4].innerHTML = moment(query_date).add('days', 1).format("YYYY MMMM Do dddd");
			    nHead.getElementsByTagName('th')[5].innerHTML = moment(query_date).add('days', 2).format("YYYY MMMM Do dddd");
			    
			    
			    
			    if(fixedheader)
			    {
			    	//for temporary fixed!!
					fixedheader._fnUpdateClones(true);
					fixedheader._fnUpdatePositions();
				}
		    },
        });
        

        
        $("#form_facility_booking input[name='purpose']").click(function(){
        	if($("#form_facility_booking input[name='purpose']:checked").val() == "DIY")
        	{
				$("#user_selector").hide();
				$("#form_facility_booking select[name='user_ID']").val('').trigger("chosen:updated");
			}else{
				$("#user_selector").show();
			}
        });
        $("#query_facility_booking_date").datepicker()
	    .on('changeDate', function(e){
	    	table_facility_booking_available_time.fnReloadAjax();
	    });
		//-------------------儀器設備列表----------------------------
        $("#table_list_facility").dataTable({
			"bProcessing": true,
	        "sAjaxSource": site_url+"facility/admin/facility/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aaSorting": [],
			
        });

        $("#table_list_user_privilege").dataTable({
            "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": site_url+"facility/admin/privilege/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			
			"aoColumnDefs": [ 
				{
			      "aTargets": [ 3 ],
				  "bSortable": false,
			    }
			],
        });
		//-------------------門禁設備列表----------------------------
		$("#table_list_door").dataTable({
			"bProcessing": true,
			"sAjaxSource": site_url+"facility/admin/door/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [],
		});
		//------------------------可預約儀器列表-------------------------
		var facility_booking_available_table = $("#table_list_booking_available").dataTable({
			"sAjaxSource": site_url+"facility/user/available/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [],
		});
		//-------------------------預約記錄列表---------------------------
		var facility_booking_table = $("#table_list_booking").dataTable({
			"bProcessing": true,
			"sServerMethod": "POST",
			"sAjaxSource": site_url+"facility/booking/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[4,'desc']],
			"fnServerParams": function ( aoData ) {
				aoData.push({"name":"facility_ID","value":$("select[name='facility_ID[]']").val().join("|")},
							{"name":"start_date","value":$("#start_date").val()},
							{"name":"end_date","value":$("#end_date").val()},
							{"name":"start_time","value":$("#start_time").val()},
							{"name":"end_time","value":$("#end_time").val()}
							);
	        },
		});
		$("#query_facility_booking").click(function(){
			facility_booking_table.fnReloadAjax();
		});
		$("#table_list_booking").on("click","button[name='del']",function(){
			$("#confirm_modal").data("ID",$(this).val()).modal('show');
		});
		$("#confirm_modal button[name='confirm']").click(function(){
			$.ajax({
				url: site_url+'facility/user/booking/del/'+$("#confirm_modal").data("ID"),
				beforeSend: function(){
					showRequest();
				}
			}).always(function(data){
				showResponse(data);
				facility_booking_table.fnReloadAjax(null,null,true);
			});
		});
		//--------------------預約不計費列表------------------------
		var facility_booking_nocharge_table = $("#table_list_nocharge").dataTable({
			"sAjaxSource": site_url+"facility/admin/nocharge/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[1,'desc']],
			
		});
		//------------------------儀器維修調校烈表-----------------------
		var facility_maintenance = $("#table_list_maintenance").dataTable({
			"bProcessing": true,
			"sAjaxSource": site_url+"facility/admin/maintenance/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc']],
		});
		//------------------------磁卡申請列表-----------------------
		var facility_card_application = $("#table_list_card_application").dataTable({
			"bAutoWidth": false,
			"bProcessing": true,
			"sAjaxSource": site_url+"facility/admin/card/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc']],
		});
		
		$("#table_list_card_application").delegate("button[name='notify']","click",function(){
			$("#form_facility_card_application_modal").modal({backdrop:'static',keyboard:true,show:true});
			var url = $("#form_facility_card_application_modal form").attr("action");
			$("#form_facility_card_application_modal form").attr("action",url+$(this).val());
		});
		$("#table_list_card_application").delegate("button[name='issue']","click",function(){
			$.ajax(site_url+"facility/admin/card/update/"+$(this).val())
			.done(function(){
				facility_card_application.fnReloadAjax(null,null,true);
			});
		});
		
		//-------------------門禁卡開關與進出----------------------------
		var access_card_table = $("#table_list_access_card").dataTable({
            "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": site_url+"facility/admin/access/card/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc'],[1,'desc']],
			"fnServerParams": function ( aoData ) {
				aoData.push({"name":"start_date","value":$("#start_date").val()},
							{"name":"end_date","value":$("#end_date").val()},
							{"name":"start_time","value":$("#start_time").val()},
							{"name":"end_time","value":$("#end_time").val()},
							{"name":"facility_ctrl_no","value":$("#facility_ctrl_no").val()}
							);
	        },
        });
		var access_ctrl_table = $("#table_list_access_ctrl").dataTable({
            "bProcessing": true,
	        "sAjaxSource": site_url+"facility/admin/access/ctrl/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc']],
			"fnServerParams": function ( aoData ) {
		      aoData.push(  {"name": "facility_ctrl_no", "value": $("#facility_ctrl_no").val() },
							{"name":"start_date","value":$("#start_date").val()},
							{"name":"end_date","value":$("#end_date").val()},
							{"name":"start_time","value":$("#start_time").val()},
							{"name":"end_time","value":$("#end_time").val()} );
		    }
        });
        $("#table_list_access_ctrl ").on("click","button[name='access_ctrl_del']",function(){
        	$.ajax(
        		site_url+"facility/admin/access/ctrl/del/"+$(this).val()
        	).always(function(data){
        		alert(data);
	        	access_ctrl_table.fnReloadAjax(null,null,true);
	        });
        });
        $("#table_list_access_ctrl ").on("click","button[name='access_ctrl_update']",function(){
        	$.ajax(
        		site_url+"facility/admin/access/ctrl/update/"+$(this).val()
        	).always(function(data){
        		alert(data);
	        	access_ctrl_table.fnReloadAjax(null,null,true);
	        });
        });
        
		$("#query_access_ctrl").click(function(){
			access_ctrl_table.fnReloadAjax();
		});
		
		$("#synchronize_access_card").click(function(){
			access_card_table.fnReloadAjax(null,null,true);
		});
		//-----------------------卡機連線列表------------------------------
		var access_link_table = $("#table_list_access_link").dataTable({
            "sAjaxSource": site_url+"facility/admin/access/link/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'asc']],
        });
        
        $("#table_list_access_link").delegate("button[name='del']","click",function(){
        	$.ajax(site_url+"facility/admin/access/link/del/"+$(this).val())
        	.always(function(data){
        		access_link_table.fnReloadAjax(null,null,true);
        	});
        });
        
	}
	
	var handleClock = function(){
		//------------------------手動打卡(中心人員)-----------------------------
        var table_admin_manual_clock_list = $("#table_admin_manual_clock_list").dataTable({
            "sAjaxSource": site_url+"admin/clock/query/manual",
            "sDom": "<'row-fluid'<'span12'f>r>t<'row-fluid'>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'desc']],
			"iDisplayLength": 100,
        });
		$("#table_admin_manual_clock_list").on("click","button[name='del']",function(){
			$.ajax(site_url+'admin/clock/del/'+$(this).val())
			.always(function(){
				table_admin_manual_clock_list.fnReloadAjax(null,null,true);
			});
		});
		$("#form_admin_manual_clock button[type='submit']").click(function(){
			setTimeout(function(){
				table_admin_manual_clock_list.fnReloadAjax(null,null,true);
			},1000);
		});
		//------------------------自動打卡(使用者)-----------------------------
		var table_user_clock_list = $("#table_user_clock_list").dataTable({
            "sAjaxSource": site_url+"user/clock/query",
            "sDom": "<'row-fluid'<'span12'f>r>t<'row-fluid'>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "fnInitComplete": function(oSettings, json) {
		      setInterval(function(){
		      	table_user_clock_list.fnReloadAjax();
		      },10000);
		      setTimeout(function(){
		      	document.location.reload(true);
		      },86400);
		      $("#to_fullscreen").click(function(){
		      	$("#table_user_clock_list").fullScreen(true);
		      });
		    },
			"aaSorting": [[0,'desc']],
			"iDisplayLength": 100,
			"fnServerParams": function ( aoData ) {
		      aoData.push(  {"name": "location_ID", "value": $("#location_ID").val() } );
		    },
		    "aoColumnDefs": [ {
		      "aTargets": [ 0 ],
		      "mData": function ( source, type, val ) {
		        if (type === 'set') {
		          source[0] = val;
		          // Store the computed dislay and filter values for efficiency
		          source.last_access_datetime_display = moment(val).fromNow();
		          return;
		        }
		        else if (type === 'display') {
		          return source.last_access_datetime_display;
		        }
		        // 'sort', 'type' and undefined all just use the integer
		        return source[0];
		      }
		    } ]
        });
	}
	
	var handleTablesCurriculum = function(){

		//course
		var table_curriculum_list = $("#table_curriculum_list").dataTable({
            "sAjaxSource": site_url+"curriculum/course/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'asc']],
        });
        $("#table_curriculum_list").on("click","button[name='del']",function(){
        	$.ajax(site_url+'curriculum/course/del/'+$(this).val())
        	.always(function(data){
        		table_curriculum_list.fnReloadAjax(null,null,true);
        	});
        });
        //class
        var table_curriculum_class_list = $("#table_curriculum_class_list").dataTable({
        	"bProcessing": true,
            "sAjaxSource": site_url+"curriculum/class/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [],
			"fnServerParams": function ( aoData ) {
		      aoData.push(  
		      	{"name": "class_code", "value": $("#query_curriculum_class_month").val() },
		      	{"name": "class_type", "value": $("#query_curriculum_class_type").val() }
		      );
		    }
        });
        $("#query_curriculum_class_month").datepicker()
	    .on('changeDate', function(e){
	    	setTimeout(function(){
	    		table_curriculum_class_list.fnReloadAjax();
	    	},100);
	    });
        $("#query_curriculum_class_list").click(function(){
        	table_curriculum_class_list.fnReloadAjax(null,null,true);
        });
        //管理者刪除所開之課程
        $("#table_curriculum_class_list").on("click","button[name='del_class']",function(){
        	$.ajax({
        		url:site_url+"curriculum/class/del/"+$(this).val(),
        		beforeSend:function(xhr){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_class_list.fnReloadAjax(null,null,true);
        	});
        });
        //使用者方的註冊
        $("#table_curriculum_class_list").on("click","button[name='reg']",function(){
        	$.ajax({
        		url:site_url+"curriculum/reg/add/"+$(this).val(),
        		beforeSend:function(xhr){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_class_list.fnReloadAjax(null,null,true);
        	});
        });
        //使用者方的刪除
        $("#table_curriculum_class_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url:site_url+"curriculum/reg/del/"+$(this).val(),
        		beforeSend:function(xhr){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_class_list.fnReloadAjax(null,null,true);
        	});
        });
        //registration
        var table_curriculum_reg_list = $("#table_curriculum_reg_list").dataTable({
            "sAjaxSource": site_url+"curriculum/reg/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [
				{ 'bSortable': false, 'aTargets': [8] }
			],
            "fnDrawCallback": function () {
				handleUniform();
				var check_all_box = $(this).find("thead tr th").find("input[type='checkbox'][data-model='check_all']");
				var check_box = $(this).find("tbody tr td").find("input[type='checkbox'][name='reg_ID[]']");
				if(check_box.length)
				{
					check_all_box.parent().show();
					$("#confirm_curriculum_reg,#certify_curriculum_reg").show();
				}else{
					check_all_box.parent().hide();
					$("#confirm_curriculum_reg,#certify_curriculum_reg").hide();
				}
			},
			"aaSorting": [],
			"fnServerParams": function ( aoData ) {
		      aoData.push(
		      	{"name": "class_code", "value": $("#query_curriculum_reg_month,#query_curriculum_reg_class").map(function(){return this.value}).get().join('-') },
		      	{"name": "course_ID", "value": $("#query_curriculum_reg_course_ID option:selected").val() }
		      );
		    }
        });
        $("#query_curriculum_reg_month").datepicker()
	    .on('changeDate', function(e){
	    	setTimeout(function(){
	    		table_curriculum_reg_list.fnReloadAjax();
	    	},100);
	    });
	    $("#query_curriculum_reg_course_ID").change(function(){
	    	table_curriculum_reg_list.fnReloadAjax();
	    });
	    $("#query_curriculum_reg_class").change(function(){
	    	table_curriculum_reg_list.fnReloadAjax();
	    });
        $("#table_curriculum_reg_list").on("click","button[name='update']",function(){
        	$.ajax({
        		url:site_url+"curriculum/reg/update/"+$(this).val(),
        		beforeSend:function(xhr){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_reg_list.fnReloadAjax(null,null,true);
        	});
        });
        $("#table_curriculum_reg_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url:site_url+"curriculum/reg/del/"+$(this).val(),
        		beforeSend:function(xhr){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_reg_list.fnReloadAjax(null,null,true);
        	});
        });
        $("#confirm_curriculum_reg,#certify_curriculum_reg").click(function(){
        	ajaxSubmitOptions = { 
			    beforeSubmit:  showRequest,  // pre-submit callback 
			    success:       function(responseText, statusText){
			    	showResponse(responseText, statusText);
			    	table_curriculum_reg_list.fnReloadAjax(null,null,true);
			    },  // post-submit callback 
			    data:{
					action_btn:$(this).attr('name')
				},
		    }; 
		    $(this).parents("form").ajaxSubmit(ajaxSubmitOptions);
        });
        //signature
//        var table_curriculum_signature_list = $("#table_curriculum_signature_list").dataTable({
//        	"bProcessing": true,
//            "sAjaxSource": site_url+"curriculum/signature/query/",
//            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
//            "sPaginationType": "bootstrap",
//            "oLanguage": {
//                "sLengthMenu": "_MENU_ records per page",
//                "oPaginate": {
//                    "sPrevious": "Prev",
//                    "sNext": "Next"
//                }
//            },
//            "aoColumnDefs": [
//				{ 'bSortable': false, 'aTargets': [7] }
//			],
//			"aaSorting": [],
//			"fnInitComplete": function(){
//				
//			},
//			"fnDrawCallback": function(){
//				handleUniform();
//				
//				//查詢狀況
//				var is_confirmation_available = $(this).find("tbody tr td").eq(7).find("input[type='checkbox'][name='signature_ID[]']").length;
//				var is_certification_available = $(this).find("tbody tr td").eq(7).find("input[type='checkbox'][name='reg_ID[]']").length;
//				var check_all_box = $(this).find("thead tr th").eq(7).find("input[type='checkbox']");
//				if(is_confirmation_available)
//				{
//					$("#confirm_curriculum_signature").show();
//
//				}else{
//					$("#confirm_curriculum_signature").hide();
//
//				}
//				if(is_certification_available)
//				{
//					$("#certify_curriculum_signature").show();
//				}else{
//					$("#certify_curriculum_signature").hide();
//				}
//				if(is_certification_available || is_confirmation_available)
//				{
//					//					check_all_box.show();
//					check_all_box.parent().show();//not good, but for temporary solution.
//				}else{
//					//					check_all_box.hide();
//					check_all_box.parent().hide();
//				}
//			},
//			"fnServerParams": function ( aoData ) {
//		      aoData.push(  
//		      	{"name": "lesson_start_date", "value": $("#lesson_start_date").val() },
//		      	{"name": "lesson_end_date", "value": $("#lesson_end_date").val() }
//		      );
//		    }
//        });
//        $("#table_curriculum_signature_list").on("click","button[name='sign']",function(){
//        	$.ajax({
//        		url:site_url+'curriculum/signature/add/'+$(this).val(),
//        		beforeSend:function(){
//					showRequest();	
//				},
//        	}).always(function(data){
//        		showResponse(data);
//        		table_curriculum_signature_list.fnReloadAjax(null,null,true);
//        	});
//        });
//        $("#table_curriculum_signature_list").on("click","button[name='del_sign']",function(){
//        	$.ajax({
//        		url:site_url+'curriculum/signature/del/'+$(this).val(),
//        		beforeSend:function(){
//					showRequest();	
//				},
//        	}).always(function(data){
//        		showResponse(data);
//        		table_curriculum_signature_list.fnReloadAjax(null,null,true);
//        	});
//        });
//
//        $("#query_curriculum_signature_list").click(function(){
//        	table_curriculum_signature_list.fnReloadAjax(null,null,true);
//        	
//        });
        //lesson
        var table_curriculum_lesson_list = $("#table_curriculum_lesson_list").dataTable({
            "sAjaxSource": site_url+"curriculum/lesson/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[2,'asc']],
			"fnServerParams": function ( aoData ) {
		      aoData.push(  {"name": "class_ID", "value": $("input[name='class_ID']").val() } );
		    }
        });
        $("#table_curriculum_lesson_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url: site_url+'curriculum/lesson/del/'+$(this).val(),
        		beforeSend: function(){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_lesson_list.fnReloadAjax(null,null,true);
        	});
        });
        //booking
        var table_curriculum_booking_list = $("#table_curriculum_booking_list").dataTable({
            "sAjaxSource": site_url+"curriculum/booking/query/",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
			"aaSorting": [[0,'asc']],
			"fnServerParams": function ( aoData ) {
		      aoData.push(  {"name": "lesson_ID", "value": $("input[name='lesson_ID']").val() } );
		    }
        });
        $("#form_curriculum_facility_booking select[name='facility_ID']").change(function(){
			table_facility_booking_available_time.fnReloadAjax();        	
        });
        $("#table_curriculum_booking_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url: site_url+'curriculum/booking/del/'+$(this).val(),
        		beforeSend: function(){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_curriculum_booking_list.fnReloadAjax(null,null,true);
        	});
        	
        });
	}
	
	//奈米標章檢測項目對應表
	var handleTableNanomarkTestItem = function(){
		$("#table_nanomark_test_item button[name='add']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse,  // post-submit callback
				type: "POST",
				data:{serial_no: $(this).closest("tr").find("input[name='serial_no']").val(), 
					  name: $(this).closest("tr").find("input[name='name']").val(),
					  facility_ID: $(this).closest("tr").find("select[name='facility_ID']").val()},
				url: site_url+"nanomark/add_test_item",
	    	}; 
			$(this).ajaxSubmit(ajaxSubmitOptions);
		});
		$("#table_nanomark_test_item button[name='update']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse,  // post-submit callback
				type: 'POST',
				data:{serial_no: $(this).closest("tr").find("input[name='serial_no']").val(), 
					  name: $(this).closest("tr").find("input[name='name']").val(),
					  facility_ID: $(this).closest("tr").find("select[name='facility_ID']").val()},
				url: site_url+"nanomark/update_test_item",
	    	}; 
			$(this).ajaxSubmit(ajaxSubmitOptions);
		});
	}
	//奈米標章規範列表
	var handleTableNanomarkVerificationNorm = function(){
		$("#table_list_verification_norm button[name='add']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse,  // post-submit callback
				type: "POST",
				data:
					{
						name: $(this).closest("tr").find("input[name='name']").val(),
					},
				url: site_url+"nanomark/add_verification_norm",
	    	}; 
			$(this).ajaxSubmit(ajaxSubmitOptions);
		});
	}
	
	//奈米標章報價單
	var handleFormNanomarkQuotation = function(){
		
		
		//紀錄要加的字串
		var trStr = "<tr>"+$("#form_nanomark_quotation #new_row").html()+"</tr>";
		
		$("#form_nanomark_quotation #add_row_btn").click(function(){
			
			//先加一行
			$("#table_nanomark_quotation_test_item tbody tr").eq(-2).after(trStr);
			//再觸發
			$("#table_nanomark_quotation_test_item tbody select").chosen();
			
			//改變項次編號顯示
			var num = $("#table_nanomark_quotation_test_item tbody tr").length-1;
			$("#table_nanomark_quotation_test_item tbody tr").eq(-2).find("td:first").text(num);
		});
		
		//表單驗證
		$('#form_nanomark_quotation').validate
		(
			{
				rules:{
					organization:{required:true},
					contact_name:{required:true},
					contact_tel:{required:true},
					entrust_item:{required:true},
					"test_item_amount[]":{required:true,minlength: 1},
				},
				messages:{

				},
			}
		);
		
		//自動跳金額
		$("#form_nanomark_quotation").delegate("input[name='test_item_amount[]'],input[name='test_item_fees[]']","change",function(){
			$(this).closest("tr").find("input[name='test_item_total_fees[]']").val($(this).closest("tr").find("input[name='test_item_amount[]']").val()*$(this).closest("tr").find("input[name='test_item_fees[]']").val());
			var total = 0;
			$("#form_nanomark_quotation input[name='test_item_total_fees[]']").each(function(){
				total += Number($(this).val());
			});
			$("#form_nanomark_quotation input[name='total_fees']").val(total);
		});
		

	}
	
	//奈米標章委託單
	var handleFormNanomarkApplication = function(){
		//處理收據抬頭同報告抬頭功能
		$("#form_nanomark_application input[name='as_report_title']").click(function(){
			if(this.checked){
				$("input[name='receipt_title']").val($("input[name='report_title']").val());
				$("input[name='receipt_title']").prop("readonly",true);
			}else{
				$("input[name='receipt_title']").prop("readonly",false);
			}
				
		});
		
		
		//表單驗證
		$('#form_nanomark_application').validate
		(
			{
				rules:{
				},
				messages:{
				},
			}
		);
		$("#widget_application button[name='preview']").click(function(){
			var d = new Date();
			var strDate = d.getFullYear() + "年" + (d.getMonth()+1) + "月" + d.getDate() + "日";
			
			$("#widget_application").hide();
			$("#widget_preview_report").show();
			$("#widget_preview_report #specimen_name").text($("#widget_application input[name='specimen_name[]']").val());
			$("#widget_preview_report #report_title").text($("#widget_application input[name='report_title']").val());
			$("#widget_preview_report #report_address").text($("#widget_application input[name='report_address']").val());
			$("#widget_preview_report #application_date").text(strDate);
			$("#widget_preview_report #specimen_company_name").text($("#widget_application input[name='specimen_company_name[]']").val());
			$("#widget_preview_report #specimen_brand").text($("#widget_application input[name='specimen_brand[]']").val());
			$("#widget_preview_report #specimen_model").text($("#widget_application input[name='specimen_model[]']").val());
		});
		$("#widget_preview_report button[name='back']").click(function(){
			$("#widget_application").show();
			$("#widget_preview_report").hide();
		});
		
		$("#form_nanomark_application button[name='del']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse,  // post-submit callback 
		 		url:       site_url+'nanomark/delete_application/',        // override for form's 'action' attribute 
	    	}; 
			
			$("#form_nanomark_application").ajaxSubmit(ajaxSubmitOptions);
		});
		
		//add new row function
		var row_str = "<tr>"+$("#form_nanomark_application tr[class='hide']").html()+"</tr>";
		$("#table_nanomark_application_specimen #add_row_btn").click(function(){
			$("#table_nanomark_application_specimen tbody tr").eq(-1).before(row_str);
			$("#form_nanomark_application select").chosen();
		});
		
		//del row function
		$("#form_nanomark_application").on("click","button[name='del_row']",function(){
			$(this).closest("tr").remove();
		});

	}
	var handleFormNanomarkOutsourcing = function() {
		
		//自動跳已選的項目數量
		$("select[name='test_items_name_1[]']").change(function(){
			$("input[name='test_items_1_amount']").val($("select[name='test_items_name_1[]'] > option:selected").length);
		});
		
	
		
		$("#form_nanomark_outsourcing button[name='update']").click(function(){
			$("#form_nanomark_outsourcing").prop("action",site_url+'nanomark/update_outsourcing/'+$("input[name='specimen_SN']").val());
			$("#form_nanomark_outsourcing").submit();
		});
		$("#form_nanomark_outsourcing button[name='del']").click(function(){
			ajaxSubmitOptions = {
				beforeSubmit:  showRequest,  // pre-submit callback 
	        	success:       showResponse,  // post-submit callback 
				url:       site_url+'nanomark/delete_outsourcing/'+$("input[name='specimen_SN']").val(),        // override for form's 'action' attribute 
			}
			$("#form_nanomark_outsourcing").ajaxSubmit(ajaxSubmitOptions);

		});
		
		
	}
	
	var handleFormNanomarkCustomerSurvey = function(){
		
	}
	var handleFormNanomarkReportRevision = function(){
		$("#form_nanomark_report_revision button[name='update']").click(function(){
			ajaxSubmitOptions = {
				beforeSubmit:  showRequest,  // pre-submit callback 
	        	success:       showResponse,  // post-submit callback 
				url:       site_url+'nanomark/update_report_revision/',        // override for form's 'action' attribute 
				data:{
					result:$(this).val(),
				},
			};
			
			$("#form_nanomark_report_revision").submit();
		});
		
		$('#form_nanomark_report_revision').validate
		(
			{
				rules:{
					report_ID:{required:true},
					"mistake_outline[]":{required:true,minlength:1},
					mistake_description:{required:true},
					mistake_analysis:{required:true},
					disposal_revision:{required:true},
				},
				messages:{

				},
			}
		);
		
		//根據選擇的報告編號，自動跳公司名稱
		$("#form_nanomark_report_revision select[name='report_ID']").change(function(){
			$("#form_nanomark_report_revision select[name='org_name']").find("option[value='"+$(this).val()+"']").prop("selected","selected");
		});
	}
	
	var handleRewardListTable = function(){
		if (!jQuery().dataTable) {
            return;
        }

        // begin first table
        var reward_list_table = $('#reward_list_table').dataTable({
        	"sAjaxSource": site_url+"reward/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            },{
                'bSortable': false,
                'aTargets': [4]
            }]
        });

		
		$("#reward_list_table").on("click","button[name='del_row']",function(){
			$.ajax({
				url:       site_url+'reward/delete/'+$(this).val(),// override for form's 'action' attribute  
				beforeSend: function(){
					showRequest();
				}
			}).always(function(data){
				showResponse(data);
				reward_list_table.fnReloadAjax(null,null,true);
			});
		});
	}
	
	var handleRewardApplication = function(){
		
		$("#reward_application button[name='update']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse,  // post-submit callback 
		        url:       site_url+'reward/update/'         // override for form's 'action' attribute  
	    	}; 	
			$("#reward_application").ajaxSubmit(ajaxSubmitOptions);
		});

		
		//表單驗證
		$validator = $("#reward_application").validate({
			rules:{
				applicant_name:{required:true},
				department:{required:true},
				tel:{required:true},
				email:{required:true,email:true},
				"research_field[]":{required:true,minlength:1,},
				paper_title:{required:true},
				journal:{required:true},
				journal_year:{required:true},
				awardees_no:{required:true},
				userfile:{required:true},
			},
			messages:{
				applicant_name:{required:'請輸入您的姓名'},
				department:{required:'請輸入您的學校名稱與系所'},
				tel:{required:'請輸入您的聯絡電話'},
				email:{required:'請輸入您的email',email:'請輸入正確的email格式'},
				"research_field[]":"請選擇您的研究領域",
				paper_title:{required:'請輸入您的論文主旨'},
				journal:{required:'請輸入您投稿的期刊全名'},
				journal_year:{required:'請輸入期刊的發表年份'},
				awardees_no:{required:'請輸入欲抵扣的帳戶姓名'},
				userfile:{required:'請選擇一個文件'},
			},
		});
		
	}
	
	var handleRewardPlan = function(){
		var table_reward_plan_list = $("#table_reward_plan_list").dataTable({
        	"sAjaxSource": site_url+"reward/plan/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
        });
        
        $("#table_reward_plan_list").on("click","button[name='del']",function(){
        	$.ajax({
        		url: site_url+'reward/plan/del/'+$(this).val(),
        		beforeSend: function(){
					showRequest();	
				}
        	}).always(function(data){
        		showResponse(data);
        		table_reward_plan_list.fnReloadAjax(null,null,true);
        	});
        });
	}
	
	var handleAccess = function(){
		var table_access_card_temp_application_list = $("#table_access_card_temp_application_list").dataTable({
        	"sAjaxSource": site_url+"access/card/application/temp/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
        });
        $("#table_access_card_temp_application_list").on("click","button[name='reject']",function(){
        	$.ajax({
        		url: site_url+"access/card/application/temp/del/"+$(this).val(),
        		beforeSend: function(){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_access_card_temp_application_list.fnReloadAjax(null,null,true);
        	});
        });
        $("#table_access_card_temp_application_list").on("click","button[name='refund']",function(){
        	$.ajax({
        		url: site_url+"access/card/application/temp/update/",
        		data: {serial_no:$(this).val()},
        		type: "POST",
        		beforeSend: function(){
					showRequest();
				}
        	}).always(function(data){
        		showResponse(data);
        		table_access_card_temp_application_list.fnReloadAjax(null,null,true);
        	});
        });
        //---------------POOL--------------
        var table_access_card_pool_list = $("#table_access_card_pool_list").dataTable({
        	"sAjaxSource": site_url+"access/card/pool/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "fnDrawCallback": function( oSettings ){
				handleUniform();
			}
        });
        $("#form_access_card_pool_list button[name='del']").click(function(){
			ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       function(data){
		        	showResponse(data);
		        	table_access_card_pool_list.fnReloadAjax(null,null,true);
		        },  // post-submit callback 
		        url:       site_url+"access/card/pool/del",// override for form's 'action' attribute  
	    	}; 	
        });
        $("#form_access_card_pool_add_batch").click(function(){
        	ajaxSubmitOptions = { 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       function(data){
		        	showResponse(data);
		        	table_access_card_pool_list.fnReloadAjax(null,null,true);
		        },  // post-submit callback 
		        url:       site_url+"access/card/pool/add/batch",// override for form's 'action' attribute  
	    	}; 	
        });
	}
	
    var handleFormWizards = function () {
        if (!jQuery().bootstrapWizard) {
            return;
        }
    }

    var handleTagsInput = function () {
        if (!jQuery().tagsInput) {
            return;
        }
        $('#tags_1').tagsInput({
            width: 'auto'
        });
        $('#tags_2').tagsInput({
            width: 240
        });
    }

    var handleGoTop = function () {
        /* set variables locally for increased performance */
        jQuery('#footer .go-top').click(function () {
            App.scrollTo();
        });

    }

    // this is optional to use if you want animated show/hide. But plot charts can make the animation slow.
    var handleSidebarTogglerAnimated = function () {

        $('.sidebar-toggler').click(function () {
            if ($('#sidebar > ul').is(":visible") === true) {
                $('#main-content').animate({
                    'margin-left': '25px'
                });

                $('#sidebar').animate({
                    'margin-left': '-190px'
                }, {
                    complete: function () {
                        $('#sidebar > ul').hide();
                        $("#container").addClass("sidebar-closed");
                    }
                });
            } else {
                $('#main-content').animate({
                    'margin-left': '215px'
                });
                $('#sidebar > ul').show();
                $('#sidebar').animate({
                    'margin-left': '0'
                }, {
                    complete: function () {
                        $("#container").removeClass("sidebar-closed");
                    }
                });
            }
        })
    }

    // by default used simple show/hide without animation due to the issue with handleSidebarTogglerAnimated.
    var handleSidebarToggler = function () {

        $('.sidebar-toggler').click(function () {
            if ($('#sidebar > ul').is(":visible") === true) {
                $('#main-content').css({
                    'margin-left': '25px'
                });
                $('#sidebar').css({
                    'margin-left': '-190px'
                });
                $('#sidebar > ul').hide();
                $("#container").addClass("sidebar-closed");
            } else {
               $('#main-content').css({
                    'margin-left': '215px'
                });
                $('#sidebar > ul').show();
                $('#sidebar').css({
                    'margin-left': '0'
                });
                $("#container").removeClass("sidebar-closed");
            }
        })
    }
	
	var handleMainMenuInit = function(){
		//新的寫法
		for(var url_array = document.location.href.split('/');url_array.length;url_array.pop())
		{
			var found = $("li a[href='"+url_array.join('/')+"']").parents("li");
			if(found.length)
			{
				found.addClass("active");
				break;
			}
		}
	}
	
	var handleMyLibrary = function(){
		$("[data-model='select_all']").change(function(){
			var checked = $(this).prop("checked");
			var target = $(this).attr('data-target');
			$("#"+target+" option").prop("selected",checked);
			if($("#"+target).hasClass("chosen")){
				$("#"+target).trigger("chosen:updated");
			}
		});
		$("[data-model='check_all']").change(function(){
			var checked = $(this).prop("checked");
			var target = $(this).attr('data-target').split('|');
			$.each(target,function(index,value){
				$("input[name='"+value+"']").prop("checked",checked);
				$.uniform.update($("input[name='"+value+"']"));
			});
			
		});
	}

    return {

        //main function to initiate template pages
        init: function () {

            if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) == 8) {
                isIE8 = true; // checkes for IE8 browser version
                $('.visible-ie8').show();
            }
			
            handleDeviceWidth(); // handles proper responsive features of the page
            handleChoosenSelect(); // handles bootstrap chosen dropdowns

            if (isMainPage) {
                handleDashboardCharts(); // handles plot charts for main page
                handleJQVMAP(); // handles vector maps for home page
                handleDashboardCalendar(); // handles full calendar for main page
                handleChat() // handles dashboard chat
            } else {
                handleCalendar(); // handles full calendars
                handlePortletSortable(); // handles portlet draggable sorting
            }

            if (isMapPage) {
                handleAllJQVMAP(); // handles vector maps for interactive map page
            }
			
			globalFunc();
//            handleScrollers(); // handles slim scrolling contents
            handleUniform(); // handles uniform elements
//            handleClockfaceTimePickers(); //handles form clockface timepickers
            handleTagsInput() // handles tag input elements
            
//            handleCharts(); // handles plot charts
            handleWidgetTools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
            handlePulsate(); // handles pulsate functionality on page elements
//            handlePeity(); // handles pierty bar and line charts
            handleGritterNotifications(); // handles gritter notifications
            handleTooltip(); // handles bootstrap tooltips
            handlePopover(); // handles bootstrap popovers
            handleToggleButtons(); // handles form toogle buttons
            handleWysihtml5(); //handles WYSIWYG Editor 
            handleDateTimePickers(); //handles form timepickers
//            handleColorPicker(); // handles form color pickers
//            handleFancyBox(); // handles fancy box image previews
//            handleStyler(); // handles style customer tool
            handleMainMenu(); // handles main menu
            handleFixInputPlaceholderForIE(); // fixes/enables html5 placeholder attribute for IE9, IE8
            handleGoTop(); //handles scroll to top functionality in the footer
            handleAccordions();
            /*------------------------------*/
			handleTableAccountList();
			handleFormAccount();
			handleOrganization();
			handleBoss();
			handleFormFacility();//儀器預約系統
			handleTablesFacility();
			handleTablesCurriculum();
			handleTablesNanomark(); // handles data tables
			handleTableNanomarkTestItem();
			handleTableNanomarkVerificationNorm();
			handleFormNanomarkQuotation();
			handleFormNanomarkApplication();
			handleFormNanomarkOutsourcing();
			handleFormNanomarkCustomerSurvey();
			handleFormNanomarkReportRevision();
			handleRewardListTable();
			handleRewardApplication();
			handleRewardPlan();
			handleAccess();
			handleClock();
			/*------------------------------*/
//            handleFormWizards();
            handleSidebarToggler();
			handleMainMenuInit();
			/*------------------------------*/
			handleMyLibrary();
			
            if (isMainPage) { // this is for demo purpose. you may remove handleIntro function for your project
                handleIntro();
            }
        },

        // login page setup
        initLogin: function () {
            handleLoginForm();
            handleFixInputPlaceholderForIE();
			handleUniform();
        },

        // wrapper function for page element pulsate
        pulsate: function (el, options) {
            var opt = jQuery.extend(options, {
                color: '#d12610', // set the color of the pulse
                reach: 15, // how far the pulse goes in px
                speed: 300, // how long one pulse takes in ms
                pause: 0, // how long the pause between pulses is in ms
                glow: false, // if the glow should be shown too
                repeat: 1, // will repeat forever if true, if given a number will repeat for that many times
                onHover: false // if true only pulsate if user hovers over the element
            });

            jQuery(el).pulsate(opt);
        },

        // wrapper function to scroll to an element
        scrollTo: function (el) {
            pos = el ? el.offset().top : 0;
            jQuery('html,body').animate({
                scrollTop: pos
            }, 'slow');
        },

        // wrapper function to  block element(indicate loading)
        blockUI: function (el, loaderOnTop) {
            lastBlockedUI = el;
            jQuery(el).block({
                message: '<img src="img/loading.gif" align="absmiddle">',
                css: {
                    border: 'none',
                    padding: '2px',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.05,
                    cursor: 'wait'
                }
            });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function (el) {
            jQuery(el).unblock({
                onUnblock: function () {
                    jQuery(el).removeAttr("style");
                }
            });
        },

        // set main page
        setMainPage: function (flag) {
            isMainPage = flag;
        },

        // set map page
        setMapPage: function (flag) {
            isMapPage = flag;
        }

    };

    //input mask

    $('.inputmask').inputmask();

}();

//tooltips

$('.element').tooltip();


// Slider input js
try{
    jQuery("#Slider1").slider({ from: 5, to: 50, step: 2.5, round: 1, dimension: '&nbsp;$', skin: "round_plastic" });
    jQuery("#Slider2").slider({ from: 5000, to: 150000, heterogeneity: ['50/50000'], step: 1000, dimension: '&nbsp;$', skin: "round_plastic" });
    jQuery("#Slider3").slider({ from: 1, to: 30, heterogeneity: ['50/5', '75/15'], scale: [1, '|', 3, '|', '5', '|', 15, '|', 30], limits: false, step: 1, dimension: '', skin: "round_plastic" });
    jQuery("#Slider4").slider({ from: 0, to: 1440, step: 30, dimension: '', scale: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'], limits: false, skin: "round_plastic", calculate: function( value ){
        var hours = Math.floor( value / 60 );
        var mins = ( value - hours*60 );
        return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
    }});
} catch (e){
    errorMessage(e);
}


//knob

$(".knob").knob();






