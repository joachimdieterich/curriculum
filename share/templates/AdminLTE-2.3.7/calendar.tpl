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
    <!-- script src="{$template_url}plugins/fastclick/fastclick.min.js"></script-->
    <!-- AdminLTE App -->
    <!--script src="{$template_url}js/app.min.js"></script-->
    <!-- jQuery UI 1.11.4 -->
    <script src="{$template_url}plugins/fullcalendar/jquery-ui.min.js"></script>
/*    <script src="{$template_url}plugins/fullcalendar-scheduler-1.9.2//scheduler.min.js"></script> */
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
          schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
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
            right: 'timelineDay,month,agendaWeek,agendaDay, listWeek'
          },
          buttonText: {
            today: 'Heute',
            month: 'Monat',
            week: 'Woche',
            day: 'Tag'
          },
          lang: 'de',
          nowIndicator: true,
          minTime: '06:00',
          eventRender: function(event, element, view) {

            var theDate = event.start;
            var endDate = event.dowend;
            var startDate = event.dowstart;

            if (theDate >= endDate) {
                    return false;
            }

            if (theDate <= startDate) {
              return false;
            }

          },
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
<link rel="stylesheet" href="{$template_url}plugins/fullcalendar/fullcalendar.print.min.css" media="print">
/* <link rel="stylesheet" href="{$template_url}plugins/fullcalendar-scheduler-1.9.2//scheduler.min.css" media="print"> */
{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/'}   

<!-- Main content -->
<section class="content">
    <div class="row">
            {*<div class="col-md-3">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title">Termine</h4>
                </div>
                <div class="box-body">
                  <!-- the events -->
                  <div id="external-events">
                    <div class="external-event bg-green">Fortbildung</div>
                    <div class="external-event bg-yellow">Konferenzraum</div>
                    <div class="external-event bg-aqua">Treffen</div>
                    <div class="external-event bg-light-blue">Pädagogisches Landesinstitut</div>
                    <div class="external-event bg-red">Medienzentrum</div>
                    <div class="checkbox">
                      <label for="drop-remove">
                        <input type="checkbox" id="drop-remove">
                        nach Verwendung entfernen
                      </label>
                    </div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Neuer Termin</h3>
                </div>
                <div class="box-body">
                  <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                    <ul class="fc-color-picker" id="color-chooser">
                      <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                    </ul>
                  </div><!-- /btn-group -->
                  <div class="input-group">
                    <input id="new-event" type="text" class="form-control" placeholder="z. B. Treffen im Medienzentrum">
                    <div class="input-group-btn">
                      <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Hinzufügen</button>
                    </div><!-- /btn-group -->
                  </div><!-- /input-group -->
                </div>
              </div>
            </div><!-- /.col -->*}
            <div class="col-md-12">
              <div class="box box-default">
                <div class="box-body">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}