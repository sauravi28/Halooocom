#!/bin/bash

# Check if the user is root
if [ "$(id -u)" -ne 0 ]; then
    echo "This script must be run as root." >&2
    exit 1
fi

# Specify the root password
root_password="haloocom"

# Specify the output file
output_file="/home/Haloocom/scripts/reboot_output.log"

# Perform the shutdown using the provided password and save the output to a file
echo "$root_password" | sudo -S /sbin/shutdown -r now > "$output_file" 2>&1

# Check the exit status of the last command
if [ $? -eq 0 ]; then
    echo "Reboot command executed successfully. Output saved to $output_file."
else
    echo "Error: Reboot command failed. Check $output_file for details."
fi

