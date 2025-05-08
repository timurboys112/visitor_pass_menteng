<?php
require_once 'db_connection.php';

$type = $_GET['type'] ?? '';

if ($type === 'in_location') {
    // Tamu yang masih di lokasi
    $sql = "SELECT * FROM guests WHERE check_out_time IS NULL ORDER BY check_in_time DESC";
    $result = mysqli_query($conn, $sql);

    echo '<table class="table table-bordered table-striped">';
    echo '<thead class="table-success"><tr><th>No</th><th>Nama Tamu</th><th>Jenis Tamu</th><th>Waktu Check-In</th><th>Status</th></tr></thead><tbody>';

    if (mysqli_num_rows($result) > 0) {
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>" . htmlspecialchars($row['guest_name']) . "</td>
                    <td>" . htmlspecialchars($row['visitor_type']) . "</td>
                    <td>" . date('d-m-Y H:i', strtotime($row['check_in_time'])) . "</td>
                    <td><span class='badge bg-success'>Di Lokasi</span></td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='5' class='text-center'>Tidak ada tamu di lokasi saat ini.</td></tr>";
    }

    echo '</tbody></table>';
} elseif ($type === 'history') {
    // Riwayat kunjungan 20 terakhir
    $sql = "SELECT * FROM guests ORDER BY check_in_time DESC LIMIT 20";
    $result = mysqli_query($conn, $sql);

    echo '<table class="table table-bordered table-striped">';
    echo '<thead class="table-primary"><tr><th>No</th><th>Nama Tamu</th><th>Jenis Tamu</th><th>Check-In</th><th>Check-Out</th><th>Status</th></tr></thead><tbody>';

    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $status = $row['check_out_time'] ? "<span class='badge bg-secondary'>Selesai</span>" : "<span class='badge bg-success'>Di Lokasi</span>";
        echo "<tr>
                <td>{$no}</td>
                <td>" . htmlspecialchars($row['guest_name']) . "</td>
                <td>" . htmlspecialchars($row['visitor_type']) . "</td>
                <td>" . date('d-m-Y H:i', strtotime($row['check_in_time'])) . "</td>
                <td>" . ($row['check_out_time'] ? date('d-m-Y H:i', strtotime($row['check_out_time'])) : '-') . "</td>
                <td>{$status}</td>
              </tr>";
        $no++;
    }

    echo '</tbody></table>';
}

mysqli_close($conn);
?>