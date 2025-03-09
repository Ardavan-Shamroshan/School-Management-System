<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Kavenegar\Laravel\Message\KavenegarMessage;
use Kavenegar\Laravel\Notification\KavenegarBaseNotification;

class SendSMSNotification extends KavenegarBaseNotification {
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(
		private readonly string $pattern,
		private readonly array $tokens,
	) {}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via($notifiable): array {
//		$channels =[];

//		if (in_array(OTPTarget::MOBILE, $this->targets)) {
//			$channels[] = 'kavenegar';
//		}
//
//		if ($this->type == OTPType::RECOVERY && in_array(OTPTarget::EMAIL, $this->targets)) {
//			$channels[] = 'mail';
//		}
		$channels[] = 'kavenegar';

		return $channels;
	}

	public function toKavenegar($notifiable): KavenegarMessage {
		return (new KavenegarMessage())->verifyLookup($this->pattern, $this->tokens)->to($notifiable->mobile);
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage {
		return (new MailMessage)
			->line('The introduction to the notification.')
			->action('Notification Action', url('/'))
			->line('Thank you for using our application!');
	}
}
