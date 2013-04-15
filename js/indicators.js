// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX
  
  // global variables
  var circles = [];
  var maxdatavalue = 0;
  var maxcirclearea = 2000000000000;
  var request_url = ""
  var selected_type = "";
  var indicator_data;

  // We run this function on each filter save.
  function initialize_map(url, sel_year, type, indicator_id, countries, regions, cities){
    
    if(type){
    selected_type = type;
    }

    //set max area depending on page
    if(type=='cpi'){
        maxcirclearea = 500000000000;
    }
    if (type=='indicator'){
        maxcirclearea = 2000000000000;
    }

    request_url = url;

    // show loader, hide map
    $('#map-loader').show();
    $('#map').hide();

    // clear current circles
    clear_circles();
    circles = [];

    // get data
    indicator_data = get_indicator_data(indicator_id, countries, regions, cities);
    
    // check for what years data is available and add the right classes to the slider year blocks
    draw_available_data_blocks(indicator_data);

    // draw the circles
    draw_circles(indicator_data);

    // set current year
    $( "#map-slider-tooltip div" ).html(sel_year);
    $( "#map-slider-tooltip" ).val(sel_year);
    $( "#year-" + sel_year).addClass("active");
    refresh_circles(sel_year.toString());

    // hide loader, show map
    $('#map').show(); 
    $('#map-loader').hide();
    
    

    //load filters depending on page
    if(type=='cpi'){
        set_filters_cpi(indicator_data);
        create_cpi_table();
    }
    if (type=='indicator'){
        set_filters_indicator(indicator_data);
    }

    return indicator_data;
  }

  function create_cpi_table(){
    google.load('visualization', '1', {packages:['table'], callback:drawCityPropTable});

  }

    function drawCityPropTable() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'City');
    data.addColumn('number', 'Value');

    for (var i=0;i<circles.length;i++)
    { 
      try{

        var value = circles[i].values["y2012"];
        if (value === undefined || value === null){
          //  circles[i].circleinfo.setRadius(0);
        } else {
          data.addRow([circles[i].countryname, value]);
        }

      } catch (err){
        //console.log(err);
      }
    }

    var table = new google.visualization.Table(document.getElementById('table-city-prosperity'));
    table.draw(data, {
      showRowNumber: false,
      sortColumn: 0,
      sortAscending: true
    });
    
  }

  function clear_circles(){
    for (var i=0;i<circles.length;i++)
    { 
      try{
        map.removeLayer(circles[i].circleinfo);
      } catch (err){
        console.log("removal of circle failed");
      }
    }
  }

  function set_filters_indicator(data){
    
    region_html = create_filter_attributes(data['regions'], 4);
    $('#region-filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], 4);
    $('#country-filters').html(country_html);

    // city_html = create_filter_attributes(data['cities'], 4);
    // $('#city-filters').html(city_html);

    indicator_html = create_filter_attributes(data['indicators'], 3);
    $('#indicator-filters').html(indicator_html);
  }

  function set_filters_cpi(data){

    region_html = create_filter_attributes(data['regions'], 4);
    $('#region-filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], 4);
    $('#country-filters').html(country_html);

    city_html = create_filter_attributes(data['cities'], 4);
    $('#city-filters').html(city_html);

    indicator_html = create_filter_attributes(data['indicators'], 4);
    $('#indicator-filters').html(indicator_html);
  }

  function get_indicator_data(indicator_id, countries, regions, cities){
    
    var indicator_json_data = [];
    var url = request_url;
     $.ajax({
        type: 'GET',
         url: url,
         async: false,
         contentType: "application/json",
         dataType: 'json',
         success: function(data){
          indicator_json_data = data;
         }
     });
    return indicator_json_data;
  }

  function draw_available_data_blocks(indicator_data){
    
    $('.slider-year').removeClass('slider-active');
    for (var i=1950;i<2051;i++){
      var curyear = "y" + i;
      
      $.each(indicator_data, function(key, value){
          if (value.years){

            if (curyear in value.years){
              $("#year-" + i).addClass("slider-active");
              return false;
            }
          }   
          
        
      });
    }
  }

  function draw_circles(indicator_data){
    $.each(indicator_data, function(key, value){

      try{
        if (value.max_value){
            maxdatavalue = value.max_value;

        }

        var circle = L.circle(new L.LatLng(value.longitude, value.latitude), 1, {
          color: 'red',
          weight: '0',
          fillColor: 'red',
          fillOpacity: 0.6
          }).addTo(map);

          var singlecircleinfo = new Object();
          singlecircleinfo.countryiso2 = key;
          singlecircleinfo.countryname = value.name;
          singlecircleinfo.values = value.years;
          singlecircleinfo.circleinfo = circle;
          singlecircleinfo.indicator = value.indicator;
          singlecircleinfo.friendly_label = value.indicator_friendly;
          
          singlecircleinfo.type_data = value.type_data;
          circles.push(singlecircleinfo);

      }catch(err){
          //console.log(err);
      }
    });
  }

  
// XXXXXXXXXXXXXX INDICATOR SLIDER XXXXXXXXXXXXXXXXX


  $( "#map-slider-tooltip" ).noUiSlider({
    range: [1950, 2050],
    handles: 1,
    start: 2000,
    step: 1,
    slide: slide_tooltip
  });

  function slide_tooltip(){
    var curval = $("#map-slider-tooltip").val();
    $( "#map-slider-tooltip div" ).text(curval);

    refresh_circles(curval);
    $( ".slider-year").removeClass("active");
    $( "#year-" + curval).addClass("active");
   // $( "#year-" + (ui.value+1)).removeClass("active");
   // $( "#year-" + (ui.value-1)).removeClass("active");
  }

  function refresh_circles(year){
    var curyear = "y" + year;
    var max = maxdatavalue;

    var factor = Math.round(maxcirclearea / max);

    for (var i=0;i<circles.length;i++)
    { 
      try{
        //circles[i].unbindPopup();
        //circles[i].bindPopup(circles[i].values[curyear]);

        var value = circles[i].values[curyear];

        if (value === undefined || value === null){
          //  circles[i].circleinfo.setRadius(0);
        } else {
          circle_radius = Math.round(Math.sqrt((factor * value) / Math.PI));
          circles[i].circleinfo.setRadius(circle_radius); 
          if (circles[i].type_data == '1000'){
            value = value * 1000;
            value = CommaFormatted(value+'.');
          }
          if (circles[i].type_data == 'p'){
            // if (value < 1){
            //   value = value * 100;
            // }
            // value = value + ' %';
          }
          circles[i].circleinfo.bindPopup('<h4>'+circles[i].countryname+'</h4><p>' + circles[i].friendly_label + ': ' + value + '</p>');
          circles[i].circleinfo.on('mouseover', function(evt) {
            evt.target.openPopup();
          });
        }

      } catch (err){
        //console.log(err);
      }
    }
    
  }

  $(".slider-year").click(
    function() {
      var curId = $(this).attr('id');
      var curYear = curId.replace("year-", "");
      refresh_circles(curYear);
      $( "#map-slider-tooltip" ).val(parseInt(curYear));
      $( "#map-slider-tooltip div" ).text(curYear);
      $( ".slider-year").removeClass("active");
      $(this).addClass("active");
  });

  // $("#project-share-export").click(function(){

  //   initialize_map("","", "", "");
  //   return false;
  // });

  // initialize_map("","", "", "");


// XXXXXXXXXXXXXXXX INDICATOR GRAPHS XXXXXXXXXXXXXXXX 
      function drawTreemap() {



        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Location', 'Parent', 'Market trade volume (size)'],
          ['Global',    null,                 0],
          ['America',   'Global',             0],
          ['Europe',    'Global',             0],
          ['Asia',      'Global',             0],
          ['Australia', 'Global',             0],
          ['Africa',    'Global',             0],
          ['Brazil',    'America',            11],
          ['USA',       'America',            52],
          ['Mexico',    'America',            24],
          ['Canada',    'America',            16],
          ['France',    'Europe',             42],
          ['Germany',   'Europe',             31],
          ['Sweden',    'Europe',             22],
          ['Italy',     'Europe',             17],
          ['UK',        'Europe',             21],
          ['China',     'Asia',               36],
          ['Japan',     'Asia',               20],
          ['India',     'Asia',               40],
          ['Laos',      'Asia',               4],
          ['Mongolia',  'Asia',               1],
          ['Israel',    'Asia',               12],
          ['Iran',      'Asia',               18],
          ['Pakistan',  'Asia',               11],
          ['Egypt',     'Africa',             21],
          ['S. Africa', 'Africa',             30],
          ['Sudan',     'Africa',             12],
          ['Congo',     'Africa',             10],
          ['Zair',      'Africa',             8]
        ]);
      // Create and draw the visualization.
        var tree = new google.visualization.TreeMap(document.getElementById('treemap-placeholder'));
        tree.draw(data, {
          headerHeight: 0,
          fontColor: '#797974',
          fontFamily: 'HelveticaNeueW01-45Ligh',
          fontSize: 24,
          showScale: false});
      }

      function drawLineChart(){
        var curyear = parseInt($(".ui-slider-handle").html());
        var currentData = [];
        var minyear = curyear - 10;
        var maxyear = curyear + 10;
        var lineChartData = [];
        //var lineChartLines = new Object();

        currentData.push(['Year', circles[0].countryiso2, circles[1].countryiso2, circles[2].countryiso2, circles[3].countryiso2, circles[4].countryiso2]);


        for (var i=1950;i<2051;i++)
        { 
            var c1val;
            var c2val;
            var c3val;
            var c4val;
            var c5val;

            var curvalue = circles[0].values["y" + i.toString()];
            if (curvalue === undefined || curvalue === null){ c1val = null; } else { c1val = curvalue; }

            var curvalue = circles[1].values["y" + i.toString()];
            if (curvalue === undefined || curvalue === null){ c2val = null; } else { c2val = curvalue; }
            
            var curvalue = circles[2].values["y" + i.toString()];
            if (curvalue === undefined || curvalue === null){ c3val = null; } else { c3val = curvalue; }
          
            var curvalue = circles[3].values["y" + i.toString()];
            if (curvalue === undefined || curvalue === null){ c4val = null; } else { c4val = curvalue; }
            
            var curvalue = circles[4].values["y" + i.toString()];
            if (curvalue === undefined || curvalue === null){ c5val = null; } else { c5val = curvalue; }
            
            currentData.push([i.toString(), c1val, c2val, c3val, c4val, c5val]);
        }



        var data = google.visualization.arrayToDataTable(currentData);

        var options = {
          title: 'Line chart header?',
          backgroundColor: '#F1EEE8',
          interpolateNulls: true
        };

        var chart = new google.visualization.LineChart(document.getElementById('line-chart-placeholder'));
        chart.draw(data, options);
      
      }

      function drawTableChart(){

        

        var indicator_data = get_indicator_data("", "", "", "");
        
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Country');
        data.addColumn('number', 'Value');
        
        var curyear = "y" + $(".ui-slider-handle").html();

        for (var i=0;i<circles.length;i++)
        { 
          try{

            var value = circles[i].values[curyear];
            if (value === undefined || value === null){

            } else {
              data.addRow([circles[i].countryname, parseInt(value)]);
            }

          } catch (err){
          }
        }


        var table = new google.visualization.Table(document.getElementById('table-chart-placeholder'));
        table.draw(data, {
          //showRowNumber: true,
          sortColumn: 1,
          sortAscending: false
        });
      }

