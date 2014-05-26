<?php
/**
 * Productive Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

return array(

    'doctrine' => array(

        'connection' => array(
            'orm_default' => array(

                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '***REMOVED***',
                    'dbname'   => 'armasquads',
                    'charset'  => 'utf8',
                    'driver_options' => array(
                        \PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET NAMES utf8',
                    )
                )
            )
        )
    ),

);
