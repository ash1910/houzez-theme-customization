/***************************************************
==================== JS INDEX ======================
****************************************************
Mobile Menu Js


****************************************************/

(function ($) {
	"use strict";

	// portfolio slider
	window.usePortfolioSlides = function () {
		const portfolioSlides = document.querySelectorAll(".ms-new-projects__wrap");
		if (portfolioSlides?.length) {
			portfolioSlides?.forEach((portfolioSlide, id) => {
				portfolioSlide?.addEventListener("mouseenter", function (e) {
					portfolioSlides?.forEach(portfolioSlide => {
						portfolioSlide.classList.remove("active");
					});

					this.classList.add("active");
				});
			});
		}
	};
	usePortfolioSlides();
	// portfolio
	window.usePortfolioSlider = function () {
		const portfolioSlider = new Swiper(".ms-new-projects__slider", {
			slidesPerView: 1.4,
			spaceBetween: 12,
			centeredSlides: true,
			loop: true,
			autoplay: {
				delay: 5000,
			},
			breakpoints: {
				768: {
					slidesPerView: 3,
					spaceBetween: 20,
				},
			},
		});
	};
	usePortfolioSlider();
	// form slider
	var formSlider = new Swiper(".ms-form__slider", {
		slidesPerView: 1,
		spaceBetween: 0,
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
		loop: true,
		autoplay: {
			delay: 10000,
		},
	});
	// card slider
	var formSlider = new Swiper(".ms-aparments-maincardslider", {
		slidesPerView: 1,
		spaceBetween: 0,
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
		loop: true,
	});
	// apartment
	window.useapartmentSlider = function () {
		const apartmentSlider = new Swiper(".ms-apartments__slider", {
			slidesPerView: 1.5,
			spaceBetween: 20,
			centeredSlides: true,
			loop: true,
			autoplay: {
				delay: 5000,
			},
			breakpoints: {
				1024: {
					slidesPerView: 3.5,
					spaceBetween: 30,
				},
			},
		});
	};
	useapartmentSlider();
	$(".ms-btn--gallery").on("shown.bs.tab", function () {
		// Re-layout Isotope when the tab becomes visible

		useapartmentSlider();
	});
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

	const tabs = document.querySelectorAll('.nav-tab button[data-toggle="tab"]');
	tabs.forEach(tab => {
		tab.addEventListener("shown.bs.tab", e => {
			const target = e.target.getAttribute("data-target");
			useapartmentSlider(); // Update Swiper when the tab is shown
		});
	});
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
	// nice select
	$(".ms-nice-select:not(.ms-nice-select__country-code)").niceSelect();
	$(".ms-nice-select:not(.ms-nice-select__country-code)").on(
		"change",
		function () {
			// selected funtionality
			const selectParent = $(this).closest(".ms-input");
			console.log(selectParent);
			if (selectParent?.length) {
				selectParent.addClass("ms-input--selected");
			}
		}
	);

	// nice select for country code
	if ($(".ms-nice-select__country-code")?.length) {
		fetch("https://restcountries.com/v3.1/all")
			.then(response => response.json())
			.then(data => {
				let select = $(".ms-nice-select__country-code");

				let countryCodes = new Set(); // To avoid duplicates

				data.forEach(country => {
					if (country.idd?.root) {
						let fullCode =
							country.idd.root +
							(country.idd.suffixes ? country.idd.suffixes[0] : "");
						countryCodes.add(fullCode);
					}
				});

				// Sort and add unique country codes to the dropdown
				[...countryCodes].sort().forEach(code => {
					select.append(`<option value="${code}">${code}</option>`);
				});

				select.niceSelect(); // Initialize Nice Select
			})
			.catch(error => console.error("Error fetching country codes:", error));
	}
	/* ---------------------------------------------------------
							32. Price Slider
					--------------------------------------------------------- */
	$(".ms-slider-range").slider({
		range: true,

		min: 0,
		max: 2000,
		values: [200, 1500],
		slide: function (event, ui) {
			$(".ms-input__content__value--min").html("$" + ui.values[0]);
			$(".ms-input__content__value--max").html("$" + ui.values[1]);
			// selected funftionality
			const priceRangeParent = ui.handle.closest(".ms-input--price");

			if (priceRangeParent) {
				priceRangeParent.classList.add("ms-input--selected");
			}
		},
	});

	// get all button in form
	const forms = document.querySelectorAll(".ms-hero__form");
	if (forms?.length) {
		const buttonsInForm = document.querySelectorAll(
			".ms-btn__not-submit:not([data-toggle='modal'])"
		);
		if (buttonsInForm?.length) {
			buttonsInForm?.forEach(button => {
				button.addEventListener("click", function (e) {
					e.preventDefault();
					e.stopPropagation();
					// bed related
					const bedInputParent = $(this).closest(".ms-input--bed");
					const bedInputList = $(this).closest(".ms-input__list");

					// price range related
					const priceRangeParent = $(this).closest(".ms-input--price");

					const isOpen = this.classList.contains("open");
					const isReset = this.classList.contains("ms-btn__reset__price");
					const isApply = this.classList.contains("ms-btn__apply__price");
					const isDeselectBtn = this.classList.contains("ms-input__deselect");

					buttonsInForm?.forEach(button => {
						button.classList.remove("open");
					});

					if (!isOpen && !isDeselectBtn) {
						this.classList.add("open");
						$(".ms-nice-select").removeClass("open");
					}
					// apply price range input

					if (isApply) {
						priceRangeParent.find(".open").removeClass("open");
					}
					// reset price range input
					if (isReset) {
						// Find the closest parent with class "ms-input--price"

						// Find the slider inside this parent and reset its values
						priceRangeParent
							.find(".ms-slider-range")
							.slider("values", [200, 1500]);

						// Reset displayed values inside this specific price range
						priceRangeParent
							.find(".ms-input__content__value--min")
							.html("$200");
						priceRangeParent
							.find(".ms-input__content__value--max")
							.html("$1500");
					}

					// selected funtionality

					if (bedInputList?.length) {
						bedInputParent.addClass("ms-input--selected");
					}
				});

				document?.body?.addEventListener(
					"click",
					function (e) {
						console.log(!e.target.closest(".ms-input__content"));
						if (!e.target.closest(".ms-input__content")) {
							button.classList.remove("open");
						}
					},
					false
				);
				const buttonParent = button?.parentNode;

				buttonParent?.parentNode?.addEventListener(
					"click",
					function (e) {
						if (!e.target.closest(".ms-input__content")) {
							button.classList.remove("open");
						}
					},
					false
				);
			});
		}

		forms?.forEach((form, idx) => {
			// add class ms-inpu on onchange event
			const allInputs = form?.querySelectorAll(".ms-input input");

			if (allInputs?.length) {
				allInputs?.forEach((input, idx) => {
					input.addEventListener("change", function () {
						const inputParent = this.parentNode;

						if (inputParent) {
							inputParent.classList.add("ms-input--selected");
						}
					});
				});
			}
		});
	}
	// deselect selected input
	const deselectBtns = document.querySelectorAll(".ms-input__deselect");
	if (deselectBtns?.length) {
		deselectBtns?.forEach(deselectBtn => {
			const selectCommon = deselectBtn.parentNode?.querySelector(".ms-btn");
			const niceSelectCurrent = deselectBtn.parentNode?.querySelector(
				".ms-nice-select .current"
			);

			const inputText = deselectBtn.parentNode?.querySelector(
				".ms-hero__search-loaction"
			);

			let selectCommonDefaultValue = "";
			let niceSelectCurrentCommonDefaultValue = "";
			let inputDefaultValue = "";

			if (selectCommon) {
				selectCommonDefaultValue = selectCommon.innerHTML;
			}
			if (niceSelectCurrent) {
				niceSelectCurrentCommonDefaultValue = niceSelectCurrent.textContent;
			}

			const bathSelectDefaultValue =
				deselectBtn.parentNode?.querySelector(".ms-btn");

			deselectBtn.addEventListener("click", function () {
				const selectedInput = this.closest(".ms-input--selected");
				const msBtn = selectedInput.querySelector(".ms-btn");
				const niceSelect = selectedInput.querySelector(".ms-nice-select");

				if (selectedInput) {
					selectedInput.classList.remove("ms-input--selected");
					if (niceSelectCurrentCommonDefaultValue) {
						niceSelectCurrent.textContent = niceSelectCurrentCommonDefaultValue;
						console.log(niceSelectCurrent);
					}
					if (selectCommonDefaultValue) {
						selectCommon.innerHTML = selectCommonDefaultValue;
					}
					if (inputText) {
						inputText.value = "";
					}
				}
			});
		});
	}
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
			rtl: true,
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
			rtl: true,
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
			slidesToShow: 2,
			slidesToScroll: 1,
			initialSlide: 0,

			dots: false /* image slide dots */,
			arrows: false /* image slide arrow */,
			centerMode: false,
			focusOnSelect: true,
			centerPadding: "30px",
			rtl: true,
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
	/* --------------------------------------------------------
            LightCase jQuery Active
        --------------------------------------------------------- */
	const lightCaseGallery = $("a[data-rel^=lightcase]");
	if (lightCaseGallery?.length) {
		lightCaseGallery.lightcase({
			transition: "elastic",
			swipe: true,
			maxWidth: 1170,
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
	// controll location
	const locationList = document.querySelector(
		"ul.ms-apartments-main__location"
	);

	if (locationList) {
		const viewAllBtn = locationList.querySelector(
			".ms-apartments-main__location__all"
		);
		const viewLessBtn = locationList.querySelector(
			".ms-apartments-main__location__less"
		);
		function msControlLoaction(isAll) {
			const allItems = locationList.querySelectorAll("li");
			allItems?.forEach((item, idx) => {
				item?.classList?.remove("ms-show");
			});
			if (isAll) {
				allItems?.forEach((item2, idx2) => {
					item2?.classList?.add("ms-show");
				});
				viewAllBtn?.classList?.remove("ms-show");
				viewLessBtn?.classList?.add("ms-show");
			} else {
				allItems?.forEach((item2, idx2) => {
					if (idx2 < 6) {
						item2?.classList?.add("ms-show");
					}
				});
				viewAllBtn?.classList?.add("ms-show");
			}
		}
		msControlLoaction(false);
		viewAllBtn?.addEventListener("click", function () {
			msControlLoaction(true);
		});
		viewLessBtn?.addEventListener("click", function () {
			msControlLoaction(false);
		});
	}
})(jQuery);
