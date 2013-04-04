
// XXXXXXXXXXXXXXX INDICATORS XXXXXXXXXXXXXXXX



jQuery(function($) {
 	  

// XXXXXXXXXXXXXX INDICATOR SLIDER XXXXXXXXXXXXXXXXX


  $( "#map-slider-tooltip" ).slider({ 
    min: 1950,
    max: 2050,
//    value: 2013,
    slide: slide_tooltip,
    change: change_tooltip
  });
  
  

  //$( "#map-slider-tooltip a" ).append("2013");
  //$( "#year-2013").addClass("active");
  
  
  
  function change_tooltip(event, ui){
    $( "#map-slider-tooltip a" ).text(ui.value);
//    console.log(event);
    try{
        if(event.originalEvent.type.toString() == 'mouseup'){
            console.log(event.originalEvent);
            console.log(event.originalEvent.currentTarget.activeElement.childNodes[0].data);
            select_year(event.originalEvent.currentTarget.activeElement.childNodes[0].data);
        }
    }
        
    catch(err){
        
    }
    
    
  }
  

  function slide_tooltip(event, ui){
    $( "#map-slider-tooltip a" ).text(ui.value);
    $( ".slider-year").removeClass("active");
    $( "#year-" + ui.value).addClass("active");
   // $( "#year-" + (ui.value+1)).removeClass("active");
   // $( "#year-" + (ui.value-1)).removeClass("active");
  }

  $(".slider-year").hover(
    function() {
      var curId = $(this).attr('id');
      var curYear = curId.replace("year-", "");
      $( "#map-slider-tooltip" ).slider( "option", "value", curYear); 
      $( ".slider-year").removeClass("active");
      $(this).addClass("active");
  });




// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX

  // // var indicator1_years = new Array(); 
  // $.each(result[0], function(key, value){
  //   console.log(key, value);
  // }

  // $.each(result[0], function(key, value){
  //   console.log(key, value);
  // }

  // try{      
  //   var circle = L.circle(new L.LatLng(countryloc['<?php echo $i['country_iso'] ?>'].longitude, countryloc['<?php echo $i['country_iso'] ?>'].latitude), <?php echo sqrt(($factor * $i[$selected_indicator])/pi()); //echo round($factor * $i['population']); ?>, {
  //     color: 'orange',
  //     weight: '0',
  //     fillColor: '#f03',
  //     fillOpacity: 0.5
  //     }).addTo(map);
  //     circle.bindPopup('<?php echo $selected_indicator?>: <?php echo $i[$selected_indicator]?>');
  //     indicator1_years.push(circle);

  // }catch(err){
  //     console.log(err);
  // }

  









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