/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// OnLoad

	// Variables
	let lignes = []
	let currentLigne = null

	// Functions
	resetForm()
	addLignesFromEdit()

// Events
	
	// Buttons
	$('#btnAdd').click(function(){
		currentLigne === null
			? addLigne()
			: editLigne(currentLigne)
	})
	$('#btnRaz').click(function(){
		resetForm()
		changeButtonLigne('Ajouter une ligne')
		colorAllLigne()
	})
	$("body").on("click", ".modifier", function(e){
		resetForm()
		changeButtonLigne('Modifier la ligne')
		let idLigne = $(this)[0]['attributes'][1]["nodeValue"]
		currentLigne = idLigne
		colorAllLigne()
		colorLigneSelected(idLigne)
		let ligne = lignes[idLigne]
		$('#commande_type option[value="' + ligne['idtype'] + '"]').prop('selected', true)
		changeType(ligne['idproduit'])
		$('#commande_quantite').val(ligne['quantite'])
		$('#commande_commentaire').val(ligne['commentaire'])
	})
	$("body").on("click", ".supprimer", function(e){
		delete lignes[$(this)[0]['attributes'][1]["nodeValue"]]
		updateLignes()
		resetForm()
		colorAllLigne()
		currentLigne = null
	})
	$('#btnValid').click(function(){

		let stringLignes = ''
		for (let [id, ligne] of Object.entries(lignes)){
			stringLignes = 
				stringLignes +
				'id: ' + id + ', ' +
				'idproduit: ' + ligne['idproduit'] + ', ' +
				'quantite: ' + ligne['quantite'] + ', ' +
				'commentaire: ' + ligne['commentaire'] + ', ' +
				'exist: ' + ligne['exist'] + '| '
		}
		$("#commande_test").val(stringLignes)
	})

	// Select
	$("#commande_type").change(function(){
		changeType()
		disabledAdd()
	})
	$("#commande_produit").change(function(){
		changeProduit()
		disabledAdd()
	})

	// DataFields
	$("#commande_quantite").keyup(function(){
		totalLigne()
		disabledAdd()
	})
	$("#commande_quantite").change(function(){
		totalLigne()
	})
	$("#commande_commentaire").keyup(function(){
		disabledAdd()
	})


