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
                'title' => 'Admin Terms and Conditions',
                'description' => 'By accessing and using this farm management system as an administrator, you agree to the following terms and conditions:

1. **System Access**: You have full administrative access to manage users, farms, and system settings.
2. **Data Management**: You are responsible for maintaining data integrity and ensuring proper user permissions.
3. **User Management**: You can create, edit, and delete user accounts as needed.
4. **System Monitoring**: You have access to system logs and monitoring tools.
5. **Data Export**: You can export system data for reporting and analysis purposes.
6. **Security**: You must maintain the security of your admin credentials and report any security concerns.
7. **Compliance**: You must ensure all operations comply with relevant data protection regulations.

These terms are effective as of the date of acceptance and may be updated periodically.',
                'is_active' => true,
                'role' => 'admin',
            ],

            // Privacy Policy for Admin
            [
                'type' => 'privacy_policy',
                'title' => 'Admin Privacy Policy',
                'description' => 'This privacy policy explains how we collect, use, and protect your information as an administrator:

**Information We Collect:**
- Your login credentials and authentication data
- System access logs and activity records
- User management actions and changes
- System configuration and settings

**How We Use Your Information:**
- To provide administrative access to the system
- To monitor system usage and performance
- To maintain system security and integrity
- To provide technical support and assistance

**Data Protection:**
- All data is encrypted and stored securely
- Access is restricted to authorized personnel only
- Regular security audits are conducted
- Data retention policies are strictly followed

**Your Rights:**
- Access to your personal data
- Request for data correction or deletion
- Opt-out of certain data processing activities
- Contact us for privacy-related concerns

For questions about this privacy policy, contact our data protection officer.',
                'is_active' => true,
                'role' => 'admin',
            ],

            // Terms and Conditions for Client
            [
                'type' => 'terms',
                'title' => 'Client Terms and Conditions',
                'description' => 'Welcome to our farm management system. By using this service as a client, you agree to these terms:

**Service Usage:**
- You can manage your farm animals and events
- Access is limited to your assigned farms and data
- You must provide accurate information about your animals
- Regular updates and maintenance are required

**Data Responsibility:**
- You are responsible for the accuracy of your farm data
- Regular backups of your information are recommended
- Report any data discrepancies immediately
- Maintain proper animal records and event logs

**System Access:**
- Use your account credentials responsibly
- Do not share your login information
- Report any security concerns promptly
- Log out when not using the system

**Limitations:**
- Access is limited to your assigned farms
- Cannot modify system settings or user permissions
- Must comply with all applicable regulations
- System availability is subject to maintenance schedules

**Termination:**
- We reserve the right to suspend or terminate access
- You may request account deletion at any time
- Data retention policies apply after termination

These terms are binding and may be updated with notice.',
                'is_active' => true,
                'role' => 'client',
            ],

            // Privacy Policy for Client
            [
                'type' => 'privacy_policy',
                'title' => 'Client Privacy Policy',
                'description' => 'Your privacy is important to us. This policy explains how we handle your farm data:

**Information We Collect:**
- Your account information and contact details
- Farm data including animal records and events
- System usage patterns and preferences
- Communication records and support requests

**How We Use Your Information:**
- To provide farm management services
- To generate reports and analytics
- To improve system functionality
- To provide customer support

**Data Sharing:**
- We do not sell your personal information
- Data may be shared with authorized farm managers
- Aggregated data may be used for system improvements
- Legal requirements may necessitate data disclosure

**Data Security:**
- Industry-standard encryption protects your data
- Regular security assessments are conducted
- Access is limited to authorized personnel
- Backup systems ensure data availability

**Your Rights:**
- Access and review your data
- Request corrections or updates
- Export your data in standard formats
- Request data deletion (subject to retention policies)

**Contact Information:**
For privacy concerns, contact our support team or data protection officer.',
                'is_active' => true,
                'role' => 'client',
            ],

            // General Terms and Conditions (no specific role)
            [
                'type' => 'terms',
                'title' => 'General Terms and Conditions',
                'description' => 'These general terms apply to all users of our farm management system:

**Acceptance of Terms:**
By accessing or using this system, you agree to be bound by these terms and conditions.

**Service Description:**
This system provides farm management tools including animal tracking, event management, and reporting features.

**User Responsibilities:**
- Provide accurate and complete information
- Maintain the security of your account
- Use the system in compliance with applicable laws
- Report any technical issues or security concerns

**Intellectual Property:**
- The system and its content are protected by copyright
- You may not copy, modify, or distribute system content
- All rights are reserved by the system owners

**Limitation of Liability:**
- The system is provided "as is" without warranties
- We are not liable for any damages or losses
- Service availability is not guaranteed
- Data loss is the responsibility of the user

**Changes to Terms:**
- Terms may be updated with notice
- Continued use constitutes acceptance of changes
- Significant changes will be communicated in advance

**Governing Law:**
These terms are governed by applicable laws and regulations.',
                'is_active' => true,
                'role' => null,
            ],

            // General Privacy Policy (no specific role)
            [
                'type' => 'privacy_policy',
                'title' => 'General Privacy Policy',
                'description' => 'This privacy policy applies to all users of our farm management system:

**Information Collection:**
We collect information necessary to provide our services, including:
- Account and profile information
- Farm and animal data
- System usage and activity logs
- Communication and support records

**Data Usage:**
Your information is used to:
- Provide and improve our services
- Generate reports and analytics
- Ensure system security and performance
- Provide customer support and assistance

**Data Protection:**
We implement comprehensive security measures:
- Encryption of all sensitive data
- Regular security audits and updates
- Access controls and authentication
- Secure data storage and transmission

**Data Retention:**
- Data is retained as long as your account is active
- Deleted accounts may have data retained for legal purposes
- Backup data is retained according to our retention policy
- You may request data deletion subject to legal requirements

**Third-Party Services:**
- We may use third-party services for system functionality
- These services are bound by their own privacy policies
- We do not sell or rent your personal information
- Data sharing is limited to service provision

**Your Rights:**
You have the right to:
- Access your personal data
- Request data correction or deletion
- Opt-out of certain data processing
- Contact us with privacy concerns

**Updates to Policy:**
This policy may be updated periodically. Significant changes will be communicated to users.',
                'is_active' => true,
                'role' => null,
            ],

            // Inactive Terms for testing
            [
                'type' => 'terms',
                'title' => 'Inactive Terms',
                'description' => 'These terms are currently inactive and should not be displayed to users.',
                'is_active' => false,
                'role' => 'admin',
            ],

            [
                'type' => 'privacy_policy',
                'title' => 'Inactive Privacy Policy',
                'description' => 'This privacy policy is currently inactive and should not be displayed to users.',
                'is_active' => false,
                'role' => 'client',
            ],
        ];

        foreach ($terms as $term) {
            Term::create($term);
        }
    }
} 