
var userList = new List('assignments',  {
	valueNames: [ 'name', 'ku', 'ti', 'comm', 'app', 'final' ]
});
var achievementLine = document.getElementById("achievementLine");
var weightDonut = document.getElementById("weightDonut");

var achievementData = {
	labels: ["January", "February", "March", "April", "May", "June", "July"],
	datasets: [
	{
		label: "K/U",
		fill: false,
		lineTension: 0.3,
		borderWidth: 3,
		backgroundColor: "#f1c40f",
		borderColor: "#f1c40f",
		borderCapStyle: 'round',
		borderJoinStyle: 'round',
		pointBorderColor: "#fff",
		pointBackgroundColor: "#f1c40f",
		pointBorderWidth: 2,
		pointHoverRadius: 5,
		pointHoverBackgroundColor: "#f1c40f",
		pointHoverBorderColor: "#fff",
		pointHoverBorderWidth: 2,
		pointRadius: 5,
		pointHitRadius: 10,
		data: [null, 65, null, 30, 20],
	},
	{
		label: "T/I",
		fill: false,
		lineTension: 0.3,
		borderWidth: 3,
		backgroundColor: "#2ecc71",
		borderColor: "#2ecc71",
		borderCapStyle: 'round',
		borderJoinStyle: 'round',
		pointBorderColor: "#fff",
		pointBackgroundColor: "#2ecc71",
		pointBorderWidth: 2,
		pointHoverRadius: 5,
		pointHoverBackgroundColor: "#2ecc71",
		pointHoverBorderColor: "#fff",
		pointHoverBorderWidth: 2,
		pointRadius: 5,
		pointHitRadius: 10,
		data: [20, 60, 3, 100, 25],
	},	{
		label: "Comm",
		fill: false,
		lineTension: 0.3,
		borderWidth: 3,
		backgroundColor: "#9b59b6",
		borderColor: "#9b59b6",
		borderCapStyle: 'round',
		borderJoinStyle: 'round',
		pointBorderColor: "#fff",
		pointBackgroundColor: "#9b59b6",
		pointBorderWidth: 2,
		pointHoverRadius: 5,
		pointHoverBackgroundColor: "#9b59b6",
		pointHoverBorderColor: "#fff",
		pointHoverBorderWidth: 2,
		pointRadius: 5,
		pointHitRadius: 10,
		data: [15, 30, 75, 50, 30],
	},{
		label: "App",
		fill: false,
		lineTension: 0.3,
		borderWidth: 3,
		backgroundColor: "#e67e22",
		borderColor: "#e67e22",
		borderCapStyle: 'round',
		borderJoinStyle: 'round',
		pointBorderColor: "#fff",
		pointBackgroundColor: "#e67e22",
		pointBorderWidth: 2,
		pointHoverRadius: 5,
		pointHoverBackgroundColor: "#e67e22",
		pointHoverBorderColor: "#fff",
		pointHoverBorderWidth: 2,
		pointRadius: 5,
		pointHitRadius: 10,
		data: [80, 100, 15, 20, 35],
	}
	]
};
var achievementChart = new Chart(achievementLine, {
	type: 'line',
	data: achievementData,
	options: {
		responsive: true,
		maintainAspectRatio: true,
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero:true,
					max: 100
				}
			}],
			xAxes: [{
				ticks:{
					beginAtZero: true
				}
			}]
		}
	}
});
var weightChart = new Chart(weightDonut,{
	type:"doughnut",
	data: weightData,
	animation:{
		animateScale:true
	},
	options:{
		responsive:true
	}
});
