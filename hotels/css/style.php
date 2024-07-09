<?php 


?>

<style>


body{
      font-family: 'Montserrat', sans-serif;
}
/********index********/
.container_index_hotel{background: rgba(0, 0, 0, 0.34);margin-top:100px;}
.container_index_hotel label{color:#fff!important;font-size:12px;margin-top:10px; margin-bottom:10px;  }
.button_add_roo{background-color:white!important;
 box-shadow:none!important;border:0px solid black!important;
 width:100%;outline:none!important;
font-size:11px;height:30px;position:relative;text-align: left;}
.collapse_ful_all{width:100%;margin-top:10px;z-index:9999;box-shadow:0 0 16px 10px rgba(0,0,0,.19);background-color:white;padding:10px;
                                                color:<?php echo LIGHT_DARK; ?>!important;position:absolute;}
.submi_in_index{width:100px!important;float:right;}
 .section_rooms_All{position: relative;}
/*******************/
.padding0{padding:0px;}
.mar_for_12{margin-top:20px;}
.section_room{position: relative}


.extraRateLunch{   height: 14px;width:15px;}
.extraRateDinner{ height: 14px;width:15px;}
button{outline:none!important
	;}
	ul, label {
    margin: 0;
    padding: 0;
}
.clearfix {
    float: none; 
    clear: both;
}
.margin_topbot_10{margin-top:10px;margin-bottom: 10px;}
p{    border: 0;
    font-family: inherit;
    font-size: 15px;
    font-style: inherit;
    font-weight: inherit;
    margin: 0;
    outline: 0;
    padding: 0;
    vertical-align: baseline;}

.content_pax ul{padding:5px 0;margin:0 0;}
.areaname{font-size: 13px;color:<?php echo DARK; ?>;}
.detail_pax p {
   
    
    font-size: 11px!important;
    font-weight:bold;
 
}

.detail_pax_p{


    color:<?php echo LIGHT_DARK; ?>!important;

    border-bottom:1px solid  rgba(0, 0, 0, 0.21);
;       }

.pax_container {
    background-color:rgba(229, 230, 235, 0.52);
padding:5px 10px;
    box-shadow: 0px 0px 4px 0px rgb(114, 114, 114);
    position: relative;
   
    margin-bottom: 10px;
}

.outer_div_search {
    height: auto;
   
    width: 100%;
}

.rooms_in_hotel {
    outline: none!important;
    font-size: 10px;
    width: 70px;
    color: white!important;
    background-color: <?php echo DARK; ?>;
    border: 1px solid <?php echo DARK; ?>;
}

.detail_pax p span {
    font-size: 12px;
}
.removepax {
    cursor:pointer;
    position: absolute;
    z-index:99999;
    right:3px;
    top:2px;
    font-size: 11px;
}
.minimize {
    position: absolute;
    right: 19px;
    bottom: 4px;
    z-index: 999;
    color: #777;
    font-size: 12px;
}



.pagination li {
    color:<?php echo DARK; ?>;
    border-radius: 50%;
    padding: 0px 4px;
    font-size: 11px;
}
 .pagination li.actives {
    background-color: <?php echo DARK; ?>;
    color: #fff;
}
.pagination li:hover,
.minimize:hover {
    cursor: pointer;
}
.head_title_room {
    font-size: 11px!important;
}
.hotelname {
    background: url(../images/arrow.png) no-repeat 96% center #ffffff;
}
select {
	background: url(../images/arrow.png) no-repeat 92% center #ffffff;
    color: <?php echo DARK; ?>!important;
    font-size: 12px;
    outline: none;
    border: 1px solid <?php echo LIGHT; ?>;
    border-radius: 2px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    -o-border-radius: 0px;
    -webkit-appearance: none;
}

.icon_for_rel {
    position: absolute;
    top: 9px;
    left: 13px;
}

.checkbooking .datepi {
    padding-left: 34px;
    border-radius: 0px;
}


.txtCenter {
    text-align: center;
}

.offerBlk {
    background: <?php echo HIGHLIGHT; ?>;
    color: #fff;
    float: left;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 10px;
    padding-right: 15px;
    font-weight: bold;
    position: relative;
    font-style: normal;
    left: 0;
    border-bottom-left-radius: 0px;
    -o-border-bottom-left-radius: 0px;
    -webkit-border-bottom-left-radius: 0px;
    -moz-border-bottom-left-radius: 0px;
}

.offerBlk :after {
    position: absolute;
    content: '';
    width: 0;
    height: 0;
    border-top: 17px solid transparent;
    border-right: 11px solid #fff;
    border-bottom: 13px solid transparent;
    top: 0;
    right: 0;
}
.done {
    cursor:pointer;
    float: right;
    font-size: 11px;
    color: <?php echo DARK; ?>;
}
.posrelv {
    width: 300px;
    margin-left: -22px;
    margin-top: -10px;
}

.checkbooking {
    position: relative;
    border: 1px solid <?php echo LIGHT; ?>;
    padding: 20px;
    margin-top: 10px;
}

.checkbooking label {
    color: <?php echo DARK; ?>;
}
.totalhotel i, .totalhotel span {
    font-size: 36px;
    color:<?php echo HIGHLIGHT; ?>;

}
.book_button_hotel input {
   
}

.book_button_hotel {
    text-align: right;
    margin-top: 5px;
}
.rooms_repeat input {
    width: 100px;
}
.btn-red {
	outline:none!important;
    width: 100%;
    border: 1px solid <?php echo DARK; ?>;
    color: white!important;
    background-color: <?php echo LIGHT_DARK; ?>!important;
}
.book_iconall a {
     color: <?php echo LIGHT_DARK; ?> !important;
    margin-right: 15px;
    font-size: 28px;
}
.checkbooking label {
    color: <?php echo DARK; ?>;
}

.chdet {
    font-size: 14px;
    display: block;
    color: <?php echo DARK; ?>;
}

.chval {
    font-size: 20px;
    display: block;
    color: <?php echo DARK; ?>;
}
.tab-hotelbooking .nav-tabs>li>a {
    color: <?php echo LIGHT_DARK; ?>;
}
.rooms_repeat {
    padding: 15px;
    border: 1px solid #fff;
}

.tab-hotelbooking .tab-content {
    background-color: <?php echo LIGHT; ?>;
}

.tab-hotelbooking .nav-tabs>li.active>a {
    background-color: <?php echo LIGHT; ?>;
}

.tab-hotelbooking .nav-tabs>li>a {
    color: <?php echo DARK; ?>;
}

.tab-content .tab-pane li {
    float: none !important;
}

.rooms_repeat ul, .review_hotel ul {
	list-style-type:none;
    padding: 10px 0px;
    color: <?php echo DARK; ?>;
}

.rooms_repeat .glyphicon {
    margin-right: 10px;
    color: <?php echo LIGHT; ?>;
    font-size: 11px;
}
.custonlink {
    background-color: transparent;
    border: 0px none;
}

.similar_hotels a {
    background: rgba(0, 0, 0, 0.59);
    background-size: cover;
    position: absolute;
    bottom: 0px;
    width: 100%;
    padding: 15px;
    color: #fff;
    left: 0;
}

.similar_hotels .price {
    position: absolute;
    top: 0px;
    right: 0px;
    background-color: <?php echo LIGHT; ?>;
    padding: 4px;
    color: <?php echo DARK; ?>;
    text-align: center;
}
.stars .glyphicon-star {
    color: #FFD700;
}

.botmbg {
    background-color: <?php echo LIGHT; ?>;
    padding: 5px;
}

.similar_hotels {
    position: relative;
    border: 1px solid <?php echo LIGHT; ?>;
}

.botmbg input {
    width: 55px!important;
}
.similar_hotels{
	height: 30%;
	
}

input {
	    outline: none;
	 
    margin: 1px;
   
   height: 30%;
    height: 30px;}

    .total_guest_room {
    font-size: 11px;
    color: #808080;
}



.form-control {
	outline:none!important;
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: <?php echo DARK; ?>;
    background-color: #fff;
    background-image: none;
    border: 1px solid <?php echo LIGHT; ?>!important;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075)!important;
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}



