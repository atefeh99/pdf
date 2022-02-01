<?php


namespace App\Modules;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentField;
use Maknz\Slack\Client;
use Maknz\Slack\Message;
use Throwable;

class Slack
{
    const COLOR_GREEN = 'green';
    const COLOR_YELLOW = 'warning';
    const COLOR_RED = 'danger';

    private $hook;
    private $slack;
    private $appName;

    private $receivers;


    public function __construct()
    {
        $this->hook = env('SLACK_HOOK');
        $this->appName = env('APP_NAME');

        $settings = [
            'username' => env('SLACK_USERNAME'),
            'channel' => env('SLACK_ERROR_REPORT_CHANNEL'),
            'icon' => ':bot:',
            'allow_markdown' => true,
            'link_names' => true
        ];

        $this->slack = new Client($this->hook, $settings);
        $this->receivers = [env('SLACK_ERROR_REPORT_CHANNEL')];
    }

    public function addReceiver($username): Slack
    {
        array_push($this->receivers, $username);
        return $this;
    }

    public function sendErrorLog(Throwable $exception, Request $request, Response $response)
    {
        $headers = $request->headers->all();
        $headerStrings = [];
        foreach ($headers as $key => $values) {
            $headerStrings[] = "- *$key*: " . implode(', ', $values);
        }

        $items = [
            'URI' => $request->getUri(),
            'Method' => $request->method(),
            'Status Code' => $response->getStatusCode(),
            'Exception Class' => get_class($exception),
            'Message' => $exception->getMessage(),
            'File' => $exception->getFile(),
            'Line' => $exception->getLine(),
            'Previous' => $exception->getPrevious(),
            'Request Parameters' => json_encode($request->all(), JSON_PRETTY_PRINT),
            'Headers' => implode("\n", $headerStrings),
        ];

        $text = "An error occurred in *" . strtoupper($this->appName) . "*";
        $message = $this->makeMessage(
            $text,
            $items,
            self::COLOR_RED
        )->setMarkdownInAttachments(['Headers']);

        $this->sendMessage($message);
    }


    private function makeMessage(string $text, array $items, string $attachmentColor)
    {
        return $this->slack->createMessage()
            ->enableMarkdown()
            ->setText($text)
            ->setAttachments([$this->makeAttachments($items, $attachmentColor)]);
    }

    private function makeAttachments(array $items, string $color)
    {
        $attachmentFields = [];
        foreach ($items as $key => $value) {
            $attachmentFields[] = new AttachmentField([
                'title' => $key,
                'value' => $value,
                'short' => strlen($value) < 20
            ]);
        }

        return new Attachment([
            'fallback' => 'Details',
            'text' => 'Details',
            'color' => $color,
            'fields' => $attachmentFields,
        ]);
    }

    private function sendMessage(Message $message)
    {
        foreach ($this->receivers as $receiver) {
            $message->to($receiver)->send();
        }
    }

}
