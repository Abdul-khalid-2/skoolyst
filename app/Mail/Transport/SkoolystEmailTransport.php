<?php

namespace App\Mail\Transport;

use App\Services\SkoolystEmailService;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

/**
 * Routes ALL outgoing app mail (custom Mailables + Laravel's built-in
 * notifications like email verification / password reset) through the
 * central Skoolyst Email API instead of SMTP.
 *
 * Registered as the "skoolyst" mailer — see config/mail.php and
 * AppServiceProvider::boot(). Activate it by setting MAIL_MAILER=skoolyst
 * in .env, but only AFTER confirming /email-debug works (see EmailController).
 *
 * IMPORTANT LIMITATION: the API only accepts one plain-text body — no HTML,
 * no attachments, one address per call. This transport strips HTML down to
 * plain text and sends one API call per "to" recipient (cc/bcc included).
 */
class SkoolystEmailTransport extends AbstractTransport
{
    public function __construct(private SkoolystEmailService $service)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $subject = $email->getSubject() ?: '(no subject)';
        $body = $this->resolvePlainTextBody($email);

        $recipients = array_merge($email->getTo(), $email->getCc(), $email->getBcc());

        if (empty($recipients)) {
            throw new TransportException('SkoolystEmailTransport: message has no recipients.');
        }

        $failures = [];

        foreach ($recipients as $address) {
            /** @var Address $address */
            $result = $this->service->send($address->getAddress(), $subject, $body);

            if (!$result['success']) {
                $failures[] = sprintf(
                    '%s (HTTP %d, %s: %s)',
                    $address->getAddress(),
                    $result['status'],
                    $result['error_code'],
                    $result['error_message']
                );
            }
        }

        if (!empty($failures)) {
            throw new TransportException(
                'SkoolystEmailTransport: failed to send to: ' . implode('; ', $failures)
            );
        }
    }

    private function resolvePlainTextBody(Email $email): string
    {
        $text = $email->getTextBody();
        if (!empty(trim((string) $text))) {
            return trim($text);
        }

        $html = $email->getHtmlBody();
        if (!empty($html)) {
            return $this->htmlToText((string) $html);
        }

        return '';
    }

    private function htmlToText(string $html): string
    {
        $html = preg_replace('#<(style|script)\b[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('#<br\s*/?>#i', "\n", $html);
        $html = preg_replace('#</(p|div|tr|h[1-6])>#i', "\n\n", $html);
        $html = preg_replace('#<li[^>]*>#i', '- ', $html);
        $html = preg_replace('#</li>#i', "\n", $html);
        $html = preg_replace('#<a\b[^>]*href="([^"]*)"[^>]*>(.*?)</a>#is', '$2 ($1)', $html);

        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/[ \t]+/", ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        $lines = array_map('rtrim', explode("\n", $text));

        return trim(implode("\n", $lines));
    }

    public function __toString(): string
    {
        return 'skoolyst';
    }
}
