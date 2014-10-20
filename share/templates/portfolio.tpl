{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent} <script>
jQuery(document).ready(function($){
	var $timeline_block = $('.cd-timeline-block');

	//hide timeline blocks which are outside the viewport
	$timeline_block.each(function(){
		if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
			$(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
		}
	});

	//on scolling, show/animate timeline blocks when enter the viewport
	$(window).on('scroll', function(){
		$timeline_block.each(function(){
			if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.cd-timeline-img').hasClass('is-hidden') ) {
				$(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
			}
		});
	});
});
</script>{/block}
{block name=additional_stylesheets}{$smarty.block.parent} <style>
/* -------------------------------- 

Primary style

-------------------------------- */
img { max-width: 100%; }


/* -------------------------------- 

Modules - reusable parts of our design
-------------------------------- */

.cd-container {
  /* this class is used to give a max-width to the element it is applied to, and center it horizontally when it reaches that max-width */
  width: 90%;
  max-width: 1170px;
  margin: 0 auto;
}

.cd-container::after {
  /* clearfix */
  content: '';
  display: table;
  clear: both;
}

/* -------------------------------- 

Main components 

-------------------------------- */


#cd-timeline {
  position: relative;
  padding: 1em 0;
  margin-top: 2em;
  margin-bottom: 2em;
}

#cd-timeline::before {
  /* this is the vertical line */
  content: '';
  position: absolute;
  top: 0;
  left: 18px;
  height: 100%;
  width: 4px;
  background: #d7e4ed;
}
@media only screen and (min-width: 1170px) {

    #cd-timeline {
    margin-top: 3em;
    margin-bottom: 3em;
    }

    #cd-timeline::before {
    left: 50%;
    margin-left: -2px;
    
    }
}

.cd-timeline-block {
  position: relative;
  margin: 2em 0;
  display:inline-table;height:100%;width:100%;
 *zoom: 1;
}

.cd-timeline-block:before, 
.cd-timeline-block:after{
  content: " ";
  display:table;
}

.cd-timeline-block:after { clear: both; }
.cd-timeline-block:first-child { margin-top: 0; }
.cd-timeline-block:last-child { margin-bottom: 0; }

@media only screen and (min-width: 1170px) {
    .cd-timeline-block { margin: 4em 0; }
    .cd-timeline-block:first-child { margin-top: 0; }
    .cd-timeline-block:last-child { margin-bottom: 0; }
}

.cd-timeline-img {
  position: absolute;
  top: 0;
  left: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
 
}

.cd-timeline-img img {
  display: block;
  width: 24px;
  height: 24px;
  position: relative;
  left: 50%;
  top: 50%;
  margin-left: -12px;
  margin-top: -12px;
}

