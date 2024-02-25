<!-- Sentry monitoring -->
<?php
require_once __DIR__ . "/../vendor/autoload.php";


Sentry\init([
  'dsn' => 'https://b0935efdfe15269fb0146d4a260a1b6b@o4506536468480000.ingest.sentry.io/4506807638884352',
  // Specify a fixed sample rate
  'traces_sample_rate' => 1.0,
  // Set a sampling rate for profiling - this is relative to traces_sample_rate
  'profiles_sample_rate' => 1.0,
]);

?>
<script src="https://js.sentry-cdn.com/b0935efdfe15269fb0146d4a260a1b6b.min.js" crossorigin="anonymous"></script>

<script>
  Sentry.onLoad(function () {
    Sentry.init({
      integrations: [
        Sentry.replayIntegration(),
      ],
      // Session Replay
      replaysSessionSampleRate: 0.1, // This sets the sample rate at 10%. You may want to change it to 100% while in development and then sample at a lower rate in production.
      replaysOnErrorSampleRate: 1.0, // If you're not already sampling the entire session, change the sample rate to 100% when sampling sessions where errors occur.
    });
  });
</script>

<!-- Robot Font head-tags -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
  rel="stylesheet">

<!-- Default CSS -->
<link rel="stylesheet" href="default/default.css">
<link rel="stylesheet" href="default/roboto-font.css">