.hotel_photos{padding-bottom: 10px;}
.contains_books{padding:20px 30px; box-shadow:1px 1px 9px <?php echo LIGHT; ?>;}
.jssor_1_ssty{position:relative;margin:0 auto;top:0px;left:0px;width:809px;height:150px;overflow:hidden;visibility:hidden;}
.loader_loading{position:absolute;top:0px;left:0px;background:url('images/loading.gif') no-repeat 50% 50%;background-color:rgba(0, 0, 0, 0.7);}
.secode_gall{cursor:default;position:relative;top:0px;left:0px;width:809px;height:150px;overflow:hidden;}
.jssorb03{bottom:10px;right:10px;}
.prototype_class{width:21px;height:21px;}
.banner_rotaror{display:none;}
.jssora03l{top:0px;left:8px;width:55px;height:55px;}
.jssora03r{top:0px;right:8px;width:55px;height:55px;}
.traveller_photos{padding-bottom: 10px;}
.jsssor_s_class{position:relative;margin:0 auto;top:0px;left:0px;width:809px;height:150px;overflow:hidden;visibility:hidden;}
.loading_cls_img{position:absolute;top:0px;left:0px;background:url('images/loading.gif') no-repeat 50% 50%;background-color:rgba(0, 0, 0, 0.7);}
.secode_gall_cls{cursor:default;position:relative;top:0px;left:0px;width:809px;height:150px;overflow:hidden;}
.wordpress_rotator{display:none;}
.jssorb03{bottom:10px;right:10px;}
.prototype_cls{width:21px;height:21px;}
.jssora03l{top:0px;left:8px;width:55px;height:55px;}
.jssora03r{top:0px;right:8px;width:55px;height:55px;}
.recomend_agency{   font-size: 20px;}
.recomend_agency span{font-size: 35px}
.based_on_agency{padding-top: 15px; text-align: right;}
.based_on_a1{ color:#000;  padding: 2px 9px;   border-radius: 7px;    background-color: #fff;    border: 1px solid <?php echo HIGHLIGHT; ?>;    margin-right: 9px;}
.based_on_a2{color:#000;}
.checkbooking{position: relative;}
.button_guest_text{background-color: white!important;border: 1px solid <?php echo LIGHT; ?>!important;width: 100%;outline: none!important;font-size: 11px;height: 33px;position: relative;text-align: left;margin-top: 1px;border-radius: 0px; box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);}

.collapsing_Search_text{width:100%;margin-top:10px;z-index:9999;box-shadow:0 0 16px 10px rgba(0,0,0,.19);background-color:white;padding:10px;
                    color:<?php echo DARK;?>!important;position:absolute;}
 .noroomsflags{ margin-top:20px; padding: 0px;}
.hotelname{ border-radius:0px !important;    padding-left: 40px;}
.noroomsflagss{margin-top:20px; padding: 0px;}
.totalhotels{margin-top: 20px;text-align: center;}
.totalhotel{padding-left: 23px;}
.subtotal_guest{font-size: 12px; padding-left: 10px;}
.subtotal_guest span{font-size: 8px;}

.container_for_bookicon{padding:20px 30px; box-shadow:1px 1px 9px <?php echo LIGHT; ?>; margin-top: 10px;margin-bottom: 10px;}
.bookicon_for_inner{padding:10px 0px}
.colapsing_full_tab{border: 1px solid <?php echo LIGHT; ?>;box-shadow: 1px 1px 24px <?php echo LIGHT; ?>;}

.contents_for_tab{padding: 20px;}
.roomname_type{font-size: 19px;}
.roomedetails{margin-top:90px;}
.roomedetails a{text-decoration:none;color:<?php echo LIGHT_DARK; ?>;}
.text-success{font-size:16px;line-height: 31px;}
.custonlink{font-size:12px;}
.custonlink -{   padding-right: 6px;}
.closing_modal{   position: absolute;
    right: -12px;
    top: -17px;
    color: #fff!important;
  
    opacity: 1!important;}
    .totalhotel i{left:0px}
    .room_details{     box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    background-color: rgb(255, 255, 255);padding-bottom: 20px;margin-top:10px;}

    .contain_per_night{padding:20px 30px; box-shadow:1px 1px 9px <?php echo LIGHT; ?>;     margin-top: 10px;margin-bottom: 10px;}
    .stars_for_all{    float: left;}
    .button_in_lasts{float: right;}

/********************************/

















.breadcrum_bg
{
	background-color: <?php echo MEDIUM; ?>;
}

button{outline:none!important
    ;}
.modal-header {
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
    background-color: <?php echo MEDIUM; ?>;
    border-radius: 5px;
}
.rooms_repeat ul, .review_hotel ul {
    padding: 10px 0px;
    color: <?php echo DARK; ?>;
}

ul, label {
    margin: 0;
    padding: 0;
}
#tabs label, label {
    font-weight: normal;
    font-size: 14px;
}
ol, ul {
    list-style: none;
    margin: 0;
    text-decoration: none;
}
.review_hotel ul span {
    padding-left: 11px;
    padding-right: 10px;
}