.cd-timeline-img.cd-picture { background: #75ce66; }

.cd-timeline-img.cd-movie { background: #c03b44; }

.cd-timeline-img.cd-location { background: #f0ca45; }
@media only screen and (min-width: 1170px) {

.cd-timeline-img {
  width: 60px;
  height: 60px;
  left: 50%;
  margin-left: -30px;
  /* Force Hardware Acceleration in WebKit */
  -webkit-transform: translateZ(0);
  -webkit-backface-visibility: hidden;
   
}

.cssanimations .cd-timeline-img.is-hidden { visibility: hidden; }

.cssanimations .cd-timeline-img.bounce-in {
  visibility: visible;
  -webkit-animation: cd-bounce-1 0.6s;
  -moz-animation: cd-bounce-1 0.6s;
  animation: cd-bounce-1 0.6s;
}
}
 @-webkit-keyframes 
cd-bounce-1 {  0% {
 opacity: 0;
 -webkit-transform: scale(0.5);
}
 60% {
 opacity: 1;
 -webkit-transform: scale(1.2);
}
 100% {
 -webkit-transform: scale(1);
}
}
@-moz-keyframes 
cd-bounce-1 {  0% {
 opacity: 0;
 -moz-transform: scale(0.5);
}
 60% {
 opacity: 1;
 -moz-transform: scale(1.2);
}
 100% {
 -moz-transform: scale(1);
}
}
@-o-keyframes 
cd-bounce-1 {  0% {
 opacity: 0;
 -o-transform: scale(0.5);
}
 60% {
 opacity: 1;
 -o-transform: scale(1.2);
}
 100% {
 -o-transform: scale(1);
}
}
@keyframes 
cd-bounce-1 {  0% {
 opacity: 0;
 transform: scale(0.5);
}
 60% {
 opacity: 1;
 transform: scale(1.2);
}
 100% {
 transform: scale(1);
}
}

.cd-timeline-content {
  position: relative;
  margin-left: 60px;
  background: white;
  border-radius: 0.25em;
  border: 1px solid hsl(0,0%,70%);
  padding: 0.5em;
  box-shadow: 0 3px 0 #d7e4ed;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
 *zoom: 1;
}



.cd-timeline-content:before,
.cd-timeline-content:after
{
  content: " ";
  display: table;
}


.cd-timeline-content:after { clear: both; }

.cd-timeline-content h2 { color: #303e49; }

.cd-timeline-content p,
.cd-timeline-content .cd-read-more,
.cd-timeline-content .cd-date {
  font-size: 12px;
  font-size: 0.66rem;
}

.cd-timeline-content .cd-read-more,
.cd-timeline-content .cd-date { display: inline-block; }

.cd-timeline-content p {
  
  line-height: 1.6;
}

.cd-timeline-content .cd-read-more {
  float: right;
  margin: 0.8em;
}

.no-touch .cd-timeline-content .cd-read-more:hover { background-color: #bac4cb; }

.cd-timeline-content .cd-date {
  float: left;
  padding: .8em 0;
  opacity: .7;
}

.cd-timeline-content::before {
  content: '';
  position: absolute;
  top: 16px;
  right: 100%;
  height: 0;
  width: 0;
  border: 7px solid transparent;
  border-right: 7px solid hsl(0,0%,70%);
  margin-right: 1px;
}
@media only screen and (min-width: 768px) {

.cd-timeline-content h2 {
  font-size: 14px;
  font-size: 0.875rem;
  padding:0.8em;
}

.cd-timeline-content p {
  font-size: 13px;
  font-size: 0.8125rem;  
}

.cd-timeline-content .cd-read-more,
.cd-timeline-content .cd-date {
  font-size: 13px;
  font-size: 0.8125rem;
}
}
@media only screen and (min-width: 1170px) {

.cd-timeline-content {
  margin-left: 0;
  padding: 0.8em;
  width: 45%;
}

.cd-timeline-content::before {
  top: 24px;
  left: 100%;
  border-color: transparent;
  border-left-color: hsl(0,0%,70%);
}

.cd-timeline-content .cd-read-more { float: left; }

.cd-timeline-content .cd-date {
  position: absolute;
  width: 100%;
  left: 122%;
  top: 6px;
  font-size: 16px;
  font-size: 1rem;
}

.cd-timeline-block:nth-child(even) .cd-timeline-content { float: right; }

.cd-timeline-block:nth-child(even) .cd-timeline-content::before {
  top: 24px;
  left: auto;
  right: 100%;
  border-color: transparent;
  border-right-color: hsl(0,0%,70%);
}

.cd-timeline-block:nth-child(even) .cd-timeline-content .cd-read-more { float: right; }

.cd-timeline-block:nth-child(even) .cd-timeline-content .cd-date {
  left: auto;
  right: 122%;
  text-align: right;
}

.cssanimations .cd-timeline-content.is-hidden { visibility: hidden; }

.cssanimations .cd-timeline-content.bounce-in {
  visibility: visible;
  -webkit-animation: cd-bounce-2 0.6s;
  -moz-animation: cd-bounce-2 0.6s;
  animation: cd-bounce-2 0.6s;
}
}
 @media only screen and (min-width: 1170px) {

/* inverse bounce effect on even content blocks */

.cssanimations .cd-timeline-block:nth-child(even) .cd-timeline-content.bounce-in {
  -webkit-animation: cd-bounce-2-inverse 0.6s;
  -moz-animation: cd-bounce-2-inverse 0.6s;
  animation: cd-bounce-2-inverse 0.6s;
}
}
@-webkit-keyframes 
cd-bounce-2 {  0% {
 opacity: 0;
 -webkit-transform: translateX(-100px);
}
 60% {
 opacity: 1;
 -webkit-transform: translateX(20px);
}
 100% {
 -webkit-transform: translateX(0);
}
}
@-moz-keyframes 
cd-bounce-2 {  0% {
 opacity: 0;
 -moz-transform: translateX(-100px);
}
 60% {
 opacity: 1;
 -moz-transform: translateX(20px);
}
 100% {
 -moz-transform: translateX(0);
}
}
@-o-keyframes 
cd-bounce-2 {  0% {
 opacity: 0;
 -o-transform: translateX(-100px);
}
 60% {
 opacity: 1;
 -o-transform: translateX(20px);
}
 100% {
 -o-transform: translateX(0);
}
}
@keyframes 
cd-bounce-2 {  0% {
 opacity: 0;
 transform: translateX(-100px);
}
 60% {
 opacity: 1;
 transform: translateX(20px);
}
 100% {
 transform: translateX(0);
}
}
@-webkit-keyframes 
cd-bounce-2-inverse {  0% {
 opacity: 0;
 -webkit-transform: translateX(100px);
}
 60% {
 opacity: 1;
 -webkit-transform: translateX(-20px);
}
 100% {
 -webkit-transform: translateX(0);
}
}
@-moz-keyframes 
cd-bounce-2-inverse {  0% {
 opacity: 0;
 -moz-transform: translateX(100px);
}
 60% {
 opacity: 1;
 -moz-transform: translateX(-20px);
}
 100% {
 -moz-transform: translateX(0);
}
}
@-o-keyframes 
cd-bounce-2-inverse {  0% {
 opacity: 0;
 -o-transform: translateX(100px);
}
 60% {
 opacity: 1;
 -o-transform: translateX(-20px);
}
 100% {
 -o-transform: translateX(0);
}
}
@keyframes 
cd-bounce-2-inverse {  0% {
 opacity: 0;
 transform: translateX(100px);
}
 60% {
 opacity: 1;
 transform: translateX(-20px);
}
 100% {
 transform: translateX(0);
}
}
</style>{/block}

{block name=content} 

        <div class="border-box">
            <div class="contentheader ">{$page_title}</div>
        <h1 align="center" >Mein Lernverlauf</h1>
        <section id="cd-timeline" class="cd-container">

            {foreach key=artid item=art from=$artefact}
            <div class="cd-timeline-block">
                {if $art->artefact_type eq 1}
                    <div class="cd-timeline-img cd-picture"> <img class="okbtn" > </div>
                        <!-- cd-timeline-img -->

                        <div class="cd-timeline-content">
                        <h2>Lehrplan: {$art->curriculum}</h2>
                        <p>Ziel erfolgreich abgeschlossen: <br>{$art->title}</p>
                        <p>{$art->description}</p>
                        <p><br>Kontrolliert von <strong>{$art->accomplished_teacher}</strong></p>
                        <a href="#0" class="cd-read-more">Kommentar</a> <span class="cd-date">{$art->creation_time}</span> 
                    </div>
                {/if}
                {if $art->artefact_type eq 2}
                    <div class="cd-timeline-img cd-movie"> <img class="filebtn"> </div>
                        <!-- cd-timeline-img -->

                        <div class="cd-timeline-content">
                        <h2>{$art->curriculum}</h2>
                        <p>{$art->title}</p>
                        <img src="{$art->path}{$art->filename}" alt="">
                        <p>Autor: {$art->author}</p>
                        <p>Lizenz: {$art->licence}</p>
                        <p>Beschreibung: {$art->description}</p>
                        <a href="#0" class="cd-read-more">Kommentar</a> <span class="cd-date">{$art->creation_time}</span> 
                    </div>
                {/if}
            <!-- cd-timeline-content --> 
            </div>
            <!-- cd-timeline-block -->    
            {/foreach}
    
        </div>
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
