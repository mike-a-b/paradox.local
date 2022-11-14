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
};

export const fetchUserBalanceData = (datePeriod) => {    
    const url = `/api/v1/user-balance-history?period=${datePeriod}&user_id=24`;
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

export const drawChart = async (chartContainer, datePeriod) => {  
    const data = await fetchUserBalanceData(datePeriod);
    //console.log(data);
    // var data = [
    //     { time: "2018-03-28", value: 21.00 },
    // { time: "2018-03-29", value: 20.80 },
    // { time: "2018-03-30", value: 19.40 },
    // { time: "2018-04-02", value: 18.75 },
    // { time: "2018-04-03", value: 18.75 },
    // { time: "2018-04-04", value: 18.95 },
    // { time: "2018-04-05", value: 16.95 },
    // { time: "2018-04-06", value: 17.70 },
    // { time: "2018-04-09", value: 31.00 },
    // { time: "2018-04-10", value: 30.20 },
    // { time: "2018-04-11", value: 31.50 },
    // { time: "2018-04-12", value: 27.95 },
    // { time: "2018-04-13", value: 30.15 },
    // { time: "2018-04-16", value: 29.60 },
    // { time: "2018-04-17", value: 27.70 },
    // { time: "2018-04-18", value: 21.45 },
    // { time: "2018-04-19", value: 24.05 },
    // { time: "2018-04-20", value: 25.60 },
    // { time: "2018-04-23", value: 26.50 },
    // { time: "2018-04-24", value: 28.40 },
    // { time: "2018-04-25", value: 30.55 },
    // { time: "2018-04-26", value: 29.40 },
    // { time: "2018-04-27", value: 30.70 },
    // { time: "2018-04-30", value: 31.00 },
    // { time: "2018-05-02", value: 27.70 },
    // { time: "2018-05-03", value: 30.80 },
    // { time: "2018-05-04", value: 33.35 },
    // { time: "2018-05-07", value: 33.10 },
    // { time: "2018-05-08", value: 34.60 },
    // { time: "2018-05-10", value: 35.20 },
    // { time: "2018-05-11", value: 37.50 },
    // { time: "2018-05-14", value: 38.85 },
    // { time: "2018-05-15", value: 37.00 },
    // { time: "2018-05-16", value: 37.05 },
    // { time: "2018-05-17", value: 37.05 },
    // { time: "2018-05-18", value: 38.25 },
    // { time: "2018-05-21", value: 38.80 },
    // { time: "2018-05-22", value: 40.00 },
    // { time: "2018-05-23", value: 42.45 },
    // { time: "2018-05-24", value: 42.30 },
    // { time: "2018-05-25", value: 42.80 },
    // { time: "2018-05-28", value: 43.45 },
    // { time: "2018-05-29", value: 43.15 },
    // { time: "2018-05-30", value: 35.15 },
    // { time: "2018-05-31", value: 34.20 },
    // { time: "2018-06-01", value: 35.35 },
    // { time: "2018-06-04", value: 37.90 },
    // { time: "2018-06-05", value: 35.75 },
    // { time: "2018-06-06", value: 33.70 },
    // { time: "2018-06-07", value: 30.00 },
    // { time: "2018-06-08", value: 31.10 },
    // { time: "2018-06-11", value: 32.30 },
    // { time: "2018-06-13", value: 30.95 },
    // { time: "2018-06-14", value: 31.45 },
    // { time: "2018-06-15", value: 34.50 },
    // { time: "2018-06-18", value: 35.35 },
    // { time: "2018-06-19", value: 37.00 },
    // { time: "2018-06-20", value: 34.00 },
    // { time: "2018-06-21", value: 34.45 },
    // { time: "2018-06-22", value: 34.45 },
    // { time: "2018-06-25", value: 34.25 },
    // { time: "2018-06-26", value: 34.35 },
    // { time: "2018-06-27", value: 33.85 },
    // { time: "2018-06-28", value: 35.20 },
    // { time: "2018-06-29", value: 35.20 },
    // { time: "2018-07-02", value: 34.85 },
    // { time: "2018-07-03", value: 31.95 },
    // { time: "2018-07-04", value: 35.00 },
    // { time: "2018-07-05", value: 45.80 },
    // { time: "2018-07-06", value: 45.45 },
    // { time: "2018-07-09", value: 46.70 },
    // { time: "2018-07-10", value: 48.45 },
    // { time: "2018-07-11", value: 50.70 },
    // { time: "2018-07-12", value: 50.20 },
    // { time: "2018-07-13", value: 51.75 },
    // { time: "2018-07-16", value: 52.00 },
    // { time: "2018-07-17", value: 50.75 },
    // { time: "2018-07-18", value: 52.00 },
    // { time: "2018-07-19", value: 54.00 },
    // { time: "2018-07-20", value: 53.55 },
    // { time: "2018-07-23", value: 51.20 },
    // { time: "2018-07-24", value: 52.85 },
    // { time: "2018-07-25", value: 53.70 },
    // { time: "2018-07-26", value: 52.30 },
    // { time: "2018-07-27", value: 52.80 },
    // { time: "2018-07-30", value: 53.30 },
    // { time: "2018-07-31", value: 52.05 },
    // { time: "2018-08-01", value: 54.00 },
    // { time: "2018-08-02", value: 59.00 },
    // { time: "2018-08-03", value: 56.90 },
    // { time: "2018-08-06", value: 60.70 },
    // { time: "2018-08-07", value: 60.75 },
    // { time: "2018-08-08", value: 63.15 },
    // { time: "2018-08-09", value: 65.30 },
    // { time: "2018-08-10", value: 70.00 },
    // { time: "2018-08-13", value: 69.25 },
    // { time: "2018-08-14", value: 67.75 },
    // { time: "2018-08-15", value: 67.60 },
    // { time: "2018-08-16", value: 64.50 },
    // { time: "2018-08-17", value: 66.00 },
    // { time: "2018-08-20", value: 66.05 },
    // { time: "2018-08-21", value: 66.65 },
    // { time: "2018-08-22", value: 66.40 },
    // { time: "2018-08-23", value: 69.35 },
    // { time: "2018-08-24", value: 70.55 },
    // { time: "2018-08-27", value: 68.80 },
    // { time: "2018-08-28", value: 68.45 },
    // { time: "2018-08-29", value: 63.20 },
    // { time: "2018-08-30", value: 59.50 },
    // { time: "2018-08-31", value: 59.50 },
    // { time: "2018-09-03", value: 60.45 },
    // { time: "2018-09-04", value: 62.25 },
    // { time: "2018-09-05", value: 63.50 },
    // { time: "2018-09-06", value: 66.90 },
    // { time: "2018-09-07", value: 66.45 },
    // { time: "2018-09-10", value: 68.50 },
    // { time: "2018-09-11", value: 69.90 },
    // { time: "2018-09-12", value: 67.80 },
    // { time: "2018-09-13", value: 67.90 },
    // { time: "2018-09-14", value: 69.25 },
    // { time: "2018-09-17", value: 68.95 },
    // { time: "2018-09-18", value: 65.85 },
    // { time: "2018-09-19", value: 63.60 },
    // { time: "2018-09-20", value: 64.00 },
    // { time: "2018-09-21", value: 64.00 },
    // { time: "2018-09-24", value: 66.05 },
    // { time: "2018-09-25", value: 68.35 },
    // { time: "2018-09-26", value: 68.30 },
    // { time: "2018-09-27", value: 67.95 },
    // { time: "2018-09-28", value: 68.45 },
    // { time: "2018-10-01", value: 68.80 },
    // { time: "2018-10-02", value: 68.60 },
    // { time: "2018-10-03", value: 67.90 },
    // { time: "2018-10-04", value: 68.60 },
    // { time: "2018-10-05", value: 70.35 },
    // { time: "2018-10-08", value: 72.35 },
    // { time: "2018-10-09", value: 72.90 },
    // { time: "2018-10-10", value: 72.85 },
    // { time: "2018-10-11", value: 74.10 },
    // { time: "2018-10-12", value: 73.00 },
    // { time: "2018-10-15", value: 74.85 },
    // { time: "2018-10-16", value: 76.00 },
    // { time: "2018-10-17", value: 77.00 },
    // { time: "2018-10-18", value: 79.00 },
    // { time: "2018-10-19", value: 79.50 },
    // { time: "2018-10-22", value: 82.60 },
    // { time: "2018-10-23", value: 82.70 },
    // { time: "2018-10-24", value: 82.10 },
    // { time: "2018-10-25", value: 83.15 },
    // { time: "2018-10-26", value: 83.40 },
    // { time: "2018-10-29", value: 80.95 },
    // { time: "2018-10-30", value: 76.75 },
    // { time: "2018-10-31", value: 77.75 },
    // { time: "2018-11-01", value: 78.12 },
    // { time: "2018-11-02", value: 73.22 },
    // { time: "2018-11-06", value: 72.60 },
    // { time: "2018-11-07", value: 74.40 },
    // { time: "2018-11-08", value: 76.50 },
    // { time: "2018-11-09", value: 79.86 },
    // { time: "2018-11-12", value: 78.10 },
    // { time: "2018-11-13", value: 77.60 },
    // { time: "2018-11-14", value: 71.70 },
    // { time: "2018-11-15", value: 72.26 },
    // { time: "2018-11-16", value: 73.80 },
    // { time: "2018-11-19", value: 76.28 },
    // { time: "2018-11-20", value: 72.80 },
    // { time: "2018-11-21", value: 66.20 },
    // { time: "2018-11-22", value: 65.10 },
    // { time: "2018-11-23", value: 61.26 },
    // { time: "2018-11-26", value: 64.10 },
    // { time: "2018-11-27", value: 61.72 },
    // { time: "2018-11-28", value: 61.40 },
    // { time: "2018-11-29", value: 61.86 },
    // { time: "2018-11-30", value: 60.60 },
    // { time: "2018-12-03", value: 63.16 },
    // { time: "2018-12-04", value: 68.30 },
    // { time: "2018-12-05", value: 67.20 },
    // { time: "2018-12-06", value: 68.56 },
    // { time: "2018-12-07", value: 71.30 },
    // { time: "2018-12-10", value: 73.98 },
    // { time: "2018-12-11", value: 72.28 },
    // { time: "2018-12-12", value: 73.20 },
    // { time: "2018-12-13", value: 73.00 },
    // { time: "2018-12-14", value: 72.90 },
    // { time: "2018-12-17", value: 73.96 },
    // { time: "2018-12-18", value: 73.40 },
    // { time: "2018-12-19", value: 73.00 },
    // { time: "2018-12-20", value: 72.98 },
    // { time: "2018-12-21", value: 72.80 },
    // { time: "2018-12-24", value: 72.90 },
    // { time: "2018-12-25", value: 74.20 },
    // { time: "2018-12-26", value: 73.98 },
    // { time: "2018-12-27", value: 74.50 },
    // { time: "2018-12-28", value: 74.00 },
    // { time: "2019-01-03", value: 73.50 },
    // { time: "2019-01-04", value: 73.90 }
    // ];


    if (!chart) {
        chart = createChart(chartContainer, {
            width: 547,
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
                //timeVisible: true,
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
        // topColor: 'rgba(76, 175, 80, 0.56)',
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
        //time: Date.parse(item.created_at)/1000,
        time: parseDate(item.created_at)/1000,
        value: item.balance_usd //- item.deposits_usd
    }));

    (function () {
        var old = console.log;
        var logger = document.getElementById('testLog');
        console.log = function () {
          for (var i = 0; i < arguments.length; i++) {
            if (typeof arguments[i] == 'object') {
                logger.innerHTML += (JSON && JSON.stringify ? JSON.stringify(arguments[i], undefined, 2) : arguments[i]) + '<br />';
            } else {
                logger.innerHTML += arguments[i] + '<br />';
            }
          }
        }
    })();

    console.log(data); 
    console.log(areaSeriesData); 

    //const areaSeriesData = data;
    
    
    areaSeries.setData(areaSeriesData);    
    
    chart.timeScale().fitContent();      
}

export const initPage = async () => {        
    const chartContainer = document.getElementById('testChart');
    if (chartContainer) {

        const buttons = document.querySelectorAll("#chartUserBalance .date");
        buttons.forEach(butt => {
            butt.addEventListener('click', event => {
                const elem = event.target;
                chartDatePeriod = elem.textContent.toLowerCase();
                drawChart(chartContainer, chartDatePeriod);
                // console.log(chartDatePeriod); 
                buttons.forEach(bin => {                    
                    bin.classList.remove('selected');
                });               
                elem.classList.add('selected');             
            });        
        });

        drawChart(chartContainer, chartDatePeriod);
    }    
};