<?php
include('global_variables.php');

include('../admin/modules/operatins_links_browserstack_get_link.php');
 
$global_connect_link_to_browserstack = get_browserstack_link($connection_db_data,$global_db_name_browserstack_links);
 