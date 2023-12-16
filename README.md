# Mysql Backup And Upload FTP (CURL)

# TÜRKÇE
Bu betik MySQL/MariaDB veritabanınızı yedekler ve isteğe bağlı olarak ftp sunucusuna CURL ile aktarır.
Mysqldump kullanılmaktadır. Bu betiği çalıştıran bir cronjob oluşturarak bunu otomatik olarak yapmak isteyebilirsiniz.

## Kurulum
index.php dosyasını sunucunuzda dilediğiniz bir klasöre yüklemeniz yeterlidir. Yalnızca URL'yi çağırarak dosyaya erişilemediğinden emin olun.
Bu nedenle ya klasörü htaccess ile koruyun ya da ortak klasörünüze koymayın.
Örnek adres : domain.com/backup/index.php

Bazı paylaşılan web barındırıcıları mysqldump veya gzip komutunun yürütülmesine izin vermez.
Eğer durum buysa, uzantıyı *.phpx olarak yeniden adlandırmayı deneyebilir veya bu dosya için PHP'yi CGI modunda çalıştırabilirsiniz.

## Kullanım
```php
mysqlBackupToCurlAndFTP($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $FTPHOST, $FTPUSER, $FTPPASS, $LOCFLDR, $FTPFLDR, $zipActive, $uploadFTP, $localBackupDelete);
```

## Ayarlar
Yedekleme hizmetini aktif hale getirmek için 71. satırdaki $status ayarını 1 yapın.
```php
$status = 1; // 1 = Aktif, 0 = Pasif
if ($status == 1) {
  // diğer kodlar
}
```

Yedekleme için 52. satırdaki veritabanı bilgilerini girin.
```php
$DB_HOST = "localhost";
$DB_NAME = "dbname";
$DB_USER = "dbuser";
$DB_PASS = "dbpass";
```

Uzak sunucuya CURL ile ftp bilgilerini girerek yükleme yapmak için 57. satırda ftp bilgilerini girin.
```php
$FTPHOST = 'ftpaddress';
$FTPUSER = 'username';
$FTPPASS = 'password';
$LOCFLDR = __DIR__.'/dumps/';
$FTPFLDR = '/backup';
```

Özelleştirme için 63. satırdaki ayarları yapın.
```php
$zipActive = 0;         // sql yedeğini sıkıştırmak için ayarı 1 yapın! 
$uploadFTP = 0;         // Uzak sunucuya yedeği yüklemek için ayarı 1 yapın!
$localBackupDelete = 0; // Eğer FTP yükleme aktif ise FTP işlemi sonrası local yedek dosyalarını silmek için ayarı 1 yapın!
```

## Cron Kullanımı 
Cron ile belli aralıklarla otomatik yedek aldırmak için cron çalıştırabilirsiniz. 
güvenlik için 33. ve 34. satırdaki KEY'leri kendinize göre ayarlayın.
Cron için Örnek Kullanım: https://domain.com/backup/index.php?KEY_GET1=688787d8f&KEY_GET2=3608bca1e
```php
define("KEY_GET1", "688787d8f");
define("KEY_GET2", "3608bca1e");
```
