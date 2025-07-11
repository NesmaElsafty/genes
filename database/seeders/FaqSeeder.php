<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Admin FAQs
            [
                'question' => 'How do I manage user accounts?',
                'answer' => 'You can manage user accounts through the admin panel. Navigate to Users section to view, edit, or delete user accounts. You can also assign roles and manage permissions.',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'question' => 'What are the different user roles available?',
                'answer' => 'The system supports multiple roles: Admin (full access), Client (limited access), and Manager (moderate access). Each role has specific permissions and access levels.',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'question' => 'How can I export data from the system?',
                'answer' => 'Data export is available in the admin panel. You can export user data, farm information, and other records in CSV format. Use the export buttons in respective sections.',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'question' => 'How do I manage farm permissions?',
                'answer' => 'Farm permissions can be managed through the Farms section. You can assign users to farms, set access levels, and manage farm-specific settings.',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'question' => 'What is the bulk operations feature?',
                'answer' => 'Bulk operations allow you to perform actions on multiple items at once. You can bulk delete, toggle status, or update multiple records simultaneously.',
                'is_active' => true,
                'role' => 'admin',
            ],

            // Client FAQs
            [
                'question' => 'How do I add a new animal to my farm?',
                'answer' => 'To add a new animal, go to the Animals section and click "Add New Animal". Fill in the required information including type, breed, and other details.',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'question' => 'How can I track animal events?',
                'answer' => 'Animal events can be tracked through the Events section. You can add events like vaccinations, treatments, breeding, and other important activities.',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'question' => 'What types of animals can I manage?',
                'answer' => 'The system supports various animal types including cattle, sheep, goats, pigs, and poultry. Each type has specific breeds and management requirements.',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'question' => 'How do I view my farm statistics?',
                'answer' => 'Farm statistics are available in the dashboard. You can view animal counts, event summaries, and performance metrics for your farm.',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'question' => 'Can I export my farm data?',
                'answer' => 'Yes, you can export your farm data including animal lists, event records, and other information. Use the export features in respective sections.',
                'is_active' => true,
                'role' => 'client',
            ],

            // General FAQs (no specific role)
            [
                'question' => 'How do I reset my password?',
                'answer' => 'To reset your password, use the "Forgot Password" link on the login page. Enter your email address and follow the instructions sent to your email.',
                'is_active' => true,
                'role' => null,
            ],
            [
                'question' => 'What browsers are supported?',
                'answer' => 'The application supports all modern browsers including Chrome, Firefox, Safari, and Edge. For the best experience, use the latest version of your browser.',
                'is_active' => true,
                'role' => null,
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'For technical support, please contact our support team through the contact form or email support@farmmanagement.com. We typically respond within 24 hours.',
                'is_active' => true,
                'role' => null,
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'Yes, we implement industry-standard security measures to protect your data. All data is encrypted and stored securely. We never share your information with third parties.',
                'is_active' => true,
                'role' => null,
            ],
            [
                'question' => 'Can I access the system from mobile devices?',
                'answer' => 'Yes, the application is fully responsive and works on mobile devices, tablets, and desktop computers. You can access all features from any device.',
                'is_active' => true,
                'role' => null,
            ],

            // Inactive FAQs for testing
            [
                'question' => 'This FAQ is inactive',
                'answer' => 'This FAQ is currently inactive and should not be displayed to users.',
                'is_active' => false,
                'role' => 'admin',
            ],
            [
                'question' => 'Another inactive FAQ',
                'answer' => 'This is another inactive FAQ for testing purposes.',
                'is_active' => false,
                'role' => 'client',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
} 