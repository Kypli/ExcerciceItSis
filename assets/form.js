/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/form.css';

// start the Stimulus application
import './bootstrap';

// On Ready
jQuery(document).ready(function() {

/*********
* OnLoad
**********/

	// Variables
	var $collectionHolder = $('ul.lignes')

	// Datas
	$collectionHolder.find('li').each(function() {
		addDeleteLink($(this))
	})
	$collectionHolder.data('index', $collectionHolder.find('input').length)


/*********
* Events
**********/
	
	// Ajouter une ligne
	$('body').on('click', '.add_item_link', function(e) {
		var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
		addFormToCollection($collectionHolderClass);
	})

	// Select Type
	$('body').on('change', '.selectType', function(e) {
		var type = $(this)
		var produit = $(this).parent('div').next('div').children('select')
		var quantite = $(this).parent('div').next('div').next('div').children('input')
		var comment = $(this).parent('div').next('div').next('div').next('div').children('input')
		changeType(type, produit, quantite, comment)
	})
	$('.selectProduit').each(function (key, produit){
		if ($(produit).val()){
			getTypeFromProduit(produit, $(produit).val())
		} else {
			resetOptionsProduit(produit)
		}
	})
})


/*********
* Functions
**********/

	function changeType(type, produit, quantite, comment, idProduit = null){

		resetOptionsProduit(produit)

		if ($(type).val()){
			getProduitsOptions(produit, $(type).val(), idProduit)
			$(produit).prop("disabled", false)
		} else {
			$(produit).prop("disabled", true)
			$(produit).val("")
		}
	}

	function resetOptionsProduit(produit){
		$(produit).children('option:not(:first)').remove()
	}


	/***
	* Ajax
	***/
	function getTypeFromProduit(produit, idProduit){

		var type = ''

		$.ajax({
			type: "GET",
			url: "/ajax/typeByProduct",
			data: {
				idProduit: idProduit
			},
			timeout: 15000,
			success: function(idType){
				var type = $(produit).parent('div').prev('div').children('select')
				$(type).val(idType)
				getProduitsOptions(produit, idType, $(produit).val())

			 },
			error: function(message){
				console.log(message)
			}
		})
	}

	function getProduitsOptions(produit, idType, idProduit = null){

		$.ajax({
			type: "GET",
			url: "/ajax/commande/produits",
			data: {
				idtype: idType
			},
			timeout: 15000,
			success: function(message){

				resetOptionsProduit(produit)
				for (let [key, value] of Object.entries(message)){
					$(produit).append(`<option value="${key}">${value}</option>`)
				}

				if (idProduit != null){
					$(produit).val(idProduit)
				}
			 },
			error: function(message){
				console.log(message)
			}
		})
	}

	/***
	* Add elements
	***/
	function addFormToCollection($collectionHolderClass) {
		// Get the ul that holds the collection of tags
		var $collectionHolder = $('.' + $collectionHolderClass);

		// Get the data-prototype explained earlier
		var prototype = $collectionHolder.data('prototype');

		// get the new index
		var index = $collectionHolder.data('index');

		var newForm = prototype;
		// You need this only if you didn't set 'label' => false in your tags field in TaskType
		// Replace '__name__label__' in the prototype's HTML to
		// instead be a number based on how many items we have
		// newForm = newForm.replace(/__name__label__/g, index);

		// Replace '__name__' in the prototype's HTML to
		// instead be a number based on how many items we have
		newForm = newForm.replace(/__name__/g, index);

		// increase the index with one for the next item
		$collectionHolder.data('index', index + 1);

		// Display the form in the page in an li, before the "Add a tag" link li
		var $newFormLi = $('<li></li>').append(newForm);
		// Add the new form at the end of the list
		$collectionHolder.append($newFormLi)

		// add a delete link to the new form
		addDeleteLink($newFormLi);
	}

	function addDeleteLink($tagFormLi) {
		var $removeFormButton = $('<button type="button" class="boutonDelete">Supprimer cette ligne</button>');
		$tagFormLi.append($removeFormButton);

		$removeFormButton.on('click', function(e) {
			// remove the li for the tag form
			$tagFormLi.remove();
		});
	}
