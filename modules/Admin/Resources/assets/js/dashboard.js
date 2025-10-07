import Chart from "chart.js/auto";

async function fetchSalesAnalyticsData() {
    const response = await axios.get("/sales-analytics");

    let data = {
        labels: response.data.labels,
        sales: [],
        formatted: [],
        totalOrders: [],
    };

    for (let item of response.data.data) {
        data.sales.push(item.total.amount);
        data.formatted.push(item.total.formatted);
        data.totalOrders.push(item.total_orders);
    }

    initSalesAnalyticsChart(data);
}

fetchSalesAnalyticsData();

function initSalesAnalyticsChart(data) {
    new Chart(document.querySelector(".sales-analytics .chart"), {
        type: "bar",
        data: {
            labels: data.labels,
            datasets: [
                {
                    data: data.sales,
                    borderRadius: 6,
                    backgroundColor: [
                        "rgba(76, 201, 254, .7)",
                        "rgba(71, 90, 255, .7)",
                        "rgba(255, 119, 183, .7)",
                        "rgba(250, 64, 50, .7)",
                        "rgba(136, 194, 115, .7)",
                        "rgba(139, 93, 255, .7)",
                        "rgba(255, 127, 62, .7)",
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: false,
                tooltip: {
                    displayColors: false,
                    callbacks: {
                        label(item) {
                            let orders = `${trans(
                                "admin::dashboard.sales_analytics.orders"
                            )}: ${data.totalOrders[item.dataIndex]}`;

                            let sales = `${trans(
                                "admin::dashboard.sales_analytics.sales"
                            )}: ${data.formatted[item.dataIndex]}`;

                            return [orders, sales];
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Include the currency symbol in the ticks
                        callback: function (value) {
                            return data.formatted[0].charAt(0) + value;
                        },
                    },
                },
            },
        },
    });
}
