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

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class fbpagebuilderModelsettings extends JModelList {

    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'setting_title', 'a.setting_title',
                'api_key', 'a.api_key',
                'api_id', 'a.api_id',
                'api_secret', 'a.api_secret',
                'ordering', 'a.ordering',
                'published', 'a.published',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = JRequest::getVar('layout')) {
            $this->context .= '.' . $layout;
        }

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        // List state information.

        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select',
                        'a.*'
                )
        );
        $query->from('#__fb_settings AS a');


        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('a.published = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.published = 0 OR a.published = 1)');
        }

        //Search word
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $query->where('(a.setting_title LIKE ' . $search . ')');
            }
        }
        return $query;
    }

    /**
     * Method to get the Facebook Api.
     *
     * @return      mixed   The data for the form.
     * @since       1.6
     */
 public function getFacebookApi() {

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select',
                        'a.*'
                )
        );
        $query->from('#__fb_settings AS a');       
        $db->setQuery($query);

        $result = $db->loadObjectList();

        return $result;
    }
}
