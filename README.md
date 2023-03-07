<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# Teknasyon Challenge
## API [ MEDIUM ]

Bu başlıktaki geliştirmeleri aşağıdaki "Api Kullanımı" başlığı altında bulabilirsiniz.

## Worker [ MEDIUM ]

Bu başlıktaki geliştirme laravel schedule özelliği ile yapılmıştır
specific command veya php artisan "schedule:work" ile çalıştırılabilir "schedule:work" commandı çalıştığında saatte bir olacak şekilde kontrol sağlanmaktadır.

veya

Tek seferlik anlık olarak çalıştırılmak isteniyor ise

php artisan "app:check-subscriptions"

kullanılarak test edilebilir.

rate-limiting response'u ile karşılaşılan requestler

job/queue yöntemi ile dispatch edilmiştir.

## Callback [ BASIC ]

Bu başlıkta herhangi bir kayıt subscription modelinden updated veya created olduğu durumlarda koşula göre tespit edilerek third party api a post request i ile bildirimi sağlanmaktadır.

response ok dönmemesi durumunda daha sonra tekrar servise istek atılması için
job/queue yöntemi ile asenkron olacak şekilde tekrardan third party api'ye post işlemi sağlanmaktadır.






## API Kullanımı

Device register isteğinden gelen token'ı diğer isteklerde Header'a Client-Token olarak set edilmsei gerekmektedir
#### Device Registration Request

```Http
  POST /api/register

  {
      uid:              string|required
      appId:            string|required
      language:         string|required
      operating-system: string|required  
  }
```

#### Device Subscription Request

```http
  POST /api/purchase
   {
       receipt: string|required
  }
```
#### Device Subscription-Status Request
```http
  Get /api/check-subscription
   {
   }
```

## Database Şeması

![DB Şema](Diagram%201.jpg)

- [@DB Sql File](teknasyon.sql) Bu Link altında bulabilirsiniz.
