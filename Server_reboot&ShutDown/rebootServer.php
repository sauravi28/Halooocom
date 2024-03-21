<?php
// Specify the paths to the shell scripts
$shutdown_script = "/srv/www/htdocs/admin/server_reboot_stop/new_reboot_script.sh";


// Create a timestamp for the log file
$timestamp = date("Y-m-d_H-i-s");

// Specify the log file path
$logFilePath = "/srv/www/htdocs/admin/server_reboot_stop/script_execution_reboot.log";

// Run the Server_Shutdown script
//$shutdown_output = shell_exec("sh $shutdown_script 2>&1");

// Run the Server_Reboot script
$reboot_output = shell_exec("sh $reboot_script 2>&1");

// Combine outputs and create log entry
$logEntry = "===== Script Execution: $timestamp =====\n";
$logEntry .= "Server_Shutdown Script  Output:\n$reboot_output\n";


// Check if any script failed
if (strpos($reboot_output, "error") !== false) {
    $logEntry .= "Status: FAILURE\n";
} else {
    $logEntry .= "Status: SUCCESS\n";
}

// Write the log entry to the log file
file_put_contents($logFilePath, $logEntry, FILE_APPEND);

// Output the log file path
echo "Log file created: $logFilePath";
?>