<?php
/*
 ***********************************************************/
/**
 * @name          : Facebook Page Builder.
 * @version	      : 1.2
 * @package       : apptha
 * @since         : Joomla 1.6
 * @subpackage    : Facebook Page Builder.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract      : Customize facebook fanpage design and controll it from this component.
 * @Creation Date : July 20 2011
 * @Modified Date : November 8 2011
 * */

/*
 ***********************************************************/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * FBpagebuilder component helper.
 */
abstract class fbpagebuilderHelper {

//get facebook api for fblogin purpose.
    public static function facebookApi() {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query = "SELECT * FROM #__fb_system_settings";

        $db->setQuery($query);

        $result = $db->loadObject();

        return $result;
    }


}