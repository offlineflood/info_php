Bu SQL kodu bir məhsul (product) kateqoriyası yaratmaq və bu kateqoriyada müxtəlif məhsullar haqqında məlumatları saxlamaq üçün istifadə olunur. Gəlin, bu kodu addım-addım izah edək:

### 1. `create database categorys;`
Bu sətir, `categorys` adında yeni bir verilənlər bazası yaradır. Verilənlər bazası məhsul kateqoriyalarını və bu kateqoriyalara aid olan məhsulları saxlamaq üçün istifadə ediləcək.

- **Niyə bu addım var?** Verilənlər bazası yaratmaq, məlumatların saxlanacağı yeri müəyyən etmək üçündür. Bu olmadan verilənlər bazasında cədvəllər və məlumatlar saxlamaq mümkün olmaz.

### 2. `use categorys;`
Bu sətir, əvvəl yaradılmış `categorys` verilənlər bazasını aktivləşdirir, yəni bu verilənlər bazası ilə işləmək üçün seçilir.

- **Niyə bu addım var?** Aktiv verilənlər bazasını seçmək, hansı verilənlər bazası ilə işləyəcəyinizi müəyyən etmək üçündür. Bu addım olmadan, SQL sorğularınızın hansı verilənlər bazasında işləyəcəyini bilməzsiniz.

### 3. `create table product( id int AUTO_INCREMENT PRIMARY KEY, description varchar(255), image varchar(255), price double );`
Bu sətir, `product` adlı bir cədvəl yaradır. Cədvəldə aşağıdakı sütunlar var:

- **`id int AUTO_INCREMENT PRIMARY KEY`:** Bu sütun məhsulun unikal identifikatorudur. `int` tipi ilə tanımlanıb və `AUTO_INCREMENT` xüsusiyyəti vasitəsilə hər yeni məhsul əlavə ediləndə avtomatik olaraq artırılır. `PRIMARY KEY` isə bu sütunun cədvəldə unikal olduğunu göstərir.
  
- **`description varchar(255)`:** Bu sütun məhsulun təsvirini saxlayır və maksimum 255 simvoldan ibarət ola bilər.

- **`image varchar(255)`:** Bu sütun məhsulun şəklinin fayl adını saxlayır və maksimum 255 simvoldan ibarət ola bilər.

- **`price double`:** Bu sütun məhsulun qiymətini saxlayır. `double` tipi onluq rəqəmlər üçün istifadə olunur.

- **Niyə bu addım var?** Cədvəl yaratmaq, məhsul məlumatlarını strukturlaşdırılmış şəkildə saxlamaq üçün vacibdir. Hər bir sütun fərqli məlumat tipləri üçün ayrılmışdır.

### 4. `insert into product(description,image,price)values ('Smartfon Samsung Galaxy S24 Ultra','1.png',2234), ('Smartfon Samsung Galaxy S24 Ultra','2.png',2234), ('Smartfon Samsung Galaxy S24 Ultra','3.png',2234), ('Smartfon Samsung Galaxy S24 Ultra','4.png',2234);`
Bu sətirlər, `product` cədvəlinə dörd yeni məhsul əlavə edir. Hər bir məhsul üçün təsvir, şəkil və qiymət verilir.

- **Niyə bu addım var?** Bu addım məhsul məlumatlarını cədvələ əlavə etmək üçün istifadə olunur. Hər bir `insert` əmri yeni bir məhsulun məlumatlarını cədvələ daxil edir.

### Nəticə:
Bu kod, əvvəlcə `categorys` adlı verilənlər bazası və `product` adlı cədvəl yaradır. Daha sonra bu cədvələ dörd məhsul haqqında məlumat əlavə edir. Bu məlumatlar, məhsulun təsviri, şəkli və qiymətindən ibarətdir. Verilənlər bazasında strukturlaşdırılmış məlumatları saxlamaq və onlarla işləmək üçün bu addımlar vacibdir.

<hr size="18"/>
Bu PHP kodu sadə bir alış-veriş səbəti funksionallığını təmin edir. Gəlin, kodun hər hissəsini ətraflı izah edək.

### PHP Hissəsi

#### 1. `session_start();`
- Bu funksiya istifadəçi üçün bir sessiyanı başladır. Sessiyalar istifadəçi məlumatlarını saxlayır və veb sayt boyunca onlara daxil olmağa imkan verir. Bu kodda sessiyalar istifadə edilərək alış-veriş səbətindəki məhsullar saxlanılır.

