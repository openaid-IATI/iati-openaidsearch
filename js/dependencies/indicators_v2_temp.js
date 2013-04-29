// XXXXXXXXXXXXX INDICATOR INITIALIZATION XXXXXXXXXXX
  
// global variables
var circles = [];
var maxdatavalue = 0;
var maxcirclearea = 2000000000000;
var request_url = "";
var start_year = 2015;

// We run this function on each filter save.
function initialize_map(url, type){
    
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


    // set current year
    $( "#map-slider-tooltip div" ).html(start_year);
    $( "#map-slider-tooltip" ).val(start_year);
    $( "#year-" + start_year).addClass("active");

    indicator_data = get_indicator_data(url);
    draw_available_data_blocks(indicator_data);
    //
    init_circles(indicator_data);
    
    // if (indicator_str_filter.length > 0){
    //     var indicator_param = indicator_str_filter;
    //     for (var i = indicator_param.length - 1; i >= 0; i--) {
          
    //         if (i == 0){
    //             // get data
    //             indicator_data = get_indicator_data(request_url, indicator_param[i], countries, regions, cities);
    //             // check for what years data is available and add the right classes to the slider year blocks
    //             draw_available_data_blocks(indicator_data);
    //             // draw the circles
    //             max_value_1 = draw_circles(indicator_data, '#2B5A70',1);            
    //             refresh_circles(sel_year.toString(), max_value_1);

    //         }
    //         if (i==1){
    //             // //getting data for second indicator
    //             indicator_second = get_indicator_data(request_url,indicator_param[i], countries, regions, cities);
    //             // //draw second indicator
    //             max_value_2 = draw_circles(indicator_second, 'DarkGreen', 2);
    //             // //refresh second indicator
    //             refresh_circles(sel_year.toString(), max_value_2);
    //         }
    //     }
    // }else{
    //     // get data
    //     indicator_data = get_indicator_data(request_url,indicator_id, countries, regions, cities);            
    //     // console.log(indicator_second);
    //     // check for what years data is available and add the right classes to the slider year blocks
    //     draw_available_data_blocks(indicator_data);
    //     // draw the circles
    //     max_value_1 = draw_circles(indicator_data, '#2B5A70',1);
    //     refresh_circles(sel_year.toString(), max_value_1);
    // }
    
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


//  circles structure:
//  circle.indicator1.id
//  circle.indicator1.name
//  circle.indicator1.color
//  circle.indicator2.id
//  circle.indicator2.name
//  circle.indicator2.color
//  circle.countries <-- loop through this. Key is iso2 code.
//  circle.countries.AF.countryname
//  circle countries.AF.region <-- TO BE ADDED
//  circle.countries.AF.yearsindicator1
//  circle.countries.AF.yearsindicator1.1950
//  circle.countries.AF.yearsindicator1.1951
//  circle.countries.AF.yearsindicator2
//  circle.countries.AF.yearsindicator2.1950
//  circle.countries.AF.yearsindicator2.1951




function init_circle_main_info(){
    
    init_circle_main_info_ind(0);
    init_circle_main_info_ind(1);
}

function init_circle_main_info_ind(arrid){

    if(!(current_selection.indicators[arrid].id === undefined)){
        var ind_id = current_selection.indicators[arrid].id;
        circle.indicators[ind_id].name = current_selection.indicators[arrid].name;
        circle.indicators[ind_id].maxvalue = 100000;
    }
}

function init_circles(data_indicators){

    circles.


    var max_data_value = 0;
    $.each(data_indicators, function(key, value){

        try{

            var circle = L.circle(new L.LatLng(value.longitude, value.latitude), 1, {
                color: color,
                weight: '0',
                fillColor: color,
                fillOpacity: 0.7
            }).addTo(map);







            if (value.max_value){
                max_data_value = value.max_value;
            }

            

            var singlecircleinfo = new Object();
            singlecircleinfo.countryiso2 = key;
            singlecircleinfo.countryname = value.name;
            singlecircleinfo.values = value.years;
            singlecircleinfo.circleinfo = circle;
            singlecircleinfo.max_data_value = max_data_value;
            singlecircleinfo.indicator = value.indicator;
            singlecircleinfo.friendly_label = value.indicator_friendly;
            singlecircleinfo.first_or_second_indicator = first_or_second_indicator;
            singlecircleinfo.second_indicator_name = '';
            singlecircleinfo.second_indicator_value = '';
          
            singlecircleinfo.type_data = value.type_data;
            circles.push(singlecircleinfo);

        }catch(err){
        //console.log(err);
        }
    });
    return max_data_value;
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

function draw_circles(data_indicators, color, first_or_second_indicator){
    var max_data_value = 0;
    $.each(data_indicators, function(key, value){

        try{
            if (value.max_value){
                max_data_value = value.max_value;
            }

            var circle = L.circle(new L.LatLng(value.longitude, value.latitude), 1, {
                color: color,
                weight: '0',
                fillColor: color,
                fillOpacity: 0.7
            }).addTo(map);

            var singlecircleinfo = new Object();
            singlecircleinfo.countryiso2 = key;
            singlecircleinfo.countryname = value.name;
            singlecircleinfo.values = value.years;
            singlecircleinfo.circleinfo = circle;
            singlecircleinfo.max_data_value = max_data_value;
            singlecircleinfo.indicator = value.indicator;
            singlecircleinfo.friendly_label = value.indicator_friendly;
            singlecircleinfo.first_or_second_indicator = first_or_second_indicator;
            singlecircleinfo.second_indicator_name = '';
            singlecircleinfo.second_indicator_value = '';
          
            singlecircleinfo.type_data = value.type_data;
            circles.push(singlecircleinfo);

        }catch(err){
        //console.log(err);
        }
    });
    return max_data_value;
}






function set_filters_indicator(data){
    
    region_html = create_filter_attributes(data['regions'], 4);
    $('#regions-filters').html(region_html);

    country_html = create_filter_attributes(data['countries'], 4);
    $('#countries-filters').html(country_html);

    // city_html = create_filter_attributes(data['cities'], 4);
    // $('#cities-filters').html(city_html);

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

function refresh_circles(year){
    var curyear = "y" + year;

    for (var i=0;i<circles.length;i++)
    { 
        try{
            //circles[i].unbindPopup();
            //circles[i].bindPopup(circles[i].values[curyear]);

            var value = circles[i].values[curyear];

            if (value === undefined || value === null){
            //  circles[i].circleinfo.setRadius(0);
            } else {
                circle_radius = Math.round(Math.sqrt(((Math.round(maxcirclearea / circles[i].max_data_value)) * value) / Math.PI));
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
                if (typeof circles[i].friendly_label === "undefined"){
                    circles[i].friendly_label = 'score';
                }
            
                if (circles[i].first_or_second_indicator == 1){
                    //check if there is a match with a second indicator, if so we need to make a popup with the second indicator
                    if (integrate_second_indicator_data_into_first_indicator(i, curyear, 2)){
                        circles[i].circleinfo.bindPopup('<h4>'+circles[i].countryname+'</h4><p>' + circles[i].friendly_label + ': ' + value + '</p><p>' + circles[i].second_indicator_name + ': ' + circles[i].second_indicator_value);
                        circles[i].circleinfo.on('click', function(evt) {
                            evt.target.openPopup();
                        });
                    //if there is not a match, we will create a popup with only information of the first selected indicator
                    }else{
                        circles[i].circleinfo.bindPopup('<h4>'+circles[i].countryname+'</h4><p>' + circles[i].friendly_label + ': ' + value + '</p>');
            
                        circles[i].circleinfo.on('click', function(evt) {
                            evt.target.openPopup();
                        });
                    }

          
                }else{
                    //check if the second indicator does not match with the first indicator. If not, create a popup with only data of the second indicator
                    if(!integrate_second_indicator_data_into_first_indicator(i, curyear, 1)){
                        circles[i].circleinfo.bindPopup('<h4>'+circles[i].countryname+'</h4><p>' + circles[i].friendly_label + ': ' + value + '</p>');
            
                        circles[i].circleinfo.on('click', function(evt) {
                            evt.target.openPopup();
                        });
                    }
                }
            }

        } catch (err){
        //console.log(err);
        }
    }
}

function integrate_second_indicator_data_into_first_indicator(number_circle, curyear, compare_to){
    //check if first indicator has a match with a second indicator
    //if there is a match, we have to add this information to the first indicator
    for (var i = circles.length - 1; i >= 0; i--) {
        if (circles[i].first_or_second_indicator == compare_to){

            if (circles[number_circle].countryiso2 == circles[i].countryiso2){
                //add information to the first indicator about the second indicator
                circles[number_circle].second_indicator_value = circles[i].values[curyear];
                circles[number_circle].second_indicator_name = circles[i].friendly_label;
                return true;
            }
        }
    }
    return false;   
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


function create_cpi_table(){
    google.load('visualization', '1', {
        packages:['table'], 
        callback:drawCityPropTable
    });

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
        showRowNumber: true,
        sortColumn: 1,
        sortAscending: false
    });
    
}


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
    var lineChartData = [];

    var lineChartHeader = [];
    lineChartHeader.push('Year');
    for(var i = 0; i < circles.length;i++){
      lineChartHeader.push(circles[i].countryname);
    }
    currentData.push(lineChartHeader);

    var firstyear = 0;
    var lastyear = 0;

    for (var i=1950;i<2051;i++){ 
      var lineChartLine = [];
      lineChartLine.push(i.toString());
      
      for(var y = 0; y < circles.length;y++){
        
        var curvalue = circles[y].values["y" + i.toString()];
        if (curvalue === undefined || curvalue === null){ curvalue = null; } else{
          if(firstyear == 0){
            firstyear = i;
          }
          lastyear = i;
        }
        lineChartLine.push(curvalue);
      }

      currentData.push(lineChartLine);
    }

    var data = google.visualization.arrayToDataTable(currentData);

    var columnsTable = new google.visualization.DataTable();
    columnsTable.addColumn('number', 'colIndex');
    columnsTable.addColumn('string', 'colLabel');
    var initState= {selectedValues: []};
    // put the columns into this data table (skip column 0)
    for (var i = 1; i < data.getNumberOfColumns(); i++) {
        columnsTable.addRow([i, data.getColumnLabel(i)]);
        //initState.selectedValues.push(data.getColumnLabel(i));  
    }

    // Create a pie chart, passing some options
    var lineChart = new google.visualization.ChartWrapper({
      chartType: 'LineChart',
      containerId: 'line-chart-placeholder',
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
      containerId: 'line-chart-filter',
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
    });

}
  
  function getTableChartData(year){
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Country');
    data.addColumn('number', 'Value');
    

    for (var i=0;i<circles.length;i++)
    { 
      try{

        var value = circles[i].values["y" + year];
        if (value === undefined || value === null){

        } else {
          data.addRow([circles[i].countryname, parseInt(value)]);
        }

      } catch (err){
      }
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
    var data = getTableChartData(2015);
    
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

  function initialize_charts(){
    google.load("visualization", "1", {packages:["corechart", "controls"], callback:drawLineChart});
    google.load("visualization", "1", {packages:["table", "controls"], callback:drawTableChart});
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
  google.load("visualization", "1", {packages:["corechart", "controls"], callback:drawLineChart});
  
}

function show_table_graph(){
  // hide_all_graphs();
  // $('#table-chart-placeholder').show();
  $('html, body').animate({
    scrollTop: ($('#table-chart-placeholder').offset().top - 150)
  }, 1000);
  google.load("visualization", "1", {packages:["table"], callback:drawTableChart});
}

    
    


// });



















// Global variables
var current_selection = new Object();
var current_url = new Object();
var selected_type = '';
var standard_mapheight = '45em';
var home_url = '';
var template_directory = '';

function build_current_url(){

  var url = '?p=';
  if (!(typeof current_selection.sectors === "undefined")) url += build_current_url_add_par("sectors", current_selection.sectors);
  if (!(typeof current_selection.countries === "undefined")) url += build_current_url_add_par("countries", current_selection.countries);
  if (!(typeof current_selection.budgets === "undefined")) url += build_current_url_add_par("budgets", current_selection.budgets);
  if (!(typeof current_selection.regions === "undefined")) url += build_current_url_add_par("regions", current_selection.regions);
  if (!(typeof current_selection.indicators === "undefined")) url += build_current_url_add_par("indicator", current_selection.indicators);
  if (!(typeof current_selection.cities === "undefined")) url += build_current_url_add_par("city", current_selection.cities);
  if (!(typeof current_selection.offset === "undefined")) url += build_current_url_add_par("offset", current_selection.offset);
  if (!(typeof current_selection.per_page === "undefined")) url += build_current_url_add_par("per_page", current_selection.per_page);
  if (!(typeof current_selection.order_by === "undefined")) url += build_current_url_add_par("order_by", current_selection.order_by);
  if (!(typeof current_selection.s === "undefined")) url += build_current_url_add_par("s", current_selection.s);
  if (url == '?p='){return '';}
  url = url.replace("?p=&", "?");
  return url;

}

function build_current_url_add_par(name, arr, dlmtr){

  if(dlmtr === undefined){
    dlmtr = ","
  }

  if(arr.length == 0){return '';}
  var par = '&' + name + '=';
  for(var i = 0; i < arr.length;i++){
    par += arr[i].id.toString() + dlmtr;
  }
  par = par.substr(0, par.length - 1);

  return par;
}

function query_string_to_selection(){

  var query = window.location.search.substring(1);
  if(query != ''){
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
      var pair = vars[i].split("=");
      var vals = pair[1].split(",");

      current_selection[pair[0]] = [];
      for(var y=0;y<vals.length;y++){
        current_selection[pair[0]].push({"id":vals[y], "name":"unknown"});
      }
      
    } 
  }
}

//HTML function to create filters
function create_filter_attributes(objects, columns){
    var html = '';
    var per_col = 20;

    var sortable = [];
    for (var key in objects){
       sortable.push([key, objects[key]]);
     }
     
    sortable.sort(function(a, b){
     var nameA=a[1].toString().toLowerCase(), nameB=b[1].toString().toLowerCase()
     if (nameA < nameB) //sort string ascending
      return -1 
     if (nameA > nameB)
      return 1
     return 0 //default return value (no sorting)
    });

    for (var i = 0;i < sortable.length;i++){

      if (i%per_col == 0){
            html += '<div class="span' + (12 / columns) + '">';
        } 
        html += '<div class="squaredThree"><div>';
        html += '<input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][1].toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1]+'" />';
        html += '<label class="map-filter-cb-value" for="'+sortable[i][1].toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
        html += '</div><div><span>'+sortable[i][1]+'</span></div></div>';
        if (i%per_col == (per_col - 1)){
          html += '</div>';
        }
        if ((i + 1) > ((per_col * columns) - 1)) { break; }

    }
    // $.each(objects, function(key, value){

    //     if (counter%per_col == 0){
    //         html += '<div class="span' + (12 / columns) + '">';
    //     } 
    //     html += '<div class="squaredThree"><div>';
    //     html += '<input type="checkbox" value="'+ key +'" id="'+value.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+value+'" />';
    //     html += '<label class="map-filter-cb-value" for="'+value.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
    //     html += '</div><div><span>'+value+'</span></div></div>';
    //     if (counter%per_col == (per_col - 1)){
    //       html += '</div>';
    //     }
    //     counter++;
    //     if (counter > ((per_col * columns) - 1)) { return false; }
    // });
    return html;
}

function CommaFormatted(amount) {
  var delimiter = "."; // replace comma if desired
  amount = new String(amount);
  var a = amount.split('.',2)
  var d = a[1];
  var i = parseInt(a[0]);
  if(isNaN(i)) { return ''; }
  var minus = '';
  if(i < 0) { minus = '-'; }
  i = Math.abs(i);
  var n = new String(i);
  var a = [];
  while(n.length > 3)
  {
    var nn = n.substr(n.length-3);
    a.unshift(nn);
    n = n.substr(0,n.length-3);
  }
  if(n.length > 0) { a.unshift(n); }
  n = a.join(delimiter);
  if(d.length < 1) { amount = n; }
  else { amount = n + '.' + d; }
  amount = minus + amount;
  return amount;
}

// XXXXXXX MAP (GLOBAL) XXXXXXXXXXX


jQuery(function($) {

  // zet zoom level on 2 instead of 3 on ipad
  function isiPad(){
      return (navigator.platform.indexOf("iPad") != -1);
  }

  if (isiPad()){
      map.setZoom(2);
  }

    $('#map-lightbox-close').click(function(){
        $('#map-lightbox').hide();
        $('#map-lightbox-bg').hide();
    });

    $('#map-hide-show-button').click(function(){
        if($(this).hasClass("map-show")){
            if ($('#map-filter-overlay').is(":visible")){
                $('#map-filter-overlay').hide('slow');
            }
            hide_map();
        } else {
            show_map();
        }
    });

    function hide_map()
    {
    standard_mapheight = $('#map').css('height');
    $('#map-hide-show-button').removeClass('map-show');
        $('#map-hide-show-button').addClass('map-hide');
        $('#map-hide-show-text').html("SHOW MAP");
        animate_map('13.5em');
        hide_map_homepage();
    }

    function show_map(){
    $('#map-hide-show-button').removeClass('map-hide');
        $('#map-hide-show-button').addClass('map-show');
        $('#map-hide-show-text').html("HIDE MAP");
        animate_map(standard_mapheight);
        show_map_homepage();
    }

    function animate_map(mapheight){
        $('#map').animate({
            height: mapheight
        }, 1000, function() {
         map.invalidateSize();
    });
    }

    function hide_map_homepage(){
        $('#map-lightbox').animate({
            fontSize: "0.7em",
            top: "3em"
        }, 1000, function() {
        // Animation complete.
    });
    $('#map-lightbox-bg').animate({
            fontSize: "0.7em",
            top: "3em"
        }, 1000, function() {
        // Animation complete.
    });
    }

    function show_map_homepage(){
        $('#map-lightbox').animate({
            fontSize: "1em",
            top: "10.5em"
        }, 1000, function() {
        // Animation complete.
    });
    $('#map-lightbox-bg').animate({
            fontSize: "1em",
            top: "10.5em"
        }, 1000, function() {
        // Animation complete.
    });
    }
});


// MAP FILTER FUNCTIONS

  $('.filter-button').click(function(e){

    var curId = $(this).attr('id');
    var filterContainerName = curId.replace("-button", "");
    $('.filter-button.filter-selected').removeClass("filter-selected");
    
    if($('#map-filter-overlay').is(":hidden")){

      $('#map-filter-overlay').show("blind", { direction: "vertical" }, 600);         
      $(this).addClass("filter-selected");
      hide_all_filters();

      if($('#map-hide-show-button').hasClass("map-hide")){
        show_map();
      }

      // load the project filter options based on the current selection
      if(selected_type == 'projects'){
        initialize_project_filter_options();
      }

      initialize_filters();
      $("#" + filterContainerName).show();

    } else {
      if($("#" + filterContainerName).is(":visible")){
        $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 600);
        hide_all_filters();
        save_selection();
      } else {
        $(this).addClass("filter-selected");
        hide_all_filters();
        $("#" + filterContainerName).show();
      }
    }
  });

  function initialize_filters(){
    $('#map-filter-overlay input:checked').prop('checked', false);

    if (!(typeof current_selection.sectors === "undefined")) init_filters_loop(current_selection.sectors);
    if (!(typeof current_selection.countries === "undefined")) init_filters_loop(current_selection.countries);
    if (!(typeof current_selection.budgets === "undefined")) init_filters_loop(current_selection.budgets);
    if (!(typeof current_selection.regions === "undefined")) init_filters_loop(current_selection.regions);
    if (!(typeof current_selection.indicators === "undefined")) init_filters_loop(current_selection.indicators);
    if (!(typeof current_selection.cities === "undefined")) init_filters_loop(current_selection.cities);


  }

  function init_filters_loop(arr){
    for(var i = 0; i < arr.length;i++){
      $(':checkbox[value=' + arr[i].id + ']').prop('checked', true);
    }
  }

  function hide_all_filters(){
    $('#countries-filters').hide();
    $('#regions-filters').hide();
    $('#budgets-filters').hide();
    $('#sectors-filters').hide();
    $('#indicators-filters').hide();
    $('#cities-filters').hide();
  }

    $('#map-filter-cancel').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
        $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
    });

    $('#map-filter-save').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    save_selection();
  });

