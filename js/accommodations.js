(function($) {

	$(document).ready(function () {	
		accommodations.init();
	});
	
	var accommodations = {

		init: function () {
		
			window.bookingRequest = {};
			window.bookingRequest.extraItems = {};
			window.bookingRequest.roomCount = 1;
			window.bookingRequest.adults = 1;
			window.bookingRequest.children = 0;
			window.bookingRequest.extraItemsTotalPrice = 0;
			window.bookingRequest.totalPrice = 0;
			window.bookingRequest.totalAccommodationOnlyPrice = 0;
			window.bookingRequest.totalDays = 1;
			
			if (window.accommodationIsReservationOnly || !window.useWoocommerceForCheckout) {
			
				$('.accommodation-booking-form').validate({
					onkeyup: false,
					ignore: [],
					invalidHandler: function(e, validator) {
						var errors = validator.numberOfInvalids();
						if (errors) {
							var message = errors == 1 ? window.formSingleError : window.formMultipleError.format(errors);
							$("div.error div p").html(message);
							$("div.error").show();
						} else {
							$("div.error").hide();
						}
					},
					submitHandler: function() { 
						accommodations.processBooking(); 
					}
				});
				
				$.each(window.bookingFormFields, function(index, field) {
				
					if (field.hide !== '1' && field.id !== null && field.id.length > 0) {
						var $input = null;
						if (field.type == 'text' || field.type == 'email') {
							$input = $('.accommodation-booking-form').find('input[name=' + field.id + ']');
						} else if (field.type == 'textarea') {
							$input = $('.accommodation-booking-form').find('textarea[name=' + field.id + ']');
						}
						
						if ($input !== null && typeof($input) !== 'undefined') {
							if (field.required == '1') {
								$input.rules('add', {
									required: true,
									messages: {
										required: window.bookingFormRequiredError
									}
								});
							}
							if (field.type == 'email') {
								$input.rules('add', {
									email: true,
									messages: {
										required: window.bookingFormEmailError
									}
								});
							}
						}
					}
				});
			}
		
			$('.radio').bind('click.uniform',
				function (e) {
					if ($(this).find("span").hasClass('checked')) 
						$(this).find("input").attr('checked', true);
					else
						$(this).find("input").attr('checked', false);
				}
			);
		
			$('.booking-commands').hide();
			
			accommodations.bindGallery();
			
			if (window.accommodationDisabledRoomTypes) {
				accommodations.populateAvailableDays(window.accommodationId, window.bookingRequest.roomTypeId);				
				accommodations.populateAvailableStartDays(window.accommodationId, window.bookingRequest.roomTypeId, accommodations.bindCalendar);			
			} else {
				accommodations.bindSelectDatesButtons();
			}
		},
		bindRequiredExtraItems: function() {
			if (typeof(window.requiredExtraItems) !== 'undefined' && window.requiredExtraItems.length > 0) {
				$.each( window.requiredExtraItems, function( index, extraItemId ){
					accommodations.updateExtraItemSelection(extraItemId, 1);
					$('#extra_item_quantity_' + extraItemId).val('1');
				});
			}			
		},		
		bindFormControls: function() {
		
			$('.extra_items_total').html(accommodations.formatPrice(0));
			$('.total_price').html(accommodations.formatPrice(0));
			$('.reservation_total').html(accommodations.formatPrice(0));
		
			if (window.enableExtraItems) {
				accommodations.bindExtraItemsQuantitySelect();			
				accommodations.buildExtraItemsTable();
			}
			
			accommodations.bindResetButton();
			
			window.bookingRequest.maxAdults = 0;
			window.bookingRequest.maxChildren = 0;
			window.bookingRequest.minAdults = 0;
			window.bookingRequest.minChildren = 0;
			
			if (window.bookingRequest.roomTypeId > 0) {
				window.bookingRequest.maxAdults = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .max_adult_count').val());
				window.bookingRequest.maxChildren = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .max_child_count').val());
				if ($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_adult_count').length > 0) {
					window.bookingRequest.minAdults = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_adult_count').val());
				}
				if ($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_child_count').length > 0) {
					window.bookingRequest.minChildren = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_child_count').val());
				}
			} else {
				window.bookingRequest.maxAdults = parseInt(window.accommodationMaxAdultCount);
				window.bookingRequest.maxChildren = parseInt(window.accommodationMaxChildCount);
				window.bookingRequest.minAdults = parseInt(window.accommodationMinAdultCount);
				window.bookingRequest.minChildren = parseInt(window.accommodationMinChildCount);
			}
			
			if (window.bookingRequest.minAdults > window.bookingRequest.maxAdults) {
				window.bookingRequest.minAdults = window.bookingRequest.maxAdults;
			} else if (window.bookingRequest.minAdults <= 0) {
				window.bookingRequest.minAdults = 1;
			}
			if (window.bookingRequest.minChildren > window.bookingRequest.maxChildren) {
				window.bookingRequest.minChildren = window.bookingRequest.maxChildren;
			} else if (window.bookingRequest.minChildren < 0) {
				window.bookingRequest.minChildren = 0;
			}
			
			window.bookingRequest.adults = parseInt(window.bookingRequest.minAdults);
			window.bookingRequest.children = parseInt(window.bookingRequest.minChildren);

			if ($('#booking_form_adults option').size() === 0) {
				
				for ( var i = window.bookingRequest.minAdults; i <= window.bookingRequest.maxAdults; i++ ) {
					$('<option ' + (i == 1 ? 'selected' : '') + '>').val(i).text(i).appendTo('#booking_form_adults');
				}
				
				if (window.bookingRequest.minAdults > 0) {
					window.bookingRequest.adults = parseInt(window.bookingRequest.minAdults);
					var children = 0;
					if ($('#booking_form_children') && $('#booking_form_children').val()) {
						children = parseInt($('#booking_form_children').val());
					}
					window.bookingRequest.children = children;
						
					$('span.adults_text').html(window.bookingRequest.adults);
					$('span.people_text').html(window.bookingRequest.adults + children);
				}
				
				$('#booking_form_adults').on('change', function (e) {
				
					window.bookingRequest.adults = parseInt($(this).val());
					var children = 0;
					if ($('#booking_form_children') && $('#booking_form_children').val()) {
						children = parseInt($('#booking_form_children').val());
					}
					window.bookingRequest.children = children;
						
					$('span.adults_text').html(window.bookingRequest.adults);
					$('span.people_text').html(window.bookingRequest.adults + children);
					accommodations.buildRatesTable();
					accommodations.recalculateExtraItemTotals();				
				});

				if (window.bookingRequest.maxChildren > 0) {

					for ( var j = window.bookingRequest.minChildren; j <= window.bookingRequest.maxChildren; j++ ) {
						$('<option ' + (j == window.bookingRequest.minChildren ? 'selected' : '') + '>').val(j).text(j).appendTo('#booking_form_children');
					}
					
					if (window.bookingRequest.minChildren > 0) {
						window.bookingRequest.children = parseInt(window.bookingRequest.minChildren);
						var adults = parseInt($('#booking_form_adults').val());
						$('span.children_text').html(window.bookingRequest.children + (window.accommodationCountChildrenStayFree > 0 ? " *" : ""));
						$('span.people_text').html(adults + window.bookingRequest.children);					
					}
					
					$('#booking_form_children').on('change', function (e) {
						window.bookingRequest.children = parseInt($(this).val());
						var adults = 1;
						if ($('#booking_form_adults') && $('#booking_form_adults').val()) {
							adults = parseInt($('#booking_form_adults').val());
						}
						$('span.children_text').html(window.bookingRequest.children + (window.accommodationCountChildrenStayFree > 0 ? " *" : ""));
						$('span.people_text').html(adults + window.bookingRequest.children);						
						accommodations.buildRatesTable();
						accommodations.recalculateExtraItemTotals();
					});
					
				} else {
					$('.booking_form_children_div').hide();
					$('.booking_form_adults_div').removeClass('one-half').addClass('full-width');
				}
				
				$('#booking_form_adults').uniform();
				$('#booking_form_children').uniform();		
				$('.extra_item_quantity').uniform();
				
				if (window.accommodationCountChildrenStayFree > 0 && window.bookingRequest.maxChildren > 0) {
					$('.adult_count_div').show();
					$('.children_count_div').show();
					$('.people_count_div').hide();
				} else {
					$('.adult_count_div').hide();
					$('.children_count_div').hide();
					$('.people_count_div').show();
				}
				
				$('.toggle_breakdown').unbind('click');
				$('.toggle_breakdown').on('click', function(e) {
					if ($('.price_breakdown_row').hasClass('hidden')) {
						$('.price_breakdown_row').removeClass('hidden');
						if (window.enableExtraItems) {
							$('.price_breakdown_row').show();
						} else {
							$('.price_breakdown_row:not(.extra_items_breakdown_row)').show();
						}
						$('.toggle_breakdown').html(window.hidePriceBreakdownLabel);
					} else {
						$('.price_breakdown_row').addClass('hidden');				
						$('.price_breakdown_row').hide();
						$('.toggle_breakdown').html(window.showPriceBreakdownLabel);
					}
					
					e.preventDefault();
				});
			}

		},
		bindSelectDatesButtons: function () {
		
			$('.book-accommodation-select-dates').unbind('click');
			$('.book-accommodation-select-dates').on('click', function(e) {

				var prevRoomTypeId = 0;
				if (typeof (window.bookingRequest) !== 'undefined' && typeof(window.bookingRequest.roomTypeId) !== 'undefined') {
					prevRoomTypeId = window.bookingRequest.roomTypeId;
				}

				var roomTypeId = $(this).attr('id').replace('book-accommodation-', '');				
				$('.room_type_span').html($('li#room_type_' + roomTypeId + ' .room_type h3').html());
				$('#room_type_id').val(roomTypeId);
				
				accommodations.resetFormValues();
				
				window.bookingRequest.roomTypeTitle = $('.room_type_span').html();

				$('.room_type_row').show();
				
				$('.book-accommodation-select-dates').show();
				$(this).hide();

				if (prevRoomTypeId > 0) {
					$('.vacancy_datepicker').datepicker('destroy');
					$("#room_type_" + prevRoomTypeId + " .booking_form_controls").html('');
					$("#room_type_" + prevRoomTypeId + " .booking_form_controls").show();
				} 

				$("#room_type_" + window.bookingRequest.roomTypeId + " .booking_form_controls").html($(".booking_form_controls_holder").html());
				$("#room_type_" + window.bookingRequest.roomTypeId + " .booking_form_controls").show();
				
				$("#room_type_" + window.bookingRequest.roomTypeId + " .booking_form_controls .datepicker_holder").addClass('vacancy_datepicker');
				
				accommodations.populateAvailableDays(window.accommodationId, window.bookingRequest.roomTypeId);				
				accommodations.populateAvailableStartDays(window.accommodationId, window.bookingRequest.roomTypeId, accommodations.bindCalendar);
				
				e.preventDefault();
			});
		},
		bindExtraItemsQuantitySelect: function() {

			$('select.extra_item_quantity').unbind('change');	
			$('select.extra_item_quantity').on('change', function(e) {

				var quantity = parseInt($(this).val());
				var extraItemId = $(this).attr('id').replace('extra_item_quantity_', '');

				$(this).uniform()
				
				accommodations.updateExtraItemSelection(extraItemId, quantity);
			});		
		},
		updateExtraItemSelection: function(extraItemId, quantity) {
		
			if (extraItemId > 0) {
			
				var extraItemPrice = parseFloat($('#extra_item_price_' + extraItemId).val());
				var extraItemTitle = $('#extra_item_title_' + extraItemId).html();
				var extraItemPricePerPerson = parseInt($('#extra_item_price_per_person_' + extraItemId).val());
				var extraItemPricePerDay = parseInt($('#extra_item_price_per_day_' + extraItemId).val());
				var oldExtraItem = null;
				var extraItem = {};
				var extraItemRows = '';
				var pricingMethod = '';
				
				// reduce total by old item summed price.
				if (extraItemId in window.bookingRequest.extraItems) {
					oldExtraItem = window.bookingRequest.extraItems[extraItemId];
					window.bookingRequest.totalPrice -= parseFloat(oldExtraItem.summedPrice);	
					window.bookingRequest.extraItemsTotalPrice -= parseFloat(oldExtraItem.summedPrice);
					delete window.bookingRequest.extraItems[extraItemId];
				}
				
				$('table.extra_items_price_breakdown tbody').html('');
				
				if (quantity > 0) {
				
					extraItem.quantity = quantity;
					extraItem.id = extraItemId;
					extraItem.price = extraItemPrice;
					extraItem.pricePerPerson = extraItemPricePerPerson;
					extraItem.pricePerDay = extraItemPricePerDay;
					
					if (extraItem.pricePerPerson) {
						var adjustedChildren = window.bookingRequest.children-window.accommodationCountChildrenStayFree;
						adjustedChildren = adjustedChildren > 0 ? adjustedChildren : 0;
						extraItemPrice = (window.bookingRequest.adults * extraItemPrice) + (adjustedChildren * extraItemPrice);
					}
					
					if (extraItem.pricePerDay) {
						extraItemPrice = extraItemPrice * window.bookingRequest.totalDays;
					}
					
					extraItem.summedPrice = extraItem.quantity * extraItemPrice;
					extraItem.title = extraItemTitle;
					
					window.bookingRequest.totalPrice += extraItem.summedPrice;
					window.bookingRequest.extraItemsTotalPrice += extraItem.summedPrice;
					window.bookingRequest.extraItems[extraItemId] = extraItem;
				}
				
				if (Object.size(window.bookingRequest.extraItems) > 0) {
					$.each( window.bookingRequest.extraItems, function( index, value ){
					
						pricingMethod = '';
						if (value.pricePerDay && value.pricePerPerson)
							pricingMethod = '(' + window.pricedPerDayPerPersonLabel + ')';
						else if (value.pricePerDay)
							pricingMethod = '(' + window.pricedPerDayLabel + ')';
						else if (value.pricePerPerson)
							pricingMethod = '(' + window.pricedPerPersonLabel + ')';
							
						extraItemRows += '<tr class="extra_item_row_' + value.Id + '"><td>' + value.quantity + ' x ' + value.title + ' ' + pricingMethod + ' </td><td>' + accommodations.formatPrice(value.summedPrice) + '</td></tr>';
					});
				}
				
				$('table.extra_items_price_breakdown tbody').html(extraItemRows);
				
				$('.extra_items_total').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));		
				$('.total_price').html(accommodations.formatPrice(window.bookingRequest.totalPrice));	
			}			
		},
		bindResetButton: function () {

			$('.book-accommodation-reset').unbind('click');
			$('.book-accommodation-reset').on('click', function(e) {
			
				$('.book-accommodation-select-dates').show();

				accommodations.resetFormValues();
				
				accommodations.populateAvailableDays(window.accommodationId, window.bookingRequest.roomTypeId);				
				accommodations.populateAvailableStartDays(window.accommodationId, window.bookingRequest.roomTypeId, accommodations.refreshCalendar);
				e.preventDefault();				
			});
		},
		bindCancelButton : function() {
			
			$('.cancel-accommodation-booking').unbind('click');
			$('.cancel-accommodation-booking').on('click', function(event) {

				event.preventDefault();

				accommodations.hideBookingForm();
				accommodations.showAccommodationScreen();
				
				$('body,html').animate({
					scrollTop: 0
				}, 800);
					
			});	
		},
		bindNextButton : function () {

			$('.book-accommodation-next').unbind('click');
			
			if (window.accommodationIsReservationOnly || !window.useWoocommerceForCheckout) {
				$('.book-accommodation-next').on('click', function(event) {

					event.preventDefault();

					accommodations.hideAccommodationScreen();
					accommodations.showBookingForm();
					
					$('body,html').animate({
						scrollTop: 0
					}, 800);
						
				});			
			} else {
				$('.book-accommodation-next').on('click', function(e) {
					accommodations.addProductToCart();
					e.preventDefault();
				});	
			}
		},
		bindGallery : function() {
		
			$("#gallery").lightSlider({
				item:1,
				rtl: (window.enableRtl ? true : false),
				slideMargin:0,
				auto:true,
				loop:true,
				speed:900,
				pause:window.pauseBetweenSlides,
				keyPress:true,
				gallery:true,
				thumbItem:8,
				galleryMargin:3,
				onSliderLoad: function() {
					$('#gallery').removeClass('cS-hidden');
				} 
			});
		},
		bindCalendar : function() {
		
			$(".price_row").hide();
		
			if (window.accommodationDisabledRoomTypes) {			
				$(".booking_form_controls").html($(".booking_form_controls_holder").html());
				$(".booking_form_controls").show();				
				$(".booking_form_controls .datepicker_holder").addClass('vacancy_datepicker');
			}
		
			if (typeof ($('.vacancy_datepicker')) !== 'undefined') {

				$('.vacancy_datepicker').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: [2, 2],				
					hourMin: 6,
					hourMax: 18,
					minDate: 0,
					onSelect: function(dateText, inst) {
						
						var selectedTime = Date.UTC(inst.currentYear, inst.currentMonth, inst.currentDay),
							selectedDate = accommodations.convertLocalToUTC(new Date(selectedTime)),
							selectedDateFrom = accommodations.getSelectedDateFrom(),
							selectedDateTo = accommodations.getSelectedDateTo(),
							dateTest = true,
							dayOfWeek = selectedDate.getDay();

						if (!selectedDateFrom || selectedDateTo || (selectedDate < selectedDateFrom) || (selectedDateFrom.toString() === selectedDate.toString())) {
							$("div.error.step1_error").hide();
							if (window.accommodationRentType == 2 && selectedDate.getDate() > 1) {
								// monthly rentals allow only selecting 1st day of month as start date
								$("div.error.step1_error div p").html(window.checkinMonthlyFirstDayError);
								$("div.error.step1_error").show();
							} else if (dayOfWeek > -1 && dayOfWeek != (window.accommodationCheckinWeekday) && window.accommodationCheckinWeekday > -1) {
							
								$("div.error.step1_error div p").html(window.checkinWeekDayError);
								$("div.error.step1_error").show();
								
							} else {
								accommodations.selectDateFrom(selectedTime, dateText);														
							}
						} else {

							for (var d = selectedDateFrom; d < selectedDate; d.setDate(d.getDate() + 1)) {
								var dateToCheck = (d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' +  ("0" + d.getDate()).slice(-2));
								var datesArray = accommodations.getAccommodationVacancyDates();
								if ($.inArray(dateToCheck, datesArray) == -1) {
									dateTest = false;
									break;
								}
							}
							
							console.log('dateTest ' + dateTest);
							
							if (!dateTest) {							
								accommodations.selectDateFrom(selectedTime, dateText);
							} else {
							
								var totalDays = accommodations.calculateDifferenceInDays(accommodations.getSelectedDateFrom(), selectedDate);
								var lastDayOfMonth = accommodations.daysInMonth(selectedDate.getMonth() + 1, selectedDate.getFullYear());
								
								console.log('totalDays ' + totalDays);
								
								$("div.error.step1_error").hide();
								if (window.accommodationRentType == 2 && selectedDate.getDate() !== lastDayOfMonth) {
									// monthly rentals allow only selecting 1st day of month as start date
									$("div.error.step1_error div p").html(window.checkoutMonthlyLastDayError);
									$("div.error.step1_error").show();
									
								} else if (window.accommodationRentType == 1 && totalDays % 7 > 0) {
									$("div.error.step1_error div p").html(window.checkoutWeeklyDayError);
									$("div.error.step1_error").show();
								} else if (totalDays < window.accommodationMinDaysStay) {
								
									$("div.error.step1_error div p").html(window.minDaysStayError);
									$("div.error.step1_error").show();
									
								} else if (window.accommodationMaxDaysStay > 0 && totalDays > window.accommodationMaxDaysStay) {
								
									$("div.error.step1_error div p").html(window.maxDaysStayError);
									$("div.error.step1_error").show();
									
								} else if (dayOfWeek > -1 && dayOfWeek != (window.accommodationCheckoutWeekday) && window.accommodationCheckoutWeekday > -1) {
								
									$("div.error.step1_error div p").html(window.checkoutWeekDayError);
									$("div.error.step1_error").show();
									
								} else {
								
									$("div.error.step1_error div p").html('');
									$("div.error.step1_error").hide();

									window.bookingRequest.totalDays = totalDays;
									accommodations.selectDateTo(selectedTime, dateText);
									accommodations.buildRatesTable();
									accommodations.recalculateExtraItemTotals();
								}
							}
						}						
					},
					onChangeMonthYear: function (year, month, inst) {
						
						window.currentMonth = month;
						window.currentYear = year;
						window.currentDay = 1;
						
						var selectedDateFrom = accommodations.getSelectedDateFrom();

						accommodations.populateAvailableDays(window.accommodationId, window.bookingRequest.roomTypeId);				
						accommodations.populateAvailableStartDays(window.accommodationId, window.bookingRequest.roomTypeId, accommodations.refreshCalendar);
						
						if (selectedDateFrom) {
							accommodations.populateAvailableEndDates(selectedDateFrom, window.accommodationId, window.bookingRequest.roomTypeId, accommodations.refreshCalendar);
						} 
					},
					beforeShowDay: function(d) {
					
						var tUtc = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()).valueOf();
						var today = new Date();
						var todayUtc = Date.UTC(today.getFullYear(), today.getMonth(), today.getDate());
						var selectedTimeFrom = accommodations.getSelectedTimeFrom();
						var selectedTimeTo = accommodations.getSelectedTimeTo();
						var dateTextForCompare = '';

						if (!selectedTimeFrom) {
							if (window.accommodationVacancyStartDays) {
							
								dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
								var datesArray = accommodations.getAccommodationVacancyStartDates();
								
								if (selectedTimeFrom && tUtc == selectedTimeFrom)
									return [false, 'dp-highlight'];								
								else if ($.inArray(dateTextForCompare, datesArray) == -1)
									return [false, 'ui-datepicker-unselectable ui-state-disabled'];
								else if (todayUtc.valueOf() < tUtc && $.inArray(dateTextForCompare, datesArray) > -1)
									return [true, 'dp-highlight'];
							}
						} else if (!selectedTimeTo) {
							if (window.accommodationVacancyEndDates) {
							
								dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
								
								if (selectedTimeFrom && tUtc == selectedTimeFrom)
									return [false, 'dp-highlight'];								
								else if ($.inArray(dateTextForCompare, window.accommodationVacancyEndDates) == -1)
									return [false, 'ui-datepicker-unselectable ui-state-disabled'];
								else if (todayUtc.valueOf() < tUtc && $.inArray(dateTextForCompare, window.accommodationVacancyEndDates) > -1)
									return [true, 'dp-highlight'];
							}						
						} else if (selectedTimeFrom && selectedTimeTo) {
							if (selectedTimeFrom && ((tUtc == selectedTimeFrom) || (selectedTimeTo && tUtc >= selectedTimeFrom && tUtc <= selectedTimeTo))) {
								return [false, 'dp-hightlight dp-highlight-selected'];
							} else {
								return [false, ''];
							}
						}
					
						return [true, selectedTimeFrom && ((tUtc == selectedTimeFrom) || (selectedTimeTo && tUtc >= selectedTimeFrom && tUtc <= selectedTimeTo)) ? "dp-highlight" : ""];					
					}
				});
			}		
		},
		refreshCalendar : function() {
		
			if (typeof $('.vacancy_datepicker') !== 'undefined') {
				$('.vacancy_datepicker').datepicker( "refresh" );
			}
		},
		resetFormValues: function() {

			$("#date_from_text").html("");
			$("#selected_date_from").val("");
			$("#date_to_text").html("");
			$("#selected_date_to").val("");					
			$(".extra_item_quantity").val("0");

			$("span.adults_text").html("1");
			$("span.children_text").html("0" + (window.accommodationCountChildrenStayFree > 0 ? " *" : ""));				
			$(".dates_row").hide();
			$(".price_row").hide();
			$(".booking-commands").hide();
			$('.price_breakdown').hide();
			
			window.bookingRequest = {};
			window.bookingRequest.extraItems = {};
			window.bookingRequest.totalPrice = 0;
			window.bookingRequest.totalAccommodationOnlyPrice = 0;
			window.bookingRequest.totalDays = 0;
				
			window.bookingRequest.maxAdults = 0;
			window.bookingRequest.maxChildren = 0;
			window.bookingRequest.minAdults = 0;
			window.bookingRequest.minChildren = 0;
			
			if ($('#room_type_id').length > 0) {
				window.bookingRequest.roomTypeId = $('#room_type_id').val();
			} else {
				window.bookingRequest.roomTypeId = 0;
			}			
			
			if (window.bookingRequest.roomTypeId > 0) {
				window.bookingRequest.maxAdults = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .max_adult_count').val());
				window.bookingRequest.maxChildren = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .max_child_count').val());
				if ($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_adult_count').length > 0) {
					window.bookingRequest.minAdults = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_adult_count').val());
				}
				if ($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_child_count').length > 0) {
					window.bookingRequest.minChildren = parseInt($('li#room_type_' + window.bookingRequest.roomTypeId + ' .room-information .min_child_count').val());
				}
			} else {
				window.bookingRequest.maxAdults = parseInt(window.accommodationMaxAdultCount);
				window.bookingRequest.maxChildren = parseInt(window.accommodationMaxChildCount);
				window.bookingRequest.minAdults = parseInt(window.accommodationMinAdultCount);
				window.bookingRequest.minChildren = parseInt(window.accommodationMinChildCount);
			}
			
			if (window.bookingRequest.minAdults > window.bookingRequest.maxAdults) {
				window.bookingRequest.minAdults = window.bookingRequest.maxAdults;
			} else if (window.bookingRequest.minAdults <= 0) {
				window.bookingRequest.minAdults = 1;
			}
			if (window.bookingRequest.minChildren > window.bookingRequest.maxChildren) {
				window.bookingRequest.minChildren = window.bookingRequest.maxChildren;
			} else if (window.bookingRequest.minChildren < 0) {
				window.bookingRequest.minChildren = 0;
			}
			
			window.bookingRequest.adults = window.bookingRequest.minAdults;
			window.bookingRequest.children = window.bookingRequest.minChildren;			
			
			$('#booking_form_adults').val(window.bookingRequest.minAdults);	
			$('#booking_form_children').val(window.bookingRequest.minChildren);
			
			$('span.adults_text').html(window.bookingRequest.adults);
			$('span.people_text').html(window.bookingRequest.adults + window.bookingRequest.children);
			$('span.children_text').html(window.bookingRequest.children + (window.accommodationCountChildrenStayFree > 0 ? " *" : ""));
			
			window.bookingRequest.extraItemsTotalPrice = 0;
			window.bookingRequest.selectedTimeFrom = null;
			window.bookingRequest.selectedDateFrom = null;
			window.bookingRequest.selectedTimeTo = null;
			window.bookingRequest.selectedDateTo = null;
			// window.bookingRequest.roomTypeId = 0;
			// window.bookingRequest.roomTypeTitle = '';
			
			$('.reservation_total').html(accommodations.formatPrice(window.bookingRequest.totalAccommodationOnlyPrice));
			$('.extra_items_total').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));		
			$('.total_price').html(accommodations.formatPrice(window.bookingRequest.totalPrice));
			$('.confirm_total_price_p').html(accommodations.formatPrice(window.bookingRequest.totalPrice));
			
			$('.extra_items_total').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));
			$('table.extra_items_price_breakdown tbody').html('');
			$('table.accommodation_price_breakdown tbody').html('');

			$.uniform.update();			
		},
		recalculateExtraItemTotals: function() {
		
			if (Object.size(window.bookingRequest.extraItems) > 0) {
			
				if (window.bookingRequest.extraItemsTotalPrice > 0) {
					window.bookingRequest.totalPrice = window.bookingRequest.totalAccommodationOnlyPrice;
					window.bookingRequest.extraItemsTotalPrice = 0;
				}
			
				$.each(window.bookingRequest.extraItems, function( id, extraItem ){

					var extraItemPrice = extraItem.price;
					
					if (extraItem.pricePerPerson) {
						var adjustedChildren = window.bookingRequest.children-window.accommodationCountChildrenStayFree;
						adjustedChildren = adjustedChildren > 0 ? adjustedChildren : 0;
						extraItemPrice = (window.bookingRequest.adults * extraItemPrice) + (adjustedChildren * extraItemPrice);
					}
					
					if (extraItem.pricePerDay) {
						extraItemPrice = extraItemPrice * window.bookingRequest.totalDays;
					}
					
					extraItem.summedPrice = extraItem.quantity * extraItemPrice;
					
					window.bookingRequest.totalPrice += extraItem.summedPrice;
					window.bookingRequest.extraItemsTotalPrice += extraItem.summedPrice;
				});
				
				$('.extra_items_total').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));		
				$('.total_price').html(accommodations.formatPrice(window.bookingRequest.totalPrice));		
			}
		},
		buildExtraItemsTable : function() {
		
			$('table.extra_items_price_breakdown thead').html('');
			$('table.extra_items_price_breakdown tfoot').html('');
			$('table.extra_items_price_breakdown tbody').html('');
			
			var headerRow = '';
			headerRow += '<tr class="rates_head_row">';
			headerRow += '<th>' + window.itemLabel + '</th>';		
			headerRow += '<th>' + window.priceLabel + '</th>';
			headerRow += '</tr>';

			$('table.extra_items_price_breakdown thead').append(headerRow);	

			var footerRow = '';
			footerRow += '<tr>';
			footerRow += '<th>' + window.priceTotalLabel + '</th>';
			footerRow += '<td class="extra_items_total">' + accommodations.formatPrice(0) + '</td>';
			footerRow += '</tr>';

			$('table.extra_items_price_breakdown tfoot').append(footerRow);
		},
		buildRatesTable: function() {
		
			var roomCount = window.bookingRequest.roomCount;
			var adults = $('#booking_form_adults').val() !== null ? parseInt($('#booking_form_adults').val(), 10) : 1;
			var children = $('#booking_form_children').val() !== null ? parseInt($('#booking_form_children').val(), 10) : 0;
			var headerRow = '';
			var footerRow = '';
			var colCount = 2;
			var selectedDateFrom = accommodations.getSelectedDateFrom();
			var selectedDateTo = accommodations.getSelectedDateTo();
			var selectedTimeFrom = selectedDateFrom.valueOf();
			var selectedTimeTo = selectedDateTo.valueOf();
			
			$(".price_row").show();

			$('table.accommodation_price_breakdown thead').html('');
			$('table.accommodation_price_breakdown tfoot').html('');
			$('table.accommodation_price_breakdown tbody').html('');
			
			headerRow += '<tr class="rates_head_row">';
			headerRow += '<th>' + window.dateLabel + '</th>';		
			
			if (window.accommodationIsPricePerPerson) {
			
				if (window.bookingRequest.maxChildren > 0) {
					headerRow += '<th>' + window.pricePerAdultLabel + '</th>';
					headerRow += '<th>' + window.pricePerChildLabel + '</th>';
					colCount = 4;
				} else {
					headerRow += '<th>' + window.pricePerPersonLabel + '</th>';
					colCount = 3;
				}				
			} 
			
			headerRow += '<th>' + window.pricePerDayLabel + '</th>';		
			headerRow += '</tr>';

			$('table.accommodation_price_breakdown thead').append(headerRow);	

			footerRow += '<tr>';
			if (window.accommodationCountChildrenStayFree > 0 && window.bookingRequest.maxChildren > 0) {
				footerRow += '<th colspan="' + (colCount - 1) + '">' + window.priceTotalChildrenStayFreeLabel + '</th>';
			} else {
				footerRow += '<th colspan="' + (colCount - 1) + '">' + window.priceTotalLabel + '</th>';
			}
			footerRow += '<td class="reservation_total">' + accommodations.formatPrice(0) + '</td>';
			footerRow += '</tr>';

			$('table.accommodation_price_breakdown tfoot').append(footerRow);
			
			if (selectedDateFrom && selectedDateTo) {
			
				$('#datepicker_loading').show();
				
				window.bookingRequest.totalPrice = 0;
				window.bookingRequest.totalAccommodationOnlyPrice = 0;
				window.rateTableRowIndex = 0;
				
				while (selectedTimeFrom < selectedTimeTo) {
					accommodations.buildRateRow(selectedTimeFrom, adults, children, colCount);
					if (window.accommodationRentType == 1) {
						// weekly
						selectedTimeFrom += (86400000 * 7);
					} else if (window.accommodationRentType == 2) {
						// monthly
						var newFromDate = accommodations.firstDayInNextMonth(selectedTimeFrom);
						selectedTimeFrom = newFromDate.valueOf();					
					} else {
						// daily
						selectedTimeFrom += 86400000;
					}
				}
				
				$('.reservation_total').html(accommodations.formatPrice(window.bookingRequest.totalAccommodationOnlyPrice));
				$('.total_price').html(accommodations.formatPrice(window.bookingRequest.totalPrice));
				
				$('.booking-commands .book-accommodation-next').show();
				
				accommodations.bindNextButton();
				accommodations.bindCancelButton();
				
				$('#datepicker_loading').hide();
			}		
		},
		buildRateRow : function(fromTime, adults, children, colCount) {
		
			var fromDate = new Date(fromTime);
			var tableRow = '';
			var pricePerDay = 0;
			var pricePerChild = 0;			
			var dateToCheck = (fromDate.getFullYear() + '-' + ("0" + (fromDate.getMonth() + 1)).slice(-2) + '-' +  ("0" + fromDate.getDate()).slice(-2));
						
			var datesArray = accommodations.getAccommodationVacancyDates();
			var vacancyStartDayIndex = $.inArray(dateToCheck, datesArray);
			var vacancyDay = null;
			
			if (vacancyStartDayIndex > -1) {			
				
				vacancyDay = window.accommodationVacancyDays[vacancyStartDayIndex];
				
				// This outputs the result of the ajax request
				window.rateTableRowIndex++;
				
				if (vacancyDay.is_weekend && vacancyDay.weekend_price_per_day && vacancyDay.weekend_price_per_day > 0) {
					pricePerDay = parseFloat(vacancyDay.weekend_price_per_day);
				} else {
					pricePerDay = parseFloat(vacancyDay.price_per_day);
				}

				pricePerChild = 0;
				
				tableRow += '<tr>';
				tableRow += '<td>' + $.datepicker.formatDate(window.datepickerDateFormat, fromDate) + '</td>';
				
				if (window.accommodationIsPricePerPerson) {
					if (vacancyDay.is_weekend && vacancyDay.weekend_price_per_day_child && vacancyDay.weekend_price_per_day_child > 0) {
						pricePerChild = parseFloat(vacancyDay.weekend_price_per_day_child);
					} else {
						pricePerChild = parseFloat(vacancyDay.price_per_day_child);
					}
					tableRow += '<td>' + accommodations.formatPrice(pricePerDay) + '</td>';
					if (window.bookingRequest.maxChildren > 0) {
						tableRow += '<td>' + accommodations.formatPrice(pricePerChild) + '</td>';
					}
				} 
				
				if (window.accommodationIsPricePerPerson) {
					children = children - window.accommodationCountChildrenStayFree;
					children = children > 0 ? children : 0;
					pricePerDay = (pricePerDay * adults) + (pricePerChild * children);
				} else {
					pricePerDay = pricePerDay;
				}

				window.bookingRequest.totalPrice += pricePerDay;
				window.bookingRequest.totalAccommodationOnlyPrice += pricePerDay;
				
				tableRow += '<td>' + accommodations.formatPrice(pricePerDay) + '</td>';		
				
				tableRow += '</tr>';
				
				$('table.accommodation_price_breakdown tbody').append(tableRow);
				
				if (window.rateTableRowIndex == window.bookingRequest.totalDays) {
					
					if ($("table.accommodation_price_breakdown").data('tablesorter') === null || typeof($("table.accommodation_price_breakdown").data('tablesorter')) == 'undefined') {
						$("table.accommodation_price_breakdown").tablesorter({
							debug:false,
							dateFormat: window.datepickerDateFormat, // 'ddmmyyyy',
							sortList: [[0,0]]
						});
					}
					
					$("table.accommodation_price_breakdown").trigger("update");
					$("table.accommodation_price_breakdown").trigger("sorton", [[[0,0]]]);

					$("table.responsive").trigger('updated');
				}
			}
		
		},
		showBookingForm : function() {
		
			$('.booking_form_adults_p').html(window.bookingRequest.adults);
			$('.booking_form_children_p').html(window.bookingRequest.children);
			$('.booking_form_room_type_p').html(window.bookingRequest.roomTypeTitle);
			$('.booking_form_date_from_p').html(window.bookingRequest.selectedDateFrom);
			$('.booking_form_date_to_p').html(window.bookingRequest.selectedDateTo);
			$('.booking_form_reservation_total_p').html(accommodations.formatPrice(window.bookingRequest.totalAccommodationOnlyPrice));
			$('.booking_form_extra_items_total_p').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));
			$('.booking_form_total_p').html(accommodations.formatPrice(window.bookingRequest.totalPrice));
		
			$('.accommodation-booking-form').show();
			
			$('html, body').animate({
				scrollTop: $('#accommodation-booking-form').offset().top
			}, 1000);			
		},
		hideBookingForm : function() {
			$('.accommodation-booking-form').hide();
		},	
		showConfirmationForm : function() {		
			$('.accommodation-confirmation-form').show();
		},
		showAccommodationScreen: function() {
			$('.lSSlideOuter').show();
			$('.inner-nav').show();
			$('.tab-content:first').show();			
		},
		hideAccommodationScreen: function() {
			$('.lSSlideOuter').hide();
			$('.inner-nav').hide();
			$('.tab-content').hide();			
		},
		selectDateFrom : function(time, dateText) {
		
			window.bookingRequest.totalPrice = 0;
			window.bookingRequest.totalAccommodationOnlyPrice = 0;
			window.bookingRequest.totalDays = 0;
			window.bookingRequest.adults = 1;
			window.bookingRequest.children = 0;
			window.bookingRequest.extraItemsTotalPrice = 0;
			window.bookingRequest.selectedTimeFrom = time;
			window.bookingRequest.selectedDateFrom = dateText;
			window.bookingRequest.selectedTimeTo = null;
			window.bookingRequest.selectedDateTo = null;

			$('.price_breakdown').hide();

			$("#selected_date_from").val(time);
			$("#selected_date_to").val(null);
			$(".date_from_text").html(dateText);
			$(".date_to_text").html(window.defaultDateToText);
			
			$(".booking-commands").show();
			$(".booking-commands .book-accommodation-reset").show();
			$(".booking-commands .book-accommodation-next").hide();
			
			$(".dates_row").show();
			$(".price_row").hide();

			accommodations.populateAvailableEndDates(accommodations.getSelectedDateFrom(), window.accommodationId, window.bookingRequest.roomTypeId, accommodations.rebindEndDates);
			
			accommodations.bindFormControls();
		},
		selectDateTo: function(time, dateText) {

			$('.price_breakdown').show();
		
			$('table.accommodation_price_breakdown thead').html('');
			$('table.accommodation_price_breakdown tbody').html('');
			$('table.accommodation_price_breakdown tfoot').html('');

			$(".date_to_text").html(dateText);
			$("#selected_date_to").val(time);
			window.bookingRequest.selectedTimeTo = time;			
			window.bookingRequest.selectedDateTo = dateText;
			
			accommodations.bindRequiredExtraItems();			
		},
		getSelectedDateFrom: function () {
			if ($("#selected_date_from").val()) {
				return accommodations.convertLocalToUTC(new Date(parseInt($("#selected_date_from").val())));
			}
			return null;			
		},
		getSelectedDateTo: function () {
			if ($("#selected_date_to").val()) {
				return accommodations.convertLocalToUTC(new Date(parseInt($("#selected_date_to").val())));
			}
			return null;
		},
		getSelectedTimeFrom: function () {
			if ($("#selected_date_from").val()) {
				return parseInt($("#selected_date_from").val());
			}
			return null;
		},
		getSelectedTimeTo: function () {
			if ($("#selected_date_to").val()) {
				return parseInt($("#selected_date_to").val());
			}
			return null;
		},
		addProductToCart : function () {
			
			var selectedDateFrom = accommodations.convertLocalToUTC(new Date(window.bookingRequest.selectedTimeFrom));
			var selectedDateTo = accommodations.convertLocalToUTC(new Date(window.bookingRequest.selectedTimeTo));
			var dateFrom = selectedDateFrom.getFullYear() + "-" + (selectedDateFrom.getMonth() + 1) + "-" + selectedDateFrom.getDate(); 
			var dateTo = selectedDateTo.getFullYear() + "-" + (selectedDateTo.getMonth() + 1) + "-" + selectedDateTo.getDate(); 
		
			if (!window.bookingRequest.roomTypeId)
				window.bookingRequest.roomTypeId = 0;
				
			var roomCount = window.bookingRequest.roomCount;
			if (!roomCount)
				roomCount = 1;
			
			var dataObj = {
				'action':'accommodation_booking_add_to_cart_ajax_request',
				'user_id' : window.currentUserId,
				'accommodation_id' : window.accommodationId,
				'room_type_id' : window.bookingRequest.roomTypeId,
				'room_count' : roomCount,
				'extra_items' : window.bookingRequest.extraItems,
				'adults' : window.bookingRequest.adults,
				'children' : window.bookingRequest.children,
				'date_from' : dateFrom,
				'date_to' : dateTo,
				'nonce' : BYTAjax.nonce
			};
			
			$.each(window.bookingFormFields, function(index, field) {
				if (field.hide !== '1') {
					dataObj[field.id] = $('#' + field.id).val();
				}
			});
			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				success:function(data) {
					
					accommodations.redirectToCart();
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});	
		},
		redirectToCart : function() {		
			top.location.href = window.wooCartPageUri;
		},
		populateAvailableDays : function(accommodationId, roomTypeId) {
		
			window.accommodationVacancyDays = [];

			var dataObj = {
				'action':'accommodation_available_days_ajax_request',
				'accommodation_id' : accommodationId,
				'room_type_id' : roomTypeId,
				'month' : window.currentMonth,
				'year' : window.currentYear,
				'nonce' : BYTAjax.nonce
			};

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				success:function(datesJson) {

					window.accommodationVacancyDays = JSON.parse(datesJson);
				},
				error: function(errorThrown) {
					console.log(errorThrown);
				}
			});
		},
		populateAvailableStartDays : function(accommodationId, roomTypeId, callDelegate) {
		
			$('.datepicker_loading').show();
			window.accommodationVacancyStartDays = [];

			var dataObj = {
				'action':'accommodation_available_start_days_ajax_request',
				'accommodation_id' : accommodationId,
				'room_type_id' : roomTypeId,
				'month' : window.currentMonth,
				'year' : window.currentYear,
				'nonce' : BYTAjax.nonce
			};

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				success:function(datesJson) {

					window.accommodationVacancyStartDays = JSON.parse(datesJson);
					
					if (typeof(callDelegate) !== 'undefined') {
						callDelegate();
					}
					
					$('.datepicker_loading').hide();
				},
				error: function(errorThrown) {
					console.log(errorThrown);
				}
			});
		},
		populateAvailableEndDates : function(startDate, accommodationId, roomTypeId, callDelegate) {
		
			$('.datepicker_loading').show();
		
			window.accommodationVacancyEndDates = [];
			
			var dataObj = {
				'action':'accommodation_available_end_dates_ajax_request',
				'accommodation_id' : accommodationId,
				'room_type_id' : roomTypeId,
				'start_date' : (startDate.getFullYear() + "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate()),
				'year' : window.currentYear,
				'month' : window.currentMonth,
				'day' : window.currentDay,
				'nonce' : BYTAjax.nonce
			};	

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				success:function(json) {
				
					// This outputs the result of the ajax request
					var availableDates = JSON.parse(json);
					var i = 0;
					for (i = 0; i < availableDates.length; ++i) {
						window.accommodationVacancyEndDates.push(availableDates[i].single_date);
					}
					
					if (typeof(callDelegate) !== 'undefined') {
						callDelegate();
					}
					
					$('.datepicker_loading').hide();
				},
				error: function(errorThrown) {
					console.log(errorThrown);
				}
			});
		},
		getAccommodationVacancyDates : function () {

			var accommodationVacancyDates = [];
			
			if (window.accommodationVacancyDays) {
				$.each(window.accommodationVacancyDays, function(index, day) {
					accommodationVacancyDates.push(day.single_date);				
				});
			}
			
			return accommodationVacancyDates;
		},
		getAccommodationVacancyStartDates: function() {
			var accommodationVacancyStartDates = [];
			
			if (window.accommodationVacancyStartDays) {
				$.each(window.accommodationVacancyStartDays, function(index, day) {
					accommodationVacancyStartDates.push(day.single_date);				
				});
			}
			
			return accommodationVacancyStartDates;
		},
		getAccommodationIsReservationOnly : function (accommodationId) {
			
			var isReservationOnly = 0;

			var dataObj = {
				'action':'accommodation_is_reservation_only_ajax_request',
				'accommodation_id' : accommodationId,
				'nonce' : BYTAjax.nonce
			};

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					isReservationOnly = parseInt(data);
				},
				error: function(errorThrown) {

				}
			});

			return isReservationOnly;
		},
		rebindEndDates : function() {

			if (typeof $('.vacancy_datepicker') !== 'undefined') {

				var selectedDateFrom = accommodations.getSelectedDateFrom();
				var year = selectedDateFrom.getFullYear();
				var month = selectedDateFrom.getMonth();
				var daysInMonth = accommodations.daysInMonth(month + 1, year);
				
				if (daysInMonth < selectedDateFrom.getDate() || window.accommodationVacancyEndDates.length === 0) {
				
					$(".date_from_text").html("");
					$("#selected_date_from").val("");
					$(".date_to_text").html(window.defaultDateToText);
					$("#selected_date_to").val("");							
					$(".dates_row").hide();
					$(".price_row").hide();
				
					accommodations.populateAvailableDays(window.accommodationId, window.bookingRequest.roomTypeId);				
					accommodations.populateAvailableStartDays(window.accommodationId, window.bookingRequest.roomTypeId, accommodations.refreshCalendar);
				}
				
				$('.vacancy_datepicker').datepicker( "refresh" );			
			}
		},
		processBooking : function() {
		
			if (typeof(window.bookingRequest) !== 'undefined') {
			
				$('#wait_loading').show();
			
				var selectedDateFrom = accommodations.convertLocalToUTC(new Date(window.bookingRequest.selectedTimeFrom));
				var selectedDateTo = accommodations.convertLocalToUTC(new Date(window.bookingRequest.selectedTimeTo));
				var dateFrom = selectedDateFrom.getFullYear() + "-" + (selectedDateFrom.getMonth() + 1) + "-" + selectedDateFrom.getDate(); 
				var dateTo = selectedDateTo.getFullYear() + "-" + (selectedDateTo.getMonth() + 1) + "-" + selectedDateTo.getDate(); 
			
				if (!window.bookingRequest.roomTypeId)
					window.bookingRequest.roomTypeId = 0;
					
				var roomCount = window.bookingRequest.roomCount;
				if (!roomCount)
					roomCount = 1;
				
				var cValS = $('#c_val_s_acc').val();
				var cVal1 = $('#c_val_1_acc').val();
				var cVal2 = $('#c_val_2_acc').val();
				
				var dataObj = {
					'action':'accommodation_process_booking_ajax_request',
					'user_id' : window.currentUserId,
					'accommodation_id' : window.accommodationId,
					'room_type_id' : window.bookingRequest.roomTypeId,
					'room_count' : roomCount,
					'extra_items' : window.bookingRequest.extraItems,
					'adults' : window.bookingRequest.adults,
					'children' : window.bookingRequest.children,
					'date_from' : dateFrom,
					'date_to' : dateTo,
					'c_val_s' : cValS,
					'c_val_1' : cVal1,
					'c_val_2' : cVal2,
					'nonce' : BYTAjax.nonce
				};
				
				$.each(window.bookingFormFields, function(index, field) {
					if (field.hide !== '1') {
						dataObj[field.id] = $('#' + field.id).val();
						$('.confirm_' + field.id + '_p').html($('#' + field.id).val());
					}
				});
				
				$('.confirm_adults_p').html(window.bookingRequest.adults);
				$('.confirm_children_p').html(window.bookingRequest.children);
				$('.confirm_date_from_p').html(window.bookingRequest.selectedDateFrom);
				$('.confirm_date_to_p').html(window.bookingRequest.selectedDateTo);
				if ($('.confirm_reservation_total_p').length > 0) {
					$('.confirm_reservation_total_p').html(accommodations.formatPrice(window.bookingRequest.totalAccommodationOnlyPrice));
				}
				if ($('.confirm_extra_items_total_p').length > 0) {
					$('.confirm_extra_items_total_p').html(accommodations.formatPrice(window.bookingRequest.extraItemsTotalPrice));		
				}
				$('.confirm_total_price_p').html(accommodations.formatPrice(window.bookingRequest.totalPrice));
							
				$.ajax({
					url: BYTAjax.ajaxurl,
					data: dataObj,
					success:function(data) {
					
						// This outputs the result of the ajax request
						if (data == 'captcha_error') {
						
							$("div.error div p").html(window.invalidCaptchaMessage);
							$("div.error").show();
						} else {
						
							var returnedId = data;
							$("div.error div p").html('');
							$("div.error").hide();
							
							accommodations.hideBookingForm();
							accommodations.showConfirmationForm();
						}
						
						$('#wait_loading').hide();
					},
					error: function(errorThrown) {
						console.log(errorThrown);
					}
				}); 
			}
		},
		firstDayInNextMonth: function(yourDate) {
			var d = new Date(yourDate);
			if (d.getMonth() == 11) {
				return new Date(d.getFullYear() + 1, 0, 1);
			} else {
				return new Date(d.getFullYear(), d.getMonth() + 1, 1);
			}
		},
		formatPrice: function( price ) {
			if (window.currencySymbolShowAfter)
				return price.toFixed(window.priceDecimalPlaces) + ' ' + window.currencySymbol;
			else
				return window.currencySymbol + ' ' + price.toFixed(window.priceDecimalPlaces);
		},
		calculateDifferenceInDays : function( date1, date2) {
			return (Date.UTC(date2.getYear(), date2.getMonth(), date2.getDate()) - Date.UTC(date1.getYear(), date1.getMonth(), date1.getDate())) / 86400000;
		},
		daysInMonth : function(month, year) {
			return new Date(year, month, 0).getDate();
		},		
		convertLocalToUTC : function (date) { 
			return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds()); 
		},
		trimTrailingComma : function(str) {
			if(str.substr(-1) === ',') {
				return str.substr(0, str.length - 1);
			}
			return str;
		},
	};
})(jQuery);