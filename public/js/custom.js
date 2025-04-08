$('#calendar').datepicker({
    format: 'dd.mm.yyyy',
		});

!function ($) {
    $(document).on("click","ul.nav li.parent > a ", function(){          
        $(this).find('em').toggleClass("fa-minus");      
    }); 
    $(".sidebar span.icon").find('em:first').addClass("fa-plus");
};

(window.jQuery);
	$(window).on('resize', function () {
  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
});
$(window).on('resize', function () {
  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
});

$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('em').removeClass('fa-toggle-up').addClass('fa-toggle-down');
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('em').removeClass('fa-toggle-down').addClass('fa-toggle-up');
	}
});

$('.datepicker').datepicker();

function validateStudentConfirm(ratingStatus){
    if(ratingStatus == 1) {
        return true;
    }
    alert('You need to set rating before confirming!');
    return false;
}

function validateTeacherConfirm(homeworkStatus, ratingStatus){
    if(homeworkStatus == 1){
        if(ratingStatus == 1){
            return true;
        }
        alert('You need to set rating before confirming!');
        return false;
    }else{
        alert('You need to write homework before confirming!');
        return false;
    }
}


var eventsRoute = '/fc-load-events';

function getParams()
{
    var filters = [];
    $(".form-control").each(function () {
        var name  = $(this).prop('name');
        var value = $(this).val();

        if(value){
            filters[name] = value;
        }
    });

    var params = getUrlParameter();
    for (var key in filters) {
        params[key] = filters[key];
    };

    if(isCalendar()){
       // return params;
    }

    return joinAssociativeUrl(filters);
}

function getUrlParameter() {
    var sPageURL      = decodeURIComponent(window.location.search.substring(1));
    var sURLVariables = sPageURL.split('&');
    var sParameterName;
    var i;

    var ret = [];
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if(sParameterName[0] === undefined || sParameterName[1] === undefined ){
            continue;
        }

        ret[sParameterName[0]] = sParameterName[1];
    }
    return ret;
};

function joinAssociativeUrl(array) {
    var ret = '?';

    for (var key in array) {
        ret += key + '=' + array[key] + '&';
    }
    return ret;
}

var calendarId = 'calendar-holder';
function isCalendar()
{
    //Attempt to get the element using document.getElementById
    var element = document.getElementById(calendarId);

    //If it isn't "undefined" and it isn't "null", then it exists.
    if(typeof(element) != 'undefined' && element != null){
        return true;
    } else{
        return false;
    }
}


$(document).ready(function() {
    setState();

    function setState() {

        var params = getUrlParameter();

        console.log('set state');
        console.log(params);
        console.log('-------------');

        $(".s-filter-form").each(function () {
            var name  = $(this).prop('name');

            if(params.hasOwnProperty(name)){
                $(this).val(params[name]);
                $(this).addClass('s-filter-dropdown-checked')
            }
        });

        if(isCalendar()){
            calendar('GET', eventsRoute);
        }
    }

    $( ".s-filter-form" ).change(function() {

        if(isCalendar()){
            calendar('GET', eventsRoute);
        }else{
            refreshPage();
        }
    });

    function refreshPage(){

        if(isCalendar()){
            return;
        }

        var params = getParams();
        var route  = getCurrentRoute() + params;

        window.open(route,"_self");
    }

    $( ".base_url" ).click(function() {

        var base = $(this).attr('base');
        var file = $(this).attr('file');

        var route  = base + file;
        window.open(route);
    });

    function getCurrentRoute() {
        return 'http://' + window.location.host + window.location.pathname;
    }

    function getFullRoute(route) {
        return 'http://' + window.location.host + route;
    }

    $( ".updatePayments" ).click(function() {

        var allVals = [];
        $('input:checkbox.updateCheckbox').each(function () {
            var thisVal = (this.checked ? $(this).val() : "");

            if(thisVal){
                allVals.push(thisVal);
            }
        });

        if(allVals.length > 0){

            var url = getFullRoute('/payment/edit_multiple');

            var data = {
                'payments' : allVals
            };

            myPost(url, data)
        }else{
            alert('You need to choose payments')
        }

    });

    $( ".deletePayments" ).click(function() {

        var conf = confirm('Are you sure you want to delete payments?');

        if(conf === false){
            return;
        }

        var allVals = [];
        $('input:checkbox.updateCheckbox').each(function () {
            var thisVal = (this.checked ? $(this).val() : "");

            if(thisVal){
                allVals.push(thisVal);
            }
        });

        if(allVals.length > 0){

            var url = getFullRoute('/payment/delete_multiple');

            var data = {
                'payments' : allVals
            };

            myPost(url, data)
        }else{
            alert('You need to choose payments')
        }

    });

    $( ".deleteLessons" ).click(function() {

        var conf = confirm('Are you sure you want to delete lessons?');

        if(conf === false){
            return;
        }

        var allVals = [];
        $('input:checkbox.updateCheckbox').each(function () {
            var thisVal = (this.checked ? $(this).val() : "");

            if(thisVal){
                allVals.push(thisVal);
            }
        });

        if(allVals.length > 0){

            var url = getFullRoute('/admin/lesson/delete_multiple');

            var data = {
                'lessons' : allVals
            };

            myPost(url, data)
        }else{
            alert('You need to choose lessons')
        }

    });

    function myPost(url, data) {

        $.post(url, JSON.stringify(data), function (data, status) {
            refreshPage();

        }).fail(function (jqXHR, textStatus, error) {

        })
    }
});


function calendar(method, url)
{
    var params = getParams();
    url = url + params;

    var calendarEl = document.getElementById(calendarId);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        defaultView: 'dayGridMonth',
        editable: true,
        eventSources: [
            {
                url: url,
                method: method,
                extraParams: {
                    filters: JSON.stringify({})
                },
                failure: () => {
                    // alert("There was an error while fetching FullCalendar!");
                },
            },
        ],
        header: {
            left: 'prev,next, today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        plugins: [ 'interaction', 'dayGrid', 'timeGrid' ], // https://fullcalendar.io/docs/plugin-index
        timeZone: 'UTC',
    });
    calendar.render();
}

