<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Manna') }} - Terms of Service</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: linear-gradient(-45deg, #10B981, #059669, #047857, #065f46); background-size: 400% 400%; animation: gradientBG 15s ease infinite; min-height: 100vh; color: #1f2937; -webkit-font-smoothing: antialiased; padding: 2rem; }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container { max-width: 900px; margin: 0 auto; }

        .a4-paper {
            background: #fff;
            padding: 3rem 4rem;
            border-radius: 8px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.8s ease-out;
            min-height: 1000px;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-section { text-align: center; margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 2px solid #e5e7eb; }

        .header-section h1 { font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; letter-spacing: -0.02em; }

        .header-section p { color: #6b7280; font-size: 1.1rem; }

        .section { margin-bottom: 2.5rem; animation: fadeIn 0.6s ease-out; animation-fill-mode: both; }

        .section:nth-child(1) { animation-delay: 0.1s; }
        .section:nth-child(2) { animation-delay: 0.2s; }
        .section:nth-child(3) { animation-delay: 0.3s; }
        .section:nth-child(4) { animation-delay: 0.4s; }
        .section:nth-child(5) { animation-delay: 0.5s; }
        .section:nth-child(6) { animation-delay: 0.6s; }
        .section:nth-child(7) { animation-delay: 0.7s; }
        .section:nth-child(8) { animation-delay: 0.8s; }
        .section:nth-child(9) { animation-delay: 0.9s; }
        .section:nth-child(10) { animation-delay: 1.0s; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .section h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1rem; letter-spacing: -0.01em; }

        .section h3 { font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem; margin-top: 1.5rem; }

        .section p { color: #4b5563; line-height: 1.8; margin-bottom: 1rem; font-size: 0.95rem; }

        .section ul { list-style: none; padding-left: 0; }

        .section ul li { color: #4b5563; line-height: 1.8; margin-bottom: 0.5rem; padding-left: 1.5rem; position: relative; font-size: 0.95rem; }

        .section ul li::before { content: '•'; position: absolute; left: 0; color: #10B981; font-weight: bold; }

        .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: #fff; text-decoration: none; font-weight: 500; margin-bottom: 2rem; transition: all 0.2s ease; }

        .back-link:hover { transform: translateX(-5px); }

        .back-link svg { width: 20px; height: 20px; }

        .highlight { background: #f0fdf4; padding: 0.25rem 0.5rem; border-radius: 4px; color: #059669; font-weight: 500; }

        .effective-date { background: #f3f4f6; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; font-weight: 500; color: #374151; }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .a4-paper { padding: 2rem 1.5rem; }
            .header-section h1 { font-size: 1.75rem; }
            .section h2 { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}" class="back-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Home
        </a>

        <div class="a4-paper">
            <div class="header-section">
                <h1>Terms of Service</h1>
                <p>Last Updated: January 1, 2025</p>
            </div>

            <div class="effective-date">
                Effective Date: January 1, 2025
            </div>

            <div class="section">
                <h2>1. Agreement to Terms</h2>
                <p>By accessing or using <span class="highlight">{{ config('app.name', 'Manna') }}</span> ("we," "our," or "us"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Service.</p>
                <p>These Terms, together with our Privacy Policy, govern your use of {{ config('app.name', 'Manna') }} and all related services, features, and functionality (collectively, the "Service").</p>
            </div>

            <div class="section">
                <h2>2. Description of Service</h2>
                <p>{{ config('app.name', 'Manna') }} is a long-term rental platform that connects landlords with tenants across Africa. Our Service includes:</p>
                <ul>
                    <li>Property listing and management tools for landlords</li>
                    <li>Property search and inquiry features for tenants</li>
                    <li>Rental application and payment processing</li>
                    <li>Communication tools between landlords and tenants</li>
                    <li>Review and rating systems</li>
                    <li>Account management and user profiles</li>
                </ul>
                <p>We reserve the right to modify, suspend, or discontinue the Service at any time without prior notice.</p>
            </div>

            <div class="section">
                <h2>3. User Accounts</h2>
                <h3>3.1 Account Creation</h3>
                <p>To use certain features of the Service, you must create an account. You agree to provide accurate, complete, and current information during registration. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
                <h3>3.2 Account Responsibilities</h3>
                <p>You agree to:</p>
                <ul>
                    <li>Notify us immediately of any unauthorized use of your account</li>
                    <li>Ensure that you exit from your account at the end of each session</li>
                    <li>Not use anyone else's account at any time</li>
                    <li>Keep your password secure and not share it with others</li>
                </ul>
                <h3>3.3 Account Termination</h3>
                <p>We reserve the right to suspend or terminate your account at any time for any reason, including but not limited to violation of these Terms. You may also terminate your account at any time by contacting us or using account deletion features.</p>
            </div>

            <div class="section">
                <h2>4. User Conduct</h2>
                <p>You agree not to use the Service for any purpose that is unlawful or prohibited by these Terms. You agree not to:</p>
                <ul>
                    <li>Use the Service for any fraudulent or deceptive purpose</li>
                    <li>Post false, misleading, or inaccurate information</li>
                    <li>Impersonate any person or entity or misrepresent your affiliation</li>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe upon the rights of others</li>
                    <li>Transmit viruses, malware, or other harmful code</li>
                    <li>Interfere with or disrupt the Service or servers</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Discriminate based on race, religion, gender, or other protected characteristics</li>
                </ul>
            </div>

            <div class="section">
                <h2>5. Property Listings</h2>
                <h3>5.1 Landlord Responsibilities</h3>
                <p>As a landlord listing properties on our platform, you agree to:</p>
                <ul>
                    <li>Provide accurate and complete property information</li>
                    <li>Upload current and representative photos of the property</li>
                    <li>Disclose all material facts about the property</li>
                    <li>Respond to tenant inquiries in a timely manner</li>
                    <li>Comply with all applicable housing laws and regulations</li>
                    <li>Maintain the property in habitable condition</li>
                </ul>
                <h3>5.2 Property Content</h3>
                <p>You retain ownership of any content you post on our platform. By posting content, you grant us a worldwide, non-exclusive, royalty-free license to use, display, and distribute such content for the purpose of providing the Service.</p>
            </div>

            <div class="section">
                <h2>6. Rental Transactions</h2>
                <h3>6.1 Payment Processing</h3>
                <p>We facilitate rental payments through third-party payment processors. By using our payment features, you agree to the terms and conditions of these third-party providers. We are not responsible for any errors or issues arising from payment processing.</p>
                <h3>6.2 Rental Agreements</h3>
                <p>{{ config('app.name', 'Manna') }} is not a party to rental agreements between landlords and tenants. We provide a platform for connection and communication but do not create, enforce, or guarantee rental agreements. All rental terms are negotiated directly between landlords and tenants.</p>
                <h3>6.3 Dispute Resolution</h3>
                <p>We are not responsible for resolving disputes between landlords and tenants. We may provide tools to facilitate communication but do not mediate or arbitrate disputes. Parties are responsible for resolving their own disputes through appropriate legal channels.</p>
            </div>

            <div class="section">
                <h2>7. Intellectual Property</h2>
                <h3>7.1 Our Intellectual Property</h3>
                <p>The Service and its original content, features, and functionality are owned by {{ config('app.name', 'Manna') }} and are protected by international copyright, trademark, and other intellectual property laws.</p>
                <h3>7.2 Your Intellectual Property</h3>
                <p>You retain ownership of content you submit to the Service. By submitting content, you grant us the license described in Section 5.2. You represent and warrant that you have the right to grant such license.</p>
                <h3>7.3 Trademarks</h3>
                <p>{{ config('app.name', 'Manna') }} and related graphics, logos, and service names are trademarks of {{ config('app.name', 'Manna') }}. You may not use our trademarks without our prior written consent.</p>
            </div>

            <div class="section">
                <h2>8. Privacy</h2>
                <p>Your use of the Service is also governed by our Privacy Policy, which describes how we collect, use, and protect your personal information. Please review our Privacy Policy carefully before using the Service.</p>
            </div>

            <div class="section">
                <h2>9. Disclaimers and Warranties</h2>
                <p>THE SERVICE IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED. WE DISCLAIM ALL WARRANTIES, INCLUDING BUT NOT LIMITED TO:</p>
                <ul>
                    <li>Merchantability and fitness for a particular purpose</li>
                    <li>Non-infringement of third-party rights</li>
                    <li>Accuracy, reliability, or availability of the Service</li>
                    <li>Security of the Service or protection against unauthorized access</li>
                    <li>Freedom from viruses or other harmful components</li>
                </ul>
                <p>We do not guarantee that the Service will be uninterrupted, secure, or error-free. We are not responsible for any damage to your device or loss of data resulting from use of the Service.</p>
            </div>

            <div class="section">
                <h2>10. Limitation of Liability</h2>
                <p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, {{ config('app.name', 'Manna') }} SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO LOSS OF PROFITS, DATA, USE, OR OTHER INTANGIBLE LOSSES, RESULTING FROM:</p>
                <ul>
                    <li>Your access to or use of or inability to access or use the Service</li>
                    <li>Any conduct or content of any third party on the Service</li>
                    <li>Any content obtained from the Service</li>
                    <li>Unauthorized access, use, or alteration of your transmissions or content</li>
                </ul>
                <p>Our total liability to you for all claims shall not exceed the amount you paid to us, if any, for using the Service during the twelve months preceding the claim.</p>
            </div>

            <div class="section">
                <h2>11. Indemnification</h2>
                <p>You agree to indemnify, defend, and hold harmless {{ config('app.name', 'Manna') }} and its affiliates, officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses arising from:</p>
                <ul>
                    <li>Your use of the Service</li>
                    <li>Your violation of these Terms</li>
                    <li>Your violation of any third-party rights</li>
                    <li>Content you post or transmit through the Service</li>
                </ul>
            </div>

            <div class="section">
                <h2>12. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of Kenya, without regard to its conflict of law provisions. Any disputes arising under these Terms shall be subject to the exclusive jurisdiction of the courts of Kenya.</p>
            </div>

            <div class="section">
                <h2>13. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms at any time. We will notify you of material changes by posting the updated Terms on our website and updating the "Last Updated" date. Your continued use of the Service after such changes constitutes your acceptance of the new Terms.</p>
            </div>

            <div class="section">
                <h2>14. Contact Information</h2>
                <p>If you have any questions about these Terms, please contact us:</p>
                <ul>
                    <li>Email: support@manna.com</li>
                    <li>Phone: +254 700 000 000</li>
                    <li>Address: Nairobi, Kenya</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
