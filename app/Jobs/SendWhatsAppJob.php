<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppJob implements ShouldQueue
{
    use Queueable;

    protected $phoneNumber;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phoneNumber, string $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $whatsapp = new \App\Services\WhatsAppService();
        $result = $whatsapp->sendMessage($this->phoneNumber, $this->message);

        // Optional: You can add more complex error handling here
        // Note: Logging is already handled inside WhatsAppService
        
        if (!($result['status'] ?? false)) {
             // If failed, you might want to throw exception to retry the job
             // throw new \Exception('Failed to send WhatsApp message: ' . ($result['reason'] ?? 'Unknown error'));
        }
    }
    
    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        // Rate limit: 1 job every 5 seconds per 'whatsapp' queue
        // This prevents flooding the API
        return [(new \Illuminate\Queue\Middleware\ThrottlesExceptions(10, 5 * 60))->backoff(5)];
    }
}
