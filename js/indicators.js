
// XXXXXXXXXXXXXXX INDICATORS XXXXXXXXXXXXXXXX



jQuery(function($) {
 	  




// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX
  
  // first indicator circles
  var circles = [];

  // We run this function on each filter save.
  function initialize_map(indicator_id, countries, regions, cities){
    // show loader, hide map
    $('#map-loader').show();
    $('#map').hide();

    // clear current circles
    clear_circles();
    circles = [];

    // get data
    var indicator_data = get_indicator_data(indicator_id, countries, regions, cities);
    
    
    // end
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


//   var url = 'http://dev.oipa.openaidsearch.org/json';
//   $.ajax({
//     dataType: 'jsonp',
//     data: 'json',
//     jsonp: 'jsonp_callback',
//     url: 'http://localhost:5001/listener.php',
//     success: retorno
//   });


// function retorno(){
//   alert: "worked";
// }

// --$.getJSON("http://localhost:5001/listener.php?function=testjsonp&callback=?",retorno);


//   $.ajax({
//    type: 'GET',
//     url: url,
//     async: false,
//     jsonpCallback: 'jsonCallback',
//     contentType: "application/json",
//     dataType: 'jsonp',
//     success: function(json) {
//        console.dir(json.sites);
//     },
//     error: function(e) {
//        console.log(e.message);
//     }
// });




  // var url = 'http://dev.oipa.openaidsearch.org/json';
  //  $.ajax({
  //      type: 'GET',
  //       url: url,
  //       async: false,
  //       contentType: "application/json",
  //       dataType: 'json'
  //   }, function(data){
  //     alert("test");
  //   });

   

  function get_indicator_data(indicator_id, countries, regions, cities){
     var url = 'http://dev.oipa.openaidsearch.org/json';
     

    $.get(url, function(data){

      // check for what years data is available and add the right classes to the slider year blocks
      draw_available_data_blocks(data);

      // draw the circles
      draw_circles(data);

      // set current year, temp: set to 2010
      $( "#map-slider-tooltip a" ).append("2010");
      $( "#map-slider-tooltip" ).slider( "option", "value", "2010"); 
      $( "#year-2010").addClass("active");

      // hide loader, show map
      $('#map').show();
      $('#map-loader').hide();
      });
     
  }

  function draw_available_data_blocks(indicator_data){
    for (var i=1950;i<2051;i++){
      var curyear = "y" + i;
      $.each(indicator_data, function(key, value){
          
          if (curyear in value.years){
            $("#year-" + i).addClass("slider-active");
            return false;
          }
        
      });
    }
  }

  function draw_circles(indicator_data){
    $.each(indicator_data, function(key, value){

      try{
        var circle = L.circle(new L.LatLng(value.longitude, value.latitude), 100000, {
          color: 'red',
          weight: '0',
          fillColor: 'red',
          fillOpacity: 0.6
          }).addTo(map);

          circle.bindPopup(value.name);
          circle.on('mouseover', function(evt) {
            evt.target.openPopup();
          });
          circle.on('mouseout', function(evt) {
            evt.target.closePopup();
          });

          var singlecircleinfo = new Object();
          singlecircleinfo.countryname = value.name;
          singlecircleinfo.values = value.years;
          singlecircleinfo.circleinfo = circle;
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
    var max = 0;
    for (var i=0;i<circles.length;i++)
    { 
      try{
        if(circles[i].values[curyear] > max){
          max = circles[i].values[curyear];
        }
      } catch (err){
      }
    }

    var factor = Math.round(600000 / max);

    for (var i=0;i<circles.length;i++)
    { 
      try{
        //circles[i].unbindPopup();
        //circles[i].bindPopup(circles[i].values[curyear]);
       // <?php echo sqrt(($factor * $i[$selected_indicator])/pi()); //echo round($factor * $i['population']); ?>

        //var circle_radius = Math.round(Math.sqrt(factor * circles[i].values[curyear]) / Math.PI);
        //console.log("radius:" + circle_radius);

        circles[i].circleinfo.setRadius(circles[i].values[curyear] * 2000);
      } catch (err){
        //console.log(err);
        //circles[i].circleinfo.setRadius(0);
      }
    }
  }

  $(".slider-year").hover(
    function() {
      var curId = $(this).attr('id');
      var curYear = curId.replace("year-", "");
      refresh_circles(curYear);
      $( "#map-slider-tooltip" ).slider( "option", "value", curYear); 
      $( ".slider-year").removeClass("active");
      $(this).addClass("active");
  });

  $("#project-share-export").click(function(){

    initialize_map("","", "", "");
    return false;
  });
  




  initialize_map("","", "", "");






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

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          backgroundColor: '#F1EEE8'
        };

        var chart = new google.visualization.LineChart(document.getElementById('line-chart-placeholder'));
        chart.draw(data, options);
      
      }

      function drawTableChart(){

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Salary');
        data.addColumn('boolean', 'Full Time Employee');
        data.addRows([
          ['Mike',  {v: 10000, f: '$10,000'}, true],
          ['Jim',   {v:8000,   f: '$8,000'},  false],
          ['Alice', {v: 12500, f: '$12,500'}, true],
          ['Bob',   {v: 7000,  f: '$7,000'},  true]
        ]);

        var table = new google.visualization.Table(document.getElementById('table-chart-placeholder'));
        table.draw(data, {showRowNumber: true});
      }

    $("#project-share-graph").click(function(){

    if($('#dropdown-type-graph').is(":hidden")){
        $('#dropdown-type-graph').show("blind", { direction: "vertical" }, 200);
    } else {
        $('#dropdown-type-graph').hide("blind", { direction: "vertical" }, 200);
    }
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

    
    


});