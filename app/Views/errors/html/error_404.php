<!DOCTYPE html>
<html>

<head>
	<title>404 Page Not Found</title>
	<style>
		body {
			animation: blink-background 1s infinite;
		}

		@keyframes blink-background {
			0% {
				background-color: #f00;
			}

			50% {
				background-color: #0f0;
			}

			100% {
				background-color: #f00;
			}
		}

		h1 {
			font-size: 10em;
			text-align: center;
			margin-top: 10%;
			color: #fff;
			text-shadow: 2px 2px 8px #555;
			animation: blink-text 1s infinite;
		}

		h4 {
			text-align: center;
			margin-top: 10%;
			color: #fff;
			text-shadow: 2px 2px 8px #555;
			animation: blink-text 1s infinite;
		}

		@keyframes blink-text {
			0% {
				color: #fff;
			}

			50% {
				color: #000;
			}

			100% {
				color: #fff;
			}
		}
	</style>
</head>

<body>
	<h1>404 - File Not Found</h1>

	<h4>
		<?php if (!empty($message) && $message !== '(null)'): ?>
			<?= nl2br(esc($message)) ?>
		<?php else: ?>
			Sorry! Cannot seem to find the page you were looking for.
		<?php endif ?>
	</h4>
</body>

</html>