// FILTER SAVE FUNCTIONS

function save_selection(){

    // hide map show loader
    $('#map-loader').show();
    $('#map').hide();

    var new_selection = new Object();
    new_selection.sectors = [];
    new_selection.countries = [];
    new_selection.budgets = [];
    new_selection.regions = [];
    new_selection.indicators = [];
    new_selection.cities = [];

    // set selection as filter and load results
    $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);

    get_checked_by_filter("sectors", new_selection);
    get_checked_by_filter("countries", new_selection);
    get_checked_by_filter("budgets", new_selection);
    get_checked_by_filter("regions", new_selection);
    get_checked_by_filter("indicators", new_selection);
    get_checked_by_filter("cities", new_selection);
    current_selection = new_selection;

     var link = document.URL.toString().split("?")[0] + build_current_url();
    history.pushState(null, null, link);

    if(selected_type == "projects"){
      load_new_page(false);
    }

    fill_selection_box();
    reload_map();
}

function get_checked_by_filter(filtername, new_selection){
  $('#' + filtername + '-filters input:checked').each(function(index, value){ 
        new_selection[filtername].push({"id":value.value, "name":value.name});
    });
}

// MAP RELOAD FUNCTIONS 

function reload_map(){

  // hide map show loader
  $('#map-loader').show();
  $('#map').hide();


  var dlmtr = ',';

  var str_sector = reload_map_prepare_parameter_string("sectors", dlmtr);
  var str_country = reload_map_prepare_parameter_string("countries", dlmtr);
  var str_budget = reload_map_prepare_parameter_string("budgets", dlmtr);
  var str_region = reload_map_prepare_parameter_string("regions", dlmtr);
  var str_indicator = reload_map_prepare_parameter_string("indicators", dlmtr);
  var str_city = reload_map_prepare_parameter_string("cities", dlmtr);

  // if project filter container is on the page (= projects page)
  if (selected_type=='projects'){
  initialize_projects_map(site + 'json-activities?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region, 'projects');
  } else if (selected_type=='indicator'){
    initialize_map(site + 'json?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,'indicator');
    move_slider_to_available_year(2015);
  } else if (selected_type=='cpi'){
    initialize_map(site + 'json-city?sectors=' + str_sector + '&budgets=' + str_budget + '&countries=' + str_country + '&regions=' + str_region + '&indicator=' + str_indicator + '&city=' + str_city,'cpi');
  }

  // show map hide loader
  $('#map').show();
  $('#map-loader').hide();
  
}

function reload_map_prepare_parameter_string(filtername, dlmtr){
  var str = '';
  if(!(typeof current_selection[filtername] === 'undefined')){
    var arr = current_selection[filtername];
    if(arr.length > 0){
      for(var i = 0; i < arr.length; i++){
        str += arr[i].id + dlmtr;
      }
      str = str.substring(0, str.length-1);
    }
  }
  return str;
}

function move_slider_to_available_year(standard_year){
  
  for (var i = standard_year; i > 1949;i--){
    
    if ($("#year-"+i).hasClass("slider-active")){
      if(i == standard_year){break;}
      refresh_circles(i);
      $( "#map-slider-tooltip" ).val(i);
      $( "#map-slider-tooltip div" ).text(i.toString());
      $( ".slider-year").removeClass("active");
      $( "#year-" + i.toString()).addClass("active");
      break;
    }
  }
}



// SELECTION BOX FUNCTIONS

  $("#selection-hide-show-button").click(function(){

    if($('#selection-box').is(":hidden")){
        $('#selection-box').show("blind", { direction: "vertical" }, 500);
        $('#selected-clear').show("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("HIDE SELECTION");
    } else {
        $('#selection-box').hide("blind", { direction: "vertical" }, 500);
        $('#selected-clear').hide("blind", { direction: "vertical" }, 500);
        $('#selection-hide-show-text').html("SHOW SELECTION");
    }
  });

function fill_selection_box(){

  var html = '';
  var indicatorhtml = '';
  if (!(typeof current_selection.sectors === "undefined") && (current_selection.sectors.length > 0)) html += fill_selection_box_single_filter("SECTORS", current_selection.sectors);
  if (!(typeof current_selection.countries === "undefined") && (current_selection.countries.length > 0)) html += fill_selection_box_single_filter("COUNTRIES", current_selection.countries);
  if (!(typeof current_selection.budgets === "undefined") && (current_selection.budgets.length > 0)) html += fill_selection_box_single_filter("BUDGETS", current_selection.budgets);
  if (!(typeof current_selection.regions === "undefined") && (current_selection.regions.length > 0)) html += fill_selection_box_single_filter("REGIONS", current_selection.regions);
  if (!(typeof current_selection.cities === "undefined") && (current_selection.cities.length > 0)) html += fill_selection_box_single_filter("CITIES", current_selection.cities);
  if (!(typeof current_selection.indicators === "undefined") && (current_selection.indicators.length > 0)) indicatorhtml = fill_selection_box_single_filter("INDICATORS", current_selection.indicators);
  $("#selection-box").html(html);
  $("#selection-box-indicators").html(indicatorhtml);
  init_remove_filters_from_selection_box();
}

function fill_selection_box_single_filter(header, arr){
  var html = '<div class="select-box" id="selected-' + header.toLowerCase() + '">';
      html += '<div class="select-box-header">';
      if (header == "INDICATORS" && selected_type == "cpi"){ header = "DIMENSIONS";};
      html += header;
      html += '</div>';

      for(var i = 0; i < arr.length; i++){
        html += '<div class="select-box-selected">';
        html += '<div id="selected-' + arr[i].id.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc') + '" class="selected-remove-button"></div>';
        
        if (arr[i].name.toString() == 'unknown'){
          arr[i].name = $(':checkbox[value=' + arr[i].id + ']').attr("name");
        }

        html += '<div>' + arr[i].name + '</div>';
        html += '</div>';
      }

      html += '</div>';
      return html;
}

function init_remove_filters_from_selection_box(){
  $(".selected-remove-button").click(function(){
    var id = $(this).attr('id');
    id = id.replace("selected-", "");
    var filtername = $(this).parent().parent().attr('id');
    filtername = filtername.replace("selected-", "");
    var arr = current_selection[filtername];
    for (var i = 0; i < arr.length;i++){
      if(arr[i].id == id){
        arr.splice(i, 1);
        break;
      }
    }
    fill_selection_box();
    reload_map();
  });
}

$(".selection-clear-div").click(function(){
  current_selection = new Object();
  current_selection.indicator = [];
  current_selection.indicator.push({"id":"population", "name":"Total population"});
  fill_selection_box();
  reload_map();
});


// XXXXXXXXX Ajax wordpress simple pagination XXXXXXXX
jQuery(function($){


  // IE: prevent focus on internet explorer
  var _preventDefault = function(evt) { evt.preventDefault(); };
  $("#map-slider-tooltip div").bind("dragstart", _preventDefault).bind("selectstart", _preventDefault);


});



/* page header global dropdowns */
   
 $("#project-share-export").click(function(){

   if($('#dropdown-export-indicator').is(":hidden")){
       $('#dropdown-export-indicator').show("blind", { direction: "vertical" }, 200);
   } else {
       $('#dropdown-export-indicator').hide("blind", { direction: "vertical" }, 200);
   }
   return false;
 });


 $("#project-share-share").click(function(){

   if($('#dropdown-share-page').is(":hidden")){
       $('#dropdown-share-page').show("blind", { direction: "vertical" }, 200);
   } else {
       $('#dropdown-share-page').hide("blind", { direction: "vertical" }, 200);
   }
   return false;
 });



  $("#project-share-bookmark").click(function(){
    var href = document.URL.toString().split("?")[0] + build_current_url();
    var title = $(this).attr('alt');
    title = "Open UN-Habitat - " + title.substring(9);
    bookmarksite(title, href);
    return false;
  });


  $("#page-share-whistleblower").click(function(){
    var url = "/whistleblower/?referrer=" + encodeURIComponent(document.URL.toString());
    window.location = url;
    return false;
  });

  

/***********************************************
* Bookmark site script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/* Modified to support Opera */
function bookmarksite(title,url){
if (window.sidebar) // firefox
  window.sidebar.addPanel(title, url, "");
else if(window.opera && window.print){ // opera
  var elem = document.createElement('a');
  elem.setAttribute('href',url);
  elem.setAttribute('title',title);
  elem.setAttribute('rel','sidebar');
  elem.click();
} 
else if(document.all)// ie
  window.external.AddFavorite(url, title);
}





// $('#dropdown-share-facebook').click(function(){

//   var link = ' https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL.toString().split("?")[0] + build_current_url());
//   window.open(link);
  
// });

// $('#dropdown-share-twitter').click(function(){

//   var link = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(document.URL.toString().split("?")[0] + build_current_url());
//   console.log(link);
//   window.open(link);
// });

// $('#dropdown-share-linkedin').click(function(){

//   var link = 'http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL.toString().split("?")[0] + build_current_url());
//   console.log(link);
//   window.open(link);
// });

// $('#dropdown-share-email').click(function(){

//   var link = 'mailto:?Subject=' + encodeURIComponent('Shared to you from Open UN-Habitat') + '&body=' + encodeURIComponent('The following page was recommended to you on the Open UN-Habitat site. ' + document.URL.toString().split("?")[0] + build_current_url());
//   window.open(link);
// });
if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15){//Chrome
 $("#project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}

if (navigator.userAgent.indexOf("Opera") != -1){
  $("#project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}


