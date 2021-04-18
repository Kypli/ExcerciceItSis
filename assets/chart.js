var graphsDatas = $('#graphs').data('graphs')
var label = []
var price = []
var quantite = []

graphsDatas.forEach(function (value){
	label.push(value['type'])
	quantite.push(value['quantite'])
	price.push(value['price'])

})

// Graph Quantité
var ctx = document.getElementById('graphQtite').getContext('2d');
var myChart = new Chart(ctx, {
	type: 'doughnut',
	data: {
		labels: label,
		datasets: [{
			label: 'Type de commande par quantité',
			data: quantite,
			backgroundColor: [
				'rgb(133, 32, 10)',
				'rgb(152, 163, 152)',
				'rgb(255, 205, 86)',
				'rgb(16, 162, 12)',
				'rgb(54, 162, 235)',
				'rgb(255, 99, 132)'
			],
			hoverOffset: 4
		}]
	},
	options: {
		scales: {
			y: {
				beginAtZero: true
			}
		}
	}
})

// Graph Prix
var ctx = document.getElementById('graphPrice').getContext('2d');
var myChart = new Chart(ctx, {
	type: 'doughnut',
	data: {
		labels: label,
		datasets: [{
			label: 'Type de commande par quantité',
			data: price,
			backgroundColor: [
				'rgb(133, 32, 10)',
				'rgb(152, 163, 152)',
				'rgb(255, 205, 86)',
				'rgb(16, 162, 12)',
				'rgb(54, 162, 235)',
				'rgb(255, 99, 132)'
			],
			hoverOffset: 4
		}]
	},
	options: {
		scales: {
			y: {
				beginAtZero: true
			}
		}
	}
})