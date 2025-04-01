google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

	var chartData_json = document.getElementById('chartinfo').value;

	let obj = JSON.parse(chartData_json) ; 
	let jsonData = obj;
	var chartData = [];
	
	// Add Chart data
   	var chartData = [
	  ['fecha','quantity',{ role: 'annotation'}],
	];

    for (var key in obj) {
	  	if (obj.hasOwnProperty(key)) {
		    var val = obj[key];

		    var city = val.fecha;
		    var totalusers = Number(val.quantity);

		    // Add to Array
		    chartData.push([city,totalusers,totalusers]);
		    
	  	}
	} 

	var data = google.visualization.arrayToDataTable(chartData);

	// Options 
	var options = {
		
		height: 240,
		title:'Number of jobs in the month',
		colors: ['#ec8f6e']
		
		//colors: ['#d03d1d']
	};

	
	var chart = new google.visualization.ColumnChart(document.getElementById('trabajos'));
	chart.draw(data, options);
// Initialize 
	/*
	  chartData_json = document.getElementById('charttotalventa').value;

	  obj = JSON.parse(chartData_json) ; 
	  jsonData = obj;
	  chartData = [];
	
	// Add Chart data
   	  chartData = [
	  ['venta_fecha','Monto',{ role: 'annotation'}],
	];

    for (var key in obj) {
	  	if (obj.hasOwnProperty(key)) {
		    var val = obj[key];

		    var city = val.venta_fecha;
		    var totalusers = Number(val.total);

		    // Add to Array
		    chartData.push([city,totalusers,totalusers]);
		    
	  	}
	} 

	  data = google.visualization.arrayToDataTable(chartData);

	// Options 
	  options = {
		title:'Total por dia en el mes'
	};


	var chart1 = new google.visualization.BarChart(document.getElementById('ventaschart'));
	chart1.draw(data, options);
	*/
}