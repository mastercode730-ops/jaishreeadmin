<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Firebase {
    protected $firebase;
    protected $messaging;

    public function __construct() {
        $serviceAccountPath = APPPATH . "config/firebase-service-account.json"; 

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->create();

        $this->messaging = $this->firebase->getMessaging();
    }

    // Function to subscribe users to a topic
    public function subscribeToTopic($topic, $deviceTokens) {
        $this->messaging->subscribeToTopic($topic, $deviceTokens);
        return "Users subscribed to topic: $topic";
    }

    // Function to send topic notification
    public function sendTopicNotification($topic, $title, $message) {
        $notification = Notification::create($title, $message);

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification);

        return $this->messaging->send($message);
    }
}
