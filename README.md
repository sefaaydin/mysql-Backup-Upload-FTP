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
$LOCFLDR = __DIR__.'/dumps/'; // __DIR__ -> Ana Klasör, /dumps/ klasörüne .sql dosyası yedeklenecek. Dilerseniz değiştirebilirsiniz.
$FTPFLDR = '/backup'; // Veritabanı sql dosyasının Uzak Sunucudaki yolu. Örneğin : /backup
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


# ENGLISH
This script backs up your MySQL/MariaDB database and optionally transfers it to the ftp server via CURL.
Mysqldump is used. You may want to do this automatically by creating a cronjob that runs this script.

## Installation
Simply upload the index.php file to a folder of your choice on your server. Just make sure that the file cannot be accessed by calling the URL.
So either protect the folder with htaccess or don't put it in your public folder.
Example address : domain.com/backup/index.php

Some shared web hosts do not allow the execution of mysqldump or gzip.
If this is the case, you can try renaming the extension to *.phpx or run PHP in CGI mode for this file.

## Usage
```php
mysqlBackupToCurlAndFTP($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $FTPHOST, $FTPUSER, $FTPPASS, $LOCFLDR, $FTPFLDR, $zipActive, $uploadFTP, $localBackupDelete);
```

## Settings
Set $status to 1 in line 71 to activate the backup service.
```php
$status = 1; // 1 = Active, 0 = Inactive
if ($status == 1) {
  // other codes
}
```

Enter the database information on line 52 for the backup.
```php
$DB_HOST = "localhost";
$DB_NAME = "dbname";
$DB_USER = "dbuser";
$DB_PASS = "dbpass";
```

To upload to the remote server by entering ftp information with CURL, enter ftp information in line 57.
```php
$FTPHOST = 'ftpaddress';
$FTPUSER = 'username';
$FTPPASS = 'password';
$LOCFLDR = __DIR__.'/dumps/'; // __DIR__ -> Main Folder, .sql file will be backed up to /dumps/ folder. You can change it if you wish.
$FTPFLDR = '/backup'; // The path of the database sql file on the Remote Server. For example : /backup
```

Adjust the settings on line 63 for customization.
```php
$zipActive = 0; // Set 1 to compress sql backup! 
$uploadFTP = 0; // Set 1 to upload backup to remote server!
$localBackupDelete = 0; // If FTP upload is active, set setting 1 to delete local backup files after FTP operation!
```

## Cron Usage 
With cron you can run cron to have automatic backups at certain intervals. 
For security, set the KEYs in line 33 and 34 according to yourself.
Example Use for Cron: https://domain.com/backup/index.php?KEY_GET1=688787d8f&KEY_GET2=3608bca1e
```php
define("KEY_GET1", "688787d8f");
define("KEY_GET2", "3608bca1e");
```