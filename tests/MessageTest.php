<?php
namespace ermakove\PhpFirebaseCloudMessaging\Tests;

use ermakove\PhpFirebaseCloudMessaging\Recipient\Recipient;
use ermakove\PhpFirebaseCloudMessaging\Message;
use ermakove\PhpFirebaseCloudMessaging\Recipient\Topic;
use ermakove\PhpFirebaseCloudMessaging\Notification;
use ermakove\PhpFirebaseCloudMessaging\Recipient\Device;

class MessageTest extends PhpFirebaseCloudMessagingTestCase
{
    private $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = new Message();
    }

    public function testThrowsExceptionWhenDifferentRecepientTypesAreRegistered()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->fixture->addRecipient(new Topic('breaking-news'))
            ->addRecipient(new Recipient());
    }

    public function testThrowsExceptionWhenNoRecepientWasAdded()
    {
        $this->setExpectedException(\UnexpectedValueException::class);
        $this->fixture->jsonSerialize();
    }

    public function testThrowsExceptionWhenMultipleTopicsWereGiven()
    {
        $this->setExpectedException(\UnexpectedValueException::class);
        $this->fixture->addRecipient(new Topic('breaking-news'))
            ->addRecipient(new Topic('another topic'));

        $this->fixture->jsonSerialize();
    }

    public function testJsonEncodeWorksOnTopicRecipients()
    {
        $body = '{"to":"\/topics\/breaking-news","notification":{"title":"test","body":"a nice testing notification"}}';

        $notification = new Notification('test', 'a nice testing notification');
        $message = new Message();
        $message->setNotification($notification);

        $message->addRecipient(new Topic('breaking-news'));
        $this->assertSame(
            $body,
            json_encode($message)
        );
    }

    public function testJsonEncodeWorksOnDeviceRecipients()
    {
        $body = '{"to":"deviceId","notification":{"title":"test","body":"a nice testing notification"}}';

        $notification = new Notification('test', 'a nice testing notification');
        $message = new Message();
        $message->setNotification($notification);

        $message->addRecipient(new Device('deviceId'));
        $this->assertSame(
            $body,
            json_encode($message)
        );
    }
}