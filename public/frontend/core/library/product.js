(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng
	var timer;


	HT.popupSwiperSlide = () => {
		document.querySelectorAll(".popup-gallery").forEach(popup => {
			var swiper = new Swiper(popup.querySelector(".swiper-container"), {
				loop: true,
				autoplay: {
					delay: 2000,
					disableOnInteraction: false,
				},
				pagination: {
					el: '.swiper-pagination',
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
				thumbs: {
					swiper: {
						el: popup.querySelector('.swiper-container-thumbs'),
						slidesPerView: 4,
						spaceBetween: 10,
						slideToClickedSlide: true,
					}
				}
			});
		});
	}

	HT.changeQuantity = () => {
		
		$(document).on('click','.quantity-button', function(){
			let _this = $(this)
			let quantity = $('.quantity-text').val()
			let newQuantity = 0
			if(_this.hasClass('minus')){
				 newQuantity =  quantity - 1
			}else{
				 newQuantity = parseInt(quantity) + 1
			}
			if(newQuantity < 1){
				newQuantity = 1
			}
			$('.quantity-text').val(newQuantity)
		})

	}

	HT.selectVariantProduct = () => {
		if($('.choose-attribute').length){
			$(document).on('click', '.choose-attribute', function(e){
				e.preventDefault()
				let _this = $(this)
				let attribute_id = _this.attr('data-attributeid')
				let attribute_name = _this.text()
				_this.parents('.attribute-item').find('span').html(attribute_name)
				_this.parents('.attribute-value').find('.choose-attribute').removeClass('active')
				_this.addClass('active')
				HT.handleAttribute();
			})
		}
	}

	HT.handleAttribute = () => {
		let attribute_id = []
		let flag = true
		$('.attribute-value .choose-attribute').each(function(){
			let _this = $(this)
			if(_this.hasClass('active')){
				attribute_id.push(_this.attr('data-attributeid'))
			}
		})

		$('.attribute').each(function(){
			if($(this).find('.choose-attribute.active').length === 0){
				flag = false
				return false;
			}
		})


		if(flag){
			$.ajax({
				url: 'ajax/product/loadVariant', 
				type: 'GET', 
				data: {
					'attribute_id' : attribute_id,
					'product_id' : $('input[name=product_id]').val(),
					'language_id' : $('input[name=language_id]').val(),
				}, 
				dataType: 'json', 
				beforeSend: function() {
					
				},
				success: function(res) {
					HT.setUpVariantPrice(res)
					HT.setupVariantGallery(res)
					HT.setupVariantName(res)
					HT.setupVariantUrl(res, attribute_id)
				},
			});
		}
	}

	HT.setupVariantUrl = (res, attribute_id) => {
		let queryString = '?attribute_id=' + attribute_id.join(',')
		let productCanonical = $('.productCanonical').val()
		productCanonical = productCanonical + queryString
		let stateObject = { attribute_id: attribute_id };
		history.pushState(stateObject, "Page Title", productCanonical);
	}

	HT.setUpVariantPrice = (res) => {
		$('.popup-product .price').html(res.variantPrice.html)
	}

	HT.setupVariantName = (res) => {
		let productName = $('.productName').val()
		let productVariantName = productName + ' ' + res.variant.languages[0].pivot.name
		$('.product-main-title span').html(productVariantName)
	}

	HT.setupVariantGallery = (gallery) => {
		let album = gallery.variant.album.split(',')

		let html = `<div class="swiper-container">
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-wrapper big-pic">`
			album.forEach((val) => {
				html += ` <div class="swiper-slide" data-swiper-autoplay="2000">
					<a href="${val}" class="image img-cover"><img src="${val}" alt="${val}"></a>
				</div>`
			})

			html += `</div>
			<div class="swiper-pagination"></div>
		</div>
		<div class="swiper-container-thumbs">
			<div class="swiper-wrapper pic-list">`;
		
		album.forEach((val) => {
			html += ` <div class="swiper-slide">
				<span class="image img-cover"><img src="${val}" alt="${val}"></span>
			</div>`
		})

		html += `</div>
		</div>`

		$('.popup-gallery').html(html)
		HT.popupSwiperSlide()
			
	}

	HT.loadProductVariant = () => {
		let attributeCatalogue = JSON.parse($('.attributeCatalogue').val())
		if(typeof attributeCatalogue != 'undefined' && attributeCatalogue.length){
			HT.handleAttribute()
		}
	}


	HT.chooseReviewStar = () => {
		$(document).on('click', '.popup-rating label', function(){
			let _this = $(this)
			let title = _this.attr('title')
			$('.rate-text').removeClass('uk-hidden').html(title)
		})
	}

	$(document).ready(function(){
		/* CORE JS */
		HT.changeQuantity()
		HT.popupSwiperSlide()
		HT.selectVariantProduct()
		HT.loadProductVariant()
		HT.chooseReviewStar()
	});

})(jQuery);

