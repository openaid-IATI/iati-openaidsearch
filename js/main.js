// Global variables

var current_selection = new Object();
var selected_type = '';
var standard_mapheight = '45em';

// MAIN FLOW FUNCTIONS

// save current_selection
function save_selection(newpage){
  if (newpage){
    query_string_to_selection(save_selection_step_2);
  } else{
    save_current_selection(save_selection_step_2);
  }
  
  if (selected_type == "projects" && !newpage){load_new_page(false);}
}

// set url, load the map, load the filter options
function save_selection_step_2(){
  set_defaults();
  set_current_url();
  reload_map();
  load_filter_options();
}

// load the graphs (called by reload_map inner functions)
function save_selection_step_3(){

  if (selected_type == "indicator"){
    move_slider_to_available_year(2013);
  }
  if (selected_type == "cpi"){
    refresh_circles(2012);
  }
  
  // hide loader, show map
  $('#map').show(); 
  $('#map-loader').hide();

  if(selected_type != "projects"){
    initialize_charts();
  }
}

function set_defaults(){
  if (selected_type == "indicator"){
    if (current_selection.indicators === undefined || current_selection.indicators == ""){
      current_selection.indicators = [];
      current_selection.indicators.push({"id":"population", "name":"Total population"});
    }
  }
  if (selected_type == "cpi"){
    if (current_selection.indicators === undefined || current_selection.indicators == ""){
      current_selection.indicators = [];
      current_selection.indicators.push({"id":"cpi_5_dimensions", "name":"Five dimensions of city prosperity"});
    }
  }
}

function load_filter_options(){
  var url = create_api_url("filter");
  var data = get_filter_data(url);
}

function process_filter_options(data){

  
  // exceptions for indicator page
  if (selected_type == "indicator"){
    $.each(data['indicators'], function( key, value ) {
       if(key.indexOf("cpi") != -1){
          delete data['indicators'][key];
       }
    });
  }
  // exceptions for cpi page
  if (selected_type == "cpi"){
    $.each(data['indicators'], function( key, value ) {
       if(key.indexOf("cpi") == -1){
          delete data['indicators'][key];
       }
    });
  }
  // load filter html and implement it in the page
  if (selected_type == "projects"){
    $.each(data, function( key, value ) {
     if (!$.isEmptyObject(value)){
       var columns = 4;
       if ($.inArray(key, new Array("indicators", "sectors"))){ columns = 3};
       var filter_html = create_project_filter_attributes(value, columns);
       $('#' + key + '-filters').html(filter_html);
     }
  });
    var filter_html = create_budget_filter_attributes();
    $('#budgets-filters').html(filter_html);
  } else {
    $.each(data, function( key, value ) {
     if (!$.isEmptyObject(value)){
       var columns = 4;
       if ($.inArray(key, new Array("indicators", "sectors"))){ columns = 3};
       var filter_html = create_filter_attributes(value, columns);
       $('#' + key + '-filters').html(filter_html);
     }
  });
  }

  // reload aangevinkte vakjes
  initialize_filters();
}



// CODE STARTS HERE


// build current url based on selection made

function build_current_url(exclude_countries){

  var url = '?p=';
  if (!(typeof current_selection.sectors === "undefined")) url += build_current_url_add_par("sectors", current_selection.sectors);
  if (!exclude_countries){
    if (!(typeof current_selection.countries === "undefined")) url += build_current_url_add_par("countries", current_selection.countries);
  }
  if (!(typeof current_selection.budgets === "undefined")) url += build_current_url_add_par("budgets", current_selection.budgets);
  if (!(typeof current_selection.regions === "undefined")) url += build_current_url_add_par("regions", current_selection.regions);
  if (!(typeof current_selection.indicators === "undefined")) url += build_current_url_add_par("indicators", current_selection.indicators);
  if (!(typeof current_selection.cities === "undefined")) url += build_current_url_add_par("cities", current_selection.cities);
  if (!(typeof current_selection.reporting_organisations === "undefined")) url += build_current_url_add_par("reporting_organisations", current_selection.reporting_organisations);
  if (!(typeof current_selection.offset === "undefined")) url += build_current_url_add_par("offset", current_selection.offset);
  if (!(typeof current_selection.per_page === "undefined")) url += build_current_url_add_par("per_page", current_selection.per_page);
  if (!(typeof current_selection.order_by === "undefined")) url += build_current_url_add_par("order_by", current_selection.order_by);
  if (!(typeof current_selection.query === "undefined")) url += build_current_url_add_par("query", current_selection.query);
  if (url == '?p='){return '';}
  url = url.replace("?p=&", "?");

  return url;
}

