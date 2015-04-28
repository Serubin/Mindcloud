#!/bin/bash

dir="/var/www/mindcloud/prd/" # base directory
#git options
branch="master"
# grunt build options
grunt_dir="web/grunt" # relative to base directory
build_options="prd"

cd $dir

git checkout $branch
git pull

cd $grunt_dir
grunt build $build_options

exit