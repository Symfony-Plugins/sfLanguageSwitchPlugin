<?php

/*
 * This file is part of the symfony package.
 * (c) 2008 Thomas Boerger <tb@mosez.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Language switch component.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Thomas Boerger <tb@mosez.net>
 * @version    SVN: $Id$
 */
class BasesfLanguageSwitchComponents extends sfComponents
{
  /**
   * Get the language switch.
   *
   *  @param void
   *  @return integer sfView::SUCCESS
   */
  public function executeGet()
  {
    // define languages placeholder
    $tempvalue = array();

    // get routing class
    $routing = sfRouting::getInstance();

    // parse path info
    $pathinfo = $routing->parse($this->getRequest()->getPathInfo());

    // set current module
    $this->current_module = $pathinfo['module'];
    unset($pathinfo['module']);

    // set current action
    $this->current_action = $pathinfo['action'];
    unset($pathinfo['action']);

    // get available lnaguages
    $available_languages = sfConfig::get(
      'app_sfLanguageSwitch_availableLanguages', 
      array(
        'en' => array(
          'title' => 'English',
          'image' => '/sfLanguageSwitch/images/flag/us.png'
        ), 
        'de' => array(
          'title' => 'Deutsch'
        )
      )
    );

    // generate language information
    foreach($available_languages as $language => $information)
    {
      $tempvalue[$language] = array();
      $pathinfo['sf_culture'] = $language;

      $firstend = false;
      $query = '';
      foreach($pathinfo as $key => $value)
      {
	if(!$firstend)
	{
          $query .= '?';
	  $firstend = true;
	}
	else
	{
          $query .= '&';
	}

	$query .= $key . '=' . $value;
      }

      $tempvalue[$language]['query'] = $query;
      
      if(isset($information['title']))
      {
        $tempvalue[$language]['title'] = $information['title'];
      }
      else
      {
        $tempvalue[$language]['title'] = $language;
      }

      if(isset($information['image']))
      {
        $tempvalue[$language]['image'] = $information['image'];
      }
      else
      {
        $tempvalue[$language]['image'] = sfConfig::get('app_sfLanguageSwitch_flagPath', '/sfLanguageSwitch/images/flag') . '/' . $language . '.png';
      }
    }

    // assign language information to view
    $this->languages = $tempvalue;
    return sfView::SUCCESS;
  }
}
