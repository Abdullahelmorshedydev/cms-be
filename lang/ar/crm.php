<?php

return [
    'module_name' => 'إدارة علاقات العملاء',
    'leads' => 'العملاء المحتملون',
    'contacts' => 'جهات الاتصال',
    'deals' => 'الصفقات',
    'calls' => 'المكالمات',
    'activities' => 'الأنشطة',
    'transactions' => 'المعاملات',
    'pipeline' => 'خط الأنابيب',

    // Lead
    'lead' => 'عميل محتمل',
    'lead_number' => 'رقم العميل المحتمل',
    'name' => 'الاسم',
    'name_en' => 'الاسم (إنجليزي)',
    'name_ar' => 'الاسم (عربي)',
    'email' => 'البريد الإلكتروني',
    'phone' => 'الهاتف',
    'secondary_phone' => 'هاتف ثانوي',
    'whatsapp' => 'واتساب',
    'source' => 'المصدر',
    'priority' => 'الأولوية',
    'assigned_to' => 'مسند إلى',
    'expected_value' => 'القيمة المتوقعة',
    'expected_close_date' => 'تاريخ الإغلاق المتوقع',

    // Lead Status
    'lead_status' => [
        'new' => 'جديد',
        'contacted' => 'تم الاتصال',
        'qualified' => 'مؤهل',
        'proposal' => 'عرض',
        'negotiation' => 'تفاوض',
        'won' => 'مكتسب',
        'lost' => 'مفقود',
    ],

    // Lead Source
    'lead_source' => [
        'website' => 'الموقع الإلكتروني',
        'phone' => 'هاتف',
        'social_media' => 'وسائل التواصل الاجتماعي',
        'referral' => 'إحالة',
        'walk_in' => 'زيارة مباشرة',
        'campaign' => 'حملة',
        'other' => 'أخرى',
    ],

    // Priority
    'priority' => [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'urgent' => 'عاجلة',
    ],

    // Contact
    'contact' => 'جهة اتصال',
    'contact_number' => 'رقم جهة الاتصال',
    'contact_type' => [
        'student' => 'طالب',
        'parent' => 'ولي أمر',
        'corporate' => 'شركة',
        'individual' => 'فرد',
        'other' => 'أخرى',
    ],
    'address' => 'العنوان',
    'city' => 'المدينة',
    'country' => 'الدولة',
    'birth_date' => 'تاريخ الميلاد',
    'national_id' => 'الرقم الوطني',
    'tags' => 'الوسوم',

    // Deal
    'deal' => 'صفقة',
    'deal_number' => 'رقم الصفقة',
    'title' => 'العنوان',
    'title_en' => 'العنوان (إنجليزي)',
    'title_ar' => 'العنوان (عربي)',
    'value' => 'القيمة',
    'discount' => 'الخصم',
    'final_value' => 'القيمة النهائية',
    'probability' => 'الاحتمالية',

    // Deal Stage
    'deal_stage' => [
        'prospecting' => 'التنقيب',
        'qualification' => 'التأهيل',
        'proposal' => 'العرض',
        'negotiation' => 'التفاوض',
        'closed_won' => 'مغلقة - مكتسبة',
        'closed_lost' => 'مغلقة - مفقودة',
    ],

    // Call
    'call' => 'مكالمة',
    'call_number' => 'رقم المكالمة',
    'call_type' => [
        'inbound' => 'واردة',
        'outbound' => 'صادرة',
    ],
    'call_status' => [
        'completed' => 'مكتملة',
        'missed' => 'فائتة',
        'busy' => 'مشغول',
        'no_answer' => 'بدون رد',
        'scheduled' => 'مجدولة',
    ],
    'duration' => 'المدة',
    'outcome' => 'النتيجة',

    // Activity
    'activity' => 'نشاط',
    'activity_type' => [
        'call' => 'مكالمة',
        'email' => 'بريد إلكتروني',
        'meeting' => 'اجتماع',
        'task' => 'مهمة',
        'note' => 'ملاحظة',
        'whatsapp' => 'واتساب',
        'sms' => 'رسالة نصية',
        'visit' => 'زيارة',
        'other' => 'أخرى',
    ],
    'description' => 'الوصف',
    'due_date' => 'تاريخ الاستحقاق',
    'completed_at' => 'تاريخ الإكمال',

    // Transaction
    'transaction' => 'معاملة',
    'transaction_number' => 'رقم المعاملة',
    'amount' => 'المبلغ',
    'payment_method' => 'طريقة الدفع',
    'transaction_type' => 'نوع المعاملة',
    'reference_number' => 'الرقم المرجعي',

    // Messages
    'lead_created' => 'تم إنشاء العميل المحتمل بنجاح',
    'lead_updated' => 'تم تحديث العميل المحتمل بنجاح',
    'lead_deleted' => 'تم حذف العميل المحتمل بنجاح',
    'contact_created' => 'تم إنشاء جهة الاتصال بنجاح',
    'contact_updated' => 'تم تحديث جهة الاتصال بنجاح',
    'contact_deleted' => 'تم حذف جهة الاتصال بنجاح',
    'deal_created' => 'تم إنشاء الصفقة بنجاح',
    'deal_updated' => 'تم تحديث الصفقة بنجاح',
    'deal_deleted' => 'تم حذف الصفقة بنجاح',
    'deal_stage_updated' => 'تم تحديث مرحلة الصفقة بنجاح',
    'call_logged' => 'تم تسجيل المكالمة بنجاح',
    'call_ended' => 'تم إنهاء المكالمة بنجاح',
    'activity_created' => 'تم إنشاء النشاط بنجاح',
    'activity_completed' => 'تم تعليم النشاط كمكتمل',
    'transaction_processed' => 'تم معالجة المعاملة بنجاح',
    'email_sent' => 'تم إرسال البريد الإلكتروني بنجاح',
    'email_failed' => 'فشل إرسال البريد الإلكتروني',
];

