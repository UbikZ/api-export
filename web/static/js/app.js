$(function () {
    "use strict";

  // Charts
  var itemDayChart = new Morris.Line({
    element: 'item-by-day-chart',
    data: [],
    xkey: 'year',
    ykeys: ['value'],
    labels: ['Value']
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