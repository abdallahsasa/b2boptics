<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_ads', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('products')->index(); // products, factories, both
            $table->json('badge')->nullable(); // Translatable
            $table->json('title'); // Translatable
            $table->json('subtitle')->nullable(); // Translatable
            $table->string('image')->nullable();
            $table->json('button_text')->nullable(); // Translatable
            $table->string('button_url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // Seed default banner ads for Products List & Factory Listing
        DB::table('banner_ads')->insert([
            [
                'page' => 'products',
                'badge' => json_encode([
                    'en' => 'Sponsored Showcase',
                    'ar' => 'عرض مُموَّل',
                    'tr' => 'Sponsorlu Vitrin',
                    'zh' => '赞助展示',
                    'de' => 'Gesponsertes Schaufenster',
                    'es' => 'Escaparate Patrocinado',
                    'fr' => 'Vitrine Sponsorisée',
                    'it' => 'Vetrina Sponsorizzata',
                    'ru' => 'Спонсорская витрина',
                ]),
                'title' => json_encode([
                    'en' => 'Next-Gen High-Index 1.74 Freeform Optical Lenses',
                    'ar' => 'الجيل الجديد من العدسات البصرية عالية المؤشر 1.74',
                    'tr' => 'Yeni Nesil Yüksek İndeksli 1.74 Freeform Optik Camlar',
                    'zh' => '新一代 1.74 高折射率自由曲面光学镜片',
                    'de' => 'Next-Gen High-Index 1.74 Freiform-Brillengläser',
                    'es' => 'Lentes Ópticas Freeform de Alto Índice 1.74 de Nueva Generación',
                    'fr' => 'Verres Optiques Freeform Haut Indice 1.74 Nouvelle Génération',
                    'it' => 'Lenti Ottiche Freeform ad Alto Indice 1.74 di Nuova Generazione',
                    'ru' => 'Высокоиндексные оптические линзы 1.74 Freeform нового поколения',
                ]),
                'subtitle' => json_encode([
                    'en' => 'Ultra-thin aspheric precision with Blue-Cut & super-hydrophobic AR coating. Direct wholesale factory pricing with ISO & CE certification.',
                    'ar' => 'دقة لا كروية فائقة النحافة مع طبقة حماية من الأشعة الزرقاء وطلاء مضاد للانعكاس. أسعار جملة مباشرة من المصنع مع شهادات ISO و CE.',
                    'tr' => 'Mavi ışık korumalı ve süper hidrofobik AR kaplamalı ultra ince asferik hassasiyet. ISO ve CE sertifikalı doğrudan fabrika toptan fiyatları.',
                    'zh' => '超薄非球面精密设计，配备防蓝光与超疏水减反射镀膜。ISO与CE认证工厂直供批发价。',
                    'de' => 'Ultradünne asphärische Präzision mit Blaulichtfilter & superhydrophober AR-Beschichtung. Direkte Großhandelspreise ab Werk mit ISO- & CE-Zertifizierung.',
                    'es' => 'Precisión asférica ultrafina con filtro de luz azul y revestimiento AR superhidrofóbico. Precios directos de fábrica al por mayor con certificación ISO y CE.',
                    'fr' => 'Précision asphérique ultra-fine avec filtre anti-lumière bleue et traitement AR super-hydrophobe. Prix de gros directs usine certifiés ISO & CE.',
                    'it' => 'Precisione asferica ultrasottile con filtro luce blu e trattamento AR super-idrofobico. Prezzi all\'ingrosso diretti dalla fabbrica con certificazione ISO e CE.',
                    'ru' => 'Ультратонкая асферическая точность с защитой от синего света и супергидрофобным AR-покрытием. Прямые оптовые цены от завода с сертификацией ISO и CE.',
                ]),
                'image' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1600&auto=format&fit=crop',
                'button_text' => json_encode([
                    'en' => 'Request Wholesale Quote',
                    'ar' => 'اطلب عرض أسعار للجملة',
                    'tr' => 'Toptan Fiyat Teklifi İste',
                    'zh' => '获取批发报价',
                    'de' => 'Großhandelsangebot anfordern',
                    'es' => 'Solicitar Cotización al por Mayor',
                    'fr' => 'Demander un Devis de Gros',
                    'it' => 'Richiedi Preventivo all\'Ingrosso',
                    'ru' => 'Запросить оптовое предложение',
                ]),
                'button_url' => '/find-deal',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'factories',
                'badge' => json_encode([
                    'en' => 'Featured OEM / ODM Partner',
                    'ar' => 'شريك تصنيع OEM / ODM مميز',
                    'tr' => 'Öne Çıkan OEM / ODM Ortağı',
                    'zh' => '精选 OEM / ODM 合作伙伴',
                    'de' => 'Empfohlener OEM / ODM Partner',
                    'es' => 'Socio OEM / ODM Destacado',
                    'fr' => 'Partenaire OEM / ODM en Vedette',
                    'it' => 'Partner OEM / ODM in Evidenza',
                    'ru' => 'Рекомендуемый OEM / ODM партнер',
                ]),
                'title' => json_encode([
                    'en' => 'Partner With Audited Titanium & Acetate Frame Manufacturers',
                    'ar' => 'تعاون مع مصانع إطارات التيتانيوم والأسيتات المعتمدة',
                    'tr' => 'Denetlenmiş Titanyum ve Asetat Çerçeve Üreticileriyle Çalışın',
                    'zh' => '与经过审核的钛金属和板材镜架制造商合作',
                    'de' => 'Arbeiten Sie mit geprüften Titan- & Acetat-Fassungsherstellern zusammen',
                    'es' => 'Asóciese con Fabricantes Auditados de Monturas de Titanio y Acetato',
                    'fr' => 'Collaborez avec des Fabricants Audités de Montures en Titane et Acétate',
                    'it' => 'Collabora con Produttori Certificati di Montature in Titanio e Acetato',
                    'ru' => 'Сотрудничайте с проверенными производителями оправ из титана и ацетата',
                ]),
                'subtitle' => json_encode([
                    'en' => 'Custom private-label eyewear production, low MOQ starting at 300 pcs, 3D prototyping, and global door-to-door logistics.',
                    'ar' => 'إنتاج نظارات بعلامات تجارية خاصة، حد أدنى للطلب يبدأ من 300 قطعة، نماذج أولية ثلاثية الأبعاد، وشحن عالمي من الباب إلى الباب.',
                    'tr' => 'Özel markalı gözlük üretimi, 300 adetten başlayan düşük MOQ, 3B prototipleme ve kapıdan kapıya küresel lojistik.',
                    'zh' => '定制自有品牌眼镜生产，低至 300 件起订量，3D 原型设计及全球门到门物流服务。',
                    'de' => 'Maßgeschneiderte Private-Label-Brillenproduktion, niedrige MOQ ab 300 Stück, 3D-Prototyping und weltweite Tür-zu-Tür-Logistik.',
                    'es' => 'Producción personalizada de gafas de marca propia, MOQ bajo desde 300 uds., prototipado 3D y logística global puerta a puerta.',
                    'fr' => 'Production de lunettes en marque blanche sur mesure, faible MOQ dès 300 pcs, prototypage 3D et logistique mondiale porte-à-porte.',
                    'it' => 'Produzione personalizzata di occhiali private-label, MOQ ridotto da 300 pezzi, prototipazione 3D e logistica globale door-to-door.',
                    'ru' => 'Производство очков под собственной маркой, низкий MOQ от 300 шт., 3D-прототипирование и глобальная логистика «от двери до двери».',
                ]),
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1600&auto=format&fit=crop',
                'button_text' => json_encode([
                    'en' => 'Submit Custom RFQ',
                    'ar' => 'إرسال طلب تسعير مخصص',
                    'tr' => 'Özel Teklif Talebi Gönder',
                    'zh' => '提交定制询价 (RFQ)',
                    'de' => 'Individuelle Anfrage senden',
                    'es' => 'Enviar Solicitud de Cotización',
                    'fr' => 'Soumettre un Appel d\'Offres',
                    'it' => 'Invia Richiesta di Quotazione',
                    'ru' => 'Отправить запрос (RFQ)',
                ]),
                'button_url' => '/find-deal',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_ads');
    }
};
