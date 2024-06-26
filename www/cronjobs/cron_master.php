<?php

$db->sql("UPDATE ppat_options SET meta_value='$current_time' WHERE meta_key = 'last_cron_run'");

// clear old sessions
// include('jobs/clear_old_sessions.php');

// double check API submissions
include('jobs/check_api_triggers.php');

include( 'jobs/clear_old_access_tokens.php');


include('jobs/delete_rejected_submissions.php');  
