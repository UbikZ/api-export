var App = App || {};

App.loadCharts = function () {
  // Charts
  var itemDayChart = new Morris.Bar({
    element: 'item-by-day-chart',
    data: [],
    xkey: 'day',
    ykeys: ['items', 'approved', 'reposted'],
    labels: ['Items added by day', 'Items approved by day', 'Items reposted by day']
  });

  var feedChart = new Morris.Bar({
    element: 'feed-chart',
    data: [],
    xkey: 'label',
    ykeys: ['count'],
    labels: ['Number']
  });

  getData({url: "/feed-item-stats"}, itemDayChart);
  getData({url: "/feed-stats"}, feedChart);

  function getData(filter, chartItem) {
    $.ajax({
      type: "GET",
      dataType: 'json',
      url: filter.url
    })
      .done(function (data) {
        chartItem.setData(data);
      })
      .fail(function () {
        console.info('Impossible to retrieve data');
      });
  }
};

App.loadCharts = function (id) {
  $('#' + id + ' img').remove();
};
