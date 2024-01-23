<?php
include "conn.php";
$sorguToplamGelir = $db->query(
    "SELECT SUM(miktar) as toplam_gelir FROM gelir_gider WHERE tur = 1"
);
$toplamGelir = $sorguToplamGelir->fetch(PDO::FETCH_ASSOC)["toplam_gelir"];
$sorguToplamGider = $db->query(
    "SELECT SUM(miktar) as toplam_gider FROM gelir_gider WHERE tur = 0"
);
$toplamGider = $sorguToplamGider->fetch(PDO::FETCH_ASSOC)["toplam_gider"];
$toplamKalan = $toplamGelir - $toplamGider;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gelir Gider Takibi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>


</head>
<body>

<div class="container">
        <div class="row clearfix">
            <div class="col-md-12 mx-auto mt-5">
                <div class="row">
                      <div class="col-sm-6" style="margin-bottom:20px">
                        <a href="javascript:void(0);" style="text-decoration: none;" onclick="gelirEkle()">
                            <div class="card bg-success bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h6 class="card-title">GELİR EKLE</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6" style="margin-bottom:20px">
                        <a href="javascript:void(0);" style="text-decoration: none;" onclick="giderEkle()">
                            <div class="card bg-danger bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h6 class="card-title">GİDER EKLE</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                      <div class="col-sm-4" style="margin-bottom:20px">
                        <div class="card bg-success bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h7 class="card-title" id="gelirCard">Gelir: <?= $toplamGelir ?> ₺</h7>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="margin-bottom:20px">
                        <div class="card bg-danger bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h7 class="card-title" id="giderCard">Gider: <?= $toplamGider ?> ₺</h7>
                            </div>
                        </div>
                    </div>
                    <?php if ($toplamKalan > 0) { ?>
                    <div class="col-sm-4" style="margin-bottom:20px">
                        <div class="card bg-primary bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h7 class="card-title" id="toplamCard">Toplam: <?= $toplamKalan ?> ₺</h7>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-4" style="margin-bottom:20px">
                        <div class="card bg-dark bg-gradient text-white" style="border-radius: 1.25rem;min-height: 50px;">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h7 class="card-title" id="toplamCard">Toplam: <?= $toplamKalan ?> ₺</h7>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="card p-3">
                    <div class="body">

                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tarih</th>
                                    <th>Tür</th>
                                    <th>Miktar</th>
                                    <th>Kategori</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sorgu = $db->prepare(
                                    "SELECT * FROM gelir_gider ORDER BY tarih DESC"
                                );
                                $sorgu->execute();

                                while ($Cek = $sorgu->fetch(PDO::FETCH_OBJ)) {
                                    $tarihTurkce = strftime(
                                        "%d.%m.%Y",
                                        strtotime($Cek->tarih)
                                    ); ?>
                                <tr id="tr_<?= $Cek->id ?>">
                                    <td><?= $Cek->id ?></td>
                                    <td><?= $tarihTurkce ?></td>
                                    <td class="text-center">
                                        <?php if ($Cek->tur == 1) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" alt="Gelir" aria-label="Gelir" class="bi bi-plus-circle-fill text-success" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
</svg>';
                                        } else {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" alt="Gider" aria-label="Gider" class="bi bi-dash-circle-fill text-danger" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1z"/>
</svg>';
                                        } ?>
                                    </td>
                                    <td><?= $Cek->miktar ?> ₺</td>
                                    <td><?= $Cek->kategori ?></td>
                                    <td>
                                        <button id="<?= $Cek->id ?>" class="btn btn-raised btn-danger" onclick="deleteRecord(<?= $Cek->id ?>)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash2-fill text-white" viewBox="0 0 16 16">
                                                <path d="M2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671zm9.89-.69C10.966 2.214 9.578 2 8 2c-1.58 0-2.968.215-3.926.534-.477.16-.795.327-.975.466.18.14.498.307.975.466C5.032 3.786 6.42 4 8 4s2.967-.215 3.926-.534c.477-.16.795-.327.975-.466-.18-.14-.498-.307-.975-.466z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>



<script>
    function tiklama() {
        var id = this.id;
            Swal.fire({
                position: "top-end",
                title: "Emin misiniz?",
                text: id + " numaralı kayıt silinecektir, bu işlem geri alınamaz!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Evet, sil!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = 'id=' + id;
                    var islemyap = $.ajax({
                        type: 'POST',
                        url: "ajax.php?islem=sil",
                        data: data,
                        dataType: 'html',
                        context: document.body,
                        global: false,
                        async: false,
                        success: function(data) {
                            return data;
                        }
                    }).responseText;
                }

                if (islemyap == 'OK') {
                    Swal.fire({
                        position: "top-end",
                        title: "Silindi!",
                        text: "Kayıt Silindi",
                        icon: "success"
                    });
                    $("#tr_" + id).fadeOut("slow");
                    location.reload();
                } else {
                    Swal.fire({
                        position: "top-end",
                        title: "İptal Edildi!",
                        text: "Kayıt Silinmedi",
                        icon: "error"
                    });
                }
            });
        
    }
    var buttons = document.getElementsByTagName("button");
    var buttonsCount = buttons.length;
    for (var i = 0; i < buttonsCount; i += 1) {
        buttons[i].onclick = tiklama;
    }
