<?php
session_start();
include("conn.php");

// Fetch all data from the 'shipments' table
$query = "SELECT * FROM shipments";
$result = mysqli_query($mysqli, $query);

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    $entries = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $entries[] = $row;
    }
} else {
    $entries = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Entries List</h1>

    <!-- Display the data in a table -->
    <?php if (!empty($entries)): ?>
        <table>
            <tr>
                <th>No</th>
                <th>NIF</th>
                <th>Importer</th>
                <th>Exporter</th>
                <th>Truck No</th>
                <th>Volume</th>
                <th>Product</th>
                <th>Destination</th>
                <th>Date</th>
                <th>QR Code</th>
                <th>link</th>
            </tr>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?php echo $entry['no']; ?></td>
                    <td><?php echo $entry['nif']; ?></td>
                    <td><?php echo $entry['importer']; ?></td>
                    <td><?php echo $entry['exporter']; ?></td>
                    <td><?php echo $entry['truck_no']; ?></td>
                    <td><?php echo $entry['volume']; ?></td>
                    <td><?php echo $entry['product']; ?></td>
                    <td><?php echo $entry['destination']; ?></td>
                    <td><?php echo $entry['date']; ?></td>
                    <td><?php echo $entry['qr_code_file']; ?></td>
                    <td>
                        <a href="#" class="view-qr" data-qr-code="qr_codes/<?php echo $entry['qr_code_file']; ?>">View QR Code</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No entries found in the database.</p>
    <?php endif; ?>

    <div class="action-buttons">
        <!-- Button to go back to Home -->
        <a href="index.html">
            <button>Back Home</button>
        </a>
    </div>

    <!-- Container to display the QR code inline -->
    <div class="qr-container" id="qr-container">
        <h3>QR Code</h3>
        <img id="qr-code-image" src="" alt="QR Code" class="qr-code" />
        <button onclick="hideQRCode()">Close</button>
    </div>

    <script>
        // Function to display QR code when clicked
        const viewQRLinks = document.querySelectorAll('.view-qr');
        const qrContainer = document.getElementById('qr-container');
        const qrCodeImage = document.getElementById('qr-code-image');

        viewQRLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const qrCodePath = this.getAttribute('data-qr-code');
                <img src="images/oildrop.jpg" alt="Company Logo" class="logo">
                qrCodeImage.src = qrCodePath;
                qrContainer.style.display = 'block'; // Show the QR code container
            });
        });

        // Function to hide the QR code container
        function hideQRCode() {
            qrContainer.style.display = 'none'; // Hide the QR code container
        }
    </script>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
<br>
<footer>
    <p>DRC Congo Oil Logistics. Copyright  &copy; 2025 OMC / Exporter.</p>
    <p style="font-family: cursive;">"Empowering trade in Central Africa"</p>
</footer>
</html>
