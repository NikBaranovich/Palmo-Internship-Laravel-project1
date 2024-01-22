import 'bootstrap';

const fetchVenues = (searchVenue, datalistVenues) => {
    if (!searchVenue) {
        return;
    }
    $.ajax({
        type: 'GET',
        url: "/api/entertainment_venues/search",
        data: {
            name: searchVenue
        },
        success: function(venues) {

            const venuesArray = Object.keys(venues).map(function(key) {
                if (venues[key].name === searchVenue) {
                    fetchHalls(venues[key].id);
                } else {
                    hallInput.innerHTML = "";
                }
                return venues[key];
            });
            datalistVenues.innerHTML = venuesArray.reduce(
                (layout, venue) =>
                (layout += `<option value="${venue.id}">${venue.name} </option>`),
                ``
            );
        },
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

