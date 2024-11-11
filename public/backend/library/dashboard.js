(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.createChart = (label, data) => {
        let canvas = document.getElementById("barChart");
        let ctx = canvas.getContext("2d");
        if (window.myBarChart) {
            window.myBarChart.destroy();
        }
        let chartData = {
            labels: label,
            datasets: [
                {
                    label: revenue,
                    backgroundColor: "rgba(26, 179, 148, 0.5)",
                    borderColor: "rgba(26, 179, 148, 0.7)",
                    pointBackgroundColor: "rgba(26, 179, 148, 1)",
                    pointBorderColor: "#fff",
                    data: data,
                },
            ],
        };
        let chartOption = {
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var value = tooltipItem.yLabel;
                        value = value.toString();
                        value = value.split(/(?=(?:...)*$)/);
                        value = value.join(".");
                        return value;
                    },
                },
            },
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true,
                            userCallback: function (value, index, values) {
                                value = value.toString();
                                value = value.split(/(?=(?:...)*$)/);
                                value = value.join(".");
                                return value;
                            },
                        },
                    },
                ],
                xAxes: [
                    {
                        ticks: {},
                    },
                ],
            },
        };
        window.myBarChart = new Chart(ctx, {
            type: "bar",
            data: chartData,
            options: chartOption,
        });
    };

    HT.changeChart = () => {
        if ($(".chartButton").length) {
            $(document).on("click", ".chartButton", function (e) {
                e.preventDefault();
                let button = $(this);
                let charType = button.attr("data-chart");
                $(".chartButton").removeClass("active");
                button.addClass("active");
                HT.callChart(charType);
            });
        }
    };

    HT.callChart = (charType) => {
        $.ajax({
            url: "ajax/order/chart",
            type: "GET",
            data: {
                charType: charType,
            },
            dataType: "json",
            beforeSend: function () {},
            success: function (res) {
                HT.createChart(res.label, res.data);
            },
        });
    };

    $(document).ready(function () {
        HT.createChart(label, data);
        HT.changeChart();
    });
})(jQuery);