// Functions

	/***
	* Change
	***/
	function changeType(idProduit = null){

		resetOptionsProduit()
		resetQuantiteAndCommentaire()
		resetPrice()

		if ($("#commande_type").val()){
			getProduitsOptions($("#commande_type option:selected").val(), idProduit)
			$("#commande_produit").prop("disabled", false)
		} else {
			$("#commande_produit").prop("disabled", true)
			$("#commande_produit").val("")
		}
	}

	function changeProduit(){
		if ($("#commande_produit").val()){
			$("#commande_quantite, #commande_commentaire").prop("disabled", false)
			totalLigne()
		} else {
			resetQuantiteAndCommentaire()
			resetPrice()
		}
	}


	/***
	* Reset
	***/
	function resetForm(){
		currentLigne = null
		$("#btnAdd, #btnEdit, #commande_produit, #commande_commentaire, #commande_quantite").prop("disabled", true)
		$("#commande_type, #commande_produit, #commande_commentaire").val("")
		$("#commande_quantite").val("1")
		resetPrice()
		resetOptionsProduit()
	}

	function resetQuantiteAndCommentaire(){
		$("#commande_quantite").prop("disabled", true)
		$("#commande_quantite").val("1")
		$("#commande_commentaire").prop("disabled", true)
		$("#commande_commentaire").val("")
	}

	function resetOptionsProduit(){
		$('#commande_produit').children('option:not(:first)').remove()
	}

	function resetPrice(){
		$("#totalLigne").text("0")
	}

	function resetLignes(){
		$("#listeLignes").empty()
	}


	/***
	* Disabled
	***/
	function disabledAdd(){
		if (
			$("#commande_type").val() &&
			$("#commande_produit").val() &&
			$("#commande_commentaire").val()
		){
			$("#btnAdd").prop("disabled", false)
		} else {
			$("#btnAdd").prop("disabled", true)
		}
	}


	/***
	* get datas
	***/
	function addLignesFromEdit(){

		let idCommande = $('#btnValid').val()
		if (idCommande !== ""){

			$.ajax({
				type: "GET",
				url: "/ajax/lignes/commande",
				data: {
					idcommande: idCommande
				},
				timeout: 15000,
				success: function(message){

					for (let [key, value] of Object.entries(message)){
						lignes[value['id']] = []
						lignes[value['id']]['type'] = value['type']
						lignes[value['id']]['idtype'] = value['idtype']
						lignes[value['id']]['produit'] = value['produit'] + ' (' + value['price'] + "€ l'unité)"
						lignes[value['id']]['idproduit'] = value['idproduit']
						lignes[value['id']]['quantite'] = value['quantite']
						lignes[value['id']]['price'] = value['price']
						lignes[value['id']]['totalPrice'] = value['totalPrice']
						lignes[value['id']]['commentaire'] = value['commentaire']
						lignes[value['id']]['exist'] = 'oui'
					}
					updateLignes()
				 },
				error: function(message){
					console.log(message)
				}
			})

		}
	}


	/***
	* Change datas interface
	***/
	function updateLignes(){
		resetLignes()

		for (let [key, value] of Object.entries(lignes)){

			$('#listeLignes').append(
				"<ul id='ligne" + key + "'>" + 
					"<li>" +
						"Ligne " + (parseInt(key) + 1) +
						"  -  <span class='modifier' value='" + key + "'>(modifier)</span>" +
						"  -  <span class='supprimer' value='" + key + "'>(supprimer)</span>" +
					"</li>" +
					"<ul>" + 
						"<li>Type : " + value['type'] + "</li>" +
						"<li>Produit : " + value['produit'] + "</li>" +
						"<li>Quantité : " + value['quantite'] + "</li>" +
						"<li>Prix total : " + value['totalPrice'] + " €</li>" +
						"<li>Commentaire : " + value['commentaire'] + "</li>" +
					"</ul>" + 
				"</ul>"
			)
		}
	}

	function getProduitsOptions(idType, idProduit = null){
		$.ajax({
			type: "GET",
			url: "/ajax/commande/produits",
			data: {
				idtype: idType
			},
			timeout: 15000,
			success: function(message){

				for (let [key, value] of Object.entries(message)){
					$('#commande_produit').append(`<option value="${key}">${value}</option>`)
				}


				if (idProduit != null){
					$('#commande_produit option[value="' + idProduit + '"]').prop('selected', true)
					changeProduit()
					disabledAdd()
				}
			 },
			error: function(message){
				console.log(message)
			}
		})
	}

	function getPrice(){
		let array = $("#commande_produit option:selected").text().split('(')

		return array[1].substring(0, 2).replace("€","")
	}

	function totalLigne(){
		let price = getPrice()
		$('#totalLigne').text(price * $("#commande_quantite").val())
	}

	function colorLigneSelected(idLigne){
		$('#ligne' + idLigne).css('color', 'red')
	}

	function colorAllLigne(){
		for (let [key, value] of Object.entries(lignes)){
			$('#ligne' + key).css('color', 'black')
		}
	}

	function changeButtonLigne(name){
		$('#btnAdd').text(name)
	}


	/***
	* Save
	***/
	function addLigne(){
		lignes.push({ 
			type: $("#commande_type option:selected").text(),
			idtype: $("#commande_type option:selected").val(),
			produit: $("#commande_produit option:selected").text(),
			idproduit: $("#commande_produit option:selected").val(),
			quantite: $('#commande_quantite').val(),
			price: getPrice(),
			totalPrice: $('#totalLigne').text(),
			commentaire: $('#commande_commentaire').val(),
			exist: 'non'
		})
		resetForm()
		updateLignes()
	}

	function editLigne(idLigne){

		lignes[idLigne]['type'] = $("#commande_type option:selected").text()
		lignes[idLigne]['idtype'] = $("#commande_type option:selected").val()
		lignes[idLigne]['produit'] = $("#commande_produit option:selected").text()
		lignes[idLigne]['idproduit'] = $("#commande_produit option:selected").val()
		lignes[idLigne]['quantite'] = $('#commande_quantite').val()
		lignes[idLigne]['price'] = getPrice()
		lignes[idLigne]['totalPrice'] = $('#totalLigne').text()
		lignes[idLigne]['commentaire'] = $('#commande_commentaire').val()

		resetForm()		
		changeButtonLigne('Ajouter une ligne')
		updateLignes()
		currentLigne = null
	}