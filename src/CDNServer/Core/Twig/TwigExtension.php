<?php

namespace CDNServer\Core\Twig;

class TwigExtension extends \Twig_Extension
{	
	public function getFilters()
	{
		return array(
			'hed'				=> new \Twig_Filter_Method($this, 'htmlEntityDecode'),
			'striptags_keep_br'	=> new \Twig_Filter_Method($this, 'stripTagsKeepBr')
		);
	}
	
	/*
	public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
	{
		$price = number_format($number, $decimals, $decPoint, $thousandsSep);
		$price = '$' . $price;
	
		return $price;
	}
	*/
	
	public function htmlEntityDecode($string)
	{
		return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
	}
	public function stripTagsKeepBr($string)
	{
		return strip_tags($string, '<br>');
	}
	
	public function getName()
	{
		return 'twig_extension';
	}
}
