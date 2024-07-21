#!/usr/bin/env sh

set -x
docker run -d --network jenkins-php-selenium-test-1_jenkins-net -p 80:80 --name my-apache-php-app -v /Users/tiara/Documents/GitHub/jenkins-php-selenium-test-1/src:/var/www/html php:7.2-apache
sleep 1
set +x

echo 'Now...'
echo 'Visit http://localhost to see your PHP application in action.'

