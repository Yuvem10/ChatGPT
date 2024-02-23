#!/bin/bash

php doc.php orm:schema-tool:update --force
php doc.php orm:generate:proxies