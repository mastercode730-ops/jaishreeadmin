<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';  // Load Firebase SDK

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

if (!function_exists('get_firebase_messaging')) {
    function get_firebase_messaging() {
        $serviceAccountPath = APPPATH . "config/firebase-service-account.json";

        // Fix: Remove `create()` and use `createMessaging()`
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath);

        return $firebase->createMessaging();
    }
}

// Subscribe users to a topic
if (!function_exists('subscribe_to_topic')) {
    function subscribe_to_topic($topic, $deviceTokens) {
        $messaging = get_firebase_messaging();
        $messaging->subscribeToTopic($topic, $deviceTokens);
        return "Users subscribed to topic: $topic";
    }
}

// Send notification to a topic
if (!function_exists('send_topic_notification')) {
    function send_topic_notification($topic, $title, $message) {
        $messaging = get_firebase_messaging();
        $notification = Notification::create($title, $message);

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification);

        return $messaging->send($message);
    }
}

// Send notification to multiple devices
if (!function_exists('send_firebase_notification')) {
    function send_firebase_notification($deviceTokens, $title, $message) {
        $serviceAccountPath = APPPATH . "config/firebase-service-account.json"; 

        $client = new Google\Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope("https://www.googleapis.com/auth/firebase.messaging");

        // Get access token
        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];

        // Firebase API URL
        $projectId = "aghorimatka-2697d";
        $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

        // Split tokens into chunks of 142 characters each
        $deviceTokens = str_split($deviceTokens, 142);

        $notification = [
            'title' => $title,
            'body' => $message,
        ];

        $tokens = [];
        foreach ($deviceTokens as $token) {
            $tokens[] = $token;
        }

        // Subscribe users to topic
        $topic = "global_notifications_mahadev";
        subscribe_to_topic($topic, $tokens);

        // Prepare payload
        $fcmNotification = [
            'message' => [
                'topic' => $topic,
                'notification' => $notification,
            ],
        ];

        // Headers
        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];

        // Send request using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
