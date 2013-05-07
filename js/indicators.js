// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX
  
// global variables
var circles = {};
var maxcirclearea = 2000000000000;


// CIRCLE STRUCTURE IS AS FOLLOWS
// circles.indicators.<indicatorname>  this is the key of an indicator
// circles.indicators.<indicatorname>.name
// circles.indicators.<indicatorname>.description
// circles.indicators.<indicatorname>.name
// circles.indicators.<indicatorname>.max_value
// circles.indicators.<indicatorname>.type_data

// circles.countries.<countryid> this is the key of a country (iso2 code)
// circles.countries.<countryid>.countryname
// circles.countries.<countryid>.<indicatorname> holds the data per country per indicator
// circles.countries.<countryid>.<indicatorname>.circle  is the circle on the map
// circles.countries.<countryid>.<indicatorname>.years holds the years where data is available for within this indicator
// circles.countries.<countryid>.<indicatorname>.years
// circles.countries.<countryid>.<indicatorname>.years.<y1950 untill y2050, only if data is available>

// We run this function on each filter save.
function initialize_map(url){
    
    var sel_year = 2015;

    //set max area depending on page
    if(selected_type=='cpi'){
        maxcirclearea = 500000000000;
        sel_year = 2012;
    }
    if (selected_type=='indicator'){
        maxcirclearea = 2000000000000;
    }

    // clear current circles
    clear_circles();
    circles = {};
    
    
    // set current year
    $( "#map-slider-tooltip div" ).html(sel_year);
    $( "#map-slider-tooltip" ).val(sel_year);
    $( "#year-" + sel_year).addClass("active");

    init_circle_structure();
    init_circle_main_info();

    if (selected_type=='indicator'){
      if(!(typeof current_selection.indicators === 'undefined')){
          var arr = current_selection.indicators;
          if(arr.length > 0){
              for(var i = 0; i < arr.length; i++){
                  var cururl = create_api_url_indicator(arr[i].id);
                  var indicator_data = get_indicator_data(cururl);
                  draw_available_data_blocks(indicator_data, i);
                  init_circles(indicator_data);
              }
          }
      }
    } else {
      var indicator_data = get_indicator_data(url);
      init_circles(indicator_data);
      refresh_circles(2012);
    }

    // hide loader, show map
    $('#map').show(); 
    $('#map-loader').hide();

    //load filters depending on page
    if(selected_type=='cpi'){
        set_filters_cpi(indicator_data);
    }
    if (selected_type=='indicator'){
        set_filters_indicator(indicator_data);
    }

    return indicator_data;
}

function create_api_url_indicator(indicatorid){

  var dlmtr = ',';
  var str_country = reload_map_prepare_parameter_string("countries", dlmtr);
  var str_region = reload_map_prepare_parameter_string("regions", dlmtr);
  var str_city = reload_map_prepare_parameter_string("cities", dlmtr);

  if (selected_type=='indicator'){
    return site + 'json?countries=' + str_country + '&regions=' + str_region + '&city=' + str_city + '&indicator=' + indicatorid;
  } else if (selected_type=='cpi'){

    return site + 'json-city?countries=' + str_country + '&regions=' + str_region + '&city=' + str_city + '&indicator=' + indicatorid;
  }
}

function clear_circles(){

  if(!(circles.countries === undefined)){
      $.each(circles.countries, function(ckey, cvalue){
          $.each(circles.indicators, function(ikey, ivalue){
              if(!(cvalue[ikey] === undefined)){
                  map.removeLayer(cvalue[ikey].circle);
              }
          });
      });
  }
}

