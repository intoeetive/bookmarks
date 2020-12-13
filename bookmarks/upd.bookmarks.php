<?php

/*
=====================================================
 Bookmarks
-----------------------------------------------------
 http://www.intoeetive.com/
-----------------------------------------------------
 Copyright (c) 2012 Yuri Salimovskiy
=====================================================
 This software is intended for usage with
 ExpressionEngine CMS, version 2.0 or higher
=====================================================
 File: upd.bookmarks.php
-----------------------------------------------------
 Purpose: Lets people bookmark entries (and other data) for quick access
=====================================================
*/

if ( ! defined('BASEPATH'))
{
    exit('Invalid file request');
}

require_once PATH_THIRD.'bookmarks/config.php';

class Bookmarks_upd {

    var $version = BOOKMARKS_ADDON_VERSION;
    
    function __construct() { 

    } 
    
    function install() { 
        
        ee()->load->dbforge(); 
        
        //----------------------------------------
		// EXP_MODULES
		// The settings column, Ellislab should have put this one in long ago.
		// No need for a seperate preferences table for each module.
		//----------------------------------------
		if (ee()->db->field_exists('settings', 'modules') == FALSE)
		{
			ee()->dbforge->add_column('modules', array('settings' => array('type' => 'TEXT') ) );
		}
        
        $settings = array();
        
        $data = array( 'module_name' => 'Bookmarks' , 'module_version' => $this->version, 'has_cp_backend' => 'n', 'settings'=> serialize($settings) ); 
        ee()->db->insert('modules', $data); 
        
        $data = array( 'class' => 'Bookmarks' , 'method' => 'add' ); 
        ee()->db->insert('actions', $data); 
        
        $data = array( 'class' => 'Bookmarks' , 'method' => 'remove' ); 
        ee()->db->insert('actions', $data); 
        
        //exp_bookmarks
        $fields = array(
			'bookmark_id'		=> array('type' => 'INT',		'unsigned' => TRUE, 'auto_increment' => TRUE),
			'member_id'			=> array('type' => 'INT',		'unsigned' => TRUE, 'default' => 0),
			'type'				=> array('type' => 'VARCHAR',	'constraint'=> 250,'default' => ''),
			'site_id'			=> array('type' => 'INT',		'unsigned' => TRUE, 'default' => 0),
			'data_group_id'		=> array('type' => 'INT',		'unsigned' => TRUE, 'default' => 0),
			'data_id'			=> array('type' => 'INT',		'unsigned' => TRUE, 'default' => 0),
			'bookmark_date'	    => array('type' => 'INT',		'unsigned' => TRUE, 'default' => 0)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('bookmark_id', TRUE);
		ee()->dbforge->add_key('member_id');
		ee()->dbforge->add_key('data_id');
		ee()->dbforge->add_key('site_id');
		ee()->dbforge->add_key('data_group_id');
		ee()->dbforge->create_table('bookmarks', TRUE);
        
        return TRUE; 
        
    } 
    
    function uninstall() { 

        ee()->load->dbforge(); 
		
		ee()->db->select('module_id'); 
        $query = ee()->db->get_where('modules', array('module_name' => 'Bookmarks')); 
        
        ee()->db->where('module_id', $query->row('module_id')); 
        ee()->db->delete(version_compare(APP_VER, '6.0', '>=') ? 'module_member_roles' : 'module_member_groups'); 
        
        ee()->db->where('module_name', 'Bookmarks'); 
        ee()->db->delete('modules'); 
        
        ee()->db->where('class', 'Bookmarks'); 
        ee()->db->delete('actions'); 
        
        ee()->dbforge->drop_table('bookmarks');
        
        return TRUE; 
    } 
    
    function update($current='') 
	{ 
        if ($current < 2.0) 
		{ 
            // Do your 2.0 version update queries 
        } 
        return TRUE; 
    } 
	

}
/* END */
?>