function build_current_url_add_par(name, arr, dlmtr){

  if(dlmtr === undefined){
    dlmtr = ",";
  }

  if(arr.length == 0){return '';}
  var par = '&' + name + '=';
  for(var i = 0; i < arr.length;i++){
    par += arr[i].id.toString() + dlmtr;
  }
  par = par.substr(0, par.length - 1);

  return par;
}


// build current selection based on URL

function query_string_to_selection(callback){

  var query = window.location.search.substring(1);
  if(query != ''){
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
      var pair = vars[i].split("=");
      var vals = pair[1].split(",");
      current_selection[pair[0]] = [];

      for(var y=0;y<vals.length;y++){
        if (pair[0] != "query"){
          current_selection[pair[0]].push({"id":vals[y], "name":"unknown"});
        } else{
          current_selection[pair[0]].push({"id":vals[y], "name":vals[y]});
        }
        
      }
      
    }
  }
  if (callback){
    callback();
  }
}

// create filter options of one particular filter type, objects = the options, columns = amount of columns per filter page
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

    var page_counter = 1;
    html += '<div class="filter-page filter-page-1">';
    
    for (var i = 0;i < sortable.length;i++){

      if (i%per_col == 0){
          html += '<div class="span' + (12 / columns) + '">';
      } 

      var sortablename = sortable[i][1];
      if (columns == 4 && sortablename.length > 32){
        sortablename = sortablename.substr(0,28) + "...";
      } else if (columns == 3 && sortablename.length > 40){
        sortablename = sortablename.substr(0,36) + "...";
      }


      html += '<div class="squaredThree"><div>';
      html += '<input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][1].toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1]+'" />';
      html += '<label class="map-filter-cb-value" for="'+sortable[i][1].toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
      html += '</div><div class="squaredThree-fname"><span>'+sortablename+'</span></div></div>';
      if (i%per_col == (per_col - 1)){
        html += '</div>';
      }
      if ((i + 1) > ((page_counter * (per_col * columns))) - 1) { 
        
     
        html += '</div>';
        page_counter = page_counter + 1;
        html += '<div class="filter-page filter-page-' + page_counter + '">';
      }
        
    }

    html += '<div class="filter-total-pages" name="' + page_counter + '"></div>';

    /// if paginated, close the pagination.
    if (page_counter > 1){
      html += '</div>';
    }

    return html;
}

// create filter options of one particular filter type, objects = the options, columns = amount of columns per filter page
function create_project_filter_attributes(objects, columns){
    var html = '';
    var per_col = 20;


    var sortable = [];
    for (var key in objects){
      if (objects[key].name == null){
        objects[key].name = "Unknown";
      }
      sortable.push([key, objects[key]]);
    }
    
    sortable.sort(function(a, b){
      var nameA=a[1].name.toString().toLowerCase(), nameB=b[1].name.toString().toLowerCase()
      if (nameA < nameB) //sort string ascending
        return -1 
      if (nameA > nameB)
        return 1
      return 0 //default return value (no sorting)
    });

    var page_counter = 1;
    html += '<div class="filter-page filter-page-1">'
    
    for (var i = 0;i < sortable.length;i++){

      if (i%per_col == 0){
          html += '<div class="span' + (12 / columns) + '">';
      } 

      var sortablename = sortable[i][1].name;
      if (columns == 4 && sortablename.length > 32){
        sortablename = sortablename.substr(0,28) + "...";
      } else if (columns == 3 && sortablename.length > 46){
        sortablename = sortablename.substr(0,42) + "...";
      }
      var sortableamount = sortable[i][1].total.toString();

      html += '<div class="squaredThree"><div>';
      html += '<input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][0]+sortable[i][1].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1].name+'" />';
      html += '<label class="map-filter-cb-value" for="'+sortable[i][0]+sortable[i][1].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
      html += '</div><div class="squaredThree-fname"><span>'+sortablename+' (' + sortableamount + ')</span></div></div>';
      if (i%per_col == (per_col - 1)){
        html += '</div>';
      }
      if ((i + 1) > ((page_counter * (per_col * columns))) - 1) { 
        
     
        html += '</div>';
        page_counter = page_counter + 1;
        html += '<div class="filter-page filter-page-' + page_counter + '">';
      }
        
    }

    html += '<div class="filter-total-pages" name="' + page_counter + '"></div>';

    /// if paginated, close the pagination.
    if (page_counter > 1){
      html += '</div>';
    }

    return html;
}

