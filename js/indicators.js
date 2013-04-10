
// XXXXXXXXXXXXXXX INDICATORS XXXXXXXXXXXXXXXX



// jQuery(function($) {
    




// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX
  
  // first indicator circles
  var circles = [];
  var maxdatavalue = 0;
  var request_url = ""
var selected_type = "";
var indicator_data;



  // We run this function on each filter save.
  function initialize_map(url, sel_year, type, indicator_id, countries, regions, cities){
    // show loader, hide map
    if(type){
    selected_type = type;

    }
    request_url = url;
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

    // set current year, temp: set to 2010
    $( "#map-slider-tooltip a" ).append(sel_year);
    $( "#map-slider-tooltip" ).slider( "option", "value", sel_year); 
    $( "#year-" + sel_year).addClass("active");
    refresh_circles(sel_year.toString());

    // hide loader, show map
    $('#map').show(); 
    $('#map-loader').hide();
    // end
    //load filters depending on page
    if(type=='cpi'){
        set_filters_cpi(indicator_data);
    }
    if (type=='indicator'){
        set_filters_indicator(indicator_data);
    }

    return indicator_data
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
    region_html = create_filter_attributes(data['regions'], data['regions']);
    $('#region_filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], data['countries']);
    $('#country_filters').html(country_html);

    // city_html = create_filter_attributes(data['cities'], data['cities']);
    // $('#city_filters').html(city_html);]);
    indicator_html = create_filter_attributes(data['indicators'], data['indicators']);
    $('#indicator_filters').html(indicator_html);
  }

  function set_filters_cpi(data){
    region_html = create_filter_attributes(data['regions'], data['regions']);
    $('#region_filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], data['countries']);
    $('#country_filters').html(country_html);

    city_html = create_filter_attributes(data['cities'], data['cities']);
    $('#city_filters').html(city_html);

    indicator_html = create_filter_attributes(data['indicators'], data['indicators']);
    $('#indicator_filters').html(indicator_html);
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
    console.log(indicator_data);
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

          // circle.bindPopup('<h4>'+value.name+'</h4><p>Population: ' + value.years['y'+selected_year] + '</p>');
          // circle.on('mouseover', function(evt) {
          //   evt.target.openPopup();
          // });
          // circle.on('mouseout', function(evt) {
          //   evt.target.closePopup();
          // });

          var singlecircleinfo = new Object();
          singlecircleinfo.countryiso2 = key;
          singlecircleinfo.countryname = value.name;
          singlecircleinfo.values = value.years;
          singlecircleinfo.circleinfo = circle;
          singlecircleinfo.indicator = value.indicator;
          circles.push(singlecircleinfo);

      }catch(err){
          console.log(err);
      }
    });
  }


  
// XXXXXXXXXXXXXX INDICATOR SLIDER XXXXXXXXXXXXXXXXX


  $( "#map-slider-tooltip" ).slider({ 
    min: 1950,
    max: 2050,
    slide: slide_tooltip,
    change: change_tooltip
  });
  
  
  function change_tooltip(event, ui){
    $( "#map-slider-tooltip a" ).text(ui.value);

    // try{
    //     if(event.originalEvent.type.toString() == 'mouseup'){
    //         console.log(event.originalEvent);
    //         console.log(event.originalEvent.currentTarget.activeElement.childNodes[0].data);
    //         select_year(event.originalEvent.currentTarget.activeElement.childNodes[0].data);
    //     }
    // }
        
    // catch(err){
        
    // }
  }


  function slide_tooltip(event, ui){
    

    refresh_circles(ui.value);
    $( "#map-slider-tooltip a" ).text(ui.value);
    $( ".slider-year").removeClass("active");
    $( "#year-" + ui.value).addClass("active");
   // $( "#year-" + (ui.value+1)).removeClass("active");
   // $( "#year-" + (ui.value-1)).removeClass("active");
  }

  function refresh_circles(year){
    var curyear = "y" + year;
    var max = maxdatavalue;
    // console.log(max);

    var factor = Math.round(2000000000000 / max);

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
          circles[i].circleinfo.bindPopup('<h4>'+circles[i].countryname+'</h4><p>' + circles[i].indicator + ': ' + value + '</p>');
          circles[i].circleinfo.on('mouseover', function(evt) {
            evt.target.openPopup();
          });

        }

      } catch (err){
        //console.log(err);
      }
    }
    
  }

  // $(".slider-year").hover(
  //   function() {
  //     var curId = $(this).attr('id');
  //     var curYear = curId.replace("year-", "");
  //     refresh_circles(curYear);
  //     $( "#map-slider-tooltip" ).slider( "option", "value", curYear); 
  //     $( ".slider-year").removeClass("active");
  //     $(this).addClass("active");
  // });

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

    $("#project-share-graph").click(function(){

    if($('#dropdown-type-graph').is(":hidden")){
        $('#dropdown-type-graph').show("blind", { direction: "vertical" }, 200);
    } else {
        $('#dropdown-type-graph').hide("blind", { direction: "vertical" }, 200);
    }
    return false;
  });


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