function get_indicator_data(url){
    
    var indicator_json_data = [];

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

function draw_available_data_blocks(indicator_data, keep_active){
    
    if(!keep_active){
        $('.slider-year').removeClass('slider-active');
    }

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

function init_circles(data_indicators){
    init_circles_by_country(data_indicators);
}

function init_circle_structure(){
    circles.indicators = {};
    circles.countries = {};
}

function init_circle_main_info(){
    init_circle_main_info_indicator(0, "#2B5A70");
    init_circle_main_info_indicator(1, "DarkGreen");
    init_circle_main_info_indicator(2, "Orange");
}

function init_circle_main_info_indicator(arrid, color){

    if(!(current_selection.indicators[arrid] === undefined)){

        var ind_id = current_selection.indicators[arrid].id;
        circles.indicators[ind_id] = {};
        circles.indicators[ind_id].name = current_selection.indicators[arrid].name;
        circles.indicators[ind_id].color = color;
    }
}

function init_circles_by_country(data_indicators){

    $.each(data_indicators, function(key, value){
        if (key.length > 5 || value.longitude == null || value.latitude == null){return true;}
        try{

            // this shouldnt have to be done for every circle but with the current API call it is:

            //main indicator info
            if (!(value.indicator_friendly === undefined && value.type_data === undefined)){
            circles.indicators[value.indicator].description = value.indicator_friendly;
            circles.indicators[value.indicator].type_data = value.type_data;
            } else { // TO DO: this is temp untill city prosperity has friendly labels in the API call
              circles.indicators[value.indicator].description = circles.indicators[value.indicator].name;
              circles.indicators[value.indicator].type_data = "p";
            }


            if (value.max_value){
                circles.indicators[value.indicator].max_value = value.max_value;
            }

            // circle info
            if(!circles.countries[key]){circles.countries[key] = {};}
            if(!circles.countries[key][value.indicator]){circles.countries[key][value.indicator] = {};}

            circles.countries[key][value.indicator].years = value.years;

            var circle = L.circle(new L.LatLng(value.latitude, value.longitude), 1, {
                color: circles.indicators[value.indicator].color,
                weight: '0',
                fillColor: circles.indicators[value.indicator].color,
                fillOpacity: 0.7
            }).addTo(map);

            circles.countries[key][value.indicator].circle = circle;


            // main country info
            circles.countries[key].countryname = value.name;
            //circles.countries[key].countryregion = value.region;
          

        }catch(err){

            console.log(err);
        }
    });

}


function refresh_circles(year){
    var curyear = "y" + year;

    if(!(circles.countries === undefined)){
        $.each(circles.countries, function(ckey, cvalue){

            var popuptext = '<h4>'+cvalue.countryname+'</h4>';
            // create pop-up text
            $.each(circles.indicators, function(pkey, pvalue){
                if(!(cvalue[pkey] === undefined)){


                    var score = cvalue[pkey].years[curyear];

                    popuptext += '<p>' + pvalue.description + ': ' + score + '</p>';
                }
            });

            // set radius size and pop-up text
            $.each(circles.indicators, function(ikey, ivalue){
                if(!(cvalue[ikey] === undefined)){

                    var circle = cvalue[ikey].circle;
                    var score = cvalue[ikey].years[curyear];
                    if (!(score === undefined)){
                        circle_radius = Math.round(Math.sqrt(((Math.round(maxcirclearea / ivalue.max_value)) * score) / Math.PI));
                        circle.setRadius(circle_radius); 
                    } else {
                      circle.setRadius(1);
                    }
                    circle.bindPopup(popuptext);
                    
                }
            });

        });
    }
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
}


function set_filters_indicator(data){
    
    region_html = create_filter_attributes(data['regions'], 4);
    $('#regions-filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], 4);
    $('#countries-filters').html(country_html);

    city_html = create_filter_attributes(data['cities'], 4);
    $('#cities-filters').html(city_html);

    indicator_html = create_filter_attributes(data['indicators'], 3);
    $('#indicators-filters').html(indicator_html);
}

function set_filters_cpi(data){

    region_html = create_filter_attributes(data['regions'], 4);
    $('#regions-filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], 4);
    $('#countries-filters').html(country_html);

    city_html = create_filter_attributes(data['cities'], 4);
    $('#cities-filters').html(city_html);

    indicator_html = create_filter_attributes(data['indicators'], 4);
    $('#indicators-filters').html(indicator_html);
}

$(".slider-year").click(function() {
    var curId = $(this).attr('id');
    var curYear = curId.replace("year-", "");
    refresh_circles(curYear);
    $( "#map-slider-tooltip" ).val(parseInt(curYear));
    $( "#map-slider-tooltip div" ).text(curYear);
    $( ".slider-year").removeClass("active");
    $(this).addClass("active");
}); 




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
      ['Germany',   'Europe',             31]
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

function getLineChartData(indicatorname){

  var firstyear = 0;
  var lastyear = 0;

  var currentData = [];
  var lineChartData = [];

  var lineChartHeader = [];
  lineChartHeader.push('Year');

  $.each(circles.countries, function(key, value){
    if(!(value[indicatorname] === undefined)){
      lineChartHeader.push(value.countryname);
    }
  });

  currentData.push(lineChartHeader);

  // walk through the years
  for (var i=1950;i<2051;i++){
    var hasDataInThisYear = false;
    var lineChartLine = [];
    lineChartLine.push(i.toString());

    // per country
    $.each(circles.countries, function(key, value){

      // if country has values for this indicator
      if(!(value[indicatorname] === undefined)){
        

        var curvalue = value[indicatorname].years["y" + i.toString()];

        // add null if no value for current year, else add the year
        if (curvalue === undefined || curvalue === null){ curvalue = null; } else{
          hasDataInThisYear = true;
          if (firstyear == 0){
            firstyear = i;
          }

          lastyear = i;
          
          if (circles.indicators[indicatorname].type_data == '1000'){
            curvalue = curvalue * 1000;
          }
        }
        lineChartLine.push(curvalue);
      }
    });
    if (hasDataInThisYear){
      currentData.push(lineChartLine);
    }
    
  }

  return currentData;
}

function drawLineChart(){

  var indicatornumber = 0;
  $.each(circles.indicators, function(key, value){
    
    indicatornumber = indicatornumber + 1;

    $('#line-chart-placeholder' + indicatornumber).css('height', '36em');
    $('#line-chart-name' + indicatornumber).text(value.description);

    data = google.visualization.arrayToDataTable(getLineChartData(key));  

    var columnsTable = new google.visualization.DataTable();
    columnsTable.addColumn('number', 'colIndex');
    columnsTable.addColumn('string', 'colLabel');
    var initState = {selectedValues: []};
    // put the columns into this data table (skip column 0)
    for (var i = 1; i < data.getNumberOfColumns(); i++) {
        columnsTable.addRow([i, data.getColumnLabel(i)]);
    }

    // Create a pie chart, passing some options
    var lineChart = new google.visualization.ChartWrapper({
      chartType: 'LineChart',
      containerId: 'line-chart-placeholder' + indicatornumber,
      dataTable: data,  
      options: {
        chartArea: {
          left: 100,
          top: 20
        },
        backgroundColor: '#F1EEE8',
        interpolateNulls: true,
        fontName: 'HelveticaNeueW02-55Roma' 
      }
    });
    lineChart.draw();

    var columnFilter = new google.visualization.ControlWrapper({
      controlType: 'CategoryFilter',
      containerId: 'line-chart-filter' + indicatornumber,
      dataTable: columnsTable,
      options: {  
          filterColumnLabel: 'colLabel',
          ui: {
              caption: 'Choose a country', 
              label: '',
              allowTyping: false,
              allowMultiple: true,
              selectedValuesLayout: 'below',
              labelStacking: 'horizontal'
          }
      },
      state: initState
    });
    columnFilter.draw();
    
    google.visualization.events.addListener(columnFilter, 'statechange', function () {

        var state = columnFilter.getState();
        var row;
        var columnIndices = [0];
        for (var i = 0; i < state.selectedValues.length; i++) {
            row = columnsTable.getFilteredRows([{column: 1, value: state.selectedValues[i]}])[0];
            columnIndices.push(columnsTable.getValue(row, 0));
        }
        // sort the indices into their original order
        columnIndices.sort(function (a, b) {
            return (a - b);
        });
        lineChart.setView({columns: columnIndices});
        lineChart.draw();
        $.each( $("#line-chart-placeholder1 text"), function() {
          var text = $(this).text();
          $(this).text(text.replace(/,/g,"."));
        });
    });


    $.each( $("#line-chart-placeholder1 text"), function() {
       var text = $(this).text();
       $(this).text(text.replace(/,/g,"."));
    });
  });
}


function getMultiIndicatorChartData(year){





}

  
function getTableChartData(year, cpi){

  var curyear = "y" + year;

  var data = new google.visualization.DataTable();
  if(cpi){
    // cpi is about cities
    data.addColumn('string', 'Cities');
  } else {
    // indicators about countries
    data.addColumn('string', 'Country');
  }

  $.each(circles.indicators, function(key, value){

      data.addColumn('number', value.description);  
  });

  if(!(circles.countries === undefined)){
      $.each(circles.countries, function(ckey, cvalue){

        var current_row = [];
        current_row.push(cvalue.countryname);

        $.each(circles.indicators, function(key, value){
            if(!(cvalue[key] === undefined)){

                var score = null;
                datacel_info = null;

                if(!(cvalue[key].years[curyear] === undefined)){
                  score = cvalue[key].years[curyear];

                  if (value.type_data == '1000'){
                    score = score * 1000;
                  }

                  var formatted_score = CommaFormatted(score+'.');
                  var datacel_info = {"v": score, "f": formatted_score};
                }
                
                current_row.push(datacel_info);
            } else{
              current_row.push(null);
            }
        });

        // dont add if all values are null, else add row
        for (var i = 1; i < current_row.length;i++){
          if (current_row[i] != null){
            data.addRow(current_row);
            break;
          }
        }
        

      });
  }

  return data;
}

function getTableYearOptions(){
  var columnsTable = new google.visualization.DataTable();
  columnsTable.addColumn('number', 'colIndex');
  columnsTable.addColumn('string', 'colLabel');

  $('.slider-year.slider-active').each(function(index, value){ 

      columnsTable.addRow([index, value.id.replace("year-", "")]);
  });
  return columnsTable;
}

function drawTableChart(){

  var curyear = $(".ui-slider-handle").html();
  var first_available_year = get_first_available_year(2015);
  var data = getTableChartData(first_available_year);
  
  var columnsTable = getTableYearOptions();

  var tableChart = new google.visualization.ChartWrapper({
    chartType: 'Table',
    containerId: 'table-chart-placeholder',
    dataTable: data,
    options: {
      showRowNumber: true,
      sortColumn: 1,
      sortAscending: false
    }
  });

  tableChart.draw();

  var columnFilterT = new google.visualization.ControlWrapper({
    controlType: 'CategoryFilter',
    containerId: 'table-chart-filter',
    dataTable: columnsTable,
    options: {  
        filterColumnLabel: 'colLabel',
        ui: {
            caption: 'Choose a year', 
            label: '',
            allowTyping: false,
            allowMultiple: false,
            selectedValuesLayout: 'below',
            labelStacking: 'horizontal'
        }
    }
  });
  columnFilterT.draw();
  
  google.visualization.events.addListener(columnFilterT, 'statechange', function () {

      var state = columnFilterT.getState();
      var curyear = state.selectedValues[0];
      var curdata = getTableChartData(curyear);
      tableChart.setDataTable(curdata);
      tableChart.draw();
  });
}

function drawCpiTableChart(){

  var data = getTableChartData(2012, true);
  
  var tableChart = new google.visualization.ChartWrapper({
    chartType: 'Table',
    containerId: 'table-chart-placeholder',
    dataTable: data,
    options: {
      showRowNumber: true,
      sortColumn: 1,
      sortAscending: false
    }
  });

  tableChart.draw();
}

function initialize_charts(){
  google.load("visualization", "1", {packages:["corechart", "controls"], callback:drawLineChart});
  google.load("visualization", "1", {packages:["table", "controls"], callback:drawTableChart});
}

function initialize_cpi_charts(){
  google.load("visualization", "1", {packages:["table", "controls"], callback:drawCpiTableChart});
}



function getImgData(chartContainer) {
  var chartArea = chartContainer.getElementsByTagName('svg')[0].parentNode;    
  var svg = chartArea.innerHTML;
  var doc = chartContainer.ownerDocument;
  var canvas = doc.createElement('canvas');
  canvas.setAttribute('width', chartArea.offsetWidth);
  canvas.setAttribute('height', chartArea.offsetHeight);

  canvas.setAttribute(
      'style',
      'position: absolute; ' +
      'top: ' + (-chartArea.offsetHeight * 2) + 'px;' +
      'left: ' + (-chartArea.offsetWidth * 2) + 'px;');
  doc.body.appendChild(canvas);
  canvg(canvas, svg);
  var imgData = canvas.toDataURL("image/png");
  canvas.parentNode.removeChild(canvas);
  return imgData;
}

function saveAsImg(chartContainer) {
  var imgData = getImgData(chartContainer);
  
  // Replacing the mime-type will force the browser to trigger a download
  // rather than displaying the image in the browser window.
  //window.location = imgData.replace("image/png", "image/octet-stream");
  window.open(imgData, 'new_window');
}

function toImg(chartContainer, imgContainer) { 
  var doc = chartContainer.ownerDocument;
  var img = doc.createElement('img');
  img.src = getImgData(chartContainer);
  
  while (imgContainer.firstChild) {
    imgContainer.removeChild(imgContainer.firstChild);
  }
  imgContainer.appendChild(img);
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
  // hide_all_graphs();
  // $('#line-chart-placeholder').show();
  $('html, body').animate({
    scrollTop: ($('#line-chart-placeholder').offset().top - 150)
  }, 1000);
  // google.load("visualization", "1", {packages:["corechart", "controls"], callback:drawLineChart});
  
}

function show_table_graph(){
  // hide_all_graphs();
  // $('#table-chart-placeholder').show();
  $('html, body').animate({
    scrollTop: ($('#table-chart-placeholder').offset().top - 150)
  }, 1000);
  // google.load("visualization", "1", {packages:["table"], callback:drawTableChart});
}

$('#dropdown-line-graph-png').click(function(){
    saveAsImg(document.getElementById('line-chart-placeholder'));
});


        
$('#dropdown-png').click(function() {
    
    setTimeout(function() {
        $('#map').html2canvas({
            flashcanvas: "/wp-content/themes/OPEN-UN-HABITAT-V2/js/dependencies/flashcanvas.min.js",
            logging: false,
            profile: false,
            useCORS: true
        });
    }, 1000);
    
});

function manipulateCanvasFunction(savedMap) {
    dataURL = savedMap.toDataURL("image/png");
    dataURL = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    $.post("/wp-content/themes/OPEN-UN-HABITAT-V2/ajax/saveMap.php", { savedMap: dataURL }, function(data) {
        alert('Image Saved to : ' + data);
    });
}

    
