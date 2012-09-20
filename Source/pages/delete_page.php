<?php

# Copyright (c) 2012 John Reese
# Licensed under the MIT license

form_security_validate( 'plugin_Source_changeset_delete' );
access_ensure_global_level( plugin_config_get( 'manage_threshold' ) );

$f_changeset_id = gpc_get_string( 'id' );

$t_changeset = SourceChangeset::load( $f_changeset_id );

helper_ensure_confirmed( sprintf( plugin_lang_get( 'ensure_delete_changeset' ), $t_changeset->revision ), plugin_lang_get( 'delete' ) );

$t_user_id = auth_get_current_user_id();
$t_changeset->delete( $t_user_id );

form_security_purge( 'plugin_Source_changeset_delete' );
print_successful_redirect( plugin_page( 'list', true ) . '&id=' . $t_changeset->repo_id );
