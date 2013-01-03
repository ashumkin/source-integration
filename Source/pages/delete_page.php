<?php

# Copyright (c) 2012 Alexey Shumkin
# Licensed under the MIT license

form_security_validate( 'plugin_Source_changeset_delete' );
access_ensure_global_level( plugin_config_get( 'manage_threshold' ) );

$f_changeset_id = gpc_get_string( 'id' );

$t_changeset = SourceChangeset::load( $f_changeset_id );

$t_changesets = SourceChangeset::load_by_parent( $t_changeset->repo_id, $t_changeset->revision );

if ( count( $t_changesets ) == 0 ) {
	helper_ensure_confirmed( sprintf( plugin_lang_get( 'ensure_delete_changeset' ), $t_changeset->revision ), plugin_lang_get( 'delete' ) );
} else {
	html_page_top();

	echo "<br />\n<div align=\"center\">\n";
	print_hr();
	echo "Changeset ";
	print_link( plugin_page( 'view' ) . '&id=' . $t_changeset->id, $t_changeset->revision );
	echo " cannot be deleted! It has following children:<p>";
	foreach( $t_changesets as $t_changeset_a ) {
		print_link( plugin_page( 'view' ) . '&id=' . $t_changeset_a->id, $t_changeset_a->revision );
		echo "<br />";
	}

	print_hr();
	echo "</div>\n";
	html_page_bottom();

	exit;
}

$t_user_id = auth_get_current_user_id();
$t_changeset->delete( $t_user_id );

form_security_purge( 'plugin_Source_changeset_delete' );
print_successful_redirect( plugin_page( 'list', true ) . '&id=' . $t_changeset->repo_id );
