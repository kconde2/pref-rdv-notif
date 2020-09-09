<?php

namespace App\Command;

use App\Service\AppointmentService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CheckAppointmentCommand extends Command
{
    protected static $defaultName = 'app:appointment:check';

    private $appointmentService;

    private $notificationService;

    public function __construct(
        AppointmentService $appointmentService,
        NotificationService $notificationService
    ) {

        parent::__construct();

        $this->appointmentService = $appointmentService;

        $this->notificationService = $notificationService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check appointment availibility and send notificatiion');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            setlocale(LC_TIME, "fr_FR.utf8"); //Setting the locale to French with UTF-8
            $url = $_ENV['APP_APPOINTMENT_URL'];
            $emails = explode(",", $_ENV['APP_APPOINTMENT_RECEIVERS']);
            $date = strftime("le %d %B %Y à %H:%M:%S", strtotime(date('Y-m-d H:i:s', strtotime("1 minute"))));
            $message = "Prochaine information à $date";
            $this->appointmentService->gotoPage($url);

            if ($this->appointmentService->checkAvailability()) {
                $message = "Ah y'a des rendez-vous disponible 🔥. " . $message;
                $io->success($message);
            } else {
                $message = "Aucun rendez-vous disponible 🥵. " . $message;
                $io->note($message);
            }

            $this->notificationService->send($emails, $message);
        } catch (\Throwable $th) {
            $io->note($th->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
