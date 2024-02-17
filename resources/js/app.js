import 'bootstrap';

const fetchVenues = (searchVenue, success) => {
    if (!searchVenue) {
        return;
    }
    $.ajax({
        type: 'GET',
        url: "/api/entertainment_venues/search",
        data: {
            name: searchVenue
        },
        success,
        error: function(response) {
            console.error(response);
        }
    });
}

window.fetchVenues = fetchVenues;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

