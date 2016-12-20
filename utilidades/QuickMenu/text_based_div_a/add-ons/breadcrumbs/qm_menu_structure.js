/* 

     Note! 

         When modifying the menu structure in this format be very careful!!!!  Each line must end with a '\'
         character and cannot have any spaces or tabs after it.  If a line is missing the '\' or there are
         spaces after a '\' the menu will fail to load. Single quotes (') must be escaped with a '\'. Here
         is a sample single quote in a menu item... <a href="../../../../../recursos/QuickMenu/text_based_div_a/add-ons/breadcrumbs/mylink.html">The Internet\'s</a>
         

         This template uses this structure to serve multiple pages from on external JavaScript file on the
         client side.  For ease of customization we recommend  using the inline method as presented in most
         of the templates in this download or use a server side include.

*/



document.write('\
\
<div id="qm0" class="qmmc">\
<a href="company.html">Company</a>\
\
		<div>\
		<a href="cust_service.html">Customer Service</a>\
		<a href="tech_support.html">Tech. Support</a>\
		<a href="product_overview.html">Product Overviews</a>\
\
			<div style="width:7em;">\
			<a href="im_overview.html">Infnite Menus</a>\
			<a href="qm_overview.html" style="border-bottom-width:0px;">QuickMenu</a>\
			</div>\
\
		<a href="customers.html" style="border-bottom-width:0px;">Customers</a>\
\
			<div>\
			<a href="fed-ex.html">Fex-Ex</a>\
			<a href="tyco.html">Tyco</a>\
			<a href="bestbuy.html">Best Buy</a>\
			<a href="kofax.html">Kofax</a>\
			<a href="rayovac.html">Rayovac</a>\
			<a href="bea.html">BEA</a>\
			<a href="polycom.html">Polycom</a>\
			<a href="act.html">Act</a>\
			<a href="delta.html" style="border-bottom-width:0px;">Delta</a>\
			</div>\
\
		<a href="../../../../../recursos/QuickMenu/text_based_div_a/add-ons/breadcrumbs/who_we_are.html" style="border-bottom-width:0px;">Who We Are</a>\
\
		</div>\
\
	<a href="quickmenu.html">QuickMenu</a>\
\
		<div>\
		<a href="qm_specs.html">Specifications</a>\
		<a href="qm_support.html">Browser Support</a>\
		<a href="qm_samples.html">Sample Menus</a>\
		<a href="qm_download.html" style="border-bottom-width:0px;">Download</a>\
		</div>\
\
	<a href="infinite_menus.html">Infinite Menus</a>\
\
		<div>\
		<a href="im_specs.html">Specifications</a>\
		<a href="im_support.html">Browser Support</a>\
		<a href="im_screenshots.html">Screen Shots</a>\
		<a href="im_download.html" style="border-bottom-width:0px;">Download</a>\
		</div>\
\
	<a style="border-right-width:1px;" href="buy_now.html">Buy Now!</a>\
\
<span class="qmclear"> </span></div>\
');
