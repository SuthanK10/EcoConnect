<?php
// app/controllers/DonationController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

function donation_index(PDO $pdo)
{
    $error = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = (float)($_POST['amount'] ?? 0);

        if ($amount < 100) { 
            $error = 'Minimum donation amount is Rs. 100.';
        } else {
            try {
                \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'lkr',
                            'product_data' => [
                                'name' => 'Donation to Eco-Connect',
                                'description' => 'Thank you for supporting our ocean cleanup mission.',
                                'images' => [BASE_URL . '/public/assets/img/logo.png'], 
                            ],
                            'unit_amount' => $amount * 100,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => BASE_URL . '/public/index.php?route=donation_success&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => BASE_URL . '/public/index.php?route=donations',
                ]);

                // Store pending donation in DB
                $user_id = $_SESSION['user_id'] ?? null;
                $stmt = $pdo->prepare("INSERT INTO donations (user_id, amount, stripe_session_id, status) VALUES (?, ?, ?, 'pending')");
                $stmt->execute([$user_id, $amount, $checkout_session->id]);

                // Redirect to Stripe Checkout
                header("HTTP/1.1 303 See Other");
                header("Location: " . $checkout_session->url);
                exit;
            } catch (Exception $e) {
                $error = 'Stripe Error: ' . $e->getMessage();
            }
        }
    }

    $pageTitle = 'Donate to EcoConnect';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/donations/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function donation_success(PDO $pdo)
{
    $session_id = $_GET['session_id'] ?? '';
    
    if ($session_id) {
        try {
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            if ($session->payment_status === 'paid') {
                // Update donation status in DB
                $stmt = $pdo->prepare("UPDATE donations SET status = 'completed' WHERE stripe_session_id = ?");
                $stmt->execute([$session_id]);
            }
        } catch (Exception $e) {
            // Log error or handle
        }
    }
    
    $pageTitle = 'Thank You!';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/donations/success.php';
    include __DIR__ . '/../views/layouts/footer.php';
}


