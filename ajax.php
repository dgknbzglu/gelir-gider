<?php
session_start();
include("conn.php");

$islem = isset($_GET['islem']) ? $_GET['islem'] : '';

if ($islem == 'gelir_ekle') {
    $tarih = $_POST['tarih'];
    $miktar = $_POST['miktar'];
    $kategori = $_POST['kategori'];

    $sorgu = $db->prepare("INSERT INTO gelir_gider (tarih, miktar, kategori, tur) VALUES (?, ?, ?, 1)");
    $ekle = $sorgu->execute([$tarih, $miktar, $kategori]);

    if ($ekle) {
        $id = $db->lastInsertId();
        $response = ['status' => 'success', 'id' => $id];
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error']);
    }
} elseif ($islem == 'gider_ekle') {
    $tarih = $_POST['tarih'];
    $miktar = $_POST['miktar'];
    $kategori = $_POST['kategori'];

    $sorgu = $db->prepare("INSERT INTO gelir_gider (tarih, miktar, kategori, tur) VALUES (?, ?, ?, 0)");
    $ekle = $sorgu->execute([$tarih, $miktar, $kategori]);

    if ($ekle) {
        $id = $db->lastInsertId(); 
        $response = ['status' => 'success', 'id' => $id];
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error']);
    }
} elseif ($islem == 'sil') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if ($id) {
        $sorgu = $db->prepare("DELETE FROM gelir_gider WHERE id = ?");
        $sil = $sorgu->execute([$id]);

        if ($sil) {
            echo 'OK';
        } else {
            echo 'HATA';
        }
    } else {
        echo 'HATA';
    }
} else {
    echo 'HATALI İŞLEM';
}
?>