function create_budget_filter_attributes(objects, columns){
    var html = '';

    html += '<div class="filter-page filter-page-1">'
    html += '<div class="span4">';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="0-10000" id="0-10000" name="0 - 10.000" />';
    html += '<label class="map-filter-cb-value" for="0-10000"></label>';
    html += '</div><div class="squaredThree-fname"><span>0 - 10.000</span></div></div>';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="10000-100000" id="10000-100000" name="10.000 - 100.000" />';
    html += '<label class="map-filter-cb-value" for="10000-100000"></label>';
    html += '</div><div class="squaredThree-fname"><span>10.000 - 100.000</span></div></div>';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="100000-1000000" id="100000-1000000" name="100.000 - 1.000.000" />';
    html += '<label class="map-filter-cb-value" for="100000-1000000"></label>';
    html += '</div><div class="squaredThree-fname"><span>100.000 - 1.000.000</span></div></div>';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="1000000-10000000" id="1000000-10000000" name="1.000.000 - 10.000.000" />';
    html += '<label class="map-filter-cb-value" for="1000000-10000000"></label>';
    html += '</div><div class="squaredThree-fname"><span>1.000.000 - 10.000.000</span></div></div>';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="10000000-50000000" id="10000000-50000000" name="10.000.000-50.000.000" />';
    html += '<label class="map-filter-cb-value" for="10000000-50000000"></label>';
    html += '</div><div class="squaredThree-fname"><span>10.000.000 - 50.000.000</span></div></div>';

    html += '<div class="squaredThree"><div>';
    html += '<input type="checkbox" value="50000000" id="50000000" name="50.000.000 +" />';
    html += '<label class="map-filter-cb-value" for="50000000"></label>';
    html += '</div><div class="squaredThree-fname"><span>50.000.000 +</span></div></div>';

    html += '</div></div>';

    return html;
}

function comma_formatted(amount) {
  var delimiter = "."; // replace comma if desired
  amount = new String(amount);
  var a = amount.split(delimiter, 2)
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
  else { amount = n + delimiter + d; }
  amount = minus + amount;
  return amount;
}

