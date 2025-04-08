<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\Slot;
use App\Helper\DateTimeHelper;
use App\Repository\EventStatusRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aluminij:fixtures:first',
    description: 'Add a short description for your command',
)]
class AluminijFixturesFirstCommand extends Command
{
    public function __construct(
        private EventStatusRepository $eventStatusRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('event', null, InputOption::VALUE_REQUIRED, 'Event name')
            ->addOption('date', null, InputOption::VALUE_REQUIRED, 'Event date')
            ->addOption('startTime', null, InputOption::VALUE_REQUIRED, 'Start of the event')
            ->addOption('endTime', null, InputOption::VALUE_REQUIRED, 'End of the event')
            ->addOption('duration', null, InputOption::VALUE_REQUIRED, 'Slot duration')
            ->addOption('seats', null, InputOption::VALUE_REQUIRED, 'Seats per slot')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $eventName  = $input->getOption('event');
        $date       = $input->getOption('date');
        $startTime  = $input->getOption('startTime');
        $endTime    = $input->getOption('endTime');
        $duration   = $input->getOption('duration');
        $seats      = $input->getOption('seats');

        $startTimeSplit = explode(':', $startTime);
        $endTimeSplit = explode(':', $endTime);

        $activeStatus = $this->eventStatusRepository->findByName(EventStatus::ACTIVE);

        $eventDate = DateTimeHelper::getNew($date, 'Europe/Sarajevo');
        $eventDateImmutable = DateTimeHelper::getNewForImmutable($date, 'Europe/Sarajevo');

        $eventStartTime = clone($eventDate);
        $eventStartTime->setTime($startTimeSplit[0], $startTimeSplit[1]);

        $eventEndTime = clone($eventDate);
        $eventEndTime = $eventEndTime->setTime($endTimeSplit[0], $endTimeSplit[1]);

        $event = new Event();
        $event->setName($eventName);
        $event->setStatus($activeStatus);
        $event->setDate($eventDateImmutable);

        $this->eventStatusRepository->save($event);

        $endSlotTime = clone($eventStartTime);
        $endSlotTime->add(new \DateInterval('PT' .$duration . 'M'));

        $slot = new Slot();
        $slot->setEvent($event);
        $slot->setSeats($seats);
        $slot->setTimeStart($eventStartTime);
        $slot->setTimeEnd($endSlotTime);

        $this->eventStatusRepository->save($slot);

        $this->startTime = clone($eventStartTime);
        $this->endTime = clone($endSlotTime);

        while($this->endTime < $eventEndTime){

            $this->startTime->add(new \DateInterval('PT' .$duration . 'M'));

            $this->endTime->add(new \DateInterval('PT' .$duration . 'M'));

            $start = clone($this->startTime);
            $end = clone($this->endTime);

            $slot = new Slot();
            $slot->setEvent($event);
            $slot->setSeats($seats);
            $slot->setTimeStart($start);
            $slot->setTimeEnd($end);

            $this->eventStatusRepository->save($slot);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private \DateTime $startTime;
    private \DateTime $endTime;
}
