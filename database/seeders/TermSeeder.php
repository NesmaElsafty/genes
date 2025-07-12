<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = [
            // Terms and Conditions for Admin
            [
                'type' => 'terms',
                'title' => 'شروط وأحكام المدير',
                'description' => 'من خلال الوصول إلى نظام إدارة المزرعة كمدير، فإنك توافق على الشروط التالية: 1. لديك صلاحيات كاملة لإدارة المستخدمين والمزارع. 2. أنت مسؤول عن سلامة البيانات. 3. يمكنك تصدير البيانات. 4. يجب الحفاظ على سرية بيانات الدخول. 5. يجب الالتزام بجميع القوانين واللوائح.',
                'is_active' => true,
                'role' => 'admin',
            ],
            // Privacy Policy for Admin
            [
                'type' => 'privacy_policy',
                'title' => 'سياسة الخصوصية للمدير',
                'description' => 'سياسة الخصوصية هذه توضح كيفية جمع واستخدام وحماية معلوماتك كمدير. جميع البيانات مشفرة ويتم تقييد الوصول عليها. لديك الحق في طلب تصحيح أو حذف بياناتك.',
                'is_active' => true,
                'role' => 'admin',
            ],
            // Terms and Conditions for Client
            [
                'type' => 'terms',
                'title' => 'شروط وأحكام العميل',
                'description' => 'باستخدامك لهذا النظام كعميل، فإنك توافق على: 1. إدارة حيواناتك وأحداثها بدقة. 2. الحفاظ على سرية بيانات الدخول. 3. الالتزام بالقوانين المحلية. 4. يمكنك تصدير بيانات مزرعتك عند الحاجة.',
                'is_active' => true,
                'role' => 'client',
            ],
            // Privacy Policy for Client
            [
                'type' => 'privacy_policy',
                'title' => 'سياسة الخصوصية للعميل',
                'description' => 'نحن نهتم بخصوصيتك. يتم جمع بياناتك فقط لتقديم الخدمة وتحسينها. لا يتم مشاركة بياناتك مع أي طرف ثالث بدون إذنك.',
                'is_active' => true,
                'role' => 'client',
            ],
            // General Terms and Conditions (no specific role)
            [
                'type' => 'terms',
                'title' => 'الشروط والأحكام العامة',
                'description' => 'هذه الشروط تنطبق على جميع مستخدمي النظام: 1. الالتزام بدقة البيانات. 2. الحفاظ على سرية الحساب. 3. استخدام النظام للأغراض المصرح بها فقط.',
                'is_active' => true,
                'role' => null,
            ],
            // General Privacy Policy (no specific role)
            [
                'type' => 'privacy_policy',
                'title' => 'سياسة الخصوصية العامة',
                'description' => 'سياسة الخصوصية هذه تنطبق على جميع المستخدمين. يتم جمع البيانات الضرورية فقط ويتم حمايتها وفقًا لأعلى معايير الأمان.',
                'is_active' => true,
                'role' => null,
            ],
            // Inactive Terms for testing
            [
                'type' => 'terms',
                'title' => 'شروط غير نشطة',
                'description' => 'هذه الشروط غير نشطة ولا يجب عرضها للمستخدمين.',
                'is_active' => false,
                'role' => 'admin',
            ],
            [
                'type' => 'privacy_policy',
                'title' => 'سياسة خصوصية غير نشطة',
                'description' => 'هذه السياسة غير نشطة ولا يجب عرضها للمستخدمين.',
                'is_active' => false,
                'role' => 'client',
            ],
        ];

        foreach ($terms as $term) {
            Term::create($term);
        }
    }
} 