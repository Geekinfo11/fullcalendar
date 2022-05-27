<!DOCTYPE html>

<html>

<head>

    <title>Laravel Fullcalender</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    {{-- calendar css--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    {{-- calendar scheduler --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.css' rel='stylesheet' />

    {{-- toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    {{-- --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.print.css' rel='stylesheet'
        media='print' />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@1.10.4/dist/scheduler.min.js'></script>
    {{-- --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- jquery modal popup--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>


    <style>
        .modal {
            /* z-index: 2; */
            height: 460px !important;
            width: 500px !important;
        }

        .fc-event-container .fc-content {
            display: flex;
            flex-direction: column;
        }

        .fc-cell-text {
            padding: 50px !important;
        }

        .fc-time-area .fc-rows td>div {
            padding: 0px 0px 40px 0px;
        }
    </style>

</head>

<body>
    <div class="container">

        {{-- calendar code --}}

        <div id='calendar'></div>

        {{-- modal for adding new events --}}

        <div id="modal-c-events" class="modal">
            <div class="form-group">
                <label for="event-title">Event</label>
                <input type="text" class="form-control" id="event-title-m1" name="title">
            </div>

            <div class="form-group">
                <label for="start">Start</label>
                <input type="time" class="form-control" id="event-start-m1" name="start">
            </div>

            <div class="form-group">
                <label for="end">End</label>
                <input type="time" class="form-control" id="event-end-m1" name="end">
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Doctors</label>
                <select class="form-control" id="select-el-dctrs">
                </select>
            </div>

            <a href="#" id="modal-cancel" class="btn btn-primary btn-block" rel="modal:close">Cancel</a>
            <a href="#" id="modal-ok" class="btn btn-success btn-block" rel="modal:close">OK</a>
        </div>

        {{-- modal for updating, deleteting events--}}

        <div id="modal-ud-events" class="modal">
            <div class="form-group">
                <label for="event-title">Event</label>
                <input type="text" class="form-control" id="event-title" name="title">
            </div>

            <div class="form-group">
                <label for="start">Start</label>
                <input type="time" class="form-control" id="event-start" name="start">
            </div>

            <div class="form-group">
                <label for="end">End</label>
                <input type="time" class="form-control" id="event-end" name="end">
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Doctors</label>
                <select class="form-control" id="select-el-dctrs-modal-2">
                </select>
            </div>

            <div id="modal-bttns" class="d-flex flex-row justify-content-around">
                <a href="#" id="modal-cancel" class="btn btn-primary" rel="modal:close">Cancel</a>
                <a href="#" id="modal-update" class="btn btn-success" rel="modal:close">Update</a>
                <a href="#" id="modal-delete" class="btn btn-danger" rel="modal:close">Delete</a>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {

        var SITEURL = "{{ url('/') }}";

        var doctors = [];

        $.ajaxSetup({

            headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });

        function convertToDateTimeString(date, time){
            var newDate = new Date(date);
            // var startHour = $('#event-start').val();
            // var endHour = $('#event-end').val();

            var day = newDate.getDate();
            var month = newDate.getMonth() + 1;
            var year = newDate.getFullYear();

            var fullStartDate = year+ '-' +month+ '-' +day;
            
            var fullStartDateTime = fullStartDate+ ' ' +time+ ':' + '00';

            return fullStartDateTime;
        }

        function addDoctorsToSelectElModal(selectId){
            // clear the select element after setting it to avoid duplicate values
            $('#'+selectId).empty();

            // get doctors from database to set our select element in the modal popup
            $.ajax({
                url: SITEURL + '/fullcalender/resources',
                
                type: "GET",
            
                success: function (response) {
                    // set doctors in modal select element
                    // $('#select-el-dctrs')
                    for (let index = 0; index < response.length; index++) {
                        let doctor = response[index];
                        $('#'+selectId).append(new Option(doctor.title, doctor.id));
                    }
                }
            });
        }

var calendar = $('#calendar').fullCalendar({

        header: {
            // right: 'month, agendaWeek, agendaDay, listMonth',
            // right: 'prev, next',
            center: 'title',
            left: 'timelineMonth, timelineWeek, timelineDay, listMonth, agendaDay'
        },

        defaultView: 'timelineMonth',

        
        buttonText:{
            month: 'mois',
            agendaWeek: 'semaine',
            agendaDay: 'jour',
            list: 'Liste rendez-vous',
            // timelineMonth: 'mois',
        },

        editable: true,

        events: SITEURL + "/fullcalender",

        displayEventTime: false,

        //

        eventRender: function (event, element, view) {

            if (event.allDay === 'true') {

                    event.allDay = true;

            } else {

                    event.allDay = false;

            }

            element.css( {'padding': '20px', 'font-size' : '17px', 'margin': '10px 5px', 'border': '1px solid #fff',
                        'border-right-color': '#DDDDDD','border-left-color':'#DDDDDD',
                    });

            // Get Doctor by its id
            if(event.resourceId){
                $.ajax({
                    url: SITEURL + '/fullcalender/resource/'+event.resourceId,
                    
                    type: "GET",
                
                    success: function (response) {
                        element.append('<p>'+response.title+'</p>');
                    }
                });
            }
        },

        displayEventTime: true,
        displayEventEnd: true,

        selectable: true,

        selectHelper: true,

        //
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',

        resourceLabelText: 'Doctors',
        // this allow the resource to be shown in week,day,month
        // groupByResource:true,
        // 
        // resourceAreaHeaderContent: 'Rooms',
        resourceGroupField: 'id',
        resourceAreaWidth:'20%',

        // get resource from database
        resources: SITEURL + "/fullcalender/resources",

        // here you can sniff. resources render to the page
        resourceRender: function(info, domObj) {
            // info is the object resource coming from database
            // domObj is the html element for the resource
            domObj.css('background', info.eventColor);
        },

        select: function (start, end, allDay) {

            // open modal
            $('#modal-c-events').modal('show');

            // clear the select element after setting it to avoid duplicate values
            $('#select-el-dctrs').empty();
            
            // get doctors from database to set our select element in the modal popup
            addDoctorsToSelectElModal('select-el-dctrs');

            // hide modal close button
            $('.close-modal').hide();
            
            //
            $('#modal-ok').on('click', function(e) {

                var title = $('#event-title-m1').val();

                var startHour = $('#event-start-m1').val();
                var endHour = $('#event-end-m1').val();
                var doctorValue = $('#select-el-dctrs').val();

                if(title && startHour && endHour){

                    var fullStartDateTime = convertToDateTimeString(start, startHour);
    
                    var fullEndDateTime = convertToDateTimeString(start, endHour);
                    
                    $.ajax({
    
                        url: SITEURL + "/fullcalenderAjax",
    
                        data: {
    
                            title: title ?? 'event',
    
                            start: fullStartDateTime,
    
                            end: fullEndDateTime,

                            resourceId: doctorValue,
    
                            type: 'add'
    
                        },
    
                        type: "POST",
    
                        success: function (data) {
    
                            displayMessage("Event Created Successfully");
    
                            calendar.fullCalendar('renderEvent',
    
                                {
                                    id: data.id,
    
                                    title: title,
    
                                    start: start,
    
                                    end: end,
    
                                    allDay: allDay
    
                                },true);
    
                            calendar.fullCalendar('unselect');
                            window.location.reload();
    
                        }
    
                    });

                }

            });
        },

        eventDrop: function (event, delta) {

            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm");



            $.ajax({

                url: SITEURL + '/fullcalenderAjax',

                data: {

                    title: event.title,

                    start: start,

                    end: end,

                    id: event.id,

                    type: 'update'

                },

                type: "POST",

                success: function (response) {
                    displayMessage("Event Updated Successfully");
                    window.location.reload();
                }

            });

        },

        eventClick: function (event, info) {
            
            $('#modal-ud-events').modal('show');
            $('.close-modal').hide();

            // set modal
            // set title
            $('#modal-ud-events #event-title').val(event.title);

            // set start time
            var startTime = event.start._i.split(' ')[1];
            startTime = startTime.slice(0,startTime.lastIndexOf(':'));
            $('#modal-ud-events #event-start').val(startTime);
            
            // set end time
            var endTime = event.end._i.split(' ')[1];
            endTime = endTime.slice(0,endTime.lastIndexOf(':'));
            $('#modal-ud-events #event-end').val(endTime);

            // set select element in modal popup
            addDoctorsToSelectElModal('select-el-dctrs-modal-2');

            $('#modal-bttns').on('click', function(e){
                console.log($('#select-el-dctrs-modal-2').val());
                
                title = $('#modal-ud-events #event-title').val();
                startTime = $('#modal-ud-events #event-start').val();
                endTime = $('#modal-ud-events #event-end').val();
                
                var doctorValue = $('#select-el-dctrs-modal-2').val();

                var fullStartDateTime = convertToDateTimeString(event.start, startTime);
                var fullEndDateTime = convertToDateTimeString(event.start, endTime);

                // update event
                if(e.target.id == 'modal-update'){
                    $.ajax({

                    url: SITEURL + '/fullcalenderAjax',

                    data: {

                        title: title,

                        start: fullStartDateTime,

                        end: fullEndDateTime,

                        id: event.id,

                        resourceId: doctorValue,

                        type: 'update',

                    },

                    type: "POST",

                    success: function (response) {
                        displayMessage("Event Updated Successfully");
                        window.location.reload();
                    }

                    });

                    // refetch events after updating
                    calendar.fullCalendar('refetchEvents');

                }
                else if(e.target.id == 'modal-delete'){

                    $.ajax({

                    type: "POST",

                    url: SITEURL + '/fullcalenderAjax',

                    data: {

                            id: event.id,

                            type: 'delete'

                    },

                    success: function (response) {

                        calendar.fullCalendar('removeEvents', event.id);

                        displayMessage("Event Deleted Successfully", 'delete');

                    }

                    });

                }

            });

        }

    });

});


function displayMessage(message, task) {

    if(task === 'delete'){
        toastr.error(message, 'Event');
    }
    else{
        toastr.success(message, 'Event');
    }

} 
    </script>



</body>

</html>