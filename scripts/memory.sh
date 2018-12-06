#! /bin/bash

case $BLOCK_BUTTON in
	1) notify-send "$(ps axh -o cmd,%mem --sort=-%mem | head)" ;;
esac

free -h | awk '/Speicher/ {print $3 "/" $2}'
