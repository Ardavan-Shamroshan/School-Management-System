<?php

namespace App\Jobs;

use App\Exceptions\ReportableException;
use App\Models\Academy\Student;
use App\Models\Academy\Teacher;
use App\Models\User;
use App\Notifications\SendSMSNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSMSJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		private readonly Student|Teacher|User $receptor,
		private readonly string $pattern,
		private readonly array $tokens,
	) {}

	/**
	 * Execute the job.
	 * @throws Exception
	 */
	public function handle(): void {
		try {
			$this->receptor->notify(new SendSMSNotification($this->pattern, $this->tokens));
			Log::channel('user')->info(sprintf('Send  %s to: %s', $this->pattern, $this->receptor->mobile));
		} catch(Exception $e) {
			$message = sprintf('Error on send sms pattern %s to: %s because: %s', $this->pattern, $this->receptor->mobile, $e->getMessage());
			Log::channel('user')->emergency($message);
			throw new Exception($e->getMessage());
		}
	}
}
