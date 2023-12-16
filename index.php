<?php

    /* *****

        Sefa AYDIN

        İlk yayın tarihi : 16.12.2023
        Son Güncelleme Tarihi : 16.12.2023 17:50
        Versiyon : 1.0.0 (Beta)

        TR: Bu betik MySQL veritabanlarınızı yedekler ve isteğe bağlı olarak ftp sunucusuna aktarır.
        NOT: PAYLAŞIMLI HOSTINGLERDE shell_exec($command); KOMUTU ÇALIŞMAYABİLİR.

        Sonraki Geliştirme; 
            Çoklu FTP ve Çoklu Veritabanı yedekleme
            mysqldump özelliklerini özelleştirme.
            Yedeklenecek veritabanı adını kullanıcının değiştirilmesine izin verme
            İşlem sonucunda kullanıcıya mesaj metnini değiştirilme özelliği

            --dump-date --allow-keywords --add-drop-table --complete-insert --hex-blob --quote-names
            Bu komutun açıklamaları:
            --dump-date : Yedek alma işlemi tarihi
            --allow-keywords: MySQL anahtar kelimelerine izin verir.
            --add-drop-table: Tabloları oluşturmadan önce varsa düşürür (drop) ve yeniden oluşturur.
            --complete-insert: INSERT ifadesini kullanarak veriyi eksiksiz olarak ekler.
            --hex-blob: BLOB sütunları için hexadecimal formatı kullanır.
            --quote-names: Tablo ve sütun isimlerini alıntılar içine alır.
    ***** */

    // Güvenlik İçin Sabitler 
    // Example : https://domain.com/cron/dbBackup.php?KEY_GET1=688787d8f&KEY_GET2=3608bca1e

    define("KEY_GET1", "688787d8f");
    define("KEY_GET2", "3608bca1e");
    
    
    if ($_GET['KEY_GET1'] != KEY_GET1 || $_GET['KEY_GET2'] != KEY_GET2) {
        die("hack? bug bounty -> info@email.com");
    }

    /*----------------------------------------------------------------
    //Set defaults
    //----------------------------------------------------------------*/

    // Set errors & time limit
    error_reporting(E_ALL);
    set_time_limit(240);

    /*----------------------------------------------------------------------
        Aşağıdaki veritabanı ve ftp ayarlarını kendinize göre düzenleyin.
    //--------------------------------------------------------------------*/
    $DB_HOST = "localhost";
    $DB_NAME = "dbname";
    $DB_USER = "dbuser";
    $DB_PASS = "dbpass";

    $FTPHOST = 'ftpaddress';
    $FTPUSER = 'username';
    $FTPPASS = 'password';
    $LOCFLDR = __DIR__.'/dumps/'; // __DIR__ -> Ana Klasör, /dumps/ klasörüne .sql dosyası yedeklenecek. Dilerseniz değiştirebilirsiniz.
    $FTPFLDR = '/backup'; // Veritabanı sql dosyasının Uzak Sunucudaki yolu. Örneğin : /backup

    $zipActive = 0;         // sql yedeğini sıkıştırmak için ayarı 1 yapın! 
    $uploadFTP = 0;         // Uzak sunucuya yedeği yüklemek için ayarı 1 yapın!
    $localBackupDelete = 0; // eğer FTP yükleme aktif ise local yedek dosyalarını FTP yüklemesi bittikten sonra silmek için ayarı 1 yapın!

    /*---------------------------------------------------------------------- */

    function mysqlBackupToCurlAndFTP($host, $username, $password, $database, $ftpHost, $ftpUsername, $ftpPassword, $localFolders, $ftpDirectory, $zipActive, $uploadFTP, $localBackupDelete) {
        // Durum : Yedekleme hizmetinin aktif etmek için ayarı 1 yapın!
        $status = 1; // 1 = Aktif, 0 = Pasif

        if ($status == 1) {
            try {

                // Veritabanı yedeği al
                $backupFileName = $database . '_' . date('Ymd_His') . '.sql';
                $backupFilePath = $localFolders. $backupFileName;

                $command = "mysqldump --host=$host --user=$username --password=$password --dump-date --allow-keywords --add-drop-table --complete-insert --hex-blob --quote-names $database > $backupFilePath";
                shell_exec($command);

                if($zipActive == 1) {

                    // .zip dosyasını oluştur
                    $zipFileName = $backupFileName . '.zip';
                    $zipFilePath = $localFolders. $zipFileName;

                    $zip = new ZipArchive();
                    $zip->open($zipFilePath, ZipArchive::CREATE);
                    $zip->addFile($backupFilePath, $backupFileName);
                    $zip->close();

                }

                if($uploadFTP == 1){

                    // Curl ile dosyayı FTP'ye yükle
                    $ftpUrl = "ftp://$ftpUsername:$ftpPassword@$ftpHost/$ftpDirectory/$zipFileName";
                    $ch = curl_init();
                    $fp = fopen($zipFilePath, 'r');

                    curl_setopt($ch, CURLOPT_URL, $ftpUrl);
                    curl_setopt($ch, CURLOPT_UPLOAD, 1);
                    curl_setopt($ch, CURLOPT_INFILE, $fp);
                    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($zipFilePath));

                    $result = curl_exec($ch);

                    // Curl işlemini kapat
                    curl_close($ch);
                    fclose($fp);

                    if($localBackupDelete == 1){

                        // Dosyaları temizle
                        unlink($backupFilePath);
                        unlink($zipFilePath);
    
                    }
                }

                if ($result) {
                    $message = "OK";
                    return $message;
                } else {
                    $message = "Error!";
                    return $message;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }else{
            $message = "Yedekleme pasif durumda.";
            return $message;
        }
    }

    // Örnek kullanım
    mysqlBackupToCurlAndFTP($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $FTPHOST, $FTPUSER, $FTPPASS, $LOCFLDR, $FTPFLDR, $zipActive, $uploadFTP, $localBackupDelete);