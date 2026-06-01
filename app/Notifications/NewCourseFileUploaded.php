<?php

namespace App\Notifications;

use App\Models\WorkshopCourseFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCourseFileUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly WorkshopCourseFile $file) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau document disponible — '.$this->file->workshop->title)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Un nouveau document de cours a été ajouté pour votre atelier **'.$this->file->workshop->title.'**.')
            ->line('**Document :** '.$this->file->title)
            ->when($this->file->description, fn ($mail) => $mail->line($this->file->description))
            ->action('Télécharger mes documents', route('participant.downloads'))
            ->line('Bonne formation !');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_course_file',
            'file_id' => $this->file->id,
            'file_title' => $this->file->title,
            'workshop_id' => $this->file->workshop_id,
            'workshop_title' => $this->file->workshop->title,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