#### 2. `include 'my_connect.php';`
- Bu sətir, verilənlər bazası ilə əlaqə qurmaq üçün tələb olunan kodu daxil edir. `my_connect.php` faylı verilənlər bazası bağlantısı üçün lazımi məlumatları təmin edir.

#### 3. `if(isset($_POST["add"]))`
- Bu hissə, istifadəçi "Səbətə əlavə et" düyməsini basdıqda işə düşür. Yəni, məhsulun səbətə əlavə edilməsi üçün istifadə olunur.

   - **`if(isset($_SESSION["shopping_cart"]))`:** Bu hissə yoxlayır ki, alış-veriş səbəti artıq mövcuddurmu. Əgər səbət mövcuddursa, məhsulu səbətə əlavə edir.
   - **`$item_array_id = array_column($_SESSION["shopping_cart"],"product_id");`:** Bu sətir, artıq səbətdə olan məhsulların ID-lərini alır.
   - **`if(!in_array($_GET["id"],$item_array_id))`:** Bu yoxlayır ki, əlavə edilmək istənən məhsul artıq səbətdə varmı. Əgər məhsul səbətdə deyilsə, yeni məhsul səbətə əlavə olunur.
   - **`$_SESSION["shopping_cart"][$count] = $item_array;`:** Məhsul səbətə əlavə edilir və sessiyada saxlanılır.
   - **`else`:** Əgər məhsul artıq səbətdədirsə, bu halda xəbərdarlıq mesajı göstərilir ki, məhsul artıq səbətdədir.

   - **Əks halda:** Əgər səbət mövcud deyilsə, yeni bir səbət yaradılır və məhsul səbətə əlavə edilir.

#### 4. `if(isset($_GET["action"]))`
- Bu hissə, istifadəçi bir məhsulu səbətdən silmək istədikdə işə düşür. 
   - **`if($_GET["action"] == "delete")`:** Əgər istifadəçi "delete" əməliyyatını seçibsə, məhsul səbətdən silinir.
   - **`foreach($_SESSION["shopping_cart"] as $keys => $value)`:** Səbətdəki məhsullar arasında axtarış edilir.
   - **`if($value["product_id"] == $_GET["id"])`:** Əgər silinmək istənən məhsulun ID-si səbətdəki məhsulun ID-si ilə uyğunlaşırsa, məhsul silinir.
   - **`unset($_SESSION["shopping_cart"][$keys]);`:** Məhsul sessiyadan (yəni səbətdən) silinir.
   - **`echo '<script>alert("Məhsul silindi")</script>';`:** Məhsulun uğurla silindiyi barədə xəbərdarlıq mesajı göstərilir.

### HTML Hissəsi

#### 1. `<html>` və `<head>`
- Bu hissələr HTML sənədinin başlanğıcını və meta məlumatlarını ehtiva edir. Başlıqda `meta` etiketləri, `title`, və `link` etiketləri var. `link` etiketi Bootstrap kitabxanasını stil üçün daxil edir.

#### 2. `<body>`
- Bu hissədə HTML məzmunu göstərilir. Məzmun bir alış-veriş səhifəsini və alış-veriş səbətini əks etdirir.

#### 3. `<?php $query = "select * from product order by id asc"; ?>`
- Bu hissə verilənlər bazasından bütün məhsulları seçir və onları səhifədə göstərir.
   - **`$result = mysqli_query($conn,$query);`:** Seçilən məhsulların nəticələri alınır.
   - **`while($row = mysqli_fetch_array($result))`:** Bu dövrə vasitəsilə bütün məhsullar səhifədə göstərilir.
   - **Hər məhsul üçün:** Məhsulun şəkli, təsviri, qiyməti və səbətə əlavə etmək üçün bir düymə göstərilir.

#### 4. `<?php if(!empty($_SESSION["shopping_cart"])): ?>`
- Bu hissə alış-veriş səbətində məhsulların göstərilməsi üçün istifadə olunur.
   - **Hər məhsul üçün:** Məhsulun adı, miqdarı, qiyməti və ümumi qiyməti göstərilir. Həmçinin məhsulu səbətdən silmək üçün bir link də var.

   - **Cəmi:** Səbətdəki bütün məhsulların ümumi qiyməti hesablanır və göstərilir.

### Nəticə:
Bu PHP və HTML kodu sadə bir e-ticarət tətbiqinin əsasını təşkil edir. İstifadəçilər məhsulları səbətə əlavə edə, səbətdəki məhsulları görə və onları silə bilərlər. Verilənlər bazası ilə əlaqə qurmaq və istifadəçi sessiyalarını idarə etmək üçün PHP-dən istifadə olunur, HTML isə istifadəçi interfeysini yaratmaq üçün istifadə edilir.
