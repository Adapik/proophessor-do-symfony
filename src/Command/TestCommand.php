<?php

declare(strict_types=1);

namespace App\Command;

use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\Todo\TodoText;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\User;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('test:run')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $n = 10000;

        $userRepo = $this->getContainer()->get('user_collection');
        $todoRepo = $this->getContainer()->get('todo_list');

        for($i = 1; $i < $n; $i++) {
            $ids[$i] = $id = UserId::generate();
            $user    = User::registerWithData($id, UserName::fromString('user'.$i), EmailAddress::fromString('email'.$i.'@test.com'));
            $userRepo->save($user);
        }

        for($i = 1; $i < $n; $i++) {
            $todoId = TodoId::generate();
            $todo   = Todo::post(TodoText::fromString('text'), $ids[$i], $todoId);
            $todoRepo->save($todo);
        }
    }
}
