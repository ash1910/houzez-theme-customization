<?php
global $post;
$mcal_down_payment = '';
$currency_symbol = currency_maker();
$currency_symbol = $currency_symbol['currency'];
$mcal_terms = houzez_option('mcal_terms', 12);
$mcal_down_payment = houzez_option('mcal_down_payment', 15);
$mcal_interest_rate = houzez_option('mcal_interest_rate', 3.5);
$mcal_prop_tax_enable = houzez_option('mcal_prop_tax_enable', 1);
$mcal_prop_tax = houzez_option('mcal_prop_tax', 3000);
$mcal_hi_enable = houzez_option('mcal_hi_enable', 1);
$mcal_hi = houzez_option('mcal_hi', 1000);
$mcal_hoa_enable = houzez_option('mcal_hoa_enable', 1);
$mcal_hoa = houzez_option('mcal_hoa', 250);
$mcal_pmi_enable = houzez_option('mcal_pmi_enable', 1);
$mcal_pmi = houzez_option('mcal_pmi');
$property_price = get_post_meta($post->ID, 'fave_property_price', true); 
$property_price = intval($property_price);

if ( class_exists( 'FCC_Rates' ) && houzez_currency_switcher_enabled() && isset( $_COOKIE[ "houzez_set_current_currency" ] ) ) {

    $currency_data = Fcc_get_currency($_COOKIE['houzez_set_current_currency']);
    $currency_symbol = $currency_data['symbol'];

    if( function_exists('houzez_get_plain_price') ) {
	    $property_price = houzez_get_plain_price($property_price );
	}
}

if($property_price == 0) {
	$mcal_terms = $mcal_down_payment = $mcal_interest_rate = $mcal_prop_tax = $mcal_hi = $mcal_pmi = $mcal_hoa = $property_price = '';
}

?>

<div class="ms-apartments-main__mortgage-calculator">
	<div class="ms-apartments-main__mortgage-calculator__outputs">
		<div class="mortgage-calculator-chart flex-fill">
			<div class="mortgage-calculator-monthly-payment-wrap">
				<div id="m_monthly_val" class="mortgage-calculator-monthly-payment"></div>
				<div class="mortgage-calculator-monthly-requency"><?php echo houzez_option('spc_monthly', 'Monthly'); ?></div>
			</div>

			<canvas id="mortgage-calculator-chart" width="300" height="300"></canvas>


		</div><!-- mortgage-calculator-chart -->

		<ul class="ms-apartments-main__mortgage-calculator__outputs__list mortgage-calculator-data">
			<li class="ms-apartments-main__mortgage-calculator__outputs__list__item mortgage-calculator-data-1 stats-data-01">
				<p><span></span> Down Payment</p>
				<h6 id="downPaymentResult"></h6>
			</li>
			<li class="ms-apartments-main__mortgage-calculator__outputs__list__item">
				<p><span></span> Loan Amount</p>
				<h6 id="loadAmountResult"></h6>
			</li>
			<li
				class="ms-apartments-main__mortgage-calculator__outputs__list__item ms-apartments-main__mortgage-calculator__outputs__list__item--mor-payment">
				<p><span></span> Monthly Mortgage Payment</p>
				<h6 id="monthlyMortgagePaymentResult"></h6>
			</li>
			<li
				class="ms-apartments-main__mortgage-calculator__outputs__list__item ms-apartments-main__mortgage-calculator__outputs__list__item--tax">
				<p><span></span> Property Tax</p>
				<h6 id="monthlyPropertyTaxResult"></h6>
			</li>
			<li
				class="ms-apartments-main__mortgage-calculator__outputs__list__item ms-apartments-main__mortgage-calculator__outputs__list__item--insurance">
				<p><span></span> Home Insurance</p>
				<h6 id="monthlyHomeInsuranceResult"></h6>
			</li>
			<li
				class="ms-apartments-main__mortgage-calculator__outputs__list__item ms-apartments-main__mortgage-calculator__outputs__list__item--pmi">
				<p><span></span> PMI</p>
				<h6 id="monthlyPMIResult"></h6>
			</li>

			<li
				class="ms-apartments-main__mortgage-calculator__outputs__list__item ms-apartments-main__mortgage-calculator__outputs__list__item--hoa-fees">
				<p><span></span> Monthly HOA Fees</p>
				<h6 id="monthlyHOAResult"></h6>
			</li>
		</ul>
	</div>
	<div class="ms-apartments-main__mortgage-calculator__inputs">
		<form class="ms-filter__modal__form" id="houzez-calculator-form" method="post">
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__search-loaction" for="">Total Amount</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__search-loaction"><?php echo esc_attr($currency_symbol);?></label>
						<input id="homePrice" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_total_amt', 'Total Amount'); ?>" value="<?php echo intval($property_price); ?>">
					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__down-payment" for="">Down Payment</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__down-payment"><i class="icon-calendar_balck_fill"></i></label>
						<input id="downPaymentPercentage" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_down_payment', 'Down Payment'); ?>" value="<?php echo esc_attr($mcal_down_payment); ?>">
					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__interest-rate" for="">Interest Rate</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__interest-rate">%</label>
						<input id="annualInterestRate" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_ir', 'Interest Rate'); ?>" value="<?php echo esc_attr($mcal_interest_rate); ?>">

					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__loan-terms" for="">Loan Terms (Years)</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__loan-terms"><i class="icon-calendar_balck_fill"></i></label>
						<input id="loanTermInYears" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_load_term', 'Loan Terms (Years)'); ?>" value="<?php echo esc_attr($mcal_terms); ?>">

					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__property-tax" for="">Property Tax</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__property-tax"><i class="icon-tax"></i></label>
						<input id="annualPropertyTaxRate" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_prop_tax', 'Property Tax'); ?>" value="<?php echo esc_attr($mcal_prop_tax); ?>">

					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__home-insurance" for="">Home Insurance</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__home-insurance"><?php echo esc_attr($currency_symbol);?></label>
						<input id="annualHomeInsurance" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_hi', 'Home Insurance'); ?>" value="<?php echo esc_attr($mcal_hi); ?>">

					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__hoa-fees" for="">Monthly HOA Fees</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__hoa-fees"><?php echo esc_attr($currency_symbol);?></label>
						<input id="monthlyHOAFees" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_hoa', 'Monthly HOA Fees'); ?>" value="<?php echo esc_attr($mcal_hoa); ?>">

					</div>
				</div>
			</div>
			<div class="ms-input__wrapper">
				<div class="ms-input__wrapper__inner">
					<label for="ms-hero__pmi" for="">PMI</label>
					<div class="ms-input ms-input--serach">
						<label for="ms-hero__pmi"><i class="icon-calendar_balck_fill"></i></label>
						<input id="pmi" type="text" class="form-control" placeholder="<?php echo houzez_option('spc_pmi', 'PMI'); ?>" value="<?php echo esc_attr($mcal_pmi); ?>">
						</div>
				</div>
			</div>
		</form>
	</div>
</div>