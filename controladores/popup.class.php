<?php
/*******************************************************************************
* Software: TEXT                                                     	*
* Version:  0.04                                                       	*
* Date:     2006-04-05                                             	*
* Author:   Omar Salazar                                         	*
* License:  Freeware                                          	     	*
*                                                                         	    	*
* Cambia esta vaina como te de la gana.           		    *
*******************************************************************************/

if(!class_exists('popup'))
{
	define('POPUP_VERSION','0.04');
	define('POPUP_ULTIMA_ACTUALIZACION','13NOV07');		
	
	class popup
	{
		var $id													=		'';
		var $titulo											=		'';
		var $class_div										=		'listing';
		var $class_window								=		'mac_os_x';
		var $width											=		604;
		var $height											=		320;
		var $resizable										=		'false';
		var $minimizable									=		'false';
		var $maximizable									=		'false';	
		var $bloquear_pagina							=		'true';	
		var $opacidad										=		1;	
		var $constructor									=		'controles/pop_grid.php';
		
		function string_php_to_url($cadena_original)
		{
			$cadena_final	=	urlencode($cadena_original);
			$cadena_final	=	str_replace(array('%0D','%0A','%09'),'',$cadena_final);
			$cadena_final	=	urldecode ($cadena_final);
			return $cadena_final;
		}
		
		function show($id,$titulo,$variables='')
		{
			$this->id=$id;
			$this->titulo=$titulo;
			echo "		
										<!--INICIO POPUP ".date("YmdHis")."--> 
										<div class=\"".$this->class_div."\" style=\"display:none\" id=\"win_".$this->id."_codediv\">
										<xmp id=\"win_".$this->id."\">
										var win = new Window(		
										{
												".((!$variables)?"url:\"$this->constructor\",":'')."
												id:\"".$this->id."\",
												className: \"".$this->class_window."\", 
												title: \"".$this->titulo."\",
												width:".$this->width.", 
												height:".$this->height.", 
												resizable:".$this->resizable.",
												minimizable:".$this->minimizable.",
												maximizable:".$this->maximizable.",
												destroyOnClose:true,
												opacity:".$this->opacidad."												
										}
										);
										".(($variables)?"
										win.setAjaxContent
										(
												\"$this->constructor\", 		
												{
													postBody			:	\"".$this->string_php_to_url($variables)."\", 	
													method			:	'post'
												}
										);									
										":'')."
										win.showCenter($this->bloquear_pagina);										
										</xmp>
										</div>
										<!--FIN POPUP ".date("YmdHis")."--> 								
			";
			$this->constructor='controles/pop_grid.php';
		}
	}
}
?>