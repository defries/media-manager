<?php
/*
Plugin Name: Media Manager
Plugin URI: https://forsite.media/
Description: Media Manager
Version: 1.0
Author: Forsite Media
Author URI: https://forsite.media/
Text Domain: media-manager
License: GPL2

------------------------------------------------------------------------
Copyright Forsite Media

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/


/**
 * The autoloader.
 *
 * @param  string  $class  The class being instantiated
 */
function __autoload_media_manager( $class ) {

	$file_data = strtolower( $class );
	$file_data = str_replace( '_', '-', $file_data );
	$file_name = 'class-' . $file_data . '.php';

	$dir = dirname( __FILE__ );
	$path = $dir . '/inc/' . $file_name;

	if ( file_exists( $path ) ) {
		require( $path );
	}
}
spl_autoload_register( '__autoload_media_manager' );


new Media_Manager_Admin;
new Media_Manager_Delete;