// HIDDEN FOR GC MEETING
  //   $("#project-share-graph").click(function(){

  //   if($('#dropdown-type-graph').is(":hidden")){
  //       $('#dropdown-type-graph').show("blind", { direction: "vertical" }, 200);
  //   } else {
  //       $('#dropdown-type-graph').hide("blind", { direction: "vertical" }, 200);
  //   }
  //   return false;
  // });


    $('#graph-button-treemap').click(function(){
      $('#treemap-placeholder').show();
      google.load("visualization", "1", {packages:["treemap"], callback:drawTreemap});
      $('#map').hide();
      return false;
    });

    $('#graph-button-map').click(function(){
      $('#map').show();
      $('#treemap-placeholder').hide();
      return false;
    });

    $('#graph-button-graph').click(function(){
      show_line_graph();
      return false;
    });

    $('#dropdown-line-graph').click(function(){
      show_line_graph();
      $('#dropdown-type-graph').hide();
      return false;
    });

    $('#graph-button-table').click(function(){
      show_table_graph();
      return false;
    });

    $('#dropdown-table-graph').click(function(){
      show_table_graph();
      $('#dropdown-type-graph').hide();
      return false;
    });

    function hide_all_graphs(){
      $('#line-chart-placeholder').hide();
      $('#table-chart-placeholder').hide();
      $('#treemap-placeholder').hide();
      $('#map').show();
    }

    function show_line_graph(){
      hide_all_graphs();
      $('#line-chart-placeholder').show();
      $('html, body').animate({
        scrollTop: ($('#line-chart-placeholder').offset().top - 150)
      }, 1000);
      google.load("visualization", "1", {packages:["corechart"], callback:drawLineChart});
      
    }

    function show_table_graph(){
      hide_all_graphs();
      $('#table-chart-placeholder').show();
      $('html, body').animate({
        scrollTop: ($('#table-chart-placeholder').offset().top - 150)
      }, 1000);
      google.load("visualization", "1", {packages:["table"], callback:drawTableChart});
    }

    
    


// });
