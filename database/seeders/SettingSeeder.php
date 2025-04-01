<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->create([
            'site_name'        => 'سامانه مدیریت یکپارچه آموزشگاه آموزشیار',
            'site_description' => 'سامانه "آموزشیار" یک پلتفرم هوشمند برای مدیریت تمامی فرآیندهای آموزشی و اداری آموزشگاه‌ها است. این سیستم با امکاناتی مانند ثبت نام دانش‌آموزان، برنامه‌ریزی کلاس‌ها، مدیریت مالی، ارتباط با والدین و گزارش‌دهی پیشرفته، به مدیران و معلمان کمک می‌کند تا با کارایی بالاتر و سادگی بیشتر، فرآیند یاددهی-یادگیری را مدیریت کنند. قابلیت‌های آنلاین و پشتیبانی از اپلیکیشن موبایل، دسترسی آسان و سریع را برای همه کاربران فراهم می‌کند.',
            'site_logo'        => 'images/site_logo.png',
            'site_favicon'     => 'images/site_logo.png',
            'theme_color'      => '#671CC9',
            'copyright'        => 'تمامی حقوق متعلق به تیم توسعه دهنده آموزشیار می باشد.',
            'social_network'   => [
                'whatsapp'  => null,
                'instagram' => null,
                "youtube"   => null,
                "facebook"  => null,
                "linkedin"  => null,
                "telegram"  => null,
                "pinterest" => null,
                "x_twitter" => null
            ],
        ]);
    }
}