</script>

<script>
    function gelirEkle() {
        Swal.fire({
            title: 'Gelir Ekle',
            html:
                '<form id="gelirForm" class="m-5">' +
                '<input type="date" id="tarih" name="tarih" placeholder="Tarih giriniz" class="swal2-input" required>' +
                '<input type="number" id="miktar" name="miktar" placeholder="Miktar giriniz" class="swal2-input" required>' +
                '<input type="text" id="kategori" name="kategori" placeholder="Kategori giriniz" class="swal2-input" required>' +
                '</form>',
            showCancelButton: true,
            confirmButtonText: 'Kaydet',
            cancelButtonText: 'İptal',
            preConfirm: () => {
                const tarih = document.getElementById('tarih').value;
                const miktar = document.getElementById('miktar').value;
                const kategori = document.getElementById('kategori').value;

                return fetch('ajax.php?islem=gelir_ekle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `tarih=${tarih}&miktar=${miktar}&kategori=${kategori}`,
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        position: "top-end",
                        title: "Başarılı!",
                        text: "Gelir başarıyla eklendi",
                        icon: "success"
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        position: "top-end",
                        title: "Hata!",
                        text: "Bir hata oluştu, lütfen tekrar deneyin",
                        icon: "error"
                    });
                });
            }
        });
    }

    function giderEkle() {
        Swal.fire({
            title: 'Gider Ekle',
            html:
                '<form id="giderForm" class="m-5">' +
                '<input type="date" id="tarih" name="tarih" placeholder="Tarih giriniz" class="swal2-input" required>' +
                '<input type="number" id="miktar" name="miktar" placeholder="Miktar giriniz" class="swal2-input" required>' +
                '<input type="text" id="kategori" name="kategori" placeholder="Kategori giriniz" class="swal2-input" required>' +
                '</form>',
            showCancelButton: true,
            confirmButtonText: 'Kaydet',
            cancelButtonText: 'İptal',
            preConfirm: () => {
                const tarih = document.getElementById('tarih').value;
                const miktar = document.getElementById('miktar').value;
                const kategori = document.getElementById('kategori').value;

                return fetch('ajax.php?islem=gider_ekle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `tarih=${tarih}&miktar=${miktar}&kategori=${kategori}`,
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        position: "top-end",
                        title: "Başarılı!",
                        text: "Gider başarıyla eklendi",
                        icon: "success"
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        position: "top-end",
                        title: "Hata!",
                        text: "Bir hata oluştu, lütfen tekrar deneyin",
                        icon: "error"
                    });
                });
            }
        });
    }
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
    $('.js-exportable').DataTable({
        order: [[1, 'desc']],
        language: {
    "info": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
    "infoEmpty": "Kayıt yok",
    "infoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
    "infoThousands": ".",
    "lengthMenu": "Sayfada _MENU_ kayıt göster",
    "loadingRecords": "Yükleniyor...",
    "processing": "İşleniyor...",
    "search": "Ara:",
    "zeroRecords": "Eşleşen kayıt bulunamadı",
    "paginate": {
        "first": "İlk",
        "last": "Son",
        "next": "Sonraki",
        "previous": "Önceki"
    },
    "aria": {
        "sortAscending": ": artan sütun sıralamasını aktifleştir",
        "sortDescending": ": azalan sütun sıralamasını aktifleştir"
    },
    "select": {
        "rows": {
            "_": "%d kayıt seçildi",
            "1": "1 kayıt seçildi"
        },
        "cells": {
            "1": "1 hücre seçildi",
            "_": "%d hücre seçildi"
        },
        "columns": {
            "1": "1 sütun seçildi",
            "_": "%d sütun seçildi"
        }
    },
    "autoFill": {
        "cancel": "İptal",
        "fillHorizontal": "Hücreleri yatay olarak doldur",
        "fillVertical": "Hücreleri dikey olarak doldur",
        "fill": "Bütün hücreleri <i>%d<\/i> ile doldur",
        "info": "Detayı"
    },
    "buttons": {
        "collection": "Koleksiyon <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
        "colvis": "Sütun görünürlüğü",
        "colvisRestore": "Görünürlüğü eski haline getir",
        "copySuccess": {
            "1": "1 satır panoya kopyalandı",
            "_": "%ds satır panoya kopyalandı"
        },
        "copyTitle": "Panoya kopyala",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
            "-1": "Bütün satırları göster",
            "_": "%d satır göster",
            "1": "1 Satır Göster"
        },
        "pdf": "PDF",
        "print": "Yazdır",
        "copy": "Kopyala",
        "copyKeys": "Tablodaki veriyi kopyalamak için CTRL veya u2318 + C tuşlarına basınız. İptal etmek için bu mesaja tıklayın veya escape tuşuna basın.",
        "createState": "Şuanki Görünümü Kaydet",
        "removeAllStates": "Tüm Görünümleri Sil",
        "removeState": "Aktif Görünümü Sil",
        "renameState": "Aktif Görünümün Adını Değiştir",
        "savedStates": "Kaydedilmiş Görünümler",
        "stateRestore": "Görünüm -&gt; %d",
        "updateState": "Aktif Görünümün Güncelle"
    },
    "searchBuilder": {
        "add": "Koşul Ekle",
        "button": {
            "0": "Arama Oluşturucu",
            "_": "Arama Oluşturucu (%d)"
        },
        "condition": "Koşul",
        "conditions": {
            "date": {
                "after": "Sonra",
                "before": "Önce",
                "between": "Arasında",
                "empty": "Boş",
                "equals": "Eşittir",
                "not": "Değildir",
                "notBetween": "Dışında",
                "notEmpty": "Dolu"
            },
            "number": {
                "between": "Arasında",
                "empty": "Boş",
                "equals": "Eşittir",
                "gt": "Büyüktür",
                "gte": "Büyük eşittir",
                "lt": "Küçüktür",
                "lte": "Küçük eşittir",
                "not": "Değildir",
                "notBetween": "Dışında",
                "notEmpty": "Dolu"
            },
            "string": {
                "contains": "İçerir",
                "empty": "Boş",
                "endsWith": "İle biter",
                "equals": "Eşittir",
                "not": "Değildir",
                "notEmpty": "Dolu",
                "startsWith": "İle başlar",
                "notContains": "İçermeyen",
                "notStartsWith": "Başlamayan",
                "notEndsWith": "Bitmeyen"
            },
            "array": {
                "contains": "İçerir",
                "empty": "Boş",
                "equals": "Eşittir",
                "not": "Değildir",
                "notEmpty": "Dolu",
                "without": "Hariç"
            }
        },
        "data": "Veri",
        "deleteTitle": "Filtreleme kuralını silin",
        "leftTitle": "Kriteri dışarı çıkart",
        "logicAnd": "ve",
        "logicOr": "veya",
        "rightTitle": "Kriteri içeri al",
        "title": {
            "0": "Arama Oluşturucu",
            "_": "Arama Oluşturucu (%d)"
        },
        "value": "Değer",
        "clearAll": "Filtreleri Temizle"
    },
    "searchPanes": {
        "clearMessage": "Hepsini Temizle",
        "collapse": {
            "0": "Arama Bölmesi",
            "_": "Arama Bölmesi (%d)"
        },
        "count": "{total}",
        "countFiltered": "{shown}\/{total}",
        "emptyPanes": "Arama Bölmesi yok",
        "loadMessage": "Arama Bölmeleri yükleniyor ...",
        "title": "Etkin filtreler - %d",
        "showMessage": "Tümünü Göster",
        "collapseMessage": "Tümünü Gizle"
    },
    "thousands": ".",
    "datetime": {
        "amPm": [
            "öö",
            "ös"
        ],
        "hours": "Saat",
        "minutes": "Dakika",
        "next": "Sonraki",
        "previous": "Önceki",
        "seconds": "Saniye",
        "unknown": "Bilinmeyen",
        "weekdays": {
            "6": "Paz",
            "5": "Cmt",
            "4": "Cum",
            "3": "Per",
            "2": "Çar",
            "1": "Sal",
            "0": "Pzt"
        },
        "months": {
            "9": "Ekim",
            "8": "Eylül",
            "7": "Ağustos",
            "6": "Temmuz",
            "5": "Haziran",
            "4": "Mayıs",
            "3": "Nisan",
            "2": "Mart",
            "11": "Aralık",
            "10": "Kasım",
            "1": "Şubat",
            "0": "Ocak"
        }
    },
    "decimal": ",",
    "editor": {
        "close": "Kapat",
        "create": {
            "button": "Yeni",
            "submit": "Kaydet",
            "title": "Yeni kayıt oluştur"
        },
        "edit": {
            "button": "Düzenle",
            "submit": "Güncelle",
            "title": "Kaydı düzenle"
        },
        "error": {
            "system": "Bir sistem hatası oluştu (Ayrıntılı bilgi)"
        },
        "multi": {
            "info": "Seçili kayıtlar bu alanda farklı değerler içeriyor. Seçili kayıtların hepsinde bu alana aynı değeri atamak için buraya tıklayın; aksi halde her kayıt bu alanda kendi değerini koruyacak.",
            "noMulti": "Bu alan bir grup olarak değil ancak tekil olarak düzenlenebilir.",
            "restore": "Değişiklikleri geri al",
            "title": "Çoklu değer"
        },
        "remove": {
            "button": "Sil",
            "confirm": {
                "_": "%d adet kaydı silmek istediğinize emin misiniz?",
                "1": "Bu kaydı silmek istediğinizden emin misiniz?"
            },
            "submit": "Sil",
            "title": "Kayıtları sil"
        }
    },
    "stateRestore": {
        "creationModal": {
            "button": "Kaydet",
            "columns": {
                "search": "Kolon Araması",
                "visible": "Kolon Görünümü"
            },
            "name": "Görünüm İsmi",
            "order": "Sıralama",
            "paging": "Sayfalama",
            "scroller": "Kaydırma (Scrool)",
            "search": "Arama",
            "searchBuilder": "Arama Oluşturucu",
            "select": "Seçimler",
            "title": "Yeni Görünüm Oluştur",
            "toggleLabel": "Kaydedilecek Olanlar"
        },
        "duplicateError": "Bu Görünüm Daha Önce Tanımlanmış",
        "emptyError": "Görünüm Boş Olamaz",
        "emptyStates": "Herhangi Bir Görünüm Yok",
        "removeJoiner": "ve",
        "removeSubmit": "Sil",
        "removeTitle": "Görünüm Sil",
        "renameButton": "Değiştir",
        "renameLabel": "Görünüme Yeni İsim Ver -&gt; %s:",
        "renameTitle": "Görünüm İsmini Değiştir",
        "removeConfirm": "Görünümü silmek istediğinize emin misiniz?",
        "removeError": "Görünüm silinemedi"
    },
    "emptyTable": "Tabloda veri bulunmuyor",
    "searchPlaceholder": "Arayın..."
}  
    });
});
</script>

</body>
</html>
