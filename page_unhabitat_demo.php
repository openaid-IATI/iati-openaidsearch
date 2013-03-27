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

<!--<select id="regions">
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

<select id="regions">
    <?php foreach (wp_get_unique_result($indicator_results, 'dac_region_name') as $ir) : ?>
    
    <option value=""><?php echo $ir; ?></option>
    <?php endforeach ?>
</select>
<select id="list_countries">
    <?php foreach ($countries as $ir) : ?>
    
    <option value=""><?php print_r($ir); ?></option>
    <?php endforeach ?>
</select>

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
 
    </select>-->

<?php 
 

//    print_r($countries);
//    echo $selected_region;
?>



 

	
 
 <?php // en in je css in je head ?>


 <?php get_template_part( "project", "filters" ); ?>
 
 <?php get_template_part( "map" ); ?>
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


	


<?php get_footer(); ?>
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
    
    
    $('#list_indicators option[value=<?php echo $selected_indicator ?>]').attr('selected', 'selected');
    
    <?php foreach($indicator_results as $i) :?>
    
    <?php if (strlen($i[$selected_indicator])>0) :?>
    
    try
      {
// jsonPath(countryData, "$..features[?(@.id=='<?php echo $i['country_iso3'] ?>')]")[0].properties.projects = '<?php echo $i[$selected_indicator] ?>';//Run some code here
      }
    catch(err)
      {
        console.log('<?php echo $i['country_iso3'] ?>'+err);
      }
          try{
            var circle = L.circle(new L.LatLng(countryloc['<?php echo $i['country_iso'] ?>'].longitude, countryloc['<?php echo $i['country_iso'] ?>'].latitude), <?php echo sqrt(($factor * $i[$selected_indicator])/pi()); //echo round($factor * $i['population']); ?>, {
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
    
});
</script>