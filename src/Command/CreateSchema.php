<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\Table;

class CreateSchema extends Command
{
    private $container;

    public function __construct($container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('app:create:schema')

        // the short description shown while running "php bin/console list"
        ->setDescription('Create the schema if none exists.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to create the schema if it does not already exists.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schema = $this->container['db']->getSchemaManager();

        if (!$schema->tablesExist('users')) {
            $users = new Table('users');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addUniqueIndex(array('username'));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('bio', 'string', array('length' => 255, 'notnull' => false));
            $users->addColumn('picture', 'string', array('length' => 255, 'notnull' => false));
            $users->addColumn('roles', 'string', array('length' => 255));

            $schema->createTable($users);

            $this->container['db']->insert('users', array(
              'username' => 'admin',
              'password' => 'qwerty',
              'roles' => 'ROLE_ADMIN'
            ));

            $this->container['db']->insert('users', array(
              'username' => 'titi',
              'password' => 'tata',
              'roles' => 'ROLE_ADMIN'
            ));
        }

        if (!$schema->tablesExist('messages')) {
            $users = new Table('messages');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('ffrom', 'string', array('length' => 32));
            $users->addColumn('tto', 'string', array('length' => 255));
            $users->addColumn('message', 'string', array('length' => 255));

            $schema->createTable($users);

            $this->container['db']->insert('messages', array(
              'ffrom' => 'titi',
              'tto' => 'admin',
              'message' => 'Hey admin, tu pensais que’c’était ça que c’tait?'
            ));

            $this->container['db']->insert('messages', array(
              'ffrom' => 'admin',
              'tto' => 'titi',
              'message' => 'Oui mais c’était pas ça que c’tait.'
            ));
        }
    }
}
