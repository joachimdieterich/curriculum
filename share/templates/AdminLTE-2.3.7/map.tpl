{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
{literal}
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>

      
<script>
   
// the center of the world:)
var startlat = 49.1796158;
var startlon = 8.0506777;

var options = {
 center: [startlat, startlon],
 zoom: 9
};

var map = L.map('map', options);

var nzoom = 12;
var ins;
ins   = {/literal}{$adresses}{literal};

var ins_positions = new Array();
var myMarker = new Array();
var current_position, current_accuracy;

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);
map.invalidateSize(true);


function chooseAddr(ins, marker, data/*lat1, lng1*/){
     myMarker[marker] = L.marker([data.lat, data.lon], {title: ins.institution, alt: " ", draggable: false}).addTo(map).on('click', function() {
        myMarker[marker].openPopup();
    });
    myMarker[marker].closePopup();
    myMarker[marker].setLatLng([data.lat, data.lon]);
    ins_positions.push([data.lat, data.lon]);
    var name = data.address[data.type]+'<br />';;
    if (name.indexOf("undefined") >=0){
        name = ''; 
    } 
    myMarker[marker].bindPopup('<strong onclick="formloader(\'preview_institution\',\'full\','+ins.id+');">' + ins.institution +'<br /><br />'+name + data.address.road + ' '+ data.address.house_number + ' <br /> '+ data.address.postcode + ' ' + data.address.town);
}
function fitBounds(){
    map.fitBounds(ins_positions,{ padding: [20, 20] });
}

function myFunction(ins, arr){
    var i;
    if(arr.length > 0){
        for(i = 0; i < arr.length; i++){
        chooseAddr(ins, i, arr[i]);
        }
    }
}


function ins_search(){
var marker_array = Array();
    if(ins.length > 0){
        for(i = 0; i < ins.length; i++){
            if (((ins[i].street != null) || (ins[i].street != '')) && ((ins[i].postalcode != null) || (ins[i].postalcode != '')) && ((ins[i].city != null) || (ins[i].city != null))){
                var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&addressdetails=1&q=" + ins[i].street+"+"+ins[i].postalcode+"+"+ins[i].city;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if (this.readyState == 4 && this.status == 200){
                        var myArr = JSON.parse(this.responseText);
                        myFunction(ins[i], myArr);
                    }
                };
                xmlhttp.open("GET", url, false); //fals == synchon
                xmlhttp.send();
            }
        }
    }
} 
 
function addr_search(){
 var inp = document.getElementById("addr");
 var xmlhttp = new XMLHttpRequest();
 var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&addressdetails=1&q=" + inp.value;
 xmlhttp.onreadystatechange = function(){
   if (this.readyState == 4 && this.status == 200){
    var myArr = JSON.parse(this.responseText);
    myFunction(inp, myArr);
   }
 };
 xmlhttp.open("GET", url, true);
 xmlhttp.send();
}

</script>{/literal}

{/block}
{block name=additional_stylesheets}{$smarty.block.parent}
    {literal}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <style type="text/css">
        #lat, #lon { text-align:right }
        #map {height: 700px; display:block; outline: black;}
        .address { cursor:pointer }
        .address:hover { color:#AA0000;text-decoration:underline }
    </style>
    {/literal}
{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/development/'}      
<!-- Main content -->
<section class="content">
    <div class="row">
         <div class="col-xs-12">
         <div class="btn-group pull-left margin-r-5">
                <button type="button" class="btn btn-default" data-toggle="tooltip" title="anzeigen"  onclick="ins_search();">
                    <i class="fa fa-institution"></i>
                </button>
                 <button type="button" class="btn btn-default" data-toggle="tooltip" title="anzeigen"  onclick="fitBounds();">
                    <i class="fa fa-check"></i>
                </button>
            </div>
           
            {if isset($test_reset)}
                <a href="index.php?action=test" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            {/if}
            <form action="#" class="no-padding col-xs-12 col-sm-12 col-md-4 col-lg-3 pull-right" onsubmit="">
                <div class="input-group">
                  <input type="text" id="v_search" class="form-control" placeholder="Suche...">
                      <span class="input-group-addon btn" onclick="">
                            <i class="fa fa-search"></i>  
                      </span>
                </div>
            </form>
        </div>
    </div>
    <div class="row ">
        <div class="col-xs-12 top-buffer">
            <div id="map"></div>
        </div>
    </div>
    </div>
</section>  
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}