let topEvents;
function fetchTopEvents() {

    $.ajax({
        type: 'GET',
        url: "http://localhost:8080/api/events/get-top",
        data: {
            limit: 5
        },
        success: function(events) {
            console.log(events);
            const formattedData = {
                id: [],
                names: [],
                tickets_count: [],
            };
            events.forEach(event => {
                console.log(event);
                formattedData.id.push(event.id);
                formattedData.names.push(event.title);
                formattedData.tickets_count.push(event.tickets_count);
              });
            barChartOptions.series.push({
                data:formattedData.tickets_count,
                name: "tickets",
            });
            barChartOptions.xaxis.categories = formattedData.names;

            const barChart = new ApexCharts(
                document.querySelector('#bar-chart'),
                barChartOptions
              );
              barChart.render();
        },
        error: function(response) {
            console.error(response);
        }
    });
}
fetchTopEvents();


// BAR CHART
const barChartOptions = {
    series: [
    ],
    chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false,
      },
    },
    colors: ['#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1'],
    plotOptions: {
      bar: {
        distributed: true,
        borderRadius: 4,
        horizontal: false,
        columnWidth: '40%',
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    xaxis: {

    },
    yaxis: {
      title: {
      },
      forceNiceScale: true,
      labels: {
        formatter: function(val) {
          return val.toFixed(0);
        }
      }
    },
  };


  function fetchTickets() {
    $.ajax({
        type: 'GET',
        url: "http://localhost:8080/api/tickets/get-tickets-count-by-month",
        data: {
            limit: 5
        },
        success: function(tickets) {
            const formattedData = {
                month: [],
                tickets_count: [],
            };
            formattedData.month = Object.keys(tickets);
            formattedData.tickets_count =  Object.values(tickets);

            areaChartOptions.series.push({
                name: 'Purchase Orders',
                data: formattedData.tickets_count,
              },);
            areaChartOptions.labels = formattedData.month;

            const areaChart = new ApexCharts(
                document.querySelector('#area-chart'),
                areaChartOptions
              );
              areaChart.render();
        },
        error: function(response) {
            console.error(response);
        }
    });
}
fetchTickets();

  // AREA CHART
  const areaChartOptions = {
    series: [
    ],
    chart: {
      height: 350,
      type: 'area',
      toolbar: {
        show: false,
      },
    },
    colors: ['#4f35a1'],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: 'smooth',
    },
    labels: [],
    markers: {
      size: 0,
    },
    yaxis: [
      {
        title: {
          text: 'Purchase Orders',
        },
        labels: {
            formatter: function(val) {
              return val.toFixed(0);
            }
          }
      },

    ],
    tooltip: {
      shared: true,
      intersect: false,
    },
  };


