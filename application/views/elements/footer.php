<?php

$user = $this->session->get('user');

////////////
///// LOCALE
////////////
$locale_current = ($user->id >0) ? $user->locale : cookie::get('locale');

$locales = array();
foreach (gen::languages() as $lang => $locale)
{
	$res = "";
	if ($locale_current == $lang) $res .= "<b>";
	$res .= html::anchor('locale/'.$lang, $locale);
	if ($locale_current == $lang) $res .= "</b>";
	$locales[] = $res;
}

echo "Langues: ";
echo implode(' | ', $locales);
echo "<br /><br />";

////////////
///// DESIGN
////////////
$design_current = ($user->id >0) ? $user->design->name : $this->session->get('design');

$designs = array();
foreach (ORM::factory('design')->find_all() as $design)
{
	$design->general;
	if ($design->general or (!$design->general and $design->is_moderator($user)))
	{
		$res = "";
		if ($design_current == $design->name) $res .= "<b>";
		$res .= html::anchor('design/'.$design->name, Kohana::lang('design.'.$design->name)) . 
			' <small>('.count($design->users).')</small>';
		if ($design_current == $design->name) $res .= "</b>";
		$designs[] = $res;
	}
}

echo "Designs: ";
echo implode(' | ', $designs);
echo "<br /><br />";
