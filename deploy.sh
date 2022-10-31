#!/bin/bash -v

read -p "devportal directory: " dir

sudo rm -rf $dir/composer.json
echo "rm composer"
sudo rm -rf $dir/patches/
echo "rm patches"
sudo rm -rf $dir/web/modules/
echo "rm modules"
sudo rm -rf $dir/web/themes/
echo "rm themes"
sudo rm -rf $dir/web/sites/default/translation/
echo "rm translation"
sudo rm -rf $dir/web/sites/default/sync/
echo "rm sync"
sudo cp -R $(pwd)/Source/portal/web/sites/default/sync/ $dir/web/sites/default/
echo "cp sync"
sudo cp -R $(pwd)/Source/portal/web/modules/ $dir/web/
echo "cp modules"
sudo cp -R $(pwd)/Source/portal/web/themes/ $dir/web/
echo "cp themes"
sudo cp -R $(pwd)/Source/portal/web/translation/ $dir/web/sites/default/
echo "cp translation"
sudo cp -R $(pwd)/Source/portal/composer.json $dir/
echo "cp composer"
sudo cp -R $(pwd)/Source/portal/patches/ $dir/
echo "cp patches"
cd $dir/
sudo chown -R najmadmin:nginx $dir/composer.json $dir/patches/ $dir/web/modules/ $dir/web/themes/ $dir/web/translation/ $dir/web/sites/default/sync/
sudo chmod -R 777 $dir
composer up
$dir/vendor/drush/drush/drush cim -y
$dir/vendor/drush/drush/drush cr
$dir/vendor/drush/drush/drush locale:update -y
$dir/vendor/drush/drush/drush locale:import ar $dir/web/translation/ar.po --type=customized --override=all -y
$dir/vendor/drush/drush/drush cr
