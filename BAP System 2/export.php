<?php
$conn = new mysqli("localhost", "root", "", "bap_system");
$result = $conn->query("SELECT * FROM bap_entries");

$filename = "data bap-" . date('Ymd') . ".xls";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data BAP.xls");
?>

<table class="text-center" border="1">
    <thead class="text-center">
        <tr>
            <th>NOP</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>No Bayar</th>
            <th>Berita</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php foreach ($result as $row) :?>
            <tr>
                <!-- <td><?= $no++; ?></td> -->
                <td><?= $row['nop']; ?></td>
                <td><?= $row['nik']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['no_bayar']; ?></td>
                <td><?= $row['berita']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
