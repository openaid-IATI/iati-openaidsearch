<?php
/**
 * Template Name: Demo Unhabitat Drill Down
 *
 * This custom template is designed for demonstration purposes only
 *
 * @package WordPress
 * @subpackage Unhabitat
 * @since 18 March 2013
 */
?>
<?php $regions = wp_get_regions(); 
      
      
//      $countries = wp_get_unique_result($indicator_results, 'country_name', 'dac_region_name');
      $selected_region = $_GET['region'];
      $selected_country = $_GET['country'];
      $selected_year = $_GET['years'];
      $selected_indicator = $_GET['indicator'];
      
      if(!strlen($selected_indicator)>0 || $selected_indicator == 'all'){
          $selected_indicator = 'population';
      }
      
      $countries = wp_get_countries($selected_region);
      $cities = wp_get_cities($selected_country);
      
      $indicator_results = wp_get_indicator_results($selected_region, $selected_country, $selected_year);
      
      $indicator_relevant_results = wp_get_relevant_results($selected_indicator);

      
?>
<?php get_header(); ?>

<select id="regions">
    <option value="">all</option>
    <?php foreach ($regions as $region) : ?>
    <?php if ($region['code'] == $selected_region ) :?>    
    <option selected="selected" value="<?php echo $region['code']; ?>"><?php echo $region['code']; ?></option>
    <?php else :?>
    <option value="<?php echo $region['code']; ?>"><?php echo $region['code']; ?></option>
    <?php endif ?>
    <?php endforeach ?>
</select>
<?php //print_r($countries)?>
<select id="list_countries">
        <option value="">all</option>

    <?php foreach ($countries as $country) : ?>
        <?php if ($country['dac_region_code'] == $selected_region ) :?>
            <?php if ($country['iso2'] == $selected_country ) :?>
                 <option selected="selected" value="<?php echo $country['iso2']; ?>"><?php echo $country['country_name'];?></option>
            <?php else :?>
                 <option value="<?php echo $country['iso2']; ?>"><?php echo $country['country_name'];?></option>
            <?php  endif ?>
        <?php endif ?>
    <?php endforeach ?>
</select>
<select id="cities">
        <option value="">all</option>

    <?php foreach ($cities as $city) : ?>
    
    <option value="<?php echo $city['name']; ?>"><?php echo $city['name']; ?></option>
    <?php endforeach ?>
</select>

<!--<select id="regions">
    <?php foreach (wp_get_unique_result($indicator_results, 'dac_region_name') as $ir) : ?>
    
    <option value=""><?php echo $ir; ?></option>
    <?php endforeach ?>
</select>
<select id="list_countries">
    <?php foreach ($countries as $ir) : ?>
    
    <option value=""><?php print_r($ir); ?></option>
    <?php endforeach ?>
</select>-->

<select id="list_years">
            <option value="">all</option>

    <?php foreach (wp_get_unique_result($indicator_relevant_results, 'year') as $i) : ?>
     <?php if ($i == $selected_year ) :?>
            <option selected="selected" value="<?php echo $i; ?>"><?php echo $i;?></option>
     <?php else : ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

     <?php endif ?>
    <?php endforeach ?>
</select>

<select id="list_indicators">
        
    <option value="avg_annual_rate_change_percentage_urban">avg_annual_rate_change_percentage_urban</option>
        
    <option value="avg_annual_rate_change_total_population">avg_annual_rate_change_total_population</option>
        
    <option value="bottle_water">bottle_water</option>
        
    <option value="composting_toilet">composting_toilet</option>
        
    <option value="connection_to_electricity">connection_to_electricity</option>
    <option value="deprivation">deprivation</option>
        
    <option value="enrollment_female_primary_education">enrollment_female_primary_education</option>
        
    <option value="enrollment_male_primary_education">enrollment_male_primary_education</option>
        
    <option value="has_telephone">has_telephone</option>
        
    <option value="improved_floor">improved_floor</option>
        
    <option value="improved_flush_toilet">improved_flush_toilet</option>
        
    <option value="improved_pit_latrine">improved_pit_latrine</option>
        
    <option value="improved_spring_surface_water">improved_spring_surface_water</option>
        
    <option value="improved_toilet">improved_toilet</option>
        
    <option value="improved_water">improved_water</option>
        
    <option value="piped_water">piped_water</option>
        
    <option value="pit_latrine_with_slab_or_covered_latrine">pit_latrine_with_slab_or_covered_latrine</option>
        
    <option value="pit_latrine_without_slab">pit_latrine_without_slab</option>
        
    <option value="pop_rural_area">pop_rural_area</option>
        
    <option value="pop_urban_area">pop_urban_area</option>
        
    <option value="pop_urban_percentage">pop_urban_percentage</option>
        
    <option value="population">population</option>
        
    <option value="protected_well">protected_well</option>
        
    <option value="public_tap_pump_borehole">public_tap_pump_borehole</option>
        
    <option value="pump_borehole">pump_borehole</option>
        
    <option value="rainwater">rainwater</option>
        
    <option value="slum_proportion_living_urban">slum_proportion_living_urban</option>
        
    <option value="sufficient_living">sufficient_living</option>
        
    <option value="under_five_mortality_rate">under_five_mortality_rate</option>
        
    <option value="urban_population">urban_population</option>
        
    <option value="urban_slum_population">urban_slum_population</option>
 
    </select>
<?php 
//    print_r($countries);
//    echo $selected_region;
?>


<?php // Dit hoort in je head ?>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
 <!--[if lte IE 8]>
     <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" />
 <![endif]-->
 <script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
 

<script type="text/javascript">
$(document).ready(function() {
//    alert(countryData.type);
    $('#regions').change(function(e){
       console.log(e.currentTarget.value); 
       window.location = '?region=' + e.currentTarget.value;
       sel_region = e.currentTarget.value;
    });
    $('#list_countries').change(function(e){
       console.log(e.currentTarget.value, $('#regions').val()); 
       window.location = '?region=' + $('#regions').val() + '&country=' + e.currentTarget.value + '&indicator=' + $('#list_indicators').val();
    });
    $('#list_years').change(function(e){
       
       window.location = '?region=' + $('#regions').val() + '&indicator=' + $('#list_indicators').val() + '&country=' + $('#list_countries').val() + '&years=' + e.currentTarget.value;
    });
    $('#list_indicators').change(function(e){
       
       window.location = '?region=' + $('#regions').val() + '&country=' + $('#list_countries').val() + '&years=' + $('#list_years').val() + '&indicator=' + e.currentTarget.value;
    });
    
    
    $('#list_indicators option[value=<?php echo $selected_indicator ?>]').attr('selected', 'selected')
    
});
</script>	
 
 <?php // en in je css in je head ?>
 <style>
#map { height: 420px; }
.info {
    padding: 6px 8px;
    font: 14px/16px Arial, Helvetica, sans-serif;
    background: white;
    background: rgba(255,255,255,0.8);
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    border-radius: 5px;
}
.info h4 {
    margin: 0 0 5px;
    color: #777;
}
.legend {
    line-height: 18px;
    color: #555;
}
.legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin-right: 8px;
    opacity: 0.7;
}
</style>

 
 
 <div id="map"></div>
 <?php 
 
 $temp = array();
    foreach ($indicator_results as $i) {
        array_push($temp, $i[$selected_indicator]);
    }
    $max_indicator = max($temp);
    //$factor = 400000 / 40000;
    //$factor = 600000 / $max_pop;
    $maxcircleradius = 3000000000000;
    $factor = $maxcircleradius / $max_indicator;
 ?>
 <?php // Dit hoort onder je map container ?>
 <script>

var map = L.map('map').setView([5.505, -20.09], 1.7);
L.tileLayer('http://{s}.tile.cloudmade.com/6251fb4700fc4ea28ad28908a8fa8a4b/997/256/{z}/{x}/{y}.png', {
    maxZoom: 18
}).addTo(map);

//jsonPath(countryData, "$..features[?(@.id=='USA')]")[0].properties.projects





function getColor(d) {
    return d > 1000000  ? '#045A8D' :
           d > 500000   ? '#2B8CBE' :
           d > 20000   ? '#74A9CF' :
           d > 10000   ? '#BDC9E1' :
                      '#F1EEF6';
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.projects),
        weight: 0,
        opacity: 1,
        color: 'white',
        dashArray: '',
        fillOpacity: 0.7
    };
}

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 3,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera) {
        layer.bringToFront();
    }
	
	info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
	info.update();
}

var geojson;

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

geojson = L.geoJson(countryData, {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};

// method that we will use to update the control based on feature properties passed
info.update = function (props) {
    this._div.innerHTML = '<h4>Amount of IATI projects</h4>' +  (props ?
        '<b>' + props.name + '</b><br />' + props.projects + ' projects'
        : 'Hover over a country');
};

info.addTo(map);

//for (var key in countryloc){
 
//}




var legend = L.control({position: 'bottomright'});

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 10000, 200000, 500000, 1000000],
        labels = [];

    // loop through our density intervals and generate a label with a colored square for each interval
    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
            '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
            grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
    }

    return div;
};


//var geojsonLayer = new L.GeoJSON(null,{
//pointToLayer: function (latlng){
//    return new L.CircleMarker(latlng, {
//        radius: 5,
//        fillColor: "#ff7800",
//        color: "#000",
//        weight: 1,
//        opacity: 1,
//        fillOpacity: 0.8
//    });
//}});
//geojsonLayer.addTo(map);
//var circle = L.circle([51.508, -0.11], 300000, {
//    color: 'red',
//    fillColor: '#f03',
//    fillOpacity: 0.5
//}).addTo(map);
//legend.addTo(map);

 <?php foreach($indicator_results as $i) :?>
    
    <?php if (strlen($i[$selected_indicator])>0) :?>
    
    try
      {
//        jsonPath(countryData, "$..features[?(@.id=='<?php echo $i['country_iso3'] ?>')]")[0].properties.projects = '<?php echo $i[$selected_indicator] ?>';//Run some code here
      }
    catch(err)
      {
        console.log('<?php echo $i['country_iso3'] ?>'+err);
      }
          try{
            var circle = L.circle(new L.LatLng(countryloc['<?php echo $i['country_iso'] ?>'].longitude, countryloc['<?php echo $i['country_iso'] ?>'].latitude), <?php echo sqrt(($factor * $i[$selected_indicator])/pi()); //echo round($factor * $i['population']);  ?>, {
              color: 'red',
             weight: '0',
              fillColor: '#f03',
              fillOpacity: 0.5
             }).addTo(map);
             circle.bindPopup('yeahhahahahahah');
            }catch(err){
                console.log(err);
            }
        
    
    <?php endif ?>
<?php endforeach; ?>
 </script>

	


<?php get_footer(); ?>
