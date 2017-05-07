#!/bin/sh
find . -type d -perm 0777 -mtime +30 | xargs rm -rf