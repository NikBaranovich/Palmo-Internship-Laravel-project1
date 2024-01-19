console.log("Hi 2");

export function fetchVenues(searchVenue, datalistVenues) {
    if (!searchVenue) {
        return;
    }
    $.ajax({
        type: 'GET',
        url: "{{ route('api.entertainment_venues.search') }}",
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

export default {fetchVenues}
