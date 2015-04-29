#!/bin/bash

dir="/var/www/mindcloud/prd/" # base directory
#git options
branch="master"
# grunt build options
grunt_dir="web/grunt" # relative to base directory
build_options="prd"

cd $dir

echo "Updating $branch"
git checkout $branch
git pull

echo "Launching grunt build"
cd $grunt_dir
grunt build $build_options

exit
