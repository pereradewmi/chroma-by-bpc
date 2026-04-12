<?php

namespace App\Mail;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public $classRoom;

    public $month;

    public $months;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, ClassRoom $classRoom, string $month)
    {
        $this->student = $student;
        $this->classRoom = $classRoom;
        $this->month = $month;

        $this->months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Payment Confirmation - '.$this->classRoom->cName.' - '.$this->months[$this->month])
            ->view('emails.payment-notification');
    }
}
