{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    
    <!-- Page specific script -->
    <!-- fullCalendar 2.2.5 -->
    <script src="{$template_url}plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="{$template_url}plugins/fullcalendar/lang/de.js"></script>
     <!-- Slimscroll -->
    <script src="{$template_url}plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="{$template_url}plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <!--script src="{$template_url}js/app.min.js"></script-->
    <!-- jQuery UI 1.11.4 -->
    <script src="{$template_url}plugins/fullcalendar/jquery-ui.min.js"></script>
      {literal}
    <script>
      $(function () {

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
          ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
              title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
              zIndex: 1070,
              revert: true, // will cause the event to go back to its
              revertDuration: 0  //  original position after the drag
            });

          });
        }
        ini_events($('#external-events div.external-event'));

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $('#calendar').fullCalendar({
          customButtons: {
                addButton: {
                    text: '+',
                    click: function() {
                        formloader('event', 'new');
                    }
                }
          },  
          header: {
            left: 'addButton prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: 'Heute',
            month: 'Monat',
            week: 'Woche',
            day: 'Tag'
          },
          lang: 'de',
          //Random default events
          events: {/literal}{$events}{literal},
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function (date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
              // if so, remove the element from the "Draggable Events" list
              $(this).remove();
            }

          },
          eventClick:function(calEvent, jsEvent, view) {
                //alert('Event: ' +  calEvent.id);
                formloader('event', 'edit', calEvent.id);
                // change the border to see which element is used
                $(this).css('border-color', 'red');
          },
          height: window.innerHeight-150
         
            
        });

        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
          e.preventDefault();
          //Save color
          currColor = $(this).css("color");
          //Add color effect to button
          $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
        });
        $("#add-new-event").click(function (e) {
          e.preventDefault();
          
          formloader('event', 'new');
          //Get value and make sure it is not null
          var val = $("#new-event").val();
          /*if (val.length == 0) {
            return;
          }*/

          //Create events
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          $('#external-events').prepend(event);

          //Add draggable funtionality
          ini_events(event);

          //Remove event from text input
          $("#new-event").val("");
        });
      });
    </script>

    {/literal}
{/block}
{block name=additional_stylesheets}{$smarty.block.parent} 
<!-- fullCalendar 2.2.5-->
<link rel="stylesheet" href="{$template_url}plugins/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="{$template_url}plugins/fullcalendar/fullcalendar.print.css" media="print">
{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}   

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel ">
                <div class="panel-body">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}