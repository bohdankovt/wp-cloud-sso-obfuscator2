#!/bin/bash
PHP=$(eval "which php")
SCRIPT=$(realpath "$0")
$PHP "$(dirname "$SCRIPT")"/obfuscator.php "$1"