.review_hotel li, .review_hotel li span {
    font-size: 12px;
}

.review_hotel li {
    float: left;
}

.review_hotel li, .review_hotel li span {
    font-size: 12px;
}

.custonlink {
    background-color: transparent;
    border: 0px none;
}
.span_price_value {
    text-align: right;
}

.numb {
    font-size: 19px;
    background-color: <?php echo LIGHT; ?>;
    border-color: <?php echo LIGHT; ?>;
    padding: 12px 19px;
    border-radius: 32px;
}
.review_hotel label, .payment_colm label {
    color: #999;
}
.firstname_hotel {
    width: 75%;
}
.select_mr_optio {
    width: 21%;
}

#tabs input, input {
    outline: none;
    color: #000;
    margin: 1px;
    width: 100%;
    height: 30px;
    transition: all .5s;
    text-align: left;
    border-radius: 3px;
    border: 1px solid rgb(204, 204, 204);
    font-size: 11px;
    text-transform: capitalize;
    padding-left: 10px;
}

select {
    background: url(../images/arrow.png) no-repeat 97% center #ffffff!important;
    color: <?php echo DARK; ?>!important;
    font-size: 12px;
    outline: none;
    border: 1px solid <?php echo LIGHT; ?>;
    border-radius: 2px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    -o-border-radius: 0px;
    -webkit-appearance: none;
}

