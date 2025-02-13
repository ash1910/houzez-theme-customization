<?php
$agent_display = houzez_get_listing_data('agent_display_option');

if ($agent_display != 'none') { 

$agent_array = houzez20_property_contact_form();
$agent_array = $agent_array['agent_info'][0] ?? '';

$agent_name = isset($agent_array['agent_name']) ? $agent_array['agent_name'] : '';
$agent_mobile_call = isset($agent_array['agent_mobile_call']) ? $agent_array['agent_mobile_call'] : '';
$agent_whatsapp_call = isset($agent_array['agent_whatsapp_call']) ? $agent_array['agent_whatsapp_call'] : '';
$agent_number_call = isset($agent_array['agent_mobile_call']) ? $agent_array['agent_mobile_call'] : '';
$agent_picture = $agent_array['picture'] ?? '';
if( empty($agent_number_call) ) {
	$agent_number_call = isset($agent_array['agent_phone_call']) ? $agent_array['agent_phone_call'] : '';
}

$the_query = Houzez_Query::loop_agent_properties($agent_array['agent_id']);
$agent_total_listings = $the_query->found_posts; 

$agent_company = get_post_meta( $agent_array['agent_id'], 'fave_agent_company', true );
$agent_agency_id = get_post_meta( $agent_array['agent_id'], 'fave_agent_agencies', true );
$agent_is_top_broker = get_post_meta($agent_array['agent_id'], 'fave_agent_is_top_broker', true);

$href = "";
if( !empty($agent_agency_id) ) {
    $href = ' href="'.esc_url(get_permalink($agent_agency_id)).'"';
    $agent_company = get_the_title( $agent_agency_id );
}

$total_ratings = get_post_meta($agent_array['agent_id'], 'houzez_total_rating', true); 

if( empty( $total_ratings ) ) {
	$total_ratings = 0;
}

$status = get_the_terms(get_the_ID(), 'property_status'); 
?>

<div
    class="ms-apartments-main__sidebar__single ms-apartments-main__sidebar__single--broker <?php echo $status && count($status) > 0 && $status[0]->slug == 'new-projects' ? '' : 'ms-apartments-main__sidebar__single--broker-2'; ?> <?php echo isset($agent_is_top_broker) && $agent_is_top_broker == 1 ? 'ms-apartments-main__sidebar__single--broker-top' : ''; ?>">
    
    <?php if( isset($agent_is_top_broker) && $agent_is_top_broker == 1 ) { ?>
    <!-- top broker batch -->
    <div class="ms-apartments-main__sidebar__broker-batch">
        <div class="ms-apartments-main____card__price">
            <a href="javascript:void(0)"><span> TopBroker </span></a>
        </div>
    </div>
    <?php } ?>
    <div class="ms-apartments-main__sidebar__broker-details">
        <div>
            <a href="javascript:void(0)"><img src="<?php echo esc_url($agent_picture); ?>" alt="<?php echo esc_attr($agent_name); ?>" <?php if( isset($agent_is_top_broker) && $agent_is_top_broker == 1 ) { } else { ?>style="max-width: 90px;"<?php } ?> /></a>
        </div>
        <div class="ms-apartments-main__sidebar__broker-name">
            <h6><?php echo esc_attr($agent_name); ?></h6>
            <!-- list -->
            <ul class="ms-apartments-main__sidebar__broker-details__list">
                <li>
                    <span>
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.9635 5.87072C13.8719 5.58724 13.6204 5.38589 13.3229 5.35908L9.28216 4.99217L7.68432 1.25229C7.56651 0.9782 7.29819 0.800781 7.00007 0.800781C6.70195 0.800781 6.43364 0.9782 6.31582 1.25293L4.71798 4.99217L0.67656 5.35908C0.379616 5.38653 0.128816 5.58724 0.0366355 5.87072C-0.0555452 6.15421 0.0295858 6.46514 0.254216 6.66115L3.30857 9.33984L2.40791 13.3072C2.34201 13.5989 2.45523 13.9005 2.69727 14.0754C2.82737 14.1694 2.97958 14.2173 3.13307 14.2173C3.26542 14.2173 3.39669 14.1816 3.51451 14.1111L7.00007 12.0279L10.4844 14.1111C10.7393 14.2645 11.0607 14.2505 11.3022 14.0754C11.5444 13.9 11.6575 13.5983 11.5916 13.3072L10.6909 9.33984L13.7453 6.66168C13.9699 6.46514 14.0557 6.15474 13.9635 5.87072Z"
                                fill="#FFC107" />
                        </svg>
                        <?php echo sprintf("%.2f", $total_ratings); ?>
                    </span>
                </li>
                <?php if( houzez_option('agent_view_listing') != 0 ) { ?>
                <li><a href="<?php echo esc_url($agent_array[ 'link' ]); ?>">Write a Review</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <?php if($status && count($status) > 0 && $status[0]->slug == 'new-projects' ) { ?>
        <?php if( !empty( $agent_whatsapp_call ) && houzez_option('agent_whatsapp_num', 1) ) { ?>
        <div class="ms-apartments-main__sidebar__whatsapp">
            <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> " class="ms-btn ms-btn--black">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_3124_517" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0"
                        width="21" height="21">
                        <path d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z" fill="white"></path>
                    </mask>
                    <g mask="url(#mask0_3124_517)">
                        <path
                            d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                            fill="#1B1B1B"></path>
                    </g>
                </svg>

                WhatsApp</a>
        </div>
        <?php } ?>
    <?php } else { ?>
    <div class="ms-apartments-main__sidebar__whatsapp">
        <button data-toggle="modal" data-target="#msQualityListerModal" class="ms-btn ms-btn--black">
            <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M13.7573 13.5967C13.7573 13.5967 11.9814 10.5446 11.6629 9.99715C12.0253 9.88659 12.4114 9.78896 12.5718 9.51197C12.8392 9.05044 12.3633 8.29334 12.4882 7.79865C12.6169 7.28951 13.3768 6.84895 13.3768 6.33143C13.3768 5.82723 12.5912 5.23819 12.4624 4.73204C12.3366 4.23743 12.811 3.4795 12.5428 3.01854C12.2746 2.55753 11.3813 2.5954 11.0135 2.24154C10.6352 1.87738 10.6363 0.986409 10.1799 0.74237C9.72169 0.497424 8.97695 0.992462 8.46816 0.875199C7.96517 0.759277 7.51579 0.00195312 6.9904 0.00195312C6.45722 0.00195312 5.60919 0.861017 5.47618 0.891933C4.96766 1.01015 4.22196 0.516319 3.7642 0.762303C3.30825 1.00716 3.31106 1.89822 2.93337 2.26298C2.56623 2.6175 1.67283 2.58126 1.40545 3.04279C1.1381 3.50423 1.61399 4.26117 1.48907 4.75612C1.36381 5.25233 0.57747 5.7551 0.57747 6.32897C0.57747 6.84653 1.33925 7.28571 1.46875 7.79458C1.59461 8.28919 1.12016 9.04703 1.38837 9.50817C1.53438 9.75917 1.86568 9.86221 2.19654 9.96127C2.23515 9.97281 2.30818 10.016 2.25872 10.0881C2.03236 10.4788 0.210505 13.6231 0.210505 13.6231C0.0655689 13.8731 0.183264 14.0875 0.471968 14.0997L1.88513 14.1586C2.17384 14.1708 2.536 14.3806 2.69011 14.625L3.44419 15.8216C3.59829 16.066 3.8428 16.0614 3.9877 15.8114C3.9877 15.8114 6.09761 12.1687 6.09847 12.1676C6.1408 12.1182 6.18335 12.1283 6.20372 12.1456C6.43457 12.3422 6.75639 12.5383 7.02499 12.5383C7.2884 12.5383 7.53231 12.3537 7.77358 12.1478C7.79321 12.1311 7.84099 12.0968 7.87593 12.168C7.87649 12.1691 9.98407 15.7919 9.98407 15.7919C10.1293 16.0416 10.3739 16.0458 10.5275 15.8011L11.2795 14.6031C11.4332 14.3585 11.7949 14.1479 12.0836 14.1353L13.4967 14.0737C13.7853 14.0611 13.9026 13.8464 13.7573 13.5967ZM9.32584 10.32C7.76337 11.229 5.8879 11.1316 4.45541 10.228C2.35639 8.88354 1.66574 6.10227 2.92757 3.92438C4.2038 1.72138 7.00925 0.944035 9.2321 2.15467C9.24378 2.16103 9.25532 2.1676 9.26691 2.17409C9.28338 2.18321 9.29981 2.19246 9.3162 2.2018C10.002 2.59718 10.5966 3.17099 11.0224 3.90289C12.3238 6.13988 11.5628 9.01862 9.32584 10.32Z"
                    fill="white" />
                <path
                    d="M8.96698 2.85981C8.96049 2.85604 8.95392 2.85254 8.94743 2.84882C7.76001 2.16159 6.24658 2.11135 4.97817 2.8493C3.09591 3.94436 2.45542 6.36654 3.55048 8.24876C3.88463 8.82314 4.34252 9.28164 4.87081 9.61069C4.91574 9.63914 4.96118 9.66711 5.00762 9.69401C6.89191 10.7857 9.3128 10.1408 10.4044 8.25658C11.496 6.37229 10.8513 3.95145 8.96698 2.85981ZM9.43836 5.94016L8.77858 6.58325C8.57424 6.78241 8.44651 7.17553 8.49481 7.4568L8.65056 8.36485C8.69881 8.64612 8.53165 8.76758 8.27905 8.63479L7.46353 8.20604C7.21093 8.07325 6.79761 8.07325 6.54501 8.20604L5.72953 8.63479C5.47693 8.76758 5.30973 8.64612 5.35798 8.36485L5.51373 7.4568C5.56198 7.17553 5.43425 6.78241 5.22991 6.58325L4.57018 5.94016C4.36579 5.74101 4.42969 5.54445 4.71208 5.50337L5.62381 5.37089C5.9062 5.32985 6.24061 5.08689 6.36691 4.83101L6.77465 4.00485C6.90095 3.74896 7.10763 3.74896 7.23389 4.00485L7.64167 4.83101C7.76797 5.08689 8.10233 5.32985 8.38477 5.37089L9.2965 5.50337C9.57884 5.54445 9.64271 5.74101 9.43836 5.94016Z"
                    fill="white" />
            </svg>

            Quality Lister
        </button>
        <?php if( isset($agent_is_top_broker) && $agent_is_top_broker == 1 ) { ?>
        <!-- responsive broker -->
        <button data-toggle="modal" data-target="#msResponsiveBrokerModal"
            class="ms-btn ms-btn--black ms-btn--res-broker">
            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M11.1373 2.91978V4.41312H7.8929C7.08428 4.41312 6.30879 4.73434 5.73701 5.30612C5.16523 5.87789 4.84401 6.65339 4.84401 7.46201V9.81756L3.92845 10.4753C3.85306 10.5296 3.76399 10.5617 3.67131 10.568C3.57863 10.5743 3.48604 10.5545 3.40401 10.5109C3.32149 10.4691 3.25186 10.4057 3.20254 10.3274C3.15323 10.2491 3.12609 10.1589 3.12401 10.0665V9.30645C2.89505 9.30587 2.66846 9.26005 2.45726 9.17163C2.24607 9.0832 2.05443 8.95391 1.89336 8.79119C1.73228 8.62846 1.60496 8.43551 1.51869 8.22343C1.43242 8.01134 1.38892 7.78429 1.39068 7.55534V2.91978C1.39068 2.3811 1.60467 1.86448 1.98557 1.48357C2.36648 1.10266 2.8831 0.888672 3.42179 0.888672H9.10623C9.37296 0.888672 9.63708 0.941208 9.8835 1.04328C10.1299 1.14535 10.3538 1.29496 10.5424 1.48357C10.7311 1.67218 10.8807 1.89608 10.9827 2.14251C11.0848 2.38894 11.1373 2.65305 11.1373 2.91978Z"
                    fill="white" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M13.5794 5.43164H7.89049C7.3518 5.43164 6.83518 5.64563 6.45427 6.02654C6.07337 6.40745 5.85938 6.92407 5.85938 7.46275V11.8139C5.85938 12.3525 6.07337 12.8692 6.45427 13.2501C6.83518 13.631 7.3518 13.845 7.89049 13.845H11.446L13.0816 15.0139C13.1575 15.0687 13.247 15.1015 13.3404 15.1086C13.4337 15.1157 13.5272 15.0967 13.6105 15.0539C13.6913 15.0104 13.7593 14.9464 13.8076 14.8684C13.856 14.7904 13.883 14.7011 13.886 14.6094V13.8539C14.345 13.8492 14.7836 13.6636 15.1065 13.3373C15.4294 13.0111 15.6105 12.5706 15.6105 12.1116V7.46275C15.6093 6.92443 15.3949 6.40849 15.0143 6.02784C14.6336 5.64718 14.1177 5.43281 13.5794 5.43164ZM10.5305 11.685V10.1605H9.00604L11.0372 7.62275V9.1472H12.5616L10.5305 11.685Z"
                    fill="white" />
            </svg>

            Responsive Broker
        </button>
        <?php } ?>
    </div>
    <!-- button list -->
    <div class="ms-apartments-main__card--2">
        <?php if( !empty( $agent_company ) ) { ?>
        <ul class="ms-apartments-main____card__button-list ms-apartments-main____card__button-list--2">
            <li>
                <a<?php echo $href; ?> class="ms-btn ms-btn--bordered"><?php echo esc_attr( $agent_company ); ?></a>
            </li>
        </ul>
        <?php } ?>
        <ul class="ms-apartments-main____card__button-list">
            <?php if( ! empty($agent_number_call) && houzez_option('agent_mobile_num', 1) ) { ?>
            <li>
                <a href="tel:<?php echo esc_attr($agent_number_call); ?>" class="ms-btn ms-btn--bordered">
                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.3057 16.7743C17.3057 17.0743 17.2391 17.3827 17.0974 17.6827C16.9557 17.9827 16.7724 18.266 16.5307 18.5327C16.1224 18.9827 15.6724 19.3077 15.1641 19.516C14.6641 19.7243 14.1224 19.8327 13.5391 19.8327C12.6891 19.8327 11.7807 19.6327 10.8224 19.2243C9.86406 18.816 8.90573 18.266 7.95573 17.5743C6.9974 16.8743 6.08906 16.0993 5.2224 15.241C4.36406 14.3743 3.58906 13.466 2.8974 12.516C2.21406 11.566 1.66406 10.616 1.26406 9.67435C0.864063 8.72435 0.664062 7.81602 0.664062 6.94935C0.664062 6.38268 0.764062 5.84102 0.964062 5.34102C1.16406 4.83268 1.48073 4.36602 1.9224 3.94935C2.45573 3.42435 3.03906 3.16602 3.65573 3.16602C3.88906 3.16602 4.1224 3.21602 4.33073 3.31602C4.5474 3.41602 4.73906 3.56602 4.88906 3.78268L6.8224 6.50768C6.9724 6.71602 7.08073 6.90768 7.15573 7.09102C7.23073 7.26602 7.2724 7.44102 7.2724 7.59935C7.2724 7.79935 7.21406 7.99935 7.0974 8.19102C6.98906 8.38268 6.83073 8.58268 6.63073 8.78268L5.9974 9.44102C5.90573 9.53268 5.86406 9.64102 5.86406 9.77435C5.86406 9.84102 5.8724 9.89935 5.88906 9.96602C5.91406 10.0327 5.93906 10.0827 5.95573 10.1327C6.10573 10.4077 6.36406 10.766 6.73073 11.1993C7.10573 11.6327 7.50573 12.0743 7.93906 12.516C8.38906 12.9577 8.8224 13.366 9.26406 13.741C9.6974 14.1077 10.0557 14.3577 10.3391 14.5077C10.3807 14.5243 10.4307 14.5493 10.4891 14.5743C10.5557 14.5993 10.6224 14.6077 10.6974 14.6077C10.8391 14.6077 10.9474 14.5577 11.0391 14.466L11.6724 13.841C11.8807 13.6327 12.0807 13.4743 12.2724 13.3743C12.4641 13.2577 12.6557 13.1993 12.8641 13.1993C13.0224 13.1993 13.1891 13.2327 13.3724 13.3077C13.5557 13.3827 13.7474 13.491 13.9557 13.6327L16.7141 15.591C16.9307 15.741 17.0807 15.916 17.1724 16.1243C17.2557 16.3327 17.3057 16.541 17.3057 16.7743Z"
                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10"></path>
                        <path
                            d="M14.4167 8.9987C14.4167 8.4987 14.025 7.73203 13.4417 7.10703C12.9083 6.53203 12.2 6.08203 11.5 6.08203"
                            stroke="#1B1B1B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.3333 8.99935C17.3333 5.77435 14.725 3.16602 11.5 3.16602" stroke="#1B1B1B"
                            stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Call</a>
            </li>
            <?php } ?>
            <li>
                <a href="javascript:void(0)" class="ms-btn ms-btn--bordered" data-toggle="modal" data-target="#mobile-property-form">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.6719 17.5827H6.33854C3.83854 17.5827 2.17188 16.3327 2.17188 13.416V7.58268C2.17188 4.66602 3.83854 3.41602 6.33854 3.41602H14.6719C17.1719 3.41602 18.8385 4.66602 18.8385 7.58268V13.416C18.8385 16.3327 17.1719 17.5827 14.6719 17.5827Z"
                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M14.6615 8L12.0531 10.0833C11.1948 10.7667 9.78645 10.7667 8.92812 10.0833L6.32812 8"
                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    Email</a>
            </li>
            <?php if( !empty( $agent_whatsapp_call ) && houzez_option('agent_whatsapp_num', 1) ) { ?>
            <li>
                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> " class="ms-btn ms-btn--bordered">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <mask id="mask0_3124_517" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0"
                            width="21" height="21">
                            <path d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z" fill="white"></path>
                        </mask>
                        <g mask="url(#mask0_3124_517)">
                            <path
                                d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path
                                d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                fill="#1B1B1B"></path>
                        </g>
                    </svg>

                    WhatsApp</a>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <!-- bottom -->
    <ul class="ms-apartments-main__sidebar__bottom-list">
        <li>
            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.94754 1.4101L9.90023 2.12323C10.1608 2.31826 10.435 2.41807 10.76 2.43616L11.9482 2.50226C12.6596 2.54182 13.2375 3.02666 13.4 3.72048L13.6714 4.87913C13.7456 5.19601 13.8916 5.44876 14.1289 5.67148L14.9966 6.48588C15.5162 6.97351 15.6471 7.71635 15.3257 8.35229L14.7889 9.41432C14.642 9.70479 14.5914 9.9922 14.63 10.3154L14.7712 11.497C14.8558 12.2045 14.4786 12.8577 13.8236 13.1383L12.7297 13.6068C12.4305 13.7349 12.207 13.9225 12.0288 14.1949L11.3775 15.1908C10.9875 15.7871 10.2787 16.0451 9.59661 15.839L8.45748 15.4947C8.14595 15.4006 7.85411 15.4006 7.54254 15.4947L6.40342 15.839C5.72136 16.0451 5.01254 15.7871 4.62254 15.1908L3.9712 14.1949C3.79304 13.9225 3.56948 13.7349 3.27032 13.6068L2.17632 13.1382C1.52132 12.8577 1.14417 12.2044 1.22873 11.4969L1.36998 10.3153C1.40861 9.99213 1.35792 9.70473 1.21111 9.41426L0.674262 8.35223C0.352824 7.71632 0.483793 6.97348 1.00336 6.48582L1.87107 5.67141C2.10839 5.4487 2.25429 5.19595 2.32851 4.87907L2.59995 3.72041C2.76245 3.02663 3.34029 2.54179 4.05176 2.5022L5.23992 2.4361C5.56489 2.41804 5.83914 2.3182 6.0997 2.12316L7.05239 1.41004C7.62279 0.983102 8.37711 0.983102 8.94754 1.4101ZM7.39223 8.90757L6.35836 7.8737C6.09039 7.60573 5.65573 7.60573 5.38779 7.8737C5.11982 8.14166 5.11982 8.57629 5.38779 8.84426L6.90845 10.3649C7.17642 10.6328 7.61107 10.6329 7.87901 10.3649C8.79557 9.44829 9.70176 8.52141 10.6141 7.60063C10.8801 7.33213 10.8793 6.89888 10.6111 6.63223C10.3429 6.36551 9.90845 6.36626 9.64214 6.63523L7.39223 8.90757ZM7.99995 3.38863C6.58867 3.38863 5.31095 3.9607 4.38607 4.88557C3.4612 5.81048 2.88914 7.08816 2.88914 8.49945C2.88914 9.91073 3.4612 11.1884 4.38607 12.1133C5.31095 13.0382 6.58867 13.6103 7.99995 13.6103C9.41123 13.6103 10.6889 13.0382 11.6138 12.1133C12.5387 11.1884 13.1108 9.91073 13.1108 8.49945C13.1108 7.08816 12.5387 5.81045 11.6138 4.88557C10.6889 3.9607 9.41123 3.38863 7.99995 3.38863ZM11.2603 5.2391C10.4259 4.40473 9.2732 3.88863 7.99995 3.88863C6.7267 3.88863 5.57398 4.40473 4.73961 5.2391C3.90523 6.07348 3.38914 7.2262 3.38914 8.49945C3.38914 9.7727 3.90523 10.9254 4.73961 11.7598C5.57398 12.5942 6.7267 13.1103 7.99995 13.1103C9.2732 13.1103 10.4259 12.5942 11.2603 11.7598C12.0947 10.9254 12.6107 9.77273 12.6107 8.49948C12.6108 7.2262 12.0947 6.07348 11.2603 5.2391Z"
                    fill="#1B1B1B" />
            </svg>

            <a href="javascript:void(0)"> <?php echo esc_attr($agent_total_listings); ?> <span>projects</span></a>
        </li>
        <?php if( houzez_option('agent_view_listing') != 0 ) { ?>
        <li>
            <a href="<?php echo esc_url($agent_array[ 'link' ]); ?>" target="_blank"><?php echo houzez_option('spl_con_view_listings', 'View listings'); ?></a>
        </li>
        <?php } ?>
    </ul>
</div>

<div class="modal fade mobile-property-form" id="mobile-property-form">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="modal-body">
				<?php get_template_part('property-details/agent-form'); ?>
			</div>
		</div>
	</div>
</div>

<?php } ?>