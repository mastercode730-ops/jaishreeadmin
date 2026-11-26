<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

    private $serverKey = "BIduhInuHw2z3ON4hqsst0ILCp3QQIMQb-vQnn1S8GkSqOd8kpKoFQMu5JvrzTe04TX_tUTrYvmwQ4JB-HNecy4"; // Replace with your actual FCM server key

    public function sendNotification() {
        // Get Firebase Token from the frontend (React app)
        $firebaseToken = $this->input->post('token');
        $title = $this->input->post('title');
        $message = $this->input->post('message');

        if (!$firebaseToken || !$title || !$message) {
            echo json_encode(["status" => "error", "message" => "Invalid input"]);
            return;
        }

        // Notification payload
        $notificationData = [
            "to" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body"  => $message,
                "sound" => "default"
            ],
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "id" => uniqid(),
                "status" => "done"
            ]
        ];

        // Send HTTP request to FCM
        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
}
?>
