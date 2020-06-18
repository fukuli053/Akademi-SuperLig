# Ödev Açıklaması
Symfony ve Doctrine kullanarak Süper Lig sezonu simülasyonu yapmanız isteniyor.


## İstekler
 - Symfony ve Doctrine kullanılması
 - Üye girişi gibi bir sürece gerek yok
 - 18 adet takım'ı "Takım Ekle" butonuna tıklayıp açacağımız bir form sayfasında tek tek ekleyeceğiz. (Daha fazla takım eklenmemelidir)
 - 18 takım eklendikten sonra takımların listelendiği ana sayfada "Fikstür Oluştur" butonu görünecek. Bu butona tıklayınca 34 haftalık maç programı oluşturulacak. İlk 17 hafta her takım tüm takımlarla sırasıyla maç yapacak şekilde kurgulanacak. Sonraki 17 haftada ise ilk 17 haftada iç sahada oynayan takımlar aynı maçları deplasmanda oynayacak şekilde eşleştirilecektir.
 - Fikstür oluşturulduktan sonra fikstürü gördüğümüz sayfada her bir hafta için tek tek "Haftanın maçlarını oynat" butonu olacak. Butona basınca o haftanın maçları rastgele skor değerleri ile oynatılacak ve o haftanın sonuçları ile puan tablosu güncellenecektir. (Yani 5. hafta maçı oynanıyorsa 5.haftanın puan durumunu görülebilmeli)
 - 34 Hafta oynandıktan sonra proje son bulacak.
 - Form ve sayfaları twig template motoru ile render edilecek.
 - Her bir takım için takım adı girilmesi yeterli.
 - Her bir maç için her bir takım 0-5 arası gol atabilmeli.
 - Puan tablosunda bulunması istenen alanlar;
	 - Sıra
	 - Takım adı
	 - Maç sayısı
	 - Galibiyet Sayısı
	 - Beraberlik Sayısı
	 - Mağlubiyet Sayısı
	 - Attığı Gol Sayısı
	 - Yediği Gol Sayısı
	 - Averaj
	 - Puan
- Tasarımın önemi yok basit Bootstrap arayüzü kullanılabilir.
- Symfony Form yapısı kullanılmalı.
