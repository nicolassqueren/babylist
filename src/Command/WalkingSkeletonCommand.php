<?php

namespace App\Command;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class WalkingSkeletonCommand extends Command
{
	protected $mailer;

    protected static $defaultName = 'walking:skeleton';

    public function __construct(MailerInterface $mailer)
	{
		$this->mailer = $mailer;
		parent::__construct(self::$defaultName);
	}

	protected function configure()
    {
        $this
            ->setDescription('Command test walkingskeleton touchy dev Email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //Envoi de mails

		$email = (new TemplatedEmail())
			->from('nicosqueren@gmail.com')
			->to('nicolas.squeren@gmail.com')
			//->cc('cc@example.com')
			//->bcc('bcc@example.com')
			//->replyTo('fabien@example.com')
			//->priority(Email::PRIORITY_HIGH)

			->subject('Time for Symfony Mailer!')
			->htmlTemplate('emails/default.html.twig');

		$this->mailer->send($email);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