function get_filter_data(url){
  $.support.cors = true; 
  
  if(window.XDomainRequest){
    var xdr = new XDomainRequest();
    xdr.open("get", url);
    xdr.onprogress = function () { };
    xdr.ontimeout = function () { };
    xdr.onerror = function () { };
    xdr.onload = function() {
       var jsondata = $.parseJSON(xdr.responseText);
       if (jsondata == null || typeof (jsondata) == 'undefined')
       {
            jsondata = $.parseJSON(data.firstChild.textContent);
       }
       process_filter_options(jsondata);
    }
    setTimeout(function () {xdr.send();}, 0);
  } else {
    $.ajax({
          type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'json',
           success: function(data){
              process_filter_options(data);
           }
    });
  }

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
  function filter_pagination(total, curpage){
    var text = '';

    if (curpage > 1){
      text += '<div id="filter-previous-page">Previous page</div>';
    } else {
      text += '<div id="filter-previous-page-empty"></div>';
    }

    if (curpage < total){
      text += '<div id="filter-next-page">Next page</div>';
    } else {
      text += '<div id="filter-next-page"></div>';
    }
    text += '<div id="filter-pagination-overview">Page ' + curpage + ' / ' + total + '</div>';

    $('#map-filter-pagination').html(text);

    $('#filter-next-page').click(function(){

      var total_pages = parseInt($("#filter-pagination-overview").text().substr(9,1));
      var nextpage = parseInt($("#filter-pagination-overview").text().substr(5,1)) + 1;
      filter_pagination(total_pages, nextpage);
      $('.filter-page').hide();
      $('.filter-page-'+nextpage).show();
      
    });

    $('#filter-previous-page').click(function(){

      var total_pages = parseInt($("#filter-pagination-overview").text().substr(9,1));
      var nextpage = parseInt($("#filter-pagination-overview").text().substr(5,1)) - 1;
      filter_pagination(total_pages, nextpage);
      $('.filter-page').hide();
      $('.filter-page-'+nextpage).show();
    });

  }

$(document).keyup(function(e) {
  if (e.keyCode == 27) { 
    if($("#map-filter-overlay").is(":visible")){
      save_selection();
    } 
  } 
});
  

  $('.filter-button').click(function(e){

    var curId = $(this).attr('id');
    var filterContainerName = curId.replace("-button", "");
    $('.filter-button.filter-selected').removeClass("filter-selected");
    $('#map-filter-errorbox').text("");
    $('.filter-page').hide();
    $('.filter-page-1').show();

    var total_pages = $("#" + filterContainerName + " .filter-total-pages").attr("name");
    if (total_pages > 1){
      filter_pagination(total_pages, 1);
    } else{
      $('#map-filter-pagination').html('');
    }


    if($('#map-filter-overlay').is(":hidden")){

      $('#map-filter-overlay').show("blind", { direction: "vertical" }, 600, function(){
        
      });

      $(this).addClass("filter-selected");
      hide_all_filters();

      if($('#map-hide-show-button').hasClass("map-hide")){
        show_map();
      }

      $("#" + filterContainerName).show();

    } else {
      if($("#" + filterContainerName).is(":visible")){
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
    if (!(typeof current_selection.reporting_organisations === "undefined")) init_filters_loop(current_selection.reporting_organisations);
    
    fill_selection_box();
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
    $('#reporting_organisations-filters').hide();
  }

  $('#map-filter-cancel').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 1000);
  });

  $('#map-filter-save').click(function(){
    $('.filter-button.filter-selected').removeClass("filter-selected");
    save_selection();
  });


function save_current_selection(callback){
    var new_selection = new Object();
    new_selection.sectors = [];
    new_selection.countries = [];
    new_selection.budgets = [];
    new_selection.regions = [];
    new_selection.indicators = [];
    new_selection.cities = [];
    new_selection.reporting_organisations = [];

    // set selection as filter and load results
    get_checked_by_filter("sectors", new_selection);
    get_checked_by_filter("countries", new_selection);
    get_checked_by_filter("budgets", new_selection);
    get_checked_by_filter("regions", new_selection);
    get_checked_by_filter("indicators", new_selection);
    get_checked_by_filter("cities", new_selection);
    get_checked_by_filter("reporting_organisations", new_selection);

    if(new_selection.indicators.length > 2){
        too_many_indicators_error(new_selection.indicators.length - 2);
    } else {
      current_selection = new_selection;

      // hide map show loader
      $('#map-loader').show();
      $('#map').hide();

      $('#map-filter-overlay').hide("blind", { direction: "vertical" }, 400, function(){
        
        callback();
      });
    }
}

function too_many_indicators_error(count){
    $('#map-filter-errorbox').text("You can only select 2 indicators at the same time, please remove "+count+".");
}


function set_current_url(){
  var link = document.URL.toString().split("?")[0] + build_current_url();
  if (history.pushState) {
    history.pushState(null, null, link);
  }
}

function get_checked_by_filter(filtername, new_selection){
  $('#' + filtername + '-filters input:checked').each(function(index, value){ 
        new_selection[filtername].push({"id":value.value, "name":value.name});
    });
}

// MAP RELOAD FUNCTIONS 

function reload_map(){

  var url = create_api_url("mapdata");

  if (selected_type=='projects'){
    initialize_projects_map();
  } else {
    initialize_indicators_map();
  }

  // show map hide loader
  $('#map').show();
  $('#map-loader').hide();
}



function create_api_url(type, indicatorid){

  var dlmtr = ',';
  var str_sector = reload_map_prepare_parameter_string("sectors", dlmtr);
  var str_country = reload_map_prepare_parameter_string("countries", dlmtr);
  var str_budget = reload_map_prepare_budget_parameter_string("budgets", dlmtr);
  var str_region = reload_map_prepare_parameter_string("regions", dlmtr);
  var str_indicator = reload_map_prepare_parameter_string("indicators", dlmtr);
  var str_city = reload_map_prepare_parameter_string("cities", dlmtr);
  var str_reporting_organisation = reload_map_prepare_parameter_string("reporting_organisations", dlmtr);

  if (type == 'filter' && selected_type=='projects'){
    return_url = search_url + 'activity-filter-options/?format=json&reporting_organisation__in=' + organisation_id;
  } else if (type == "mapdata" && selected_type=='projects'){
    if(organisation_id){
      return_url = search_url + 'country-geojson/?format=json&reporting_organisation__in=' + organisation_id + str_sector + str_budget + str_country + str_region;
    } else{
      return_url = search_url + 'country-geojson/?format=json' + str_reporting_organisation + str_sector + str_budget + str_country + str_region;
    }
  } else if (type == 'filter' && selected_type=='indicator'){
    return_url = search_url + 'indicator-country-filter-options/?format=json' + str_country + str_region + str_city;
  } else if (type == 'mapdata' && selected_type=='indicator'){
    return_url = search_url + 'indicator-country-data/?format=json' + str_country + str_region + str_city + '&indicators__in=' + indicatorid;
  } else if (type == 'filter' && selected_type=='cpi'){
    return_url = search_url + 'indicator-city-filter-options/?format=json' + str_country + str_region + str_city;
  } else if (type == 'mapdata' && selected_type=='cpi'){
    return_url = search_url + 'indicator-city-data/?format=json' + str_country + str_region + str_city + '&indicators__in=' + indicatorid;
  } 
  // else if (type == "listdata" && selected_type=='projects'){
  //   if(organisation_id){
  //     return_url = search_url + 'activity-list/?format=json' + organisation_id + str_sector + str_budget + str_country + str_region;
  //   } else{
  //     return_url = search_url + 'activity-list/?format=json' + str_reporting_organisation + str_sector + str_budget + str_country + str_region;
  //   }
  // } 
  return return_url;
}

function reload_map_prepare_parameter_string(filtername, dlmtr){
  if(filtername == "reporting_organisations"){ filtername = "reporting_organisation"}
  var str = '';
  if(!(typeof current_selection[filtername] === 'undefined')){
    str = '&' + filtername + '__in=';
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

function reload_map_prepare_budget_parameter_string(filtername, dlmtr){
  var gte = '';
  var lte = '';
  var str = '';
  if(!(typeof current_selection[filtername] === 'undefined')){
    var arr = current_selection[filtername];
    if(arr.length > 0){
      gte = '99999999999';
      lte = '0';
      for(var i = 0; i < arr.length; i++){
        curid = arr[i].id;
        lower_higher = curid.split('-');

        if(lower_higher[0] < gte){
          gte = lower_higher[0];
        }

        if(lower_higher.length > 1){
          if(lower_higher[1] > lte){
            lte = lower_higher[1];
          }
        }
      }
    }
  }
  if (gte != '' && gte != '99999999999'){
    str += '&total_budget__gte=' + gte;
  }
  if (lte != '' && lte != '0'){
    str += '&total_budget__lte=' + lte;
  }

  return str;
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


// fill selection box based on current_selection object
function fill_selection_box(){

  var html = '';
  var indicatorhtml = '';
  if (!(typeof current_selection.sectors === "undefined") && (current_selection.sectors.length > 0)) html += fill_selection_box_single_filter("SECTORS", current_selection.sectors);
  if (!(typeof current_selection.countries === "undefined") && (current_selection.countries.length > 0)) html += fill_selection_box_single_filter("COUNTRIES", current_selection.countries);
  if (!(typeof current_selection.budgets === "undefined") && (current_selection.budgets.length > 0)) html += fill_selection_box_single_filter("BUDGETS", current_selection.budgets);
  if (!(typeof current_selection.regions === "undefined") && (current_selection.regions.length > 0)) html += fill_selection_box_single_filter("REGIONS", current_selection.regions);
  if (!(typeof current_selection.cities === "undefined") && (current_selection.cities.length > 0)) html += fill_selection_box_single_filter("CITIES", current_selection.cities);
  if (!(typeof current_selection.indicators === "undefined") && (current_selection.indicators.length > 0)) indicatorhtml = fill_selection_box_single_filter("INDICATORS", current_selection.indicators);
  if (!(typeof current_selection.reporting_organisations === "undefined") && (current_selection.reporting_organisations.length > 0)) indicatorhtml = fill_selection_box_single_filter("REPORTING_ORGANISATIONS", current_selection.reporting_organisations);
  if (!(typeof current_selection.query === "undefined") && (current_selection.query.length > 0)) html += fill_selection_box_single_filter("QUERY", current_selection.query);
  $("#selection-box").html(html);
  $("#selection-box-indicators").html(indicatorhtml);
  init_remove_filters_from_selection_box();
}

function fill_selection_box_single_filter(header, arr){
  var html = '<div class="select-box" id="selected-' + header.toLowerCase() + '">';
      html += '<div class="select-box-header">';
      if (header == "INDICATORS" && selected_type == "cpi"){ header = "DIMENSIONS";}
      if (header == "QUERY"){header = "SEARCH"}
      if (header == "REPORTING_ORGANISATIONS"){header = "REPORTING ORGANISATIONS"}
      html += header;
      html += '</div>';

      for(var i = 0; i < arr.length; i++){
        html += '<div class="select-box-selected">';
        html += '<div id="selected-' + arr[i].id.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc') + '" class="selected-remove-button"></div>';
        
        if (arr[i].name.toString() == 'unknown'){
          arr[i].name = $(':checkbox[value=' + arr[i].id + ']').attr("name");
        }

        html += '<div>' + arr[i].name + '</div>';
        if (header == "INDICATORS" || header == "DIMENSIONS"){
          html += '<div class="selected-indicator-color-filler"></div><div class="selected-indicator' + (i + 1).toString() + '-color"></div>';
        }
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
        // arr.splice(i, 1);
        $('input[name="' + arr[i].name + '"]').attr('checked', false);
        break;
      }
    }
    save_selection();
  });
}

$(".selection-clear-div").click(function(){

  $('input:checkbox').each(function(){
    $(this).attr('checked', false);
  });
  current_selection = new Object();
  current_selection.indicators = [];
  if(selected_type == 'indicators'){
    current_selection.indicators.push({"id":"population", "name":"Total population"});
  } else if(selected_type == 'cpi'){
    current_selection.indicators.push({"id":"cpi_5_dimensions", "name":"Five dimensions of city prosperity"});

  }
  
  save_selection();
  
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

 $("#project-share-embed").click(function(){
   if($('#dropdown-embed-url').is(":hidden")) {
     if($('#dropdown-type-embed').is(":hidden")){
         $('#dropdown-type-embed').show("blind", { direction: "vertical" }, 200);
     } else {
         $('#dropdown-type-embed').hide("blind", { direction: "vertical" }, 200);
     }
   } 
   return false;
 });


 $("#project-share-embed-close").click(function(){
   $('#dropdown-embed-url').hide("blind", { direction: "vertical" }, 200); 
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
    title = site_title + " - " + title.substring(9);
    bookmarksite(title, href);
    return false;
  });


  $("#page-share-whistleblower").click(function(){
    var url = "/whistleblower/?referrer=" + encodeURIComponent(document.URL.toString());
    window.location = url;
    return false;
  });

  
function get_embed_url(type){
  baseurl = ''; 
  width = '';
  height = '';
  if(type == 'projects-map'){
    baseurl = home_url + "/embed-projects/";
    width = '600';
    height = '290';
  } else if(type == 'indicator-map'){
    baseurl = home_url + "/embed-indicators-map/";
    width = '600';
    height = '350';
  } else if(type == 'line-graph'){
    baseurl = home_url + "/embed-indicator-line-graph/";
    width = '600';
    height = '400';
  } else if(type == 'table-graph'){
    baseurl = home_url + "/embed-indicator-table-graph/";
    width = '600';
    height = '400';
  }
  iframeurl = baseurl + build_current_url();
  iframecode = '<script type="text/javascript" src="http://localhost/unhabitat/wp-content/themes/unhabitat/js/embed.js"></script> \n';
  iframecode += '<script>\n oipa_embed.options(\n    url = ' + iframeurl + ',\n    width = ' + width + ',\n    height = ' + height + '\n);\n</script>';

  return iframecode;
}





function get_url_parameter_value(VarSearch, url){
    var SearchString = window.location.search.substring(1);
    if(url){
      SearchString = url.split('?')[1];
    }
    var VariableArray = SearchString.split('&');
    for(var i = 0; i < VariableArray.length; i++){
        var KeyValuePair = VariableArray[i].split('=');
        if(KeyValuePair[0] == VarSearch){
            return KeyValuePair[1];
        }
    }
    return null;
}


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

if (navigator.userAgent.indexOf('Chrome') != -1 && parseFloat(navigator.userAgent.substring(navigator.userAgent.indexOf('Chrome') + 7).split(' ')[0]) >= 15){//Chrome
 $(".project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}

if (navigator.userAgent.indexOf("Opera") != -1){
  $(".project-share-bookmark").css("display", "none");
    $("#page-share-bookmark").css("display", "none");
}


