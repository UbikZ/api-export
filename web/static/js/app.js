$(function () {
    "use strict";

  // Charts
  var itemDayChart = new Morris.Bar({
    element: 'item-by-day-chart',
    data: [],
    xkey: 'day',
    ykeys: ['items', 'approved', 'reposted'],
    labels: ['Items added by day', 'Items approved by day', 'Items reposted by day']
  });

  getData({}, itemDayChart);

  function getData(filter, chartItem) {
    $.ajax({
      type: "GET",
      dataType: 'json',
      url: "/feed-item-stats",
      data: filter
    })
      .done(function( data ) {
        chartItem.setData(data);
      })
      .fail(function() {
        console.info('Impossible to retrieve data');
      });
  }
});