<?php
/**
 * Task 1 - PHP & Server Environment Verification Script
 */
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerConnect | Environment Verification (hello.php)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success display-1"></i>
                    </div>
                    <h2 class="fw-bold mb-2">PHP & MySQL Environment Operational!</h2>
                    <p class="text-muted">Task 1 Requirement Verified for <strong><?= APP_NAME ?></strong></p>
                    <hr>
                    <div class="row g-3 text-start mt-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white border rounded">
                                <small class="text-muted d-block">PHP Version</small>
                                <strong><?= phpversion() ?></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white border rounded">
                                <small class="text-muted d-block">MySQL Status</small>
                                <strong><?= ($conn && $conn->ping()) ? '<span class="text-success"><i class="bi bi-database-check me-1"></i>Connected</span>' : '<span class="text-danger">Disconnected</span>' ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary rounded-pill px-4">Go to Landing Page &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
