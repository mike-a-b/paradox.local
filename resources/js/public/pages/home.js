import { createChart } from 'lightweight-charts';
import { parseDate } from '../lib/date';


let chartDatePeriod = '24h';
let chart = null;
let areaSeries = null;

export function getBaseHeaders() {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'credentials': 'same-origin',
        'mode': 'no-cors',
    };
}

const fetchUserBalanceData = datePeriod => {
    const url = `/api/v1/user-balance-history?period=${datePeriod}`;
    return new Promise((resolve, reject) => {
        fetch(url, {
            headers: getBaseHeaders(),
        })
        .then(async (itemsJson) => {
            const itemsData = await itemsJson.json();
            const items = itemsData.data;
            //console.log(items);
            resolve(items);
        })
        .catch(e => {
            reject(e);
        });
    });
};

const fetchUserBalanceStatistics = datePeriod => {
    const url = `/api/v1/user-balance-history/statistics?period=${datePeriod}`;
    return new Promise((resolve, reject) => {
        fetch(url, {
            headers: getBaseHeaders(),
        })
        .then(async (itemsJson) => {
            const itemsData = await itemsJson.json();
            const items = itemsData.data;
            //console.log(items);
            resolve(items);
        })
        .catch(e => {
            reject(e);
        });
    });
};

export const drawPortfolioBalanceStat = async (container, datePeriod) => {
    const data = await fetchUserBalanceStatistics(datePeriod);
    //console.log(data);
    if (container) {
        const diffPers = data.user_balance_diff_pers;
        const diffPersPrint = (diffPers > 0 ? '&plus;' : (diffPers < 0 ? '&ndash;' : '')) + Math.abs(diffPers);
        container.innerHTML = `${diffPersPrint}% (${ data.user_balance_diff_usd })`;
        container.className = diffPers < 0 ? 'fraction_color_minus' : (diffPers > 0 ? 'fraction_color_plus' : '');
    }
}

export const drawChart = async (chartContainer, datePeriod) => {
    const data = await fetchUserBalanceData(datePeriod);
    //console.log(data);

    if (!chart) {
        chart = createChart(chartContainer, {
            //width: 547,
            height: 180,
            rightPriceScale: {
                scaleMargins: {
                    top: 0.1,
                    bottom: 0.1,
                },
                borderVisible: false,
            },
            // timeScale: {
            //     borderVisible: false,
            // },

            timeScale: {
                borderVisible: false,
                timeVisible: true,
                fixLeftEdge: true,
                fixRightEdge: true,
                //secondsVisible: false,
                //borderColor: "rgba(197, 203, 206, 0.8)",
            },

            grid: {
                horzLines: {
                    color: '#eee',
                visible: false,
                },
                vertLines: {
                    color: '#ffffff',
                },
            },
        //   crosshair: {
        //           horzLine: {
        //           visible: false,
        //         labelVisible: false
        //       },
        //       vertLine: {
        //           visible: true,
        //         style: 0,
        //         width: 2,
        //         color: 'rgba(32, 38, 46, 0.1)',
        //         labelVisible: false,
        //       }
        //   },
        });
    }

    if (areaSeries) {
		chart.removeSeries(areaSeries);
		areaSeries = null;
	}

    areaSeries = chart.addAreaSeries({
        // topColor: 'rgba(76, 175, 12334380, 0.56)',
        // bottomColor: 'rgba(76, 175, 80, 0.04)',
        // lineColor: 'rgba(76, 175, 80, 1)',
        // topColor: 'rgba(250, 67, 76, 0.56)',
        // bottomColor: 'rgba(250, 67, 76, 0.04)',
        // lineColor: 'rgba(250, 67, 76, 1)',
        topColor: 'rgba(0, 120, 255, 0.2)',
        bottomColor: 'rgba(0, 120, 255, 0.0)',
        lineColor: 'rgba(0, 120, 255, 1)',
        lineWidth: 3,
        //title: 'Prise USD:',
    });

    let areaSeriesData = data.map(item => ({
        // time: Date.parse(item.created_at)/1000,
        time: parseDate(item.created_at)/1000,
        // value: item.balance_usd //- item.deposits_usd
        value: item.total_balance_usd //- item.deposits_usd
    }));

    //console.log(areaSeriesData);

    areaSeries.setData(areaSeriesData);

    chart.timeScale().fitContent();
}

export const initPage = async () => {
    const chartContainer = document.getElementById('homeUserBalanceChart');
    const portfolioStatContainer = document.getElementById('portfolio_user_balance_stat');
    if (chartContainer) {
        const buttons = document.querySelectorAll("#chartUserBalance .date");
        buttons.forEach(butt => {
            butt.addEventListener('click', event => {
                const elem = event.target;
                chartDatePeriod = elem.textContent.toLowerCase();
                drawChart(chartContainer, chartDatePeriod);
                drawPortfolioBalanceStat(portfolioStatContainer, chartDatePeriod);
                // console.log(chartDatePeriod);
                buttons.forEach(bin => {
                    bin.classList.remove('selected');
                });
                elem.classList.add('selected');
            });
        });

        await drawChart(chartContainer, chartDatePeriod);
        await drawPortfolioBalanceStat(portfolioStatContainer, chartDatePeriod);
    }
};
