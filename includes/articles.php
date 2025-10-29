<?php

$categories = [
    'gundem' => 'Gündem',
    'ekonomi' => 'Ekonomi',
    'dunya' => 'Dünya',
    'teknoloji' => 'Teknoloji',
    'saglik' => 'Sağlık',
    'spor' => 'Spor',
    'kultur' => 'Kültür & Sanat',
];

$articles = [
    [
        'slug' => 'istanbulda-ulasimda-yeni-donem',
        'title' => 'İstanbul’da Ulaşımda Yeni Dönem Başlıyor',
        'excerpt' => 'Mega kentte ulaşım ağını güçlendirecek yeni projede açılış için geri sayım başladı.',
        'content' => [
            'Ulaştırma ve Altyapı Bakanlığı, İstanbul’da kent içi ulaşımı rahatlatacak yeni metro hattının açılış tarihini duyurdu. Yetkililer, hattın günlük 1,2 milyon yolcuya hizmet vereceğini belirtti.',
            'Uzmanlar, projenin özellikle sabah ve akşam trafiğini büyük ölçüde hafifleteceğini ifade ediyor. Vatandaşlar ise kısa sürede bitirilmesini bekledikleri projenin ekonomi ve çevre için önemli bir adım olduğuna dikkat çekti.',
            'Açılış töreninin önümüzdeki ay Cumhurbaşkanı ve çok sayıda davetlinin katılımıyla yapılması planlanıyor. Tören kapsamında yerli teknolojilerin tanıtılacağı bir sergi de düzenlenecek.'
        ],
        'category' => 'gundem',
        'author' => 'Elif Korkmaz',
        'source' => 'AA',
        'image' => 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-16T08:15:00+03:00',
        'reading_time' => 4,
        'tags' => ['metro', 'ulaşım', 'altyapı']
    ],
    [
        'slug' => 'turkiyenin-yapay-zeka-stratejisi-aciklandi',
        'title' => 'Türkiye’nin Yapay Zekâ Stratejisi Açıklandı',
        'excerpt' => 'Kamu ve özel sektör temsilcilerinin katıldığı zirvede, yapay zekâ yatırımlarında yeni dönem resmen başladı.',
        'content' => [
            'Cumhurbaşkanlığı Dijital Dönüşüm Ofisi tarafından açıklanan strateji, Türkiye’nin 2030 yılına kadar yapay zekâ alanında bölgesel lider olmasını hedefliyor.',
            'Strateji belgesinde eğitim, sağlık, savunma ve tarım başta olmak üzere birçok sektörde yapay zekâ uygulamalarının yaygınlaştırılması öngörülüyor.',
            'Belge kapsamında yerli girişimlere 5 milyar TL’yi aşkın destek sağlanması ve uluslararası iş birliklerinin artırılması bekleniyor.'
        ],
        'category' => 'teknoloji',
        'author' => 'Selim Arslan',
        'source' => 'AA',
        'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-15T11:45:00+03:00',
        'reading_time' => 5,
        'tags' => ['yapay zekâ', 'startuplar', 'teknoloji yatırımları']
    ],
    [
        'slug' => 'global-piyasalarda-enflasyon-baskisi',
        'title' => 'Global Piyasalarda Enflasyon Baskısı Sürüyor',
        'excerpt' => 'Dünya genelinde artan gıda ve enerji fiyatları, merkez bankalarını yeni adımlar atmaya zorluyor.',
        'content' => [
            'ABD ve Avrupa merkez bankalarının faiz artışlarına rağmen enflasyon baskısının sürdüğü belirtiliyor. Ekonomistler, enerji fiyatlarındaki oynaklığın en önemli risk olduğunu vurguladı.',
            'Uzmanlar, gelişmekte olan ülkelerin döviz rezervlerini güçlendirmesi ve mali disiplin politikalarına ağırlık vermesi gerektiğini ifade etti.',
            'Türkiye’de ise enflasyonun yılın ikinci yarısında kademeli olarak gerilemesi bekleniyor. Analistler, ihracat performansındaki artışın cari dengeye olumlu katkı sağlayacağını düşünüyor.'
        ],
        'category' => 'ekonomi',
        'author' => 'Merve Yılmaz',
        'source' => 'Bloomberg HT',
        'image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-16T07:30:00+03:00',
        'reading_time' => 6,
        'tags' => ['ekonomi', 'enflasyon', 'piyasalar']
    ],
    [
        'slug' => 'avrupada-saglikta-yeni-dijital-atilim',
        'title' => 'Avrupa’da Sağlıkta Yeni Dijital Atılım',
        'excerpt' => 'Avrupa Birliği, dijital sağlık kayıtlarını üye ülkelerde ortak standartlara taşıyacak yeni programını duyurdu.',
        'content' => [
            'Program kapsamında hasta verilerinin güvenli şekilde paylaşılması için blok zinciri tabanlı çözümler test edilecek. Ayrıca yapay zekâ destekli teşhis uygulamaları da teşvik edilecek.',
            'Sağlık Bakanı Fahrettin Koca, Türkiye’nin de projeye gözlemci ülke olarak katılacağını ve deneyimlerden faydalanmak istediğini söyledi.',
            'Uzmanlar, dijital dönüşümün özellikle kırsal bölgelerde sağlık hizmetlerine erişimi artıracağını savunuyor.'
        ],
        'category' => 'saglik',
        'author' => 'Deniz Demir',
        'source' => 'Euronews',
        'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-14T09:05:00+03:00',
        'reading_time' => 3,
        'tags' => ['sağlık', 'dijital dönüşüm', 'avrupa birliği']
    ],
    [
        'slug' => 'milli-takim-euro-2028-hazirliklari',
        'title' => 'Milli Takım EURO 2028 Hazırlıklarını Hızlandırdı',
        'excerpt' => 'A Milli Futbol Takımı, EURO 2028 yolunda Antalya kampında yoğun tempoda çalışıyor.',
        'content' => [
            'Teknik direktör, genç oyunculara şans vererek takımın dinamizmini artırmayı hedefliyor. Kamp süresince üç hazırlık maçı yapılacağı açıklandı.',
            'Oyuncular, yeni antrenman tesislerinin modern teknolojilerle donatılmasının performanslarını artırdığını belirtti.',
            'Federasyon, taraftarların kamp sürecini dijital platformlardan canlı takip edebileceğini duyurdu.'
        ],
        'category' => 'spor',
        'author' => 'Ayşe Gür',
        'source' => 'TRT Spor',
        'image' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-13T18:20:00+03:00',
        'reading_time' => 3,
        'tags' => ['milli takım', 'futbol', 'euro 2028']
    ],
    [
        'slug' => 'uluslararasi-film-festivalinde-turk-damgasi',
        'title' => 'Uluslararası Film Festivalinde Türk Damgası',
        'excerpt' => 'Bu yıl 45’incisi düzenlenen festivalde, genç yönetmenlerin filmleri büyük ilgi gördü.',
        'content' => [
            'Festival kapsamında gösterilen beş Türk yapımı film, uluslararası basında geniş yer buldu. Yönetmenler, özgün hikâyeleri ve güçlü oyunculuklarıyla takdir topladı.',
            'Jüri üyeleri, Türkiye’den gelen yapımların teknik anlamda da büyük ilerleme kaydettiğini vurguladı. Film ekipleri, ortak yapım projeleri için yeni anlaşmalar yapmaya hazırlanıyor.',
            'Festivalin kapanış gecesinde Türk yönetmenlerden biri "En İyi Senaryo" ödülünü alarak büyük sevinç yaşadı.'
        ],
        'category' => 'kultur',
        'author' => 'Mustafa Kaplan',
        'source' => 'Anadolu Ajansı',
        'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-12T21:50:00+03:00',
        'reading_time' => 4,
        'tags' => ['sinema', 'festival', 'kültür sanat']
    ],
    [
        'slug' => 'anadoluda-yukselen-tarim-teknolojileri',
        'title' => 'Anadolu’da Yükselen Tarım Teknolojileri',
        'excerpt' => 'Yerel girişimler, tarımsal üretimi artırmak için sensör ve drone çözümleri geliştiriyor.',
        'content' => [
            'Konya ve Şanlıurfa’da faaliyet gösteren tarım teknoloji şirketleri, sulama ve gübrelemeyi optimize eden yapay zekâ destekli sistemleri tanıttı.',
            'Çiftçiler, dronelar sayesinde tarlaların anlık durumunu takip ederek verimlilikte %30’a varan artış yakalıyor. Proje kapsamında devlet desteklerinin de genişletilmesi bekleniyor.',
            'Uzmanlar, tarım teknolojilerinin kırsal kalkınma ve gıda güvenliği için kritik önem taşıdığını vurguluyor.'
        ],
        'category' => 'dunya',
        'author' => 'Gamze Yıldız',
        'source' => 'Reuters',
        'image' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1400&q=80',
        'published_at' => '2025-05-11T14:10:00+03:00',
        'reading_time' => 5,
        'tags' => ['tarım', 'teknoloji', 'girişimcilik']
    ]
];

$editorialPicks = [
    [
        'title' => 'Uzay Turizminde Yeni Hedefler',
        'link' => '#',
    ],
    [
        'title' => 'Sürdürülebilir Enerjide Türkiye’nin Rotası',
        'link' => '#',
    ],
    [
        'title' => 'Gençler İçin Dijital Okuryazarlık Programları',
        'link' => '#',
    ],
];

$liveTicker = [
    'Borsa İstanbul güne %1,2 yükselişle başladı.',
    'Dolar/TL 32,45 seviyesinde dengelendi.',
    'Avrupa Merkez Bankası faiz kararını bugün açıklayacak.',
    'Sağlık Bakanlığı aile hekimliği sisteminde yeni düzenleme yapıyor.',
];
