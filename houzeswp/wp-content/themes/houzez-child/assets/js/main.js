/***************************************************
==================== JS INDEX ======================
****************************************************
Mobile Menu Js


****************************************************/

(function ($) {
	"use strict";

	// nice select
	$(".ms-nice-select:not(.ms-nice-select__country-code)").niceSelect();
	
	// apartment
	function useBlogSlider() {
		const blogSlider = new Swiper(".ms-blog-slider", {
			slidesPerView: 1.5,
			spaceBetween: 20,
			centeredSlides: true,
			loop: true,
			autoplay: {
				delay: 5000,
			},
			breakpoints: {
				1024: {
					slidesPerView: 4,
					spaceBetween: 30,
				},
			},
		});
	}
	useBlogSlider();


	// sticky header
	// Sticky Js
	$(window).on("scroll", function () {
		var scroll = $(this).scrollTop();

		if (scroll < 200) {
			$(".ms-header--sticky").removeClass("sticky");
		} else {
			$(".ms-header--sticky").addClass("sticky");
		}
	});
	// mobile menu     // mobileMenu togglar
	const mobileMenu = document.querySelector(".ms-mobile-menu");
	if (mobileMenu) {
		const mobileMenuTogglers = document.querySelectorAll(
			".ms-mobile-menu__toggler"
		);
		if (mobileMenuTogglers?.length) {
			mobileMenuTogglers?.forEach((mobileMenuToggler, idx) => {
				mobileMenuToggler.addEventListener("click", function () {
					mobileMenu.classList.toggle("open");
				});
			});
		}
	}
	/* ---------------------------------------------------------
            32. Price Slider
        --------------------------------------------------------- */



	function formWizardController() {
		// click on next button
		jQuery(`.form-wizard-next-btn`).click(function () {
			var parentFieldset = jQuery(this).parents(".wizard-fieldset");
			var currentActiveStep = jQuery(this)
				.parents(`.form-wizard`)
				.find(`.form-wizard-steps .active`);
			var next = jQuery(this);
			var nextWizardStep = true;
			parentFieldset.find(".wizard-required").each(function () {
				var thisValue = jQuery(this).val();

				if (thisValue == "") {
					jQuery(this).siblings(".wizard-form-error").slideDown();
					nextWizardStep = false;
				} else {
					jQuery(this).siblings(".wizard-form-error").slideUp();
				}
			});
			if (nextWizardStep) {
				next.parents(".wizard-fieldset").removeClass("show", "400");
				currentActiveStep
					.removeClass("active")
					.addClass("activated")
					.next()
					.addClass("active", "400");
				next
					.parents(".wizard-fieldset")
					.next(".wizard-fieldset")
					.addClass("show", "400");
				jQuery(document)
					.find(".wizard-fieldset")
					.each(function () {
						if (jQuery(this).hasClass("show")) {
							var formAtrr = jQuery(this).attr("data-tab-content");
							jQuery(document)
								.find(`.form-wizard-steps .form-wizard-step-item`)
								.each(function () {
									if (jQuery(this).attr("data-attr") == formAtrr) {
										jQuery(this).addClass("active");
										var innerWidth = jQuery(this).innerWidth();
										var position = jQuery(this).position();
										jQuery(document)
											.find(`.form-wizard-step-move`)
											.css({ left: position.left, width: innerWidth });
									} else {
										jQuery(this).removeClass("active");
									}
								});
						}
					});
			}
		});
		//click on previous button
		jQuery(`.form-wizard-previous-btn`).click(function () {
			var counter = parseInt(jQuery(".wizard-counter").text());
			var prev = jQuery(this);
			var currentActiveStep = jQuery(this)
				.parents(`.form-wizard`)
				.find(`.form-wizard-steps .active`);
			prev.parents(".wizard-fieldset").removeClass("show", "400");
			prev
				.parents(".wizard-fieldset")
				.prev(".wizard-fieldset")
				.addClass("show", "400");
			currentActiveStep
				.removeClass("active")
				.prev()
				.removeClass("activated")
				.addClass("active", "400");
			jQuery(document)
				.find(".wizard-fieldset")
				.each(function () {
					if (jQuery(this).hasClass("show")) {
						var formAtrr = jQuery(this).attr("data-tab-content");
						jQuery(document)
							.find(`.form-wizard-steps .form-wizard-step-item`)
							.each(function () {
								if (jQuery(this).attr("data-attr") == formAtrr) {
									jQuery(this).addClass("active");
									var innerWidth = jQuery(this).innerWidth();
									var position = jQuery(this).position();
									jQuery(document)
										.find(`.form-wizard-step-move`)
										.css({ left: position.left, width: innerWidth });
								} else {
									jQuery(this).removeClass("active");
								}
							});
					}
				});
		});
	}
	formWizardController();

		/*----------------------
            hero slider
        -----------------------*/

		// Check if the document is in RTL mode
		const isRTL = document.documentElement.getAttribute('dir') === 'rtl';

		const heroSlider = $(".ms-hero__slider");
		if (heroSlider?.length) {
			heroSlider.slick({
				dots: false /* slider left or right side pagination count with line */,
				arrows: false /* slider arrow  */,
				appendDots: ".ms-hero__slider__pagination-count-pagination-count",
				infinite: true,
				autoplay: false,
				autoplaySpeed: 10000,
				speed: 500,
				asNavFor: ".ms-hero__slider__thumbs",
				slidesToShow: 1,
				slidesToScroll: 1,
				rtl: isRTL,
	
				prevArrow:
					'<a class="slick-prev"><i class="fas fa-arrow-left" alt="Arrow Icon"></i></a>',
				nextArrow:
					'<a class="slick-next"><i class="fas fa-arrow-right" alt="Arrow Icon"></i></a>',
				responsive: [
					{
						breakpoint: 1600,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 575,
						settings: {
							arrows: false,
							dots: false,
							slidesToShow: 1,
							slidesToScroll: 1,
						},
					},
				],
			});
		}
		const thumbsSlider = $(".ms-hero__slider__thumbs");
		if (thumbsSlider?.length) {
			thumbsSlider.slick({
				slidesToShow: 4,
				slidesToScroll: 1,
				initialSlide: 0,
				asNavFor: ".ms-hero__slider",
				dots: false /* image slide dots */,
				arrows: false /* image slide arrow */,
				centerMode: false,
				focusOnSelect: true,
				centerPadding: "30px",
				rtl: isRTL,
				// prevArrow:
				//   '<a class="slick-prev"><i class="fas fa-arrow-left" alt="Arrow Icon"></i></a>',
				// nextArrow:
				//   '<a class="slick-next"><i class="fas fa-arrow-right" alt="Arrow Icon"></i></a>',
				responsive: [
					{
						breakpoint: 1600,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 1200,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 768,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 767,
						settings: {
							arrows: false,
							dots: false,
							slidesToShow: 1.17,
						},
					},
				],
			});
		}

		const videoSlider = $(".ms-apartments-main__videos--slider");
		if (videoSlider?.length) {
			videoSlider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				initialSlide: 0,
	
				dots: false /* image slide dots */,
				arrows: false /* image slide arrow */,
				centerMode: false,
				focusOnSelect: true,
				centerPadding: "30px",
				rtl: isRTL,
				// prevArrow:
				//   '<a class="slick-prev"><i class="fas fa-arrow-left" alt="Arrow Icon"></i></a>',
				// nextArrow:
				//   '<a class="slick-next"><i class="fas fa-arrow-right" alt="Arrow Icon"></i></a>',
				responsive: [
					{
						breakpoint: 1600,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 1200,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 768,
						settings: {
							arrows: false,
							dots: false,
						},
					},
					{
						breakpoint: 767,
						settings: {
							arrows: false,
							dots: false,
						},
					},
				],
			});
		}
	
	/* --------------------------------------------------------
		LightCase jQuery Active
	--------------------------------------------------------- */
	const lightCaseGallery = $("a[data-rel^=lightcase]");
	if (lightCaseGallery?.length) {
		lightCaseGallery.lightcase({
			transition: "elastic",
			swipe: true,
			maxWidth: 1170,
			speedIn: 50,
			speedOut: 50,
		});

		const lightCaseControllers = document.querySelectorAll(
			"a[data-rel^=lightcase]"
		);

		if (lightCaseControllers?.length) {
			lightCaseControllers.forEach(lightCaseController => {
				lightCaseController.addEventListener("click", function () {
					setTimeout(() => {
						const lightCaseOverlay =
							document.querySelectorAll("#lightcase-overlay");
						if (lightCaseOverlay?.length) {
							lightCaseOverlay.forEach(lightCaseOverlaySingle => {
								lightCaseOverlaySingle.style.opacity = 1;
							});
						}
					}, 100);
				});
			});
		}
	}

	// hide and show password
	const toggleButtons = document.querySelectorAll(
		".ms-input__password-toggler"
	);
	if (toggleButtons?.length) {
		toggleButtons?.forEach(toggleButton => {
			const passwordField = toggleButton.parentNode?.querySelector(
				".ms-input__password"
			);
			const showIcon = toggleButton.querySelector(".ms-hide-icon");
			const hideIcon = toggleButton.querySelector(".ms-show-icon");
			toggleButton.addEventListener("click", function () {
				if (passwordField.type === "password") {
					passwordField.type = "text";
					showIcon.style.display = "none";
					hideIcon.style.display = "block";
				} else {
					passwordField.type = "password";
					showIcon.style.display = "block";
					hideIcon.style.display = "none";
				}
			});
		});
	}

	
})(jQuery);