#tabs select, select {
    outline: none;
    color: #000;
    margin: 1px;
    width: 100%;
    height: 30px;
    transition: all .5s;
    text-align: left;
    border-radius: 0px;
    border: 1px solid rgb(204, 204, 204);
    font-size: 12px;
    text-transform: capitalize;
    box-shadow: rgb(221, 221, 221) 0px 1px 4px inset;
    padding: 1px;
}
.margintopbottom {
    margin: 10px 0px;
}
.textareacls {
    line-height: inherit;
    width: 100%;
    border: 1px solid <?php echo LIGHT; ?>!important;
    border-radius: 3px;
    font-size: 11px;

}

.btn-red {
    width: 100%;
    border: 1px solid <?php echo DARK; ?>;
    color: white;
    background-color: <?php echo LIGHT_DARK; ?>;
}

.book_guestdetails input {
    width: 100px;
    
}
.review_hotel label, .payment_colm label {
    color: #999;
}
.amount p {
    padding: 15px 0;
}
.amount {
    color: <?php echo MEDIUM; ?>;
}
.amount_inner p {

    color: #777;
    margin-top: 18px;
    margin-bottom: 10px;
}

.disp_payment label {
    margin-top: 10px;
}

.custo_det_car label {
    color: <?php echo DARK; ?>;
    font-size: 13px;
   
}

.credit_tab_inner > li.active > a, .credit_tab_inner > li.active > a:focus, .credit_tab_inner > li.active > a:hover {
    color: #fff;
    background-color: <?php echo MEDIUM; ?>!important;
    border: 0px solid white;
}
.credit_tab_inner>li >a {
    font-size: 12px;
    border: 1px solid white;
    margin-right: 0px;
    border: 0px;
    color: #000;
    font-weight: 500;
    border-radius: 0px;
}

.credit_tab_inner > li {
    width: 100%;
    margin-bottom: 10px;
    background-color: #ddd;
}

.credit_tab_inner {
    border-bottom: none;
}

.details_block select {
    margin-top: 10px;
}

.custo_det_car select {
    color: rgba(85, 85, 85, 0.76)!important;
    height: 29px!important;
    padding-left: 10px;
}
.notess p {
    font-size: 12px;
    padding: 10px 0 0 15px;
}

.credit_tab_inner .hvr-bounce-to-right:before {
    background: <?php echo MEDIUM; ?>;
}

.input_groups_edits {
    margin-top: 10px!important;
}

.input_groups_edits span {
    background-color: #fff;
    border-radius: 0px;
}

.input_groups_edits input {
    border-right: none!important;
}

.custo_det_car .form-control {
    margin: 0 0!important;
    font-size: 12px;
    height: 29px;
    border-radius: 0px;
}
.error {
    font-size: 10px;
    color: red;
    display: NONE;
 
}
.totalhotel p
{
	font-size:12px;
}
.book_room
{
	margin-top: 39px;
    margin-left: 27px;
}
.totalhotel .fa-rupee:before, .totalhotels .fa-inr:before
{
	    font-size: 32px;
}
.bg-faded
{
	 background-color: <?php echo LIGHT_DARK;?>;
    border-color:transparent;
    border-radius: 0px;
}
.nav-link{
	font-size: 14px;

}
.navbar-light .navbar-nav .active>.nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.open, .navbar-light .navbar-nav .open>.nav-link
{
	color:#fff;
}

@media (min-width: 992px){
.navbar-toggleable-md .navbar-nav .nav-link{
       padding-right: 20px;
    padding-left: 20px;
}
}
.navbar
{
	margin-bottom: 0px;
	padding:0px;
}
.navbar-light .navbar-nav .nav-link
{
		color:<?php echo LIGHT;?>;
}
.navbar-light .navbar-nav .nav-link:hover
{
	color:#fff;
		background-color: <?php echo MEDIUM;?>;
}
select.form-control:not([size]):not([multiple]) {
    height: 32px;
}
.card_menu .nav
{
display: block;
}
